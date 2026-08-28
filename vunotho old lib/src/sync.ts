/**
 * VUNOTHO OFFLINE SYNCHRONIZATION ENGINE & STATE MACHINE (TypeScript)
 */

import { vunothoDB } from './db';
import { vunothoAPI } from './api';

export type SyncState = 'saved-offline' | 'syncing' | 'synced' | 'needs-attention';

export class VunothoSync {
  public states: Record<string, SyncState> = {
    SAVED_OFFLINE: 'saved-offline',
    SYNCING: 'syncing',
    SYNCED: 'synced',
    NEEDS_ATTENTION: 'needs-attention'
  };

  public currentState: SyncState = navigator.onLine ? 'synced' : 'saved-offline';
  private listeners: ((state: SyncState, detail: string) => void)[] = [];
  private isSyncing = false;

  init() {
    window.addEventListener('online', () => this.handleNetworkChange(true));
    window.addEventListener('offline', () => this.handleNetworkChange(false));
    this.updateUI();

    if (navigator.onLine) {
      this.syncPendingQueue();
    }
  }

  onStateChange(callback: (state: SyncState, detail: string) => void) {
    this.listeners.push(callback);
  }

  setState(newState: SyncState, detail = '') {
    this.currentState = newState;
    this.updateUI(detail);
    this.listeners.forEach(cb => cb(newState, detail));
  }

  isOnline(): boolean {
    return navigator.onLine;
  }

  handleNetworkChange(isOnline: boolean) {
    const banner = document.getElementById('offline-banner');
    if (isOnline) {
      if (banner) banner.classList.add('hidden');
      this.setState('syncing', 'Reconnected. Synchronizing records...');
      if ((window as any).vunothoApp) {
        (window as any).vunothoApp.showToast('Network restored! Syncing with Vunotho cloud...', 'info');
      }
      setTimeout(() => this.syncPendingQueue(), 800);
    } else {
      if (banner) banner.classList.remove('hidden');
      this.setState('saved-offline', 'Working offline. Safe actions will save locally.');
      if ((window as any).vunothoApp) {
        (window as any).vunothoApp.showToast('You are currently offline. Safe actions saved locally.', 'warning');
      }
    }
  }

  updateUI(detail = '') {
    const pill = document.getElementById('sync-status-indicator');
    const pillText = document.getElementById('sync-status-text');
    if (!pill || !pillText) return;

    pill.className = 'flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border transition-all';

    switch (this.currentState) {
      case 'saved-offline':
        pill.classList.add('bg-amber-50', 'text-amber-700', 'border-amber-200');
        pillText.textContent = 'Saved Offline';
        pill.title = detail || 'Operating locally. All safe entries are stored on device.';
        break;
      case 'syncing':
        pill.classList.add('bg-sky-50', 'text-sky-700', 'border-sky-200');
        pillText.textContent = 'Syncing...';
        pill.title = detail || 'Communicating with central servers...';
        break;
      case 'synced':
        pill.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
        pillText.textContent = 'Synced';
        pill.title = 'All records up-to-date and confirmed.';
        break;
      case 'needs-attention':
        pill.classList.add('bg-rose-50', 'text-rose-700', 'border-rose-200');
        pillText.textContent = 'Needs Attention';
        pill.title = detail || 'Conflict detected during sync.';
        break;
    }
  }

  async syncPendingQueue() {
    if (this.isSyncing || !navigator.onLine) return;
    this.isSyncing = true;
    this.setState('syncing');

    try {
      const queue = await vunothoDB.getPendingMutations();
      if (queue.length === 0) {
        this.setState('synced');
        this.isSyncing = false;
        return;
      }

      await vunothoAPI.syncMutations(queue);

      for (const item of queue) {
        if (item.id) {
          await vunothoDB.removeQueueItem(item.id);
        }
      }

      this.setState('synced');
      if ((window as any).vunothoApp) {
        (window as any).vunothoApp.showToast(`Synced ${queue.length} pending record(s) to PHP database!`, 'success');
        (window as any).vunothoApp.refreshCurrentView();
      }
    } catch (error: any) {
      console.error('Sync Error:', error);
      this.setState('needs-attention', error.message || 'Sync conflict occurred.');
    } finally {
      this.isSyncing = false;
    }
  }
}

export const vunothoSync = new VunothoSync();
if (typeof window !== 'undefined') {
  (window as any).vunothoSync = vunothoSync;
}
