// Import Firebase compatibility SDK scripts
importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js');

// Parse Firebase config from query parameters
const urlParams = new URLSearchParams(self.location.search);
const firebaseConfig = {
    apiKey: urlParams.get('apiKey'),
    authDomain: urlParams.get('authDomain'),
    projectId: urlParams.get('projectId'),
    storageBucket: urlParams.get('storageBucket'),
    messagingSenderId: urlParams.get('messagingSenderId'),
    appId: urlParams.get('appId')
};

// Initialize Firebase if credentials are valid
if (firebaseConfig.apiKey && firebaseConfig.projectId && firebaseConfig.messagingSenderId && firebaseConfig.appId) {
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    messaging.onBackgroundMessage((payload) => {
        console.log('[sw.js] Received background message ', payload);
        
        // Extract parameters supporting title, body, icon, badge, click_action
        const notificationTitle = payload.notification?.title || payload.data?.title || 'Solcon ERP';
        const notificationOptions = {
            body: payload.notification?.body || payload.data?.body || '',
            icon: payload.notification?.icon || payload.data?.icon || '/icons/icon-192x192.png',
            badge: payload.notification?.badge || payload.data?.badge || '/icons/icon-96x96.png',
            data: {
                click_action: payload.data?.click_action || payload.notification?.click_action || '/'
            }
        };

        self.registration.showNotification(notificationTitle, notificationOptions);
    });
}

// Handle notification clicks (Open/focus client window)
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const clickAction = event.notification.data?.click_action || '/';
    const targetUrl = new URL(clickAction, self.location.origin).href;

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            // First, look for an existing window that is already on the target URL
            for (let i = 0; i < windowClients.length; i++) {
                const client = windowClients[i];
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            // Second, if no window is on that exact URL, but we have some window open, navigate it and focus it
            for (let i = 0; i < windowClients.length; i++) {
                const client = windowClients[i];
                if ('focus' in client && 'navigate' in client) {
                    client.navigate(targetUrl);
                    return client.focus();
                }
            }
            // Third, if no windows are open, open a new one
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});

// PWA Offline Caching & Update lifecycle configurations
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
