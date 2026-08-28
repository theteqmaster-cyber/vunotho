/**
 * VUNOTHO OFFLINE-FIRST DATABASE ENGINE (TypeScript)
 * IndexedDB persistence layer with mutation queue.
 */

import { SyncMutation } from './types';

const DB_NAME = 'vunotho_db_v5';
const DB_VERSION = 1;

export class VunothoDB {
  private db: IDBDatabase | null = null;

  async init(): Promise<IDBDatabase> {
    return new Promise((resolve, reject) => {
      const request = indexedDB.open(DB_NAME, DB_VERSION);

      request.onupgradeneeded = (event) => {
        const db = (event.target as IDBOpenDBRequest).result;

        if (!db.objectStoreNames.contains('users')) {
          const userStore = db.createObjectStore('users', { keyPath: 'id' });
          userStore.createIndex('role', 'role', { unique: false });
        }

        if (!db.objectStoreNames.contains('listings')) {
          const listStore = db.createObjectStore('listings', { keyPath: 'id' });
          listStore.createIndex('farmer_id', 'farmer_id', { unique: false });
          listStore.createIndex('crop', 'crop', { unique: false });
        }

        if (!db.objectStoreNames.contains('demands')) {
          const demandStore = db.createObjectStore('demands', { keyPath: 'id' });
          demandStore.createIndex('buyer_id', 'buyer_id', { unique: false });
        }

        if (!db.objectStoreNames.contains('transactions')) {
          const txStore = db.createObjectStore('transactions', { keyPath: 'id' });
          txStore.createIndex('created_at', 'created_at', { unique: false });
        }

        if (!db.objectStoreNames.contains('value_recovery')) {
          const vrStore = db.createObjectStore('value_recovery', { keyPath: 'id' });
          vrStore.createIndex('pathway', 'pathway', { unique: false });
        }

        if (!db.objectStoreNames.contains('sync_queue')) {
          const queueStore = db.createObjectStore('sync_queue', { keyPath: 'id', autoIncrement: true });
          queueStore.createIndex('created_at', 'created_at', { unique: false });
        }
      };

      request.onsuccess = (event) => {
        this.db = (event.target as IDBOpenDBRequest).result;
        resolve(this.db);
      };

      request.onerror = (event) => {
        console.error('IndexedDB error:', (event.target as IDBOpenDBRequest).error);
        reject((event.target as IDBOpenDBRequest).error);
      };
    });
  }

  async put<T>(storeName: string, item: T): Promise<T> {
    if (!this.db) await this.init();
    return new Promise((resolve, reject) => {
      const transaction = this.db!.transaction([storeName], 'readwrite');
      const store = transaction.objectStore(storeName);
      const request = store.put(item);
      request.onsuccess = () => resolve(item);
      request.onerror = (e) => reject((e.target as IDBRequest).error);
    });
  }

  async get<T>(storeName: string, key: string | number): Promise<T | null> {
    if (!this.db) await this.init();
    return new Promise((resolve, reject) => {
      const transaction = this.db!.transaction([storeName], 'readonly');
      const store = transaction.objectStore(storeName);
      const request = store.get(key);
      request.onsuccess = () => resolve(request.result || null);
      request.onerror = (e) => reject((e.target as IDBRequest).error);
    });
  }

  async getAll<T>(storeName: string): Promise<T[]> {
    if (!this.db) await this.init();
    return new Promise((resolve, reject) => {
      const transaction = this.db!.transaction([storeName], 'readonly');
      const store = transaction.objectStore(storeName);
      const request = store.getAll();
      request.onsuccess = () => resolve(request.result || []);
      request.onerror = (e) => reject((e.target as IDBRequest).error);
    });
  }

  async delete(storeName: string, key: string | number): Promise<boolean> {
    if (!this.db) await this.init();
    return new Promise((resolve, reject) => {
      const transaction = this.db!.transaction([storeName], 'readwrite');
      const store = transaction.objectStore(storeName);
      const request = store.delete(key);
      request.onsuccess = () => resolve(true);
      request.onerror = (e) => reject((e.target as IDBRequest).error);
    });
  }

  async enqueueMutation(action: SyncMutation['action'], payload: any): Promise<any> {
    const queueItem: SyncMutation = {
      action,
      payload,
      created_at: new Date().toISOString(),
      attempts: 0
    };
    return this.put('sync_queue', queueItem);
  }

  async getPendingMutations(): Promise<SyncMutation[]> {
    return this.getAll<SyncMutation>('sync_queue');
  }

  async removeQueueItem(id: number): Promise<boolean> {
    return this.delete('sync_queue', id);
  }
}

export const vunothoDB = new VunothoDB();
if (typeof window !== 'undefined') {
  (window as any).vunothoDB = vunothoDB;
}
