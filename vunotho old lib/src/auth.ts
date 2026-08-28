/**
 * VUNOTHO AUTHENTICATION & ACCESS CONTROL MANAGER (TypeScript)
 * Database-backed User Registration, Authentication & KYC Profile Management
 */

import { UserProfile } from './types';
import { vunothoDB } from './db';

export class VunothoAuth {
  private STORAGE_KEY = 'vunotho_active_user';
  private currentUser: UserProfile | null = null;

  constructor() {
    this.currentUser = this.loadSession();
  }

  loadSession(): UserProfile | null {
    try {
      const raw = localStorage.getItem(this.STORAGE_KEY);
      return raw ? JSON.parse(raw) : null;
    } catch {
      return null;
    }
  }

  saveSession(user: UserProfile) {
    this.currentUser = user;
    localStorage.setItem(this.STORAGE_KEY, JSON.stringify(user));
  }

  clearSession() {
    this.currentUser = null;
    localStorage.removeItem(this.STORAGE_KEY);
  }

  isLoggedIn(): boolean {
    return this.currentUser !== null;
  }

  getUser(): UserProfile | null {
    return this.currentUser;
  }

  isAdmin(): boolean {
    return this.currentUser !== null && this.currentUser.role === 'admin';
  }

  async register(params: Partial<UserProfile> & { password?: string }): Promise<UserProfile> {
    const payload = {
      action: 'register',
      name: (params.name || '').trim(),
      organisation: (params.organisation || '').trim(),
      email_or_phone: (params.email_or_phone || '').trim(),
      password: params.password,
      role: params.role || 'farmer',
      province: params.province || 'Manicaland',
      district: params.district || 'Nyanga',
      main_produce: params.main_produce || '',
      vehicle_type: params.vehicle_type || ''
    };

    let response: Response | null = null;
    let result: any = null;
    let networkError = false;

    try {
      response = await fetch('./api/auth.php?action=register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      result = await response.json();
    } catch (err: any) {
      networkError = true;
      console.warn('Network request failed for registration:', err.message);
    }

    if (response) {
      if (!response.ok || (result && result.error)) {
        const message = (result && result.message) || 'Registration failed. Please check your input.';
        const errorObj: any = new Error(message);
        errorObj.status = response.status;
        errorObj.isConflict = response.status === 409;
        throw errorObj;
      }

      const user: UserProfile = result.user;
      this.saveSession(user);

      if ((window as any).vunothoApp) {
        (window as any).vunothoApp.showToast(`Account created for ${user.name}!`, 'success');
        (window as any).vunothoApp.handleAuthChange();
      }
      return user;
    }

    if (!navigator.onLine || networkError) {
      const allUsers = await vunothoDB.getAll<UserProfile>('users').catch(() => []);
      const existing = allUsers.find(u => (u.email_or_phone || '').toLowerCase() === payload.email_or_phone.toLowerCase());
      if (existing) {
        const errorObj: any = new Error('An account with this phone number or email already exists. Please Sign In.');
        errorObj.isConflict = true;
        throw errorObj;
      }

      const user: UserProfile = {
        id: `USR-${Date.now().toString(36).toUpperCase()}`,
        name: payload.name,
        organisation: payload.organisation,
        email_or_phone: payload.email_or_phone,
        role: payload.role as any,
        province: payload.province,
        district: payload.district,
        main_produce: payload.main_produce,
        vehicle_type: payload.vehicle_type,
        kycStatus: 'Pending KYC',
        created_at: new Date().toISOString()
      };

      await vunothoDB.put('users', user).catch(() => {});
      await vunothoDB.enqueueMutation('REGISTER_USER', payload).catch(() => {});
      this.saveSession(user);

      if ((window as any).vunothoApp) {
        (window as any).vunothoApp.showToast(`Offline mode: Account saved locally. Will sync upon connection.`, 'warning');
        (window as any).vunothoApp.handleAuthChange();
      }
      return user;
    }

    throw new Error('Unexpected registration failure.');
  }

  async login(emailOrPhone: string, password: string, role = 'farmer'): Promise<UserProfile> {
    const payload = {
      action: 'login',
      email_or_phone: (emailOrPhone || '').trim(),
      password,
      role
    };

    let response: Response | null = null;
    let result: any = null;
    let networkError = false;

    try {
      response = await fetch('./api/auth.php?action=login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      result = await response.json();
    } catch (err: any) {
      networkError = true;
      console.warn('Network request failed for login:', err.message);
    }

    if (response) {
      if (!response.ok || (result && result.error)) {
        const message = (result && result.message) || 'Login failed. Please check your credentials.';
        const errorObj: any = new Error(message);
        errorObj.status = response.status;
        errorObj.isNotFound = response.status === 404;
        throw errorObj;
      }

      const user: UserProfile = result.user;
      this.saveSession(user);

      if ((window as any).vunothoApp) {
        (window as any).vunothoApp.showToast(`Welcome back, ${user.name}!`, 'info');
        (window as any).vunothoApp.handleAuthChange();
      }
      return user;
    }

    if (!navigator.onLine || networkError) {
      const allUsers = await vunothoDB.getAll<UserProfile>('users').catch(() => []);
      const localUser = allUsers.find(u => (u.email_or_phone || '').toLowerCase() === payload.email_or_phone.toLowerCase());
      if (localUser) {
        this.saveSession(localUser);
        if ((window as any).vunothoApp) {
          (window as any).vunothoApp.showToast(`Offline session loaded for ${localUser.name}.`, 'info');
          (window as any).vunothoApp.handleAuthChange();
        }
        return localUser;
      }
    }

    throw new Error('Invalid credentials or no network connection.');
  }

  logout() {
    this.clearSession();
    if ((window as any).vunothoApp) {
      (window as any).vunothoApp.showToast('You have been signed out.', 'info');
      (window as any).vunothoApp.handleAuthChange();
    }
  }
}

export const vunothoAuth = new VunothoAuth();
if (typeof window !== 'undefined') {
  (window as any).vunothoAuth = vunothoAuth;
}
