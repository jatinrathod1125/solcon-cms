@extends('layouts.app')

@section('title', 'Epoxy Operations Floor')
@section('header-title', 'Epoxy Operations')

@php
    $appBrand = currentBrand();
    $isFixora = ($appBrand && $appBrand->id == 2);
@endphp

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-16">

    <!-- Clean Professional Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold uppercase tracking-wider {{ $isFixora ? 'text-emerald-700' : 'text-orange-700' }}">Department Floor</span>
                <span class="text-slate-300">/</span>
                <span class="text-xs font-semibold text-slate-500">{{ $dept->name ?? 'Epoxy (EPX)' }}</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                <i data-lucide="layers" class="w-6 h-6 {{ $isFixora ? 'text-emerald-600' : 'text-orange-600' }}"></i>
                Epoxy Operations Floor
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage ready component preparations, package finished bucket kits, and view warehouse execution logs.</p>
        </div>

        <!-- Action Links -->
        <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
            <a href="{{ route('epoxy.component-entry') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-xs sm:text-sm gap-2 transition shadow-xs">
                <i data-lucide="boxes" class="w-4 h-4 text-slate-500"></i>
                <span>Component Entry</span>
            </a>
            <a href="{{ route('epoxy.bucket-assembly') }}" class="inline-flex items-center justify-center px-4.5 py-2.5 {{ $isFixora ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-orange-600 hover:bg-orange-700' }} text-white font-bold rounded-xl text-xs sm:text-sm gap-2 transition shadow-xs">
                <i data-lucide="package-plus" class="w-4 h-4"></i>
                <span>Bucket Assembly</span>
            </a>
        </div>
    </div>

    <!-- Daily Performance & Date Filter Strip -->
    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-3.5">
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="bar-chart-2" class="w-4 h-4 {{ $isFixora ? 'text-emerald-600' : 'text-orange-600' }}"></i>
                    <span>Floor Summary Output</span>
                </h3>
                <p class="text-xs text-slate-500">Production statistics and prepared components for the selected date.</p>
            </div>

            <!-- Date Selector -->
            <form method="GET" action="{{ route('epoxy.index') }}" class="flex items-center gap-2">
                <label for="dateFilter" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Date:</label>
                <input type="date" id="dateFilter" name="date" value="{{ $targetDate }}" onchange="this.form.submit()"
                    class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-900 font-semibold focus:outline-none focus:bg-white {{ $isFixora ? 'focus:border-emerald-500' : 'focus:border-orange-500' }}">
            </form>
        </div>

        <!-- 3 Key Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block">Assembled Kits</span>
                    <span class="text-2xl font-black text-slate-900 font-mono">{{ $todayStats['assembled_kits_today'] ?? 0 }}</span>
                    <span class="text-[10px] text-slate-400 block font-medium">Finished units</span>
                </div>
                <div class="w-10 h-10 rounded-xl {{ $isFixora ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }} flex items-center justify-center font-bold">
                    <i data-lucide="package-check" class="w-5 h-5"></i>
                </div>
            </div>

            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block">Prepared Components</span>
                    <span class="text-2xl font-black {{ $isFixora ? 'text-emerald-700' : 'text-orange-700' }} font-mono">{{ $todayStats['components_prepared_today'] ?? 0 }}</span>
                    <span class="text-[10px] text-slate-400 block font-medium">Bulk units</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-200 text-slate-700 flex items-center justify-center font-bold">
                    <i data-lucide="package-check" class="w-5 h-5"></i>
                </div>
            </div>

            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block">Active Kits</span>
                    <span class="text-2xl font-black text-slate-900 font-mono">{{ $todayStats['total_products'] ?? $products->count() }}</span>
                    <span class="text-[10px] text-slate-400 block font-medium">Configured formulas</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                    <i data-lucide="package-check" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <!-- Daily Components Grid -->
        <div class="pt-2">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2.5">Components Prepared on {{ \Carbon\Carbon::parse($targetDate)->format('d M Y') }}:</span>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @forelse($dailySummary as $item)
                    <div class="bg-white border border-slate-200 p-3 rounded-xl flex flex-col justify-between shadow-2xs hover:border-slate-300 transition">
                        <div class="space-y-1">
                            <span class="text-[9px] font-mono text-slate-400 uppercase tracking-wider block">{{ $item->component->category }}</span>
                            <h4 class="text-xs font-bold text-slate-800 truncate">{{ $item->component->name }}</h4>
                            @if($item->color)
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-slate-600 bg-slate-100 px-1.5 py-0.2 rounded">
                                    {{ $item->color->name }}
                                </span>
                            @endif
                        </div>
                        <div class="mt-3 flex items-baseline justify-between border-t border-slate-100 pt-2">
                            <span class="text-lg font-black text-slate-900 font-mono">{{ $item->total_qty }}</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $item->component->unit->code ?? 'PCS' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-6 text-center text-slate-400 bg-slate-50/60 rounded-xl border border-dashed border-slate-200">
                        <p class="text-xs font-medium">No component preparations registered for {{ \Carbon\Carbon::parse($targetDate)->format('d M Y') }}.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Dual Logs Grid: Bucket Assemblies & Component Preparations -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- 1. Bucket Assembly Log -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg {{ $isFixora ? 'bg-emerald-100 text-emerald-800' : 'bg-orange-100 text-orange-800' }} flex items-center justify-center font-bold">
                        <i data-lucide="package" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Bucket Assembly Log</h3>
                        <p class="text-[11px] text-slate-500">History of assembled finished kits.</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md font-mono">
                    {{ $assemblies->total() }} Total
                </span>
            </div>

            <!-- Assemblies Search / Filter -->
            <form method="GET" action="{{ route('epoxy.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                <input type="hidden" name="date" value="{{ $targetDate }}">
                <div class="sm:col-span-8 relative">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID or remarks..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-3 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white {{ $isFixora ? 'focus:border-emerald-500' : 'focus:border-orange-500' }}">
                </div>
                <div class="sm:col-span-4 flex items-center gap-1.5">
                    <button type="submit" class="w-full py-1.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                        Filter
                    </button>
                    @if(request()->has('search') || request()->has('epoxy_product_id'))
                        <a href="{{ route('epoxy.index', ['date' => $targetDate]) }}" class="py-1.5 px-2 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 rounded-xl text-xs transition" title="Clear Filter">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Assemblies Cards -->
            <div class="space-y-2.5 max-h-[460px] overflow-y-auto pr-1">
                @forelse($assemblies as $assembly)
                    <div class="bg-slate-50 border border-slate-200/90 p-3.5 rounded-xl space-y-2.5 hover:border-slate-300 transition">
                        <div class="flex items-start justify-between text-xs">
                            <div>
                                <span class="font-mono text-slate-900 font-bold block">#EPX-{{ str_pad($assembly->id, 5, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $assembly->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-slate-900 font-black block text-sm font-mono">+{{ $assembly->quantity }} kits</span>
                                <span class="text-[10px] text-slate-500 font-medium">By {{ $assembly->operator->name ?? 'System' }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-t border-slate-200/60 pt-2 text-xs">
                            <div class="flex items-center gap-1.5 truncate">
                                <span class="font-bold text-slate-800 truncate">{{ $assembly->product->name ?? 'Epoxy Kit' }}</span>
                                @if($assembly->product?->brand)
                                    <span class="text-[9px] font-bold text-slate-500 bg-white border border-slate-200 px-1.5 py-0.2 rounded">
                                        {{ $assembly->product->brand->name }}
                                    </span>
                                @endif
                            </div>

                            @if($assembly->epoxyFillerColor)
                                <span class="px-2 py-0.5 rounded bg-white border border-slate-200 text-slate-700 text-[10px] font-bold shrink-0">
                                    {{ $assembly->epoxyFillerColor->name }}
                                </span>
                            @endif
                        </div>

                        @if($assembly->remarks)
                            <div class="text-[10px] text-slate-500 bg-white p-2 rounded-lg border border-slate-200/80">
                                <span class="font-bold text-slate-600">Note:</span> {{ $assembly->remarks }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                        <i data-lucide="package-open" class="w-7 h-7 mx-auto mb-1.5 text-slate-300"></i>
                        <p class="text-xs font-semibold">No bucket assemblies found.</p>
                    </div>
                @endforelse
            </div>

            <!-- Assemblies Pagination -->
            @if($assemblies->hasPages())
                <div class="pt-2">
                    {{ $assemblies->appends(['date' => $targetDate, 'search' => request('search')])->links() }}
                </div>
            @endif
        </div>

        <!-- 2. Component Preparation Log -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                        <i data-lucide="package-check" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Component Preparation Log</h3>
                        <p class="text-[11px] text-slate-500">History of ready components prepared in bulk.</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md font-mono">
                    {{ $preparations->total() }} Total
                </span>
            </div>

            <!-- Preparations Cards -->
            <div class="space-y-2.5 max-h-[500px] overflow-y-auto pr-1">
                @forelse($preparations as $prep)
                    <div class="bg-slate-50 border border-slate-200/90 p-3.5 rounded-xl space-y-2.5 hover:border-slate-300 transition">
                        <div class="flex items-start justify-between text-xs">
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold text-slate-800">{{ $prep->component->name ?? 'Component' }}</span>
                                    @if($prep->component?->brand)
                                        <span class="text-[9px] font-bold text-slate-500 bg-white border border-slate-200 px-1.5 py-0.2 rounded">
                                            {{ $prep->component->brand->name }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-slate-400 font-mono block mt-0.5">{{ $prep->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-slate-900 font-black font-mono text-sm block">+{{ $prep->quantity }} {{ $prep->component->unit->code ?? 'PCS' }}</span>
                                <span class="text-[10px] text-slate-500">By {{ $prep->operator->name ?? 'System' }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-t border-slate-200/60 pt-2 text-[11px]">
                            <div class="text-slate-500">
                                <span class="font-semibold">{{ $prep->component->category ?? 'General' }}</span>
                                <span class="text-slate-300 mx-1">•</span>
                                <span>{{ $prep->component->purpose ?? 'Assembly' }}</span>
                            </div>

                            @if($prep->color)
                                <span class="px-2 py-0.5 rounded bg-white border border-slate-200 text-slate-700 text-[10px] font-bold">
                                    {{ $prep->color->name }}
                                </span>
                            @endif
                        </div>

                        @if($prep->remarks)
                            <div class="text-[10px] text-slate-500 bg-white p-2 rounded-lg border border-slate-200/80">
                                <span class="font-bold text-slate-600">Note:</span> {{ $prep->remarks }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                        <i data-lucide="clipboard-list" class="w-7 h-7 mx-auto mb-1.5 text-slate-300"></i>
                        <p class="text-xs font-semibold">No component preparations registered.</p>
                    </div>
                @endforelse
            </div>

            <!-- Preparations Pagination -->
            @if($preparations->hasPages())
                <div class="pt-2">
                    {{ $preparations->appends(['date' => $targetDate])->links() }}
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
