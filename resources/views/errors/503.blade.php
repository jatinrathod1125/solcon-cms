<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $settings['title'] ?? 'System Under Maintenance' }} | Solcon ERP</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    animation: {
                        'spin-slow': 'spin 12s linear infinite',
                        'spin-reverse': 'spin-back 8s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }
        @keyframes spin-back {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(168, 85, 247, 0.15), transparent 40%),
                        #0b0f19;
        }
        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .particle {
            position: absolute;
            pointer-events: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            animation: float-particle 15s linear infinite;
        }
        @keyframes float-particle {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 0.8; }
            90% { opacity: 0.8; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 overflow-hidden relative text-white">

    <!-- Floating Particles Container -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none" id="particles-container"></div>

    <!-- Main Container -->
    <div class="w-full max-w-2xl relative z-10 animate-float">
        
        <!-- Apple/Stripe inspired Glassmorphic Card -->
        <main class="glass-panel rounded-3xl p-8 md:p-12 shadow-2xl relative overflow-hidden flex flex-col items-center text-center">
            
            <!-- Glow background effect inside the card -->
            <div class="absolute -top-24 -left-24 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Header Branding -->
            <header class="mb-8 flex flex-col items-center">
                @if($settings['logo'] ?? null)
                    <img src="{{ asset($settings['logo']) }}" alt="Company Logo" class="h-12 w-auto mb-4 object-contain">
                @else
                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center shadow-lg shadow-indigo-500/30 mb-4">
                        <i data-lucide="shield-alert" class="w-6 h-6 text-white"></i>
                    </div>
                @endif
                <span class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-indigo-400 bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-500/25 shadow-sm">
                    System Maintenance Active
                </span>
            </header>

            <!-- Ticking Live Time -->
            <div class="mb-6 text-xs font-medium text-slate-400 flex items-center gap-2">
                <i data-lucide="clock" class="w-3.5 h-3.5 animate-pulse-slow"></i>
                <span id="live-time">Loading system clock...</span>
            </div>

            <!-- Animated Premium Gears Grid -->
            <div class="relative w-32 h-32 mb-8 flex items-center justify-center">
                <!-- Large Gear -->
                <svg class="absolute w-20 h-20 text-indigo-500 animate-spin-slow opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
                <!-- Medium Gear (Reverse) -->
                <svg class="absolute -top-1 -right-1 w-12 h-12 text-purple-500 animate-spin-reverse opacity-75" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
                <!-- Small Gear (Bottom Left) -->
                <svg class="absolute -bottom-1 -left-1 w-8 h-8 text-indigo-400 animate-spin-slow opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
            </div>

            <!-- Content -->
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white mb-4">
                {{ $settings['title'] ?? 'System Under Maintenance' }}
            </h1>
            <p class="text-sm md:text-base text-slate-350 max-w-md mb-8 leading-relaxed">
                {{ $settings['message'] ?? 'We are upgrading our systems to serve you better. We apologize for any temporary inconvenience.' }}
            </p>

            <!-- Estimated Downtime Banner -->
            <div class="w-full bg-white/5 border border-white/5 rounded-2xl p-5 mb-8 flex items-center justify-between gap-4 text-left">
                <div>
                    <span class="block text-[10px] uppercase tracking-wider font-semibold text-slate-400">Estimated Downtime</span>
                    <strong class="text-sm text-indigo-300 font-bold block mt-0.5" id="downtime-text">{{ $settings['downtime'] ?? '2 Hours' }}</strong>
                </div>
                <!-- Countdown Visual -->
                <div class="flex items-center gap-1.5 bg-slate-900/60 px-4 py-2.5 rounded-xl border border-white/5" id="countdown-box">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-bold font-mono tracking-wider text-emerald-400" id="countdown-timer">02:00:00</span>
                </div>
            </div>

            <!-- Support Details & Unlock Action -->
            <footer class="w-full pt-6 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-left flex items-center gap-2">
                    <div class="h-8 w-8 rounded-lg bg-indigo-500/10 border border-indigo-500/25 flex items-center justify-center text-indigo-400">
                        <i data-lucide="help-circle" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="block text-[9px] uppercase tracking-wider text-slate-400">Need Assistance?</span>
                        <a href="mailto:{{ $settings['contact'] ?? 'support@solcon.com' }}" class="text-xs font-bold text-slate-200 hover:text-indigo-400 transition">
                            {{ $settings['contact'] ?? 'support@solcon.com' }}
                        </a>
                    </div>
                </div>

                <!-- Admin Unlock Action Link -->
                <a href="/unlock" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/5 border border-white/15 text-xs font-bold text-slate-250 hover:bg-white/10 hover:border-white/20 transition-all">
                    <i data-lucide="key-round" class="w-3.5 h-3.5 text-indigo-400"></i>
                    <span>Admin Bypass</span>
                </a>
            </footer>

        </main>
    </div>

    <!-- Live clock and floating particles generation scripts -->
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // 1. Live system clock
        function updateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
            document.getElementById('live-time').innerText = now.toLocaleDateString('en-US', options);
        }
        setInterval(updateTime, 1000);
        updateTime();

        // 2. Generate Floating Particles
        const container = document.getElementById('particles-container');
        const particleCount = 20;

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            
            // Random sizes, positions, delays, durations
            const size = Math.random() * 4 + 2; // 2px to 6px
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            particle.style.left = `${Math.random() * 100}vw`;
            particle.style.animationDelay = `${Math.random() * 15}s`;
            particle.style.animationDuration = `${Math.random() * 10 + 10}s`; // 10s to 20s
            
            container.appendChild(particle);
        }

        // 3. Simple countdown parser
        function startCountdown() {
            const downtimeText = "{{ $settings['downtime'] ?? '2 hours' }}";
            let totalSeconds = 7200; // Default 2 hours

            // Parse simple expressions like "2 hours" or "30 minutes"
            const num = parseInt(downtimeText);
            if (!isNaN(num)) {
                if (downtimeText.toLowerCase().includes('hour')) {
                    totalSeconds = num * 3600;
                } else if (downtimeText.toLowerCase().includes('min')) {
                    totalSeconds = num * 60;
                } else if (downtimeText.toLowerCase().includes('sec')) {
                    totalSeconds = num;
                }
            }

            const timerEl = document.getElementById('countdown-timer');
            
            function tick() {
                if (totalSeconds <= 0) {
                    timerEl.innerText = "00:00:00";
                    return;
                }
                totalSeconds--;
                
                const hours = Math.floor(totalSeconds / 3600).toString().padStart(2, '0');
                const minutes = Math.floor((totalSeconds % 3600) / 60).toString().padStart(2, '0');
                const seconds = (totalSeconds % 60).toString().padStart(2, '0');
                
                timerEl.innerText = `${hours}:${minutes}:${seconds}`;
            }

            tick();
            setInterval(tick, 1000);
        }
        startCountdown();
    </script>
</body>
</html>
