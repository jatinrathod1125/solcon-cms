<!-- PWA Install Prompt Banner (Android / Desktop Chrome / Windows / macOS) -->
<div id="pwaInstallBanner" class="pwa-install-banner hidden" style="display: none;">
    <div class="flex items-center gap-3 min-w-0">
        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center shrink-0 shadow-md">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
        </div>
        <div class="min-w-0">
            <h4 class="text-xs font-extrabold text-white tracking-wide">Install Solcon App</h4>
            <p class="text-[11px] text-slate-350 truncate">Add to home screen for fast offline access</p>
        </div>
    </div>
    <div class="flex items-center gap-2 shrink-0">
        <button id="pwaInstallBtn" type="button"
            class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold px-3 py-1.5 rounded-xl transition shadow-sm">
            Install
        </button>
        <button id="pwaCloseBtn" type="button" class="text-slate-400 hover:text-white p-1 rounded-lg transition"
            aria-label="Close">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<!-- PWA iOS Safari Install Tip Banner -->
<div id="pwaIosBanner" class="pwa-install-banner hidden" style="display: none;">
    <div class="flex items-center gap-3 min-w-0">
        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center shrink-0 shadow-md">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
        </div>
        <div class="min-w-0">
            <h4 class="text-xs font-extrabold text-white tracking-wide">Install Solcon on iPhone/iPad</h4>
            <p class="text-[11px] text-slate-350 leading-tight">Tap Share <span class="text-blue-400 font-bold">⎋</span>
                then select <span class="text-white font-bold">'Add to Home Screen'</span></p>
        </div>
    </div>
    <button id="pwaIosCloseBtn" type="button" class="text-slate-400 hover:text-white p-1 rounded-lg transition shrink-0"
        aria-label="Close">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<!-- PWA Update Available Toast -->
<div id="pwaUpdateToast" class="pwa-update-toast hidden" style="display: none;">
    <div class="flex items-center gap-2">
        <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
        </span>
        <span class="font-bold">App Update Available</span>
    </div>
    <div class="flex items-center gap-1.5">
        <button id="pwaReloadBtn" type="button"
            class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-lg transition shadow-sm">
            Reload
        </button>
        <button id="pwaUpdateCloseBtn" type="button" class="text-slate-400 hover:text-white p-1 rounded-lg transition"
            aria-label="Close">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<script>
    (function() {
    let deferredPrompt = null;
    const banner = document.getElementById('pwaInstallBanner');
    const installBtn = document.getElementById('pwaInstallBtn');
    const closeBtn = document.getElementById('pwaCloseBtn');
    const iosBanner = document.getElementById('pwaIosBanner');
    const iosCloseBtn = document.getElementById('pwaIosCloseBtn');
    const updateToast = document.getElementById('pwaUpdateToast');
    const reloadBtn = document.getElementById('pwaReloadBtn');
    const updateCloseBtn = document.getElementById('pwaUpdateCloseBtn');

    function showElement(el) {
        if (!el) return;
        el.style.display = 'flex';
        el.classList.remove('hidden');
    }

    function hideElement(el) {
        if (!el) return;
        el.style.display = 'none';
        el.classList.add('hidden');
    }

    // Check if app is running in Standalone (Installed) mode
    const isStandalone = Boolean(
        window.navigator.standalone ||
        window.matchMedia('(display-mode: standalone)').matches ||
        window.matchMedia('(display-mode: window-controls-overlay)').matches ||
        (document.referrer && document.referrer.includes('android-app://'))
    );

    // Handle Updates and service worker lifecycle (Single registration is managed in firebase.js)
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            function setupUpdateListener(registration) {
                // Only show update notification if page was ALREADY controlled by an active SW
                if (registration.waiting && navigator.serviceWorker.controller) {
                    showUpdateToast(registration.waiting);
                }

                registration.addEventListener('updatefound', function() {
                    const newWorker = registration.installing;
                    if (!newWorker) return;
                    newWorker.addEventListener('statechange', function() {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            showUpdateToast(newWorker);
                        }
                    });
                });
            }

            // Hook into the service worker registration
            if (window.swRegistration) {
                setupUpdateListener(window.swRegistration);
            } else {
                window.addEventListener('swRegistered', function(event) {
                    setupUpdateListener(event.detail);
                });
            }

            let refreshing = false;
            navigator.serviceWorker.addEventListener('controllerchange', function() {
                if (!refreshing) {
                    refreshing = true;
                    window.location.reload();
                }
            });
        });
    }

    // Desktop / Android Install Prompt (ONLY IF NOT INSTALLED / NOT STANDALONE)
    if (!isStandalone) {
        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;
            if (sessionStorage.getItem('pwa_dismissed') !== 'true' && banner) {
                showElement(banner);
            }
        });
    }

    if (installBtn) {
        installBtn.addEventListener('click', function() {
            if (!deferredPrompt) return;
            hideElement(banner);
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(function() {
                deferredPrompt = null;
            });
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            hideElement(banner);
            sessionStorage.setItem('pwa_dismissed', 'true');
        });
    }

    // Handle iOS Installation Prompt (STRICT PLATFORM CHECK & NOT STANDALONE)
    const isIos = (/iPad|iPhone|iPod/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1)) && !/Windows|Android/i.test(navigator.userAgent);

    if (!isStandalone && isIos && sessionStorage.getItem('pwa_ios_dismissed') !== 'true' && iosBanner) {
        showElement(iosBanner);
    }

    if (iosCloseBtn) {
        iosCloseBtn.addEventListener('click', function() {
            hideElement(iosBanner);
            sessionStorage.setItem('pwa_ios_dismissed', 'true');
        });
    }

    // Update Toast Handler
    function showUpdateToast(worker) {
        if (!updateToast || sessionStorage.getItem('pwa_update_dismissed') === 'true') return;
        showElement(updateToast);
        if (reloadBtn) {
            reloadBtn.onclick = function() {
                worker.postMessage({ type: 'SKIP_WAITING' });
            };
        }
    }

    if (updateCloseBtn) {
        updateCloseBtn.addEventListener('click', function() {
            hideElement(updateToast);
            sessionStorage.setItem('pwa_update_dismissed', 'true');
        });
    }
})();
</script>
