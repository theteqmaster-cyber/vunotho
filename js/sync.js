/**
 * VUNOTHO OFFLINE SYNCHRONIZATION ENGINE & STATE MACHINE
 * Manages connectivity transitions, visual status indicators, and offline mutation queues.
 */

class VunothoSync {
  constructor() {
    this.states = {
      SAVED_OFFLINE: 'saved-offline',
      SYNCING: 'syncing',
      SYNCED: 'synced',
      NEEDS_ATTENTION: 'needs-attention'
    };
    this.currentState = navigator.onLine ? this.states.SYNCED : this.states.SAVED_OFFLINE;
    this.listeners = [];
    this.isSyncing = false;
  }

  init() {
    // Listen to browser network changes
    window.addEventListener('online', () => this.handleNetworkChange(true));
    window.addEventListener('offline', () => this.handleNetworkChange(false));

    // Update UI indicator immediately
    this.updateUI();

    // Auto-attempt sync on initialization if online
    if (navigator.onLine) {
      this.syncPendingQueue();
    }
  }

  onStateChange(callback) {
    this.listeners.push(callback);
  }

  setState(newState, detail = '') {
    this.currentState = newState;
    this.updateUI(detail);
    this.listeners.forEach(cb => cb(newState, detail));
  }

  isOnline() {
    return navigator.onLine;
  }

  handleNetworkChange(isOnline) {
    const banner = document.getElementById('offline-banner');
    if (isOnline) {
      if (banner) banner.classList.add('hidden');
      this.setState(this.states.SYNCING, 'Reconnected. Synchronizing records...');
      if (window.vunothoApp) {
        window.vunothoApp.showToast('Network restored! Syncing with Vunotho cloud...', 'info');
      }
      setTimeout(() => this.syncPendingQueue(), 800);
    } else {
      if (banner) banner.classList.remove('hidden');
      this.setState(this.states.SAVED_OFFLINE, 'Working offline. Safe actions will save locally.');
      if (window.vunothoApp) {
        window.vunothoApp.showToast('You are currently offline. Offline mode active.', 'warning');
      }
    }
  }

  updateUI(detail = '') {
    const pill = document.getElementById('sync-status-indicator');
    const pillText = document.getElementById('sync-status-text');
    if (!pill || !pillText) return;

    // Reset classes
    pill.className = 'sync-status-pill';

    switch (this.currentState) {
      case this.states.SAVED_OFFLINE:
        pill.classList.add('status-saved-offline');
        pillText.textContent = 'Saved Offline';
        pill.title = detail || 'Operating locally. All safe entries are stored on device.';
        break;
      case this.states.SYNCING:
        pill.classList.add('status-syncing');
        pillText.textContent = 'Syncing...';
        pill.title = detail || 'Communicating with central servers...';
        break;
      case this.states.SYNCED:
        pill.classList.add('status-synced');
        pillText.textContent = 'Synced';
        pill.title = 'All records up-to-date and confirmed.';
        break;
      case this.states.NEEDS_ATTENTION:
        pill.classList.add('status-needs-attention');
        pillText.textContent = 'Needs Attention';
        pill.title = detail || 'Conflict detected during sync.';
        break;
    }
  }

  /**
   * Process offline queued mutations upon reconnection
   */
  async syncPendingQueue() {
    if (this.isSyncing || !navigator.onLine) return;
    this.isSyncing = true;
    this.setState(this.states.SYNCING);

    try {
      const queue = await window.vunothoDB.getPendingMutations();
      if (queue.length === 0) {
        this.setState(this.states.SYNCED);
        this.isSyncing = false;
        return;
      }

      // Sync batch to PHP backend
      if (window.vunothoAPI) {
        await window.vunothoAPI.syncMutations(queue);
      }

      for (const item of queue) {
        await window.vunothoDB.removeQueueItem(item.id);
      }

      this.setState(this.states.SYNCED);
      if (window.vunothoApp) {
        window.vunothoApp.showToast(`Synced ${queue.length} pending record(s) with PHP database!`, 'info');
        window.vunothoApp.refreshCurrentView();
      }
    } catch (error) {
      console.error('Sync Error:', error);
      this.setState(this.states.NEEDS_ATTENTION, error.message || 'Sync conflict occurred.');
    } finally {
      this.isSyncing = false;
    }
  }

  async processMutation(item) {
    const { action, payload } = item;
    // Simulate real backend ingestion
    switch (action) {
      case 'CREATE_LISTING':
        payload.sync_status = 'Synced';
        await window.vunothoDB.put('listings', payload);
        break;
      case 'CREATE_DEMAND':
        payload.sync_status = 'Synced';
        await window.vunothoDB.put('demands', payload);
        break;
      case 'ACCEPT_OFFER':
        await window.vunothoDB.put('offers', payload);
        break;
      case 'SETTLE_TRANSACTION':
        await window.vunothoDB.put('transactions', payload);
        break;
      default:
        console.warn('Unknown mutation action:', action);
    }
  }

  /**
   * Guard check: Prevents race conditions for operations requiring central lock while offline
   */
  requireOnline(actionDescription) {
    if (!navigator.onLine) {
      if (window.vunothoApp) {
        window.vunothoApp.showToast(
          `Cannot ${actionDescription} while offline to prevent order conflicts. Please reconnect.`,
          'warning'
        );
      }
      return false;
    }
    return true;
  }
}

// Global Singleton Instance
if (typeof window !== 'undefined') {
  window.vunothoSync = window.vunothoSync || new VunothoSync();
}
