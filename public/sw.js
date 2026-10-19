// PrintSheba High-Performance Service Worker for PWA & Web Share Target
const CACHE_NAME = 'printsheba-v1';
const PRECACHE_ASSETS = [
    '/',
    '/logo.svg',
    '/icon-192.png',
    '/icon-512.png',
    '/manifest.json'
];

// Install: Cache critical static shell
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS).catch((err) => {
                console.warn('PWA Precache warning:', err);
            });
        }).then(() => self.skipWaiting())
    );
});

// Activate: Claim clients immediately
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch: Network-first strategy with cache fallback for static assets
self.addEventListener('fetch', (event) => {
    // Pass-through for POST, non-GET requests, API routes, or share target actions
    if (event.request.method !== 'GET') {
        return;
    }

    const url = new URL(event.request.url);

    // Skip caching for backend agent polling, webhooks, or dynamic mutations
    if (url.pathname.startsWith('/api/') || url.pathname.includes('/sync-status') || url.pathname.includes('/live-status')) {
        return;
    }

    // For static images/icons, try cache first, fallback to network
    if (url.pathname.match(/\.(png|svg|ico|jpg|jpeg|webp|woff2|woff)$/i)) {
        event.respondWith(
            caches.match(event.request).then((cached) => {
                if (cached) return cached;
                return fetch(event.request).then((response) => {
                    if (response && response.status === 200) {
                        const copy = response.clone();
                        caches.open(CACHE_NAME).then((c) => c.put(event.request, copy));
                    }
                    return response;
                }).catch(() => cached);
            })
        );
        return;
    }

    // For HTML navigation: Network first, fallback to offline notice if unavailable
    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request);
        })
    );
});
