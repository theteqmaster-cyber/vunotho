/**
 * VUNOTHO OFFLINE-FIRST DATABASE ENGINE
 * Robust IndexedDB abstraction managing entities, offline queues, and local caches.
 */

const DB_NAME = 'vunotho_db';
const DB_VERSION = 1;

class VunothoDB {
  constructor() {
    this.db = null;
  }

  async init() {
    return new Promise((resolve, reject) => {
      const request = indexedDB.open(DB_NAME, DB_VERSION);

      request.onupgradeneeded = (event) => {
        const db = event.target.result;

        // 1. Users & Profiles (Farmers, Buyers, Transporters, Admins)
        if (!db.objectStoreNames.contains('users')) {
          const userStore = db.createObjectStore('users', { keyPath: 'id' });
          userStore.createIndex('role', 'role', { unique: false });
          userStore.createIndex('phone', 'phone', { unique: false });
        }

        // 2. Produce / Harvest Listings
        if (!db.objectStoreNames.contains('listings')) {
          const listStore = db.createObjectStore('listings', { keyPath: 'id' });
          listStore.createIndex('farmer_id', 'farmer_id', { unique: false });
          listStore.createIndex('crop', 'crop', { unique: false });
          listStore.createIndex('status', 'status', { unique: false });
          listStore.createIndex('sync_status', 'sync_status', { unique: false });
        }

        // 3. Buyer Demands
        if (!db.objectStoreNames.contains('demands')) {
          const demandStore = db.createObjectStore('demands', { keyPath: 'id' });
          demandStore.createIndex('buyer_id', 'buyer_id', { unique: false });
          demandStore.createIndex('crop', 'crop', { unique: false });
          demandStore.createIndex('status', 'status', { unique: false });
        }

        // 4. Matched Offers
        if (!db.objectStoreNames.contains('offers')) {
          const offerStore = db.createObjectStore('offers', { keyPath: 'id' });
          offerStore.createIndex('listing_id', 'listing_id', { unique: false });
          offerStore.createIndex('demand_id', 'demand_id', { unique: false });
          offerStore.createIndex('farmer_id', 'farmer_id', { unique: false });
        }

        // 5. Transporter Manifests (Aggregated Loads)
        if (!db.objectStoreNames.contains('manifests')) {
          const manifestStore = db.createObjectStore('manifests', { keyPath: 'id' });
          manifestStore.createIndex('transporter_id', 'transporter_id', { unique: false });
          manifestStore.createIndex('status', 'status', { unique: false });
        }

        // 6. Transaction Settlement & Receipts
        if (!db.objectStoreNames.contains('transactions')) {
          const txStore = db.createObjectStore('transactions', { keyPath: 'id' });
          txStore.createIndex('farmer_id', 'farmer_id', { unique: false });
          txStore.createIndex('buyer_id', 'buyer_id', { unique: false });
          txStore.createIndex('created_at', 'created_at', { unique: false });
        }

        // 7. Value-Recovery Diversion Logs
        if (!db.objectStoreNames.contains('value_recovery')) {
          const vrStore = db.createObjectStore('value_recovery', { keyPath: 'id' });
          vrStore.createIndex('pathway', 'pathway', { unique: false });
          vrStore.createIndex('crop', 'crop', { unique: false });
        }

        // 8. Offline Sync Mutation Queue
        if (!db.objectStoreNames.contains('sync_queue')) {
          const queueStore = db.createObjectStore('sync_queue', { keyPath: 'id', autoIncrement: true });
          queueStore.createIndex('created_at', 'created_at', { unique: false });
          queueStore.createIndex('action', 'action', { unique: false });
        }
      };

      request.onsuccess = (event) => {
        this.db = event.target.result;
        resolve(this.db);
      };

      request.onerror = (event) => {
        console.error('IndexedDB Initialization error:', event.target.error);
        reject(event.target.error);
      };
    });
  }

  // Generic CRUD Helper
  async put(storeName, item) {
    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([storeName], 'readwrite');
      const store = transaction.objectStore(storeName);
      const request = store.put(item);
      request.onsuccess = () => resolve(item);
      request.onerror = (e) => reject(e.target.error);
    });
  }

  async get(storeName, key) {
    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([storeName], 'readonly');
      const store = transaction.objectStore(storeName);
      const request = store.get(key);
      request.onsuccess = () => resolve(request.result);
      request.onerror = (e) => reject(e.target.error);
    });
  }

  async getAll(storeName) {
    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([storeName], 'readonly');
      const store = transaction.objectStore(storeName);
      const request = store.getAll();
      request.onsuccess = () => resolve(request.result || []);
      request.onerror = (e) => reject(e.target.error);
    });
  }

  async delete(storeName, key) {
    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([storeName], 'readwrite');
      const store = transaction.objectStore(storeName);
      const request = store.delete(key);
      request.onsuccess = () => resolve(true);
      request.onerror = (e) => reject(e.target.error);
    });
  }

  async clearStore(storeName) {
    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([storeName], 'readwrite');
      const store = transaction.objectStore(storeName);
      const request = store.clear();
      request.onsuccess = () => resolve(true);
      request.onerror = (e) => reject(e.target.error);
    });
  }

  // Sync Queue Operations
  async enqueueMutation(action, payload) {
    const queueItem = {
      action,
      payload,
      created_at: new Date().toISOString(),
      attempts: 0
    };
    return this.put('sync_queue', queueItem);
  }

  async getPendingMutations() {
    return this.getAll('sync_queue');
  }

  async removeQueueItem(id) {
    return this.delete('sync_queue', id);
  }
}

// Global Singleton Instance
window.vunothoDB = new VunothoDB();
