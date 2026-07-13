const CACHE_NAME = 'solcon-pwa-v1.0.1';
const OFFLINE_URL = '/offline.html';

const PRECACHE_ASSETS = [
    '/',
    '/offline.html',
    '/manifest.json',
    '/icons/icon-72x72.png',
    '/icons/icon-96x96.png',
    '/icons/icon-128x128.png',
    '/icons/icon-192x192.png',
    '/icons/icon-256x256.png',
    '/icons/icon-384x384.png',
    '/icons/icon-512x512.png'
];

// Install Event - Pre-cache core shell & offline page
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS);
        })
    );
});

// Activate Event - Clean up stale caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Event
self.addEventListener('fetch', (event) => {
    const request = event.request;

    // Do not handle non-GET or chrome-extension / external cross-origin requests
    if (request.method !== 'GET' || !request.url.startsWith(self.location.origin)) {
        return;
    }

    // Handle HTML Page Navigation
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseClone);
                        });
                    }
                    return networkResponse;
                })
                .catch(() => {
                    return caches.match(request).then((cachedResponse) => {
                        if (cachedResponse) {
                            return cachedResponse;
                        }
                        return caches.match(OFFLINE_URL);
                    });
                })
        );
        return;
    }

    // Handle Static Assets (CSS, JS, Fonts, Images) - Cache First Strategy
    if (
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'image' ||
        request.destination === 'font'
    ) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                if (cachedResponse) {
                    // Serve from cache, update cache in background
                    fetch(request).then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200) {
                            const responseClone = networkResponse.clone();
                            caches.open(CACHE_NAME).then((cache) => cache.put(request, responseClone));
                        }
                    }).catch(() => {/* ignore network failure on background update */ });
                    return cachedResponse;
                }
                return fetch(request).then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, responseClone));
                    }
                    return networkResponse;
                });
            })
        );
        return;
    }

    // Fallback default network-first
    event.respondWith(
        fetch(request).catch(() => caches.match(request))
    );
});

// Listen for skipWaiting message from client
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});
