import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";

$(document).ready(function () {
    const configMeta = $('meta[name="firebase-config"]').attr('content');
    let firebaseConfig = null;

    if (configMeta) {
        try {
            firebaseConfig = JSON.parse(configMeta);
        } catch (e) {
            console.error("Failed to parse Firebase configuration.");
        }
    }

    // Check if Firebase configurations are present and valid
    const isFirebaseValid = firebaseConfig && 
        firebaseConfig.api_key && 
        firebaseConfig.api_key !== 'mock-api-key-here' && 
        !firebaseConfig.api_key.startsWith('mock');

    let swUrl = '/sw.js';
    if (isFirebaseValid) {
        const configParams = new URLSearchParams({
            apiKey: firebaseConfig.api_key,
            authDomain: firebaseConfig.auth_domain || '',
            projectId: firebaseConfig.project_id,
            storageBucket: firebaseConfig.storage_bucket || '',
            messagingSenderId: firebaseConfig.messaging_sender_id,
            appId: firebaseConfig.app_id
        }).toString();
        swUrl = `/sw.js?${configParams}`;
    }

    // Request notification permission and register token
    function requestPermissionAndGetToken(messaging, registration) {
        if (!('Notification' in window)) {
            console.warn("This browser does not support desktop notifications.");
            return;
        }

        Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                console.log('Notification permission granted.');

                // Get FCM token
                if (!firebaseConfig.vapid_key || firebaseConfig.vapid_key === 'BPl...' || firebaseConfig.vapid_key.includes('...')) {
                    console.warn("FCM token retrieval skipped: VAPID Key is a placeholder ('BPl...'). Please configure a valid VAPID Key in your .env file to enable push notifications.");
                    return;
                }

                getToken(messaging, {
                    vapidKey: firebaseConfig.vapid_key,
                    serviceWorkerRegistration: registration
                }).then((currentToken) => {
                    if (currentToken) {
                        console.log('FCM Device Token retrieved:', currentToken);
                        registerTokenOnServer(currentToken);
                    } else {
                        console.log('No registration token available. Request permission to generate one.');
                    }
                }).catch((err) => {
                    console.warn('FCM token registration skipped/failed (this is expected if using mismatched or mock credentials):', err.message);
                });
            } else if (permission === 'denied') {
                console.warn('Notification permission was denied.');
            }
        });
    }

    // Helper to detect browser and platform details
    function getBrowserAndOS() {
        const userAgent = navigator.userAgent;
        let browserName = "Unknown";
        let platformName = "Unknown";

        if (userAgent.indexOf("Chrome") > -1) browserName = "Chrome";
        else if (userAgent.indexOf("Safari") > -1) browserName = "Safari";
        else if (userAgent.indexOf("Firefox") > -1) browserName = "Firefox";
        else if (userAgent.indexOf("MSIE") > -1 || !!document.documentMode == true) browserName = "IE";

        if (userAgent.indexOf("Win") > -1) platformName = "Windows";
        else if (userAgent.indexOf("Mac") > -1) platformName = "MacOS";
        else if (userAgent.indexOf("Linux") > -1) platformName = "Linux";
        else if (userAgent.indexOf("Android") > -1) platformName = "Android";
        else if (userAgent.indexOf("like Mac") > -1) platformName = "iOS";

        return {
            browser_name: browserName,
            platform: platformName,
            device_name: navigator.maxTouchPoints > 0 ? "Mobile/Tablet" : "Desktop"
        };
    }

    // Register token via Ajax
    function registerTokenOnServer(token) {
        const deviceDetails = getBrowserAndOS();
        $.ajax({
            url: '/notifications/devices',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                device_token: token,
                browser_name: deviceDetails.browser_name,
                platform: deviceDetails.platform,
                device_name: deviceDetails.device_name
            },
            success: function (response) {
                console.log('Device token successfully registered on server.');
            },
            error: function (xhr) {
                console.error('Failed to register device token on server: ', xhr.responseText);
            }
        });
    }

    let messaging = null;

    // Register Service Worker (Exactly ONE registration in the application)
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register(swUrl)
            .then((registration) => {
                console.log('Service Worker registered with scope:', registration.scope);
                window.swRegistration = registration;

                // Dispatch event so PWA installer or other components can hook into it
                window.dispatchEvent(new CustomEvent('swRegistered', { detail: registration }));

                if (isFirebaseValid) {
                    // Initialize client-side Firebase messaging if config is valid
                    const app = initializeApp({
                        apiKey: firebaseConfig.api_key,
                        authDomain: firebaseConfig.auth_domain,
                        projectId: firebaseConfig.project_id,
                        storageBucket: firebaseConfig.storage_bucket,
                        messagingSenderId: firebaseConfig.messaging_sender_id,
                        appId: firebaseConfig.app_id,
                        measurementId: firebaseConfig.measurement_id
                    });

                    messaging = getMessaging(app);

                    // Request permission and fetch FCM token passing registration
                    requestPermissionAndGetToken(messaging, registration);

                    // Listen for foreground messages
                    onMessage(messaging, (payload) => {
                        console.log('Received foreground message:', payload);

                        const notifTitle = payload.notification?.title || payload.data?.title || 'New Notification';
                        const notifBody = payload.notification?.body || payload.data?.body || '';
                        const clickUrl = payload.data?.click_action || payload.notification?.click_action;

                        // System Notification (Foreground)
                        if (Notification.permission === "granted") {
                            const notification = new Notification(notifTitle, {
                                body: notifBody,
                                icon: "/icons/icon-192x192.png",
                                badge: "/icons/icon-96x96.png",
                                data: {
                                    click_action: clickUrl || '/'
                                }
                            });

                            notification.onclick = function(event) {
                                event.preventDefault();
                                const url = this.data?.click_action;
                                if (url) {
                                    window.focus();
                                    window.location.href = url;
                                }
                            };
                        }

                        // SweetAlert (Foreground Toast)
                        Swal.fire({
                            title: notifTitle,
                            text: notifBody,
                            icon: 'info',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 6000,
                            timerProgressBar: true,
                            background: '#0f172a',
                            color: '#ffffff',
                            showCloseButton: true,
                            customClass: {
                                popup: 'rounded-2xl border border-slate-800 shadow-2xl'
                            },
                            didOpen: (toast) => {
                                toast.addEventListener('click', () => {
                                    if (clickUrl) {
                                        window.location.href = clickUrl;
                                    }
                                });
                            }
                        });
                    });
                }
            })
            .catch((err) => {
                console.error('Service Worker registration failed:', err);
            });
    }

    // Trigger updates on notifications count and bell UI dynamically
    if (window.fetchUnreadNotifications) {
        window.fetchUnreadNotifications();
    }
});
