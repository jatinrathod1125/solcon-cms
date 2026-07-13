import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";

$(document).ready(function () {
    const configMeta = $('meta[name="firebase-config"]').attr('content');
    if (!configMeta) return;

    let firebaseConfig;
    try {
        firebaseConfig = JSON.parse(configMeta);
    } catch (e) {
        console.error("Failed to parse Firebase configuration.");
        return;
    }

    if (!firebaseConfig.api_key || 
        !firebaseConfig.project_id || 
        firebaseConfig.api_key === 'mock-api-key-here' || 
        firebaseConfig.api_key.startsWith('mock')) {
        console.warn("Firebase is using mock/placeholder credentials. Push notifications disabled. Please set a valid FIREBASE_API_KEY and other parameters in your .env file.");
        return;
    }

    // Initialize Firebase
    const app = initializeApp({
        apiKey: firebaseConfig.api_key,
        authDomain: firebaseConfig.auth_domain,
        projectId: firebaseConfig.project_id,
        storageBucket: firebaseConfig.storage_bucket,
        messagingSenderId: firebaseConfig.messaging_sender_id,
        appId: firebaseConfig.app_id,
        measurementId: firebaseConfig.measurement_id
    });

    const messaging = getMessaging(app);

    // Request notification permission and register token
    function requestPermissionAndGetToken() {
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
                    serviceWorkerRegistration: window.swRegistration
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

    // Register Service Worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/firebase-messaging-sw.js')
            .then((registration) => {
                console.log('FCM Service Worker registered with scope:', registration.scope);
                window.swRegistration = registration;
                
                // Permission request after login is completed
                requestPermissionAndGetToken();
            })
            .catch((err) => {
                console.error('Service Worker registration failed:', err);
            });
    }

    // Listen for foreground messages
    onMessage(messaging, (payload) => {
        console.log('Received foreground message:', payload);
        
        // Show in-app custom notification banner using SweetAlert toast
        Swal.fire({
            title: payload.notification.title,
            text: payload.notification.body,
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
                    const clickUrl = payload.data && payload.data.click_action;
                    if (clickUrl) {
                        window.location.href = clickUrl;
                    }
                });
            }
        });

        // Trigger updates on notifications count and bell UI dynamically
        if (window.fetchUnreadNotifications) {
            window.fetchUnreadNotifications();
        }
    });
});
