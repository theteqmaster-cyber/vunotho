/**
 * VUNOTHO AUTHENTICATION & ACCESS CONTROL MANAGER
 * Database-backed User Registration, Authentication & KYC Profile Management
 */

class VunothoAuth {
  constructor() {
    this.STORAGE_KEY = 'vunotho_active_user';
    this.currentUser = this.loadSession();
  }

  loadSession() {
    try {
      const raw = localStorage.getItem(this.STORAGE_KEY);
      return raw ? JSON.parse(raw) : null;
    } catch (e) {
      return null;
    }
  }

  saveSession(user) {
    this.currentUser = user;
    localStorage.setItem(this.STORAGE_KEY, JSON.stringify(user));
  }

  clearSession() {
    this.currentUser = null;
    localStorage.removeItem(this.STORAGE_KEY);
  }

  isLoggedIn() {
    return this.currentUser !== null;
  }

  getUser() {
    return this.currentUser;
  }

  isAdmin() {
    return this.currentUser && this.currentUser.role === 'admin';
  }

  /**
   * Register a new user and save directly to Supabase PostgreSQL database
   */
  async register(paramsOrName, emailOrPhone, password, role, district) {
    let payload = {};
    if (typeof paramsOrName === 'object' && paramsOrName !== null) {
      payload = {
        action: 'register',
        name: (paramsOrName.name || '').trim(),
        organisation: (paramsOrName.organisation || '').trim(),
        email_or_phone: (paramsOrName.email_or_phone || paramsOrName.emailOrPhone || '').trim(),
        password: paramsOrName.password,
        role: paramsOrName.role || 'farmer',
        province: paramsOrName.province || 'Manicaland',
        district: paramsOrName.district || 'Nyanga',
        main_produce: paramsOrName.main_produce || paramsOrName.mainProduce || '',
        vehicle_type: paramsOrName.vehicle_type || paramsOrName.vehicleType || ''
      };
    } else {
      payload = {
        action: 'register',
        name: (paramsOrName || '').trim(),
        organisation: '',
        email_or_phone: (emailOrPhone || '').trim(),
        password,
        role: role || 'farmer',
        province: 'Manicaland',
        district: district || 'Nyanga',
        main_produce: '',
        vehicle_type: ''
      };
    }

    let response = null;
    let result = null;
    let networkError = false;

    try {
      response = await fetch('./api/auth.php?action=register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      result = await response.json();
    } catch (err) {
      networkError = true;
      console.warn('Network request failed for registration:', err.message);
    }

    // 1. If the server responded (even with an HTTP 4xx/5xx error), respect it!
    if (response) {
      if (!response.ok || (result && result.error)) {
        const message = (result && result.message) || 'Registration failed. Please check your input.';
        const errorObj = new Error(message);
        errorObj.status = response.status;
        errorObj.isConflict = response.status === 409;
        throw errorObj;
      }

      const user = result.user;
      this.saveSession(user);

      if (window.vunothoApp) {
        window.vunothoApp.showToast(`Account created for ${user.name}!`, 'info');
        window.vunothoApp.handleAuthChange();
      }
      return user;
    }

    // 2. True Offline Fallback (Network failure / disconnected)
    if (!navigator.onLine || networkError) {
      const allUsers = await window.vunothoDB.getAll('users').catch(() => []);
      const existing = allUsers.find(u => (u.email_or_phone || '').toLowerCase() === payload.email_or_phone.toLowerCase());
      if (existing) {
        const errorObj = new Error('An account with this phone number or email already exists. Please Sign In.');
        errorObj.isConflict = true;
        throw errorObj;
      }

      const user = {
        id: `USR-${Date.now().toString(36).toUpperCase()}`,
        name: payload.name,
        organisation: payload.organisation,
        email_or_phone: payload.email_or_phone,
        role: payload.role,
        province: payload.province,
        district: payload.district,
        main_produce: payload.main_produce,
        vehicle_type: payload.vehicle_type,
        kycStatus: 'Pending KYC',
        created_at: new Date().toISOString()
      };

      await window.vunothoDB.put('users', user).catch(() => {});
      await window.vunothoDB.enqueueMutation('REGISTER_USER', payload).catch(() => {});
      this.saveSession(user);

      if (window.vunothoApp) {
        window.vunothoApp.showToast(`Offline mode: Account saved locally. Will sync upon connection.`, 'warning');
        window.vunothoApp.handleAuthChange();
      }
      return user;
    }
  }

  /**
   * Authenticate an existing user against Supabase PostgreSQL database
   */
  async login(emailOrPhone, password, role = 'farmer') {
    const payload = {
      action: 'login',
      email_or_phone: (emailOrPhone || '').trim(),
      password,
      role
    };

    let response = null;
    let result = null;
    let networkError = false;

    try {
      response = await fetch('./api/auth.php?action=login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      result = await response.json();
    } catch (err) {
      networkError = true;
      console.warn('Network request failed for login:', err.message);
    }

    // 1. If backend responded, respect the authentication verdict
    if (response) {
      if (!response.ok || (result && result.error)) {
        const message = (result && result.message) || 'Login failed. Please check your credentials.';
        const errorObj = new Error(message);
        errorObj.status = response.status;
        errorObj.isNotFound = response.status === 404;
        throw errorObj;
      }

      const user = result.user;
      this.saveSession(user);

      if (window.vunothoApp) {
        window.vunothoApp.showToast(`Welcome back, ${user.name}!`, 'info');
        window.vunothoApp.handleAuthChange();
      }
      return user;
    }

    // 2. True Offline Authentication: Only succeed if profile was previously cached locally
    if (!navigator.onLine || networkError) {
      const allUsers = await window.vunothoDB.getAll('users').catch(() => []);
      const localUser = allUsers.find(u => (u.email_or_phone || '').toLowerCase() === payload.email_or_phone.toLowerCase());
      if (localUser) {
        this.saveSession(localUser);
        if (window.vunothoApp) {
          window.vunothoApp.showToast(`Offline mode: Signed in as ${localUser.name}`, 'warning');
          window.vunothoApp.handleAuthChange();
        }
        return localUser;
      }

      throw new Error('Offline: No cached account found on this device. Please connect to the internet to sign in.');
    }
  }

  logout() {
    const name = this.currentUser ? this.currentUser.name : 'User';
    this.clearSession();
    if (window.vunothoApp) {
      window.vunothoApp.showToast(`${name} signed out.`, 'info');
      window.vunothoApp.handleAuthChange();
    }
  }
}

window.vunothoAuth = new VunothoAuth();
