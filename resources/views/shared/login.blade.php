@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black p-4 relative overflow-hidden">
    <!-- Decorative background glow -->
    <div class="absolute top-1/4 left-1/4 -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full bg-cyan-500/10 blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 translate-x-1/2 translate-y-1/2 w-96 h-96 rounded-full bg-indigo-500/10 blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-5xl bg-slate-900/50 backdrop-blur-xl rounded-3xl border border-slate-800 shadow-2xl overflow-hidden flex flex-col md:flex-row relative z-10">
        <!-- Brand Info Side -->
        <div class="md:w-1/2 bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 p-8 md:p-12 flex flex-col justify-between border-b md:border-b-0 md:border-r border-slate-800/85">
            <div class="flex items-center space-x-2">
                <span class="p-2 bg-gradient-to-tr from-cyan-500 to-indigo-600 rounded-xl shadow-lg shadow-cyan-500/20 text-white font-black text-xl leading-none">SI</span>
                <span class="text-xl font-bold tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-200 to-slate-400">SOLCON</span>
            </div>
            
            <div class="my-12 space-y-4">
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    Production Tracking <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-500">Digitized</span>.
                </h1>
                <p class="text-slate-400 text-sm md:text-base leading-relaxed">
                    Designed for Solcon Industries to record batches, formulas, and track inventory movement for Tile Adhesive, Grout, Epoxy, and Resin Hardener.
                </p>
            </div>

            <div class="text-xs text-slate-500 flex items-center space-x-2">
                <span class="inline-block w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                <span>System Status: Online</span>
            </div>
        </div>

        <!-- Login Form Side -->
        <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-slate-950/40">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-white">Welcome back</h2>
                <p class="text-sm text-slate-400 mt-1">Please enter your credentials to access your dashboard</p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/25 text-rose-400 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="block w-full pl-10 pr-4 py-3 bg-slate-900/80 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                            placeholder="username@solcon.com">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Password</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                            class="block w-full pl-10 pr-4 py-3 bg-slate-900/80 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-slate-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-800 bg-slate-900 text-cyan-500 focus:ring-cyan-500/30 focus:ring-offset-0 mr-2 cursor-pointer">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" 
                    class="w-full py-3 px-4 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-200 transform active:scale-[0.98] shadow-lg shadow-cyan-500/20 hover:shadow-cyan-500/35 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 flex items-center justify-center space-x-2 text-sm">
                    <span>Sign In</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Lucide Icons CDN script -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
