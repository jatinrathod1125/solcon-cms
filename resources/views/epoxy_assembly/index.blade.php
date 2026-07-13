@extends('layouts.app')

@section('title', 'Epoxy Operations Floor')
@section('header-title', 'Epoxy Operations')

@section('content')
<div class="space-y-6">
    <!-- Department Header & Quick Stats -->
    <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-indigo-950/60 border border-slate-800 p-6 rounded-3xl relative overflow-hidden shadow-2xl">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 uppercase tracking-widest mb-3">
                    <span class="h-1.5 w-1.5 rounded-full bg-cyan-400 animate-pulse"></span>Epoxy Operations Center
                </span>
                <h2 class="text-3xl font-black text-white tracking-tight">Industrial Manufacturing</h2>
                <p class="text-sm text-slate-400 mt-2 max-w-xl">Log manual component preparations and package finished kits. All raw material deductions are computed dynamically from active formulation models.</p>
            </div>
            
            <div class="flex flex-wrap gap-3 shrink-0">
                <a href="{{ route('epoxy.component-entry') }}" class="inline-flex items-center justify-center px-5 py-3.5 bg-slate-900 border border-slate-800 hover:border-cyan-500/30 text-white font-bold rounded-2xl transition-all text-sm gap-2.5 shadow-lg shadow-slate-950/50">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-cyan-400"></i>
                    <span>Component Entry</span>
                </a>
                <a href="{{ route('epoxy.bucket-assembly') }}" class="inline-flex items-center justify-center px-6 py-3.5 bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 text-white font-extrabold rounded-2xl transition-all duration-205 shadow-xl shadow-cyan-500/20 text-sm gap-2.5">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    <span>Bucket Assembly</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Live Today's Summary Report -->
    <div class="bg-slate-955 border border-slate-850 rounded-2xl p-5 md:p-6 shadow-xl space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-900 pb-4">
            <div>
                <h3 class="text-sm font-extrabold text-white uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 text-cyan-400"></i>
                    <span>Live Today's Summary</span>
                </h3>
                <p class="text-xs text-slate-400">Daily sums of components prepared by workers on the factory floor.</p>
            </div>
            
            <form method="GET" action="{{ route('epoxy.index') }}" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ $targetDate }}" onchange="this.form.submit()"
                    class="bg-slate-900 border border-slate-850 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:ring-1 focus:ring-cyan-500">
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($dailySummary as $item)
                <div class="bg-slate-900/60 border border-slate-850 p-4.5 rounded-2xl flex flex-col justify-between hover:border-slate-800 transition-all">
                    <div class="space-y-1">
                        <span class="text-[9px] font-mono text-slate-500 uppercase tracking-widest block">{{ $item->component->category }}</span>
                        <span class="text-sm font-bold text-white block truncate">{{ $item->component->name }}</span>
                    </div>
                    <div class="mt-4 flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-cyan-400 font-mono tracking-tight">{{ $item->total_qty }}</span>
                        <span class="text-xs text-slate-550 font-bold uppercase tracking-wider">{{ $item->component->unit->code }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-slate-500">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <i data-lucide="clipboard-list" class="w-8 h-8 text-slate-700"></i>
                        <span class="text-xs font-semibold">No components prepared on {{ \Carbon\Carbon::parse($targetDate)->format('d M Y') }}.</span>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Logs Grid (Bucket Assembly & Components list) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Bucket Assembly Log -->
        <div class="bg-slate-955 border border-slate-850 rounded-2xl p-5 md:p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-900 pb-3">
                <h3 class="text-sm font-extrabold text-white uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="box" class="w-4 h-4 text-purple-400"></i>
                    <span>Bucket Assembly Log</span>
                </h3>
            </div>
            
            <div class="space-y-3">
                @forelse($assemblies as $assembly)
                    <div class="bg-slate-900/40 border border-slate-850/70 p-4 rounded-xl space-y-3 hover:border-slate-800 transition-all">
                        <div class="flex items-start justify-between text-xs">
                            <div>
                                <span class="font-mono text-purple-400 font-bold block">#EPX-{{ str_pad($assembly->id, 5, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-slate-500 font-mono">{{ $assembly->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-white font-extrabold block text-sm">{{ $assembly->quantity }} kits</span>
                                <span class="text-[10px] text-slate-500 font-medium">Operator: {{ $assembly->operator->name }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-900 pt-2.5 text-xs">
                            <div>
                                <span class="text-[9px] text-slate-500 uppercase tracking-wider block">Target Product</span>
                                <span class="font-semibold text-slate-350">{{ $assembly->product->name }}</span>
                            </div>
                            @if($assembly->epoxyFillerColor)
                                <span class="px-2 py-0.5 rounded bg-purple-950/40 text-purple-400 border border-purple-900/40 text-[10px] font-bold">
                                    {{ $assembly->epoxyFillerColor->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-550">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <i data-lucide="package-open" class="w-8 h-8 text-slate-700"></i>
                            <span class="text-xs font-semibold">No bucket assemblies registered.</span>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Assemblies Pagination -->
            @if($assemblies->hasPages())
                <div class="pt-2">
                    {{ $assemblies->links() }}
                </div>
            @endif
        </div>

        <!-- Component Preparation Log -->
        <div class="bg-slate-955 border border-slate-850 rounded-2xl p-5 md:p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-900 pb-3">
                <h3 class="text-sm font-extrabold text-white uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-cyan-400"></i>
                    <span>Component Preparation Log</span>
                </h3>
            </div>
            
            <div class="space-y-3">
                @forelse($preparations as $prep)
                    <div class="bg-slate-900/40 border border-slate-850/70 p-4 rounded-xl space-y-3 hover:border-slate-800 transition-all">
                        <div class="flex items-start justify-between text-xs">
                            <div>
                                <span class="font-bold text-white block">{{ $prep->component->name }}</span>
                                <span class="text-slate-500 font-mono">{{ $prep->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-cyan-400 font-extrabold font-mono text-sm">+{{ $prep->quantity }} {{ $prep->component->unit->code }}</span>
                                <span class="text-[10px] text-slate-500 block">Operator: {{ $prep->operator->name }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-900 pt-2.5 text-[10px]">
                            <div>
                                <span class="text-slate-500 uppercase tracking-wider block">Classification</span>
                                <span class="text-slate-350 font-bold">{{ $prep->component->category }} &bull; {{ $prep->component->purpose }}</span>
                            </div>
                            @if($prep->component->color)
                                <span class="px-2 py-0.5 rounded bg-cyan-950/40 text-cyan-450 border border-cyan-900/40 font-bold">
                                    {{ $prep->component->color->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-550">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <i data-lucide="clipboard-list" class="w-8 h-8 text-slate-700"></i>
                            <span class="text-xs font-semibold">No component preparations registered.</span>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Preparations Pagination -->
            @if($preparations->hasPages())
                <div class="pt-2">
                    {{ $preparations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
