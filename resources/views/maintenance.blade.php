<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="System is currently under maintenance. We will be back online shortly.">
    <meta name="theme-color" content="#090d16">
    <title>System Under Maintenance | Solcon Industries</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>
    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }
        .animate-pulse-slow {
            animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        .glow-effect {
            box-shadow: 0 0 50px -10px rgba(99, 102, 241, 0.12);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-100 flex flex-col items-center justify-center p-4 overflow-hidden relative selection:bg-indigo-500/30 selection:text-indigo-205 antialiased">
    <!-- Animated background glows -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px] animate-pulse-slow pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-blue-600/10 rounded-full blur-[150px] animate-pulse-slow pointer-events-none" style="animation-delay: 2s;"></div>

    <div class="w-full max-w-lg relative z-10 animate-fade-in">
        <!-- Main Card -->
        <div class="glass-card glow-effect rounded-3xl p-8 md:p-12 text-center flex flex-col items-center">
            <!-- Glowing Logo Container -->
            <div class="relative mb-8">
                <div class="absolute inset-0 bg-indigo-500/20 rounded-2xl blur-xl animate-pulse"></div>
                <div class="relative bg-slate-900 border border-white/10 p-4 rounded-2xl">
                    <img src="/icons/icon-192x192.png" alt="Solcon Logo" class="h-14 w-14 object-contain">
                </div>
            </div>

            <!-- Loading Indicator -->
            <div class="relative mb-6 flex items-center justify-center">
                <div class="w-16 h-16 border-4 border-indigo-500/20 border-t-indigo-500 rounded-full animate-spin"></div>
                <div class="absolute w-10 h-10 bg-indigo-500/10 rounded-full animate-ping"></div>
            </div>

            <!-- Heading -->
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white mb-4 bg-gradient-to-r from-white via-slate-200 to-slate-400 bg-clip-text text-transparent">
                System Under Maintenance
            </h1>

            <!-- Description -->
            <p class="text-sm md:text-base text-slate-400 font-medium leading-relaxed max-w-sm mb-8">
                We are currently performing scheduled upgrades to improve system performance. The ERP will be back online shortly.
            </p>

            <!-- Status Info -->
            <div class="flex items-center gap-2 px-4 py-2 bg-white/5 rounded-full border border-white/5 text-xs text-indigo-300 font-semibold tracking-wide uppercase">
                <span class="h-2 w-2 rounded-full bg-indigo-400 animate-pulse"></span>
                Upgrading Database Schema
            </div>
        </div>

        <div class="text-center mt-8">
            <p class="text-xs text-slate-650">
                &copy; {{ date('Y') }} Solcon Industries. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
