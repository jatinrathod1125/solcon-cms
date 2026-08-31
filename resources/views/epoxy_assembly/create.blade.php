@extends('layouts.app')

@section('title', 'Bucket Assembly')
@section('header-title', 'Epoxy Assembly Floor')

@php
    $appBrand = currentBrand();
    $isFixora = ($appBrand && $appBrand->id == 2);
@endphp

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-16">

    <!-- Professional Clean Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('epoxy.index') }}" class="inline-flex items-center gap-1 text-xs text-slate-500 hover:text-slate-900 font-semibold transition">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Back to Epoxy Floor</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-xs font-semibold {{ $isFixora ? 'text-emerald-700' : 'text-orange-700' }}">Assembly Floor</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                <i data-lucide="package-check" class="w-6 h-6 {{ $isFixora ? 'text-emerald-600' : 'text-orange-600' }}"></i>
                Bucket Assembly Floor
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Select a finished kit, scale the formulation, verify warehouse stock, and log assembly output.</p>
        </div>

        <!-- Metric Badges -->
        <div class="flex items-center gap-3">
            <div class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-center shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Products</span>
                <span class="text-base font-extrabold text-slate-900">{{ $stats['total_products'] ?? $products->count() }}</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-center shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Formulas</span>
                <span class="text-base font-extrabold {{ $isFixora ? 'text-emerald-600' : 'text-orange-600' }}">{{ $stats['active_formulas'] ?? 0 }}</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-center shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Today Assembled</span>
                <span class="text-base font-extrabold text-slate-900">{{ $stats['today_assembled'] ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Error Alerts -->
    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 p-4 rounded-xl text-rose-800 text-sm space-y-1">
            <div class="flex items-center gap-2 font-bold text-rose-900">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                <span>Please correct the following errors:</span>
            </div>
            <ul class="list-disc pl-6 text-xs text-rose-700 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Workspace Layout -->
    <form id="assemblyForm" action="{{ route('epoxy.bucket-assembly.store') }}" method="POST">
        @csrf
        <input type="hidden" name="epoxy_product_id" id="selected_product_id" value="{{ old('epoxy_product_id') }}">
        <input type="hidden" name="epoxy_filler_color_id" id="selected_color_id" value="{{ old('epoxy_filler_color_id') }}">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- LEFT COLUMN: Assembly Configuration (7 cols) -->
            <div class="lg:col-span-7 space-y-5">

                <!-- 1. Select Product Section -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full {{ $isFixora ? 'bg-emerald-100 text-emerald-800' : 'bg-orange-100 text-orange-800' }} inline-flex items-center justify-center text-xs font-black">1</span>
                                Select Epoxy Product
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Choose the bucket kit to assemble.</p>
                        </div>

                        <!-- Filter Chips -->
                        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-semibold">
                            <button type="button" data-filter="all" class="filter-btn px-3 py-1 rounded-lg bg-white text-slate-900 shadow-xs font-bold transition">All</button>
                            <button type="button" data-filter="color" class="filter-btn px-3 py-1 rounded-lg text-slate-600 hover:text-slate-900 transition">Color Based</button>
                            <button type="button" data-filter="standard" class="filter-btn px-3 py-1 rounded-lg text-slate-600 hover:text-slate-900 transition">Standard</button>
                        </div>
                    </div>

                    <!-- Search Input -->
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5"></i>
                        <input type="text" id="productSearchInput" placeholder="Search product by name or code..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white {{ $isFixora ? 'focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500' : 'focus:border-orange-500 focus:ring-1 focus:ring-orange-500' }} transition">
                    </div>

                    <!-- Product Cards Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-[340px] overflow-y-auto pr-1" id="productsContainer">
                        @forelse($products as $product)
                            @php
                                $hasFormula = $product->activeFormula()->exists();
                                $isColorBased = (bool)$product->requires_color;
                            @endphp
                            <div data-product-id="{{ $product->id }}"
                                 data-product-name="{{ $product->name }}"
                                 data-product-code="{{ $product->code }}"
                                 data-requires-color="{{ $isColorBased ? '1' : '0' }}"
                                 data-has-formula="{{ $hasFormula ? '1' : '0' }}"
                                 data-is-color="{{ $isColorBased ? '1' : '0' }}"
                                 class="product-item-card border border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/70 rounded-xl p-3.5 cursor-pointer transition-all flex flex-col justify-between space-y-3 relative select-none {{ !$hasFormula ? 'opacity-50 cursor-not-allowed' : '' }} {{ old('epoxy_product_id') == $product->id ? ($isFixora ? 'border-emerald-600 bg-emerald-50/40 ring-1 ring-emerald-500' : 'border-orange-600 bg-orange-50/40 ring-1 ring-orange-500') : '' }}">
                                
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-[10px] font-mono font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded">
                                            {{ $product->code }}
                                        </span>
                                        @if($isColorBased)
                                            <span class="text-[10px] font-semibold text-blue-700 bg-blue-50 border border-blue-200 px-1.5 py-0.2 rounded">
                                                Color Variant
                                            </span>
                                        @else
                                            <span class="text-[10px] font-semibold text-slate-600 bg-slate-100 px-1.5 py-0.2 rounded">
                                                Standard Kit
                                            </span>
                                        @endif
                                    </div>

                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900">{{ $product->name }}</h4>
                                        @if($product->description)
                                            <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $product->description }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                                    @if($hasFormula)
                                        <span class="text-emerald-700 font-semibold flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Active Recipe
                                        </span>
                                    @else
                                        <span class="text-slate-400 font-medium flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> No Recipe
                                        </span>
                                    @endif

                                    <span class="selected-badge hidden text-[10px] font-bold {{ $isFixora ? 'text-emerald-700' : 'text-orange-700' }}">
                                        ✓ Selected
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-8 text-center text-slate-400 text-xs font-semibold">
                                No active products found.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- 2. Dynamic Color Selection Section (Visible only when product requires color) -->
                <div id="colorSectionCard" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4 hidden">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full {{ $isFixora ? 'bg-emerald-100 text-emerald-800' : 'bg-orange-100 text-orange-800' }} inline-flex items-center justify-center text-xs font-black">2</span>
                                Select Epoxy Filler Color
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Required for color-based epoxy formulation.</p>
                        </div>

                        <!-- Active Color Preview -->
                        <div id="activeColorTag" class="hidden items-center gap-2 px-2.5 py-1 bg-slate-100 rounded-lg text-xs font-semibold text-slate-800 border border-slate-200">
                            <span id="activeColorDot" class="w-3 h-3 rounded-full border border-slate-300"></span>
                            <span id="activeColorNameText">Select Color</span>
                        </div>
                    </div>

                    <!-- Color Search -->
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5"></i>
                        <input type="text" id="colorSearchInput" placeholder="Search color shade (e.g. Ivory, White, Grey)..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white {{ $isFixora ? 'focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500' : 'focus:border-orange-500 focus:ring-1 focus:ring-orange-500' }} transition">
                    </div>

                    <!-- Color Chips Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 max-h-[220px] overflow-y-auto pr-1" id="colorChipsContainer">
                        @foreach($colors as $color)
                            @php
                                $nameLower = strtolower($color->name);
                                $dotBg = '#cbd5e1';
                                if(str_contains($nameLower, 'white')) $dotBg = '#ffffff';
                                elseif(str_contains($nameLower, 'black')) $dotBg = '#1e293b';
                                elseif(str_contains($nameLower, 'grey') || str_contains($nameLower, 'gray')) $dotBg = '#64748b';
                                elseif(str_contains($nameLower, 'ivory') || str_contains($nameLower, 'cream')) $dotBg = '#fef08a';
                                elseif(str_contains($nameLower, 'terracotta') || str_contains($nameLower, 'brown')) $dotBg = '#9a3412';
                                elseif(str_contains($nameLower, 'blue')) $dotBg = '#3b82f6';
                                elseif(str_contains($nameLower, 'green')) $dotBg = '#10b981';
                                elseif(str_contains($nameLower, 'red')) $dotBg = '#ef4444';
                                elseif(str_contains($nameLower, 'yellow') || str_contains($nameLower, 'gold')) $dotBg = '#f59e0b';
                            @endphp
                            <div data-color-id="{{ $color->id }}"
                                 data-color-name="{{ $color->name }}"
                                 data-color-code="{{ $color->code }}"
                                 data-color-dot="{{ $dotBg }}"
                                 class="color-chip-card border border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50 rounded-xl p-2.5 cursor-pointer transition flex items-center gap-2 select-none {{ old('epoxy_filler_color_id') == $color->id ? ($isFixora ? 'border-emerald-600 bg-emerald-50/40 ring-1 ring-emerald-500' : 'border-orange-600 bg-orange-50/40 ring-1 ring-orange-500') : '' }}">
                                <span class="w-3.5 h-3.5 rounded-full shrink-0 border border-slate-300 shadow-2xs" style="background-color: {{ $dotBg }};"></span>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-slate-800 truncate">{{ $color->name }}</div>
                                    <div class="text-[10px] font-mono text-slate-400 truncate">{{ $color->code }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Quantity & Batch Size -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full {{ $isFixora ? 'bg-emerald-100 text-emerald-800' : 'bg-orange-100 text-orange-800' }} inline-flex items-center justify-center text-xs font-black">3</span>
                                Enter Batch Quantity
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Total kits / buckets to assemble.</p>
                        </div>

                        <!-- Auto Max Button -->
                        <button type="button" id="autoMaxBtn" class="hidden items-center gap-1.5 px-3 py-1.5 {{ $isFixora ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' : 'bg-orange-50 text-orange-800 border-orange-200 hover:bg-orange-100' }} border rounded-xl text-xs font-bold transition">
                            <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                            <span>Max Available: <strong id="maxPossibleDisplay">0</strong></span>
                        </button>
                    </div>

                    <!-- Clean Numeric Stepper -->
                    <div class="flex items-center gap-3">
                        <button type="button" data-step="-1" class="h-11 w-11 flex items-center justify-center bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl text-slate-700 font-bold text-lg transition select-none">
                            -
                        </button>
                        
                        <div class="flex-1">
                            <input type="number" name="quantity" id="quantityInput" value="{{ old('quantity', 1) }}" min="1" required
                                class="w-full h-11 text-center bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-lg font-black font-mono focus:outline-none focus:bg-white {{ $isFixora ? 'focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500' : 'focus:border-orange-500 focus:ring-1 focus:ring-orange-500' }}">
                        </div>

                        <button type="button" data-step="1" class="h-11 w-11 flex items-center justify-center bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl text-slate-700 font-bold text-lg transition select-none">
                            +
                        </button>
                    </div>

                    <!-- Quick Preset Pills -->
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-xs text-slate-400 font-semibold mr-1">Presets:</span>
                        <button type="button" data-preset="1" class="preset-btn px-2.5 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 transition">1</button>
                        <button type="button" data-preset="5" class="preset-btn px-2.5 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 transition">5</button>
                        <button type="button" data-preset="10" class="preset-btn px-2.5 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 transition">10</button>
                        <button type="button" data-preset="25" class="preset-btn px-2.5 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 transition">25</button>
                        <button type="button" data-preset="50" class="preset-btn px-2.5 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 transition">50</button>
                        <button type="button" data-preset="100" class="preset-btn px-2.5 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 transition">100</button>
                    </div>
                </div>

                <!-- 4. Remarks -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-2">
                    <label for="remarks" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Remarks / Batch Notes (Optional)
                    </label>
                    <input type="text" name="remarks" id="remarks" value="{{ old('remarks') }}"
                        placeholder="e.g. Lot #EPX-8849, Morning shift assembly..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white {{ $isFixora ? 'focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500' : 'focus:border-orange-500 focus:ring-1 focus:ring-orange-500' }} transition">
                </div>

            </div>

            <!-- RIGHT COLUMN: Bill of Materials & Authorization (5 cols) -->
            <div class="lg:col-span-5 space-y-5 lg:sticky lg:top-24">

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Bill of Materials (BOM)</h3>
                            <p class="text-xs text-slate-500">Live formula ingredients &amp; stock verification.</p>
                        </div>
                        <span id="bomItemsCountBadge" class="text-xs font-bold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg border border-slate-200">
                            0 Items
                        </span>
                    </div>

                    <!-- Target Selection Summary -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-mono font-bold uppercase text-slate-400" id="summaryProductCode">SELECT PRODUCT</span>
                            <span class="text-xs font-extrabold text-slate-900 font-mono" id="summaryBatchQty">1 Kit</span>
                        </div>
                        <h4 class="text-sm font-extrabold text-slate-900" id="summaryProductName">No Product Selected</h4>
                        <div id="summaryColorWrapper" class="hidden items-center gap-1.5 pt-0.5">
                            <span class="w-2.5 h-2.5 rounded-full border border-slate-300" id="summaryColorDot"></span>
                            <span class="text-xs font-semibold text-slate-600" id="summaryColorName">Color</span>
                        </div>
                    </div>

                    <!-- Stock Readiness Alert -->
                    <div id="stockReadinessCard" class="bg-slate-50 border border-slate-200 rounded-xl p-3 space-y-1.5">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-slate-700">Stock Readiness:</span>
                            <span id="readinessStatusBadge" class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-slate-200 text-slate-700">
                                Idle
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-500 flex items-center justify-between">
                            <span id="maxCapacityText">Max Capacity: <strong>0 units</strong></span>
                            <span id="bottleneckNotice" class="text-rose-600 font-semibold truncate"></span>
                        </div>
                    </div>

                    <!-- Formula Items List -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Required Items</label>
                        <div id="bomItemsList" class="space-y-2 max-h-[300px] overflow-y-auto pr-1">
                            <div class="py-8 text-center text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                <p class="text-xs font-medium">Select a product to view ingredients and stock availability.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Authorization Button -->
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <button type="button" id="submitAssemblyBtn" disabled
                            class="w-full py-3.5 px-4 {{ $isFixora ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-orange-600 hover:bg-orange-700' }} disabled:bg-slate-300 text-white font-bold text-sm rounded-xl transition duration-150 shadow-xs flex items-center justify-center gap-2 disabled:cursor-not-allowed">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            <span id="submitBtnText">Complete Assembly</span>
                        </button>
                        <p class="text-[11px] text-center text-slate-400">Warehouse raw materials and packing items will be deducted immediately.</p>
                    </div>
                </div>

                <!-- Recent Floor Runs -->
                @if(isset($recentAssemblies) && $recentAssemblies->count() > 0)
                    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs space-y-2.5">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                <i data-lucide="history" class="w-3.5 h-3.5 text-slate-400"></i>
                                Recent Floor Runs
                            </h4>
                            <a href="{{ route('epoxy.index') }}" class="text-[10px] font-bold text-slate-500 hover:text-slate-900">View Floor Log</a>
                        </div>
                        <div class="space-y-1.5 text-xs">
                            @foreach($recentAssemblies as $rec)
                                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-100">
                                    <div class="min-w-0 pr-2">
                                        <span class="font-bold text-slate-800 block truncate">{{ $rec->product->name ?? 'Epoxy Kit' }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono">#EPX-{{ str_pad($rec->id, 5, '0', STR_PAD_LEFT) }} • {{ $rec->created_at->diffForHumans() }}</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded bg-white border border-slate-200 text-slate-800 font-bold text-xs font-mono shrink-0">
                                        +{{ $rec->quantity }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {

    var isFixora = {{ $isFixora ? 'true' : 'false' }};
    var activeBrandBorderClass = isFixora ? 'border-emerald-600 bg-emerald-50/40 ring-1 ring-emerald-500' : 'border-orange-600 bg-orange-50/40 ring-1 ring-orange-500';

    function escapeHtml(text) {
        if (!text) return '';
        return $('<div>').text(text).html();
    }

    var state = {
        productId: $('#selected_product_id').val() || null,
        colorId: $('#selected_color_id').val() || null,
        quantity: parseInt($('#quantityInput').val()) || 1,
        requiresColor: false,
        maxPossible: 0,
        canAssemble: false,
        formulaItems: []
    };

    // Product Category Filters
    $('.filter-btn').click(function() {
        $('.filter-btn').removeClass('bg-white text-slate-900 shadow-xs font-bold').addClass('text-slate-600');
        $(this).addClass('bg-white text-slate-900 shadow-xs font-bold').removeClass('text-slate-600');

        var filter = $(this).data('filter');
        applyProductFilters(filter, $('#productSearchInput').val());
    });

    // Product Search
    $('#productSearchInput').on('input', function() {
        var activeFilter = $('.filter-btn.font-bold').data('filter') || 'all';
        applyProductFilters(activeFilter, $(this).val());
    });

    function applyProductFilters(category, query) {
        query = (query || '').toLowerCase().trim();
        $('.product-item-card').each(function() {
            var $card = $(this);
            var name = ($card.data('product-name') || '').toLowerCase();
            var code = ($card.data('product-code') || '').toLowerCase();
            var isColor = $card.data('is-color') == 1;

            var matchesCategory = true;
            if (category === 'color') matchesCategory = isColor;
            if (category === 'standard') matchesCategory = !isColor;

            var matchesQuery = !query || name.includes(query) || code.includes(query);

            if (matchesCategory && matchesQuery) {
                $card.show();
            } else {
                $card.hide();
            }
        });
    }

    // Color Search
    $('#colorSearchInput').on('input', function() {
        var query = $(this).val().toLowerCase().trim();
        $('.color-chip-card').each(function() {
            var name = ($(this).data('color-name') || '').toLowerCase();
            var code = ($(this).data('color-code') || '').toLowerCase();
            if (!query || name.includes(query) || code.includes(query)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Select Product
    $(document).on('click', '.product-item-card', function(e, isInitial) {
        var $card = $(this);
        if ($card.data('has-formula') != 1) {
            Swal.fire({
                icon: 'warning',
                title: 'No Active Recipe',
                text: 'This product does not have an active formula configured.',
                confirmButtonColor: isFixora ? '#059669' : '#ea580c'
            });
            return;
        }

        $('.product-item-card').removeClass(activeBrandBorderClass);
        $('.product-item-card .selected-badge').addClass('hidden');

        $card.addClass(activeBrandBorderClass);
        $card.find('.selected-badge').removeClass('hidden');

        var productId = $card.data('product-id');
        var productName = $card.data('product-name');
        var productCode = $card.data('product-code');
        var reqColor = $card.data('requires-color') == 1;

        state.productId = productId;
        state.requiresColor = reqColor;
        $('#selected_product_id').val(productId);

        $('#summaryProductName').text(productName);
        $('#summaryProductCode').text(productCode);

        if (reqColor) {
            $('#colorSectionCard').slideDown(250, function() {
                if (!isInitial) {
                    $('html, body').stop().animate({
                        scrollTop: $('#colorSectionCard').offset().top - 90
                    }, 400);
                }
            });
            $('#summaryColorWrapper').removeClass('hidden').addClass('flex');
            
            if (!state.colorId) {
                var $firstColor = $('.color-chip-card').first();
                if ($firstColor.length) {
                    $firstColor.click();
                }
            } else {
                $(`.color-chip-card[data-color-id="${state.colorId}"]`).addClass(activeBrandBorderClass);
            }
        } else {
            $('#colorSectionCard').slideUp(250);
            $('#summaryColorWrapper').addClass('hidden').removeClass('flex');
            state.colorId = null;
            $('#selected_color_id').val('');
            $('.color-chip-card').removeClass(activeBrandBorderClass);
            $('#activeColorTag').addClass('hidden').removeClass('flex');

            if (!isInitial) {
                $('html, body').stop().animate({
                    scrollTop: $('#quantityInput').closest('.bg-white').offset().top - 90
                }, 400);
            }
        }

        fetchFormulaPreview();
    });

    // Select Color
    $(document).on('click', '.color-chip-card', function() {
        var $chip = $(this);
        $('.color-chip-card').removeClass(activeBrandBorderClass);
        $chip.addClass(activeBrandBorderClass);

        var colorId = $chip.data('color-id');
        var colorName = $chip.data('color-name');
        var colorDot = $chip.data('color-dot');

        state.colorId = colorId;
        $('#selected_color_id').val(colorId);

        $('#activeColorTag').removeClass('hidden').addClass('flex');
        $('#activeColorDot').css('background-color', colorDot);
        $('#activeColorNameText').text(colorName);

        $('#summaryColorDot').css('background-color', colorDot);
        $('#summaryColorName').text(colorName);

        fetchFormulaPreview();
    });

    // Stepper Controls
    $('[data-step]').click(function() {
        var step = parseInt($(this).data('step'));
        var current = parseInt($('#quantityInput').val()) || 1;
        var next = Math.max(1, current + step);
        $('#quantityInput').val(next);
        state.quantity = next;
        $('#summaryBatchQty').text(next + (next === 1 ? ' Kit' : ' Kits'));
        fetchFormulaPreview();
    });

    // Preset Buttons
    $('.preset-btn').click(function() {
        var val = parseInt($(this).data('preset')) || 1;
        $('#quantityInput').val(val);
        state.quantity = val;
        $('#summaryBatchQty').text(val + (val === 1 ? ' Kit' : ' Kits'));
        fetchFormulaPreview();
    });

    // Manual Input
    $('#quantityInput').on('input change', function() {
        var val = parseInt($(this).val()) || 1;
        if (val < 1) val = 1;
        state.quantity = val;
        $('#summaryBatchQty').text(val + (val === 1 ? ' Kit' : ' Kits'));
        fetchFormulaPreview();
    });

    // Auto-Max Button
    $('#autoMaxBtn').click(function() {
        if (state.maxPossible > 0) {
            $('#quantityInput').val(state.maxPossible);
            state.quantity = state.maxPossible;
            $('#summaryBatchQty').text(state.maxPossible + (state.maxPossible === 1 ? ' Kit' : ' Kits'));
            fetchFormulaPreview();
        }
    });

    // AJAX Formula Preview
    function fetchFormulaPreview() {
        if (!state.productId) {
            renderEmptyBOM();
            return;
        }

        if (state.requiresColor && !state.colorId) {
            renderMissingColorBOM();
            return;
        }

        $('#bomItemsCountBadge').text('Loading...');
        
        $.ajax({
            url: `/epoxy-production/products/${state.productId}/formula-preview`,
            method: 'GET',
            data: {
                quantity: state.quantity,
                epoxy_filler_color_id: state.colorId
            },
            success: function(res) {
                state.maxPossible = res.max_possible_qty || 0;
                state.canAssemble = res.can_assemble;
                state.formulaItems = res.items || [];

                renderBOM(res);
            },
            error: function(xhr) {
                renderErrorBOM(xhr.responseJSON?.error || 'Failed to scale formula.');
            }
        });
    }

    function renderEmptyBOM() {
        $('#bomItemsCountBadge').text('0 Items');
        $('#submitAssemblyBtn').prop('disabled', true);
        $('#submitBtnText').text('Select Product');
        $('#autoMaxBtn').addClass('hidden').removeClass('flex');
        $('#readinessStatusBadge').removeClass('bg-emerald-100 text-emerald-800 bg-rose-100 text-rose-800').addClass('bg-slate-200 text-slate-700').text('Idle');
        $('#maxCapacityText').html('Max Capacity: <strong>0 units</strong>');
        $('#bottleneckNotice').text('');

        $('#bomItemsList').html(`
            <div class="py-8 text-center text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                <p class="text-xs font-medium">Select a product to view ingredients and stock availability.</p>
            </div>
        `);
    }

    function renderMissingColorBOM() {
        $('#bomItemsCountBadge').text('Color Needed');
        $('#submitAssemblyBtn').prop('disabled', true);
        $('#submitBtnText').text('Select Color');
        $('#autoMaxBtn').addClass('hidden').removeClass('flex');
        $('#readinessStatusBadge').removeClass('bg-emerald-100 text-emerald-800 bg-rose-100 text-rose-800').addClass('bg-amber-100 text-amber-800').text('Color Required');

        $('#bomItemsList').html(`
            <div class="py-8 text-center text-amber-700 bg-amber-50 rounded-xl border border-dashed border-amber-200">
                <p class="text-xs font-semibold">Please choose an Epoxy Filler Color above.</p>
            </div>
        `);
    }

    function renderErrorBOM(errorMsg) {
        $('#bomItemsCountBadge').text('Error');
        $('#submitAssemblyBtn').prop('disabled', true);
        $('#submitBtnText').text('Cannot Assemble');
        $('#autoMaxBtn').addClass('hidden').removeClass('flex');

        $('#bomItemsList').html(`
            <div class="py-6 text-center text-rose-700 bg-rose-50 rounded-xl border border-rose-200">
                <p class="text-xs font-semibold">${escapeHtml(errorMsg)}</p>
            </div>
        `);
    }

    function renderBOM(data) {
        var items = data.items || [];
        $('#bomItemsCountBadge').text(`${items.length} Items`);
        
        // Auto-max display
        $('#maxPossibleDisplay').text(data.max_possible_qty);
        if (data.max_possible_qty > 0) {
            $('#autoMaxBtn').removeClass('hidden').addClass('flex');
        } else {
            $('#autoMaxBtn').addClass('hidden').removeClass('flex');
        }
        $('#maxCapacityText').html(`Max Capacity: <strong class="text-slate-900">${data.max_possible_qty} units</strong>`);

        var html = '';
        var hasShortage = false;
        var bottleneckName = '';

        items.forEach(function(item) {
            var isAvailable = item.status === 'Available';

            if (!isAvailable) {
                hasShortage = true;
                if (!bottleneckName) bottleneckName = item.name;
            }

            var statusBadge = isAvailable
                ? `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">In Stock</span>`
                : `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">Shortage</span>`;

            var itemBorder = isAvailable ? 'border-slate-200' : 'border-rose-200 bg-rose-50/20';

            html += `
                <div class="p-2.5 rounded-xl border ${itemBorder} bg-white shadow-2xs space-y-1.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <div class="text-xs font-bold text-slate-800 truncate">${escapeHtml(item.name)}</div>
                            <div class="text-[10px] font-mono text-slate-400">${escapeHtml(item.code)} • ${escapeHtml(item.type)}</div>
                        </div>
                        ${statusBadge}
                    </div>
                    <div class="flex items-center justify-between text-[11px] font-mono pt-1 border-t border-slate-100">
                        <span class="text-slate-500">Needed: <strong class="text-slate-900">${Number(item.quantity).toLocaleString()} ${escapeHtml(item.unit)}</strong></span>
                        <span class="text-slate-500">Stock: <strong class="${item.stock < item.quantity ? 'text-rose-600' : 'text-slate-800'}">${Number(item.stock).toLocaleString()} ${escapeHtml(item.unit)}</strong></span>
                    </div>
                </div>
            `;
        });

        $('#bomItemsList').html(html);

        if (!hasShortage && items.length > 0) {
            $('#readinessStatusBadge').removeClass('bg-slate-200 text-slate-700 bg-rose-100 text-rose-800').addClass('bg-emerald-100 text-emerald-800').text('100% Ready');
            $('#bottleneckNotice').text('');

            $('#submitAssemblyBtn').prop('disabled', false);
            $('#submitBtnText').text(`Complete Assembly (${state.quantity} Kits)`);
        } else {
            $('#readinessStatusBadge').removeClass('bg-emerald-100 text-emerald-800 bg-slate-200 text-slate-700').addClass('bg-rose-100 text-rose-800').text('Shortage');
            $('#bottleneckNotice').text(bottleneckName ? `Shortage: ${bottleneckName}` : 'Missing stock');

            $('#submitAssemblyBtn').prop('disabled', true);
            $('#submitBtnText').text('Cannot Assemble (Stock Shortage)');
        }

        if (window.lucide) lucide.createIcons();
    }

    // Submit with SweetAlert
    $('#submitAssemblyBtn').click(function(e) {
        e.preventDefault();

        if (!state.canAssemble) {
            Swal.fire('Cannot Assemble', 'Warehouse stock is insufficient for this batch.', 'error');
            return;
        }

        var productName = $('#summaryProductName').text();
        var colorName = state.requiresColor ? $('#summaryColorName').text() : '';
        var colorText = colorName ? ` (Color: ${colorName})` : '';

        Swal.fire({
            title: 'Confirm Assembly?',
            text: `Assemble ${state.quantity} unit(s) of ${productName}${colorText}? Raw materials and packing stock will be deducted immediately.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Complete Assembly',
            cancelButtonText: 'Cancel',
            confirmButtonColor: isFixora ? '#059669' : '#ea580c'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#assemblyForm').submit();
            }
        });
    });

    // Auto-select initial product
    var initialProdId = $('#selected_product_id').val();
    if (initialProdId && $(`.product-item-card[data-product-id="${initialProdId}"]`).length) {
        $(`.product-item-card[data-product-id="${initialProdId}"]`).trigger('click', [true]);
    } else {
        $('.product-item-card[data-has-formula="1"]').first().trigger('click', [true]);
    }
});
</script>
@endsection
