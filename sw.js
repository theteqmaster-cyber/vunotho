/**
 * VUNOTHO ROBUST SERVICE WORKER
 * Safe Network-First caching with graceful fallback
 */

const CACHE_NAME = 'vunotho-v5.0-clean';

const PRECACHE_ASSETS = [
  '/',
  '/index.php',
  '/css/tailwind.css',
  '/js/pricing.js',
  '/js/settlement.js',
  '/images/vunotho_logo.jpg',
  '/images/favicon.jpg',
  '/images/icon.svg'
];

self.addEventListener('install', (event) => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      // Pre-cache core assets gracefully (don't fail install if any single asset fails)
      return Promise.allSettled(
        PRECACHE_ASSETS.map((url) =>
          fetch(url, { cache: 'no-cache' })
            .then((res) => {
              if (res && res.status === 200) {
                return cache.put(url, res);
              }
            })
            .catch(() => {})
        )
      );
    })
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.map((key) => {
          if (key !== CACHE_NAME) {
            return caches.delete(key);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  // Only handle HTTP/HTTPS GET requests from the same origin
  if (event.request.method !== 'GET') return;
  if (!event.request.url.startsWith(self.location.origin)) return;

  // Never intercept API routes or dynamic PHP auth endpoints
  const url = new URL(event.request.url);
  if (url.pathname.startsWith('/api/') || url.pathname.includes('logout.php')) {
    return;
  }

  // Network-first strategy with safe error isolation
  event.respondWith(
    fetch(event.request)
      .then((networkResponse) => {
        // Only attempt to cache complete, uncorrupted, standard 200 responses
        if (
          networkResponse &&
          networkResponse.status === 200 &&
          networkResponse.type === 'basic'
        ) {
          try {
            const clone = networkResponse.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(event.request, clone).catch(() => {});
            }).catch(() => {});
          } catch (e) {
            // Ignore cloning errors on compressed/streamed responses
          }
        }
        return networkResponse;
      })
      .catch(async () => {
        // Offline recovery: attempt cache lookup
        try {
          const cached = await caches.match(event.request);
          if (cached) return cached;

          // For document navigations when offline, serve cached index
          if (event.request.mode === 'navigate') {
            const indexCached = await caches.match('/index.php') || await caches.match('/');
            if (indexCached) return indexCached;
          }
        } catch (e) {
          // Fall through
        }

        return new Response('Vunotho is offline. Please check your network connection.', {
          status: 503,
          statusText: 'Service Unavailable',
          headers: { 'Content-Type': 'text/plain; charset=utf-8' }
        });
      })
  );
});
