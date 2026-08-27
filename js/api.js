/**
 * VUNOTHO FRONTEND REST API CLIENT
 * Connects directly to PHP Backend on Vercel with automatic local IndexedDB fallback
 */

class VunothoAPI {
  constructor() {
    if (window.location.origin && !window.location.origin.includes('file://')) {
      this.baseUrl = `${window.location.origin}/api`;
    } else {
      this.baseUrl = 'http://localhost:8099/api';
    }
  }

  async request(endpoint, options = {}) {
    const url = `${this.baseUrl}/${endpoint}.php`;
    const headers = {
      'Content-Type': 'application/json',
      ...(options.headers || {})
    };

    try {
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 8000);

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
    } catch (err) {
      console.warn(`Fetch to ${url} failed, falling back to local storage:`, err.message);
      throw err;
    }
  }

  // Listings
  async getListings() {
    if (navigator.onLine) {
      try {
        const data = await this.request('listings');
        // Update local cache
        if (Array.isArray(data)) {
          for (const item of data) {
            await window.vunothoDB.put('listings', item);
          }
        }
        return data;
      } catch (e) {
        return window.vunothoDB.getAll('listings');
      }
    }
    return window.vunothoDB.getAll('listings');
  }

  async createListing(listingData) {
    if (navigator.onLine) {
      try {
        const res = await this.request('listings', {
          method: 'POST',
          body: JSON.stringify(listingData)
        });
        listingData.sync_status = 'Synced';
        await window.vunothoDB.put('listings', listingData);
        return res;
      } catch (e) {
        listingData.sync_status = 'Saved Offline';
        await window.vunothoDB.put('listings', listingData);
        await window.vunothoDB.enqueueMutation('CREATE_LISTING', listingData);
        return { success: true, offline: true };
      }
    } else {
      listingData.sync_status = 'Saved Offline';
      await window.vunothoDB.put('listings', listingData);
      await window.vunothoDB.enqueueMutation('CREATE_LISTING', listingData);
      return { success: true, offline: true };
    }
  }

  // Demands
  async getDemands() {
    if (navigator.onLine) {
      try {
        const data = await this.request('demands');
        if (Array.isArray(data)) {
          for (const item of data) {
            await window.vunothoDB.put('demands', item);
          }
        }
        return data;
      } catch (e) {
        return window.vunothoDB.getAll('demands');
      }
    }
    return window.vunothoDB.getAll('demands');
  }

  async createDemand(demandData) {
    if (navigator.onLine) {
      try {
        const res = await this.request('demands', {
          method: 'POST',
          body: JSON.stringify(demandData)
        });
        await window.vunothoDB.put('demands', demandData);
        return res;
      } catch (e) {
        await window.vunothoDB.put('demands', demandData);
        await window.vunothoDB.enqueueMutation('CREATE_DEMAND', demandData);
        return { success: true, offline: true };
      }
    } else {
      await window.vunothoDB.put('demands', demandData);
      await window.vunothoDB.enqueueMutation('CREATE_DEMAND', demandData);
      return { success: true, offline: true };
    }
  }

  // Transactions & Settlements
  async getTransactions() {
    if (navigator.onLine) {
      try {
        const data = await this.request('transactions');
        if (Array.isArray(data)) {
          for (const item of data) {
            await window.vunothoDB.put('transactions', item);
          }
        }
        return data;
      } catch (e) {
        return window.vunothoDB.getAll('transactions');
      }
    }
    return window.vunothoDB.getAll('transactions');
  }

  async createTransaction(txData) {
    if (navigator.onLine) {
      try {
        const res = await this.request('transactions', {
          method: 'POST',
          body: JSON.stringify(txData)
        });
        await window.vunothoDB.put('transactions', txData);
        return res;
      } catch (e) {
        await window.vunothoDB.put('transactions', txData);
        await window.vunothoDB.enqueueMutation('SETTLE_TRANSACTION', txData);
        return { success: true, offline: true };
      }
    } else {
      await window.vunothoDB.put('transactions', txData);
      await window.vunothoDB.enqueueMutation('SETTLE_TRANSACTION', txData);
      return { success: true, offline: true };
    }
  }

  // Value Recovery
  async getValueRecoveryLogs() {
    if (navigator.onLine) {
      try {
        const data = await this.request('value_recovery');
        if (Array.isArray(data)) {
          for (const item of data) {
            await window.vunothoDB.put('value_recovery', item);
          }
        }
        return data;
      } catch (e) {
        return window.vunothoDB.getAll('value_recovery');
      }
    }
    return window.vunothoDB.getAll('value_recovery');
  }

  async createValueRecoveryLog(vrData) {
    if (navigator.onLine) {
      try {
        const res = await this.request('value_recovery', {
          method: 'POST',
          body: JSON.stringify(vrData)
        });
        await window.vunothoDB.put('value_recovery', vrData);
        return res;
      } catch (e) {
        await window.vunothoDB.put('value_recovery', vrData);
        await window.vunothoDB.enqueueMutation('LOG_VALUE_RECOVERY', vrData);
        return { success: true, offline: true };
      }
    } else {
      await window.vunothoDB.put('value_recovery', vrData);
      await window.vunothoDB.enqueueMutation('LOG_VALUE_RECOVERY', vrData);
      return { success: true, offline: true };
    }
  }

  // Stats
  async getImpactStats() {
    if (navigator.onLine) {
      try {
        return await this.request('stats');
      } catch (e) {
        return null;
      }
    }
    return null;
  }

  // Configurations & Global Parameters
  async getConfigs() {
    if (navigator.onLine) {
      try {
        const res = await this.request('configs');
        return res.configs || res;
      } catch (e) {
        return null;
      }
    }
    return null;
  }

  async saveConfigs(configs) {
    return this.request('configs', {
      method: 'POST',
      body: JSON.stringify({ configs })
    });
  }

  // Users & KYC Administration
  async getUsers() {
    return this.request('auth', {
      method: 'GET'
    });
  }

  async updateUserKYC(userId, kycStatus) {
    return this.request('auth?action=update_kyc', {
      method: 'POST',
      body: JSON.stringify({
        action: 'update_kyc',
        user_id: userId,
        kyc_status: kycStatus
      })
    });
  }

  // Batch Sync
  async syncMutations(mutations) {
    return this.request('sync', {
      method: 'POST',
      body: JSON.stringify({ mutations })
    });
  }
}

window.vunothoAPI = new VunothoAPI();
