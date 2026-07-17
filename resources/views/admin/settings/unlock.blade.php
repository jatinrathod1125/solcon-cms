<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unlock Bypass | Solcon ERP</title>
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
            50% { transform: translateY(-5px) rotate(1deg); }
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.12), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(168, 85, 247, 0.12), transparent 40%),
                        #0b0f19;
        }
        .glass-panel {
            background: rgba(17, 24, 39, 0.75);
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
    <div class="w-full max-w-md relative z-10 animate-float">
        
        <!-- Apple/Stripe inspired Glassmorphic Card -->
        <main class="glass-panel rounded-3xl p-8 shadow-2xl relative overflow-hidden flex flex-col items-center">
            
            <!-- Glow background effect inside the card -->
            <div class="absolute -top-20 -left-20 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Header Branding -->
            <header class="mb-6 text-center flex flex-col items-center">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center shadow-lg shadow-indigo-500/20 mb-3">
                    <i data-lucide="key-round" class="w-5 h-5 text-white"></i>
                </div>
                <h2 class="text-xl font-bold tracking-tight text-white">Unlock Bypass</h2>
                <p class="text-xs text-slate-400 mt-1">Enter your password to bypass maintenance mode for this session.</p>
            </header>

            <!-- Ticking Live Time -->
            <div class="mb-6 text-[10px] font-semibold text-slate-500 flex items-center gap-1.5">
                <i data-lucide="clock" class="w-3 h-3 animate-pulse-slow"></i>
                <span id="live-time">Clock loading...</span>
            </div>

            <!-- Unlock Form -->
            <form action="/unlock" method="POST" class="w-full space-y-4">
                @csrf

                <!-- Error Messages -->
                @if($errors->has('password'))
                    <div class="bg-rose-500/10 border border-rose-500/25 rounded-2xl p-3 flex items-start gap-2.5 text-xs text-rose-350">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-rose-400 mt-0.5"></i>
                        <span>{{ $errors->first('password') }}</span>
                    </div>
                @endif

                <!-- Password Input -->
                <div class="space-y-1.5">
                    <label for="password" class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Unlock Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required autofocus
                            class="block w-full px-4 py-3 bg-slate-900/60 border border-white/10 rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-indigo-650 hover:bg-indigo-750 active:scale-[0.98] text-white font-bold rounded-2xl text-sm transition-all shadow-lg shadow-indigo-600/15 flex items-center justify-center gap-2">
                    <i data-lucide="unlock" class="w-4 h-4"></i>
                    <span>Authenticate Bypass</span>
                </button>
            </form>

            <!-- Footer links -->
            <footer class="mt-6 w-full pt-4 border-t border-white/5 flex justify-center">
                <a href="/" class="inline-flex items-center gap-1 text-[11px] font-bold text-slate-400 hover:text-slate-200 transition">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Back to Downtime Screen</span>
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
            const options = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
            document.getElementById('live-time').innerText = now.toLocaleTimeString('en-US', options);
        }
        setInterval(updateTime, 1000);
        updateTime();

        // 2. Generate Floating Particles
        const container = document.getElementById('particles-container');
        const particleCount = 15;

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            
            // Random sizes, positions, delays, durations
            const size = Math.random() * 3 + 2; // 2px to 5px
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            particle.style.left = `${Math.random() * 100}vw`;
            particle.style.animationDelay = `${Math.random() * 15}s`;
            particle.style.animationDuration = `${Math.random() * 10 + 10}s`; // 10s to 20s
            
            container.appendChild(particle);
        }
    </script>
</body>
</html>
