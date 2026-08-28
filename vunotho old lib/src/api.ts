/**
 * VUNOTHO FRONTEND REST API CLIENT (TypeScript)
 * Connects directly to PHP Backend with automatic local IndexedDB fallback
 */

import { Listing, Demand, Transaction, ValueRecoveryLog, UserProfile, SystemConfig, SyncMutation } from './types';
import { vunothoDB } from './db';

export class VunothoAPI {
  private baseUrl: string;

  constructor() {
    if (typeof window !== 'undefined' && window.location.origin && !window.location.origin.includes('file://')) {
      this.baseUrl = `${window.location.origin}/api`;
    } else {
      this.baseUrl = 'http://localhost:8099/api';
    }
  }

  async request<T>(endpoint: string, options: RequestInit = {}): Promise<T> {
    const url = `${this.baseUrl}/${endpoint}.php`;
    const headers = {
      'Content-Type': 'application/json',
      ...(options.headers || {})
    };

    try {
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 12000);

      const response = await fetch(url, {
        ...options,
        headers,
        signal: controller.signal
      });

      clearTimeout(timeoutId);

      if (!response.ok) {
        throw new Error(`API Error [${response.status}]: ${response.statusText}`);
      }

      return await response.json();
    } catch (err: any) {
      console.warn(`Fetch to ${url} failed, falling back to local storage:`, err.message);
      throw err;
    }
  }

  // Listings
  async getListings(): Promise<Listing[]> {
    if (navigator.onLine) {
      try {
        const data = await this.request<Listing[]>('listings');
        if (Array.isArray(data)) {
          for (const item of data) {
            await vunothoDB.put('listings', item);
          }
          return data;
        }
      } catch {
        // Fall back to local DB
      }
    }
    return vunothoDB.getAll<Listing>('listings');
  }

  async createListing(listingData: Partial<Listing>): Promise<any> {
    if (navigator.onLine) {
      try {
        const res = await this.request('listings', {
          method: 'POST',
          body: JSON.stringify(listingData)
        });
        listingData.sync_status = 'Synced';
        await vunothoDB.put('listings', listingData);
        return res;
      } catch {
        listingData.sync_status = 'Saved Offline';
        await vunothoDB.put('listings', listingData);
        await vunothoDB.enqueueMutation('CREATE_LISTING', listingData);
        return { success: true, offline: true };
      }
    } else {
      listingData.sync_status = 'Saved Offline';
      await vunothoDB.put('listings', listingData);
      await vunothoDB.enqueueMutation('CREATE_LISTING', listingData);
      return { success: true, offline: true };
    }
  }

  // Demands
  async getDemands(): Promise<Demand[]> {
    if (navigator.onLine) {
      try {
        const data = await this.request<Demand[]>('demands');
        if (Array.isArray(data)) {
          for (const item of data) {
            await vunothoDB.put('demands', item);
          }
          return data;
        }
      } catch {
        // Fall back to local DB
      }
    }
    return vunothoDB.getAll<Demand>('demands');
  }

  async createDemand(demandData: Partial<Demand>): Promise<any> {
    if (navigator.onLine) {
      try {
        const res = await this.request('demands', {
          method: 'POST',
          body: JSON.stringify(demandData)
        });
        await vunothoDB.put('demands', demandData);
        return res;
      } catch {
        await vunothoDB.put('demands', demandData);
        await vunothoDB.enqueueMutation('CREATE_DEMAND', demandData);
        return { success: true, offline: true };
      }
    } else {
      await vunothoDB.put('demands', demandData);
      await vunothoDB.enqueueMutation('CREATE_DEMAND', demandData);
      return { success: true, offline: true };
    }
  }

  // Transactions
  async getTransactions(): Promise<Transaction[]> {
    if (navigator.onLine) {
      try {
        const data = await this.request<Transaction[]>('transactions');
        if (Array.isArray(data)) {
          for (const item of data) {
            await vunothoDB.put('transactions', item);
          }
          return data;
        }
      } catch {
        // Fall back to local DB
      }
    }
    return vunothoDB.getAll<Transaction>('transactions');
  }

  async createTransaction(txData: Partial<Transaction>): Promise<any> {
    if (navigator.onLine) {
      try {
        const res = await this.request('transactions', {
          method: 'POST',
          body: JSON.stringify(txData)
        });
        await vunothoDB.put('transactions', txData);
        return res;
      } catch {
        await vunothoDB.put('transactions', txData);
        await vunothoDB.enqueueMutation('SETTLE_TRANSACTION', txData);
        return { success: true, offline: true };
      }
    } else {
      await vunothoDB.put('transactions', txData);
      await vunothoDB.enqueueMutation('SETTLE_TRANSACTION', txData);
      return { success: true, offline: true };
    }
  }

  // Value Recovery
  async getValueRecoveryLogs(): Promise<ValueRecoveryLog[]> {
    if (navigator.onLine) {
      try {
        const data = await this.request<ValueRecoveryLog[]>('value_recovery');
        if (Array.isArray(data)) {
          for (const item of data) {
            await vunothoDB.put('value_recovery', item);
          }
          return data;
        }
      } catch {
        // Fall back to local DB
      }
    }
    return vunothoDB.getAll<ValueRecoveryLog>('value_recovery');
  }

  async createValueRecoveryLog(vrData: Partial<ValueRecoveryLog>): Promise<any> {
    if (navigator.onLine) {
      try {
        const res = await this.request('value_recovery', {
          method: 'POST',
          body: JSON.stringify(vrData)
        });
        await vunothoDB.put('value_recovery', vrData);
        return res;
      } catch {
        await vunothoDB.put('value_recovery', vrData);
        await vunothoDB.enqueueMutation('LOG_VALUE_RECOVERY', vrData);
        return { success: true, offline: true };
      }
    } else {
      await vunothoDB.put('value_recovery', vrData);
      await vunothoDB.enqueueMutation('LOG_VALUE_RECOVERY', vrData);
      return { success: true, offline: true };
    }
  }

  // Stats
  async getImpactStats(): Promise<any> {
    if (navigator.onLine) {
      try {
        return await this.request('stats');
      } catch {
        return null;
      }
    }
    return null;
  }

  // Configs
  async getConfigs(): Promise<SystemConfig | null> {
    if (navigator.onLine) {
      try {
        const res = await this.request<{ success: boolean; configs: SystemConfig }>('configs');
        return res.configs || (res as any);
      } catch {
        return null;
      }
    }
    return null;
  }

  async saveConfigs(configs: Partial<SystemConfig>): Promise<any> {
    try {
      return await this.request('configs', {
        method: 'POST',
        body: JSON.stringify({ configs })
      });
    } catch (e: any) {
      console.warn('Failed to save configs to server:', e.message);
      return { success: false, error: e.message };
    }
  }

  // Users & KYC
  async getUsers(): Promise<UserProfile[]> {
    if (navigator.onLine) {
      try {
        const users = await this.request<UserProfile[]>('auth', {
          method: 'GET'
        });
        if (Array.isArray(users)) {
          for (const u of users) {
            await vunothoDB.put('users', u);
          }
          return users;
        }
      } catch (e: any) {
        console.warn('Failed to get users from server, falling back to local DB:', e.message);
      }
    }
    return vunothoDB.getAll<UserProfile>('users');
  }

  async updateUserKYC(userId: string, kycStatus: string): Promise<any> {
    try {
      return await this.request('auth?action=update_kyc', {
        method: 'POST',
        body: JSON.stringify({
          action: 'update_kyc',
          user_id: userId,
          kyc_status: kycStatus
        })
      });
    } catch (e: any) {
      console.warn('Failed to update KYC on server:', e.message);
      return { success: false, error: e.message };
    }
  }

  // Batch Sync
  async syncMutations(mutations: SyncMutation[]): Promise<any> {
    return this.request('sync', {
      method: 'POST',
      body: JSON.stringify({ mutations })
    });
  }
}

export const vunothoAPI = new VunothoAPI();
if (typeof window !== 'undefined') {
  (window as any).vunothoAPI = vunothoAPI;
}
