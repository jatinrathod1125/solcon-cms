@extends('layouts.app')

@section('title', 'Raw Material Stock IN / OUT')
@section('header-title', 'Stock IN / OUT')

@section('styles')
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection

@section('content')
<div class="mx-auto max-w-[1600px] space-y-4">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <p class="text-xs font-semibold text-slate-400">Perform manual raw material Stock IN (Receive) and Stock OUT (Deduct) logs.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="openAdjustModal()" class="erp-button bg-blue-600 text-white hover:bg-blue-500">
                <i data-lucide="plus" class="w-4 h-4"></i>Record Stock IN / OUT
            </button>
        </div>
    </div>

    <!-- Alert Blocks -->
    @if(session('success'))
        <div class="bg-emerald-550/10 border border-emerald-500/20 text-emerald-700 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-550/10 border border-rose-500/20 text-rose-700 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
            <i data-lucide="alert-octagon" class="w-4 h-4 text-rose-600"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Permanent Sleek Filter Bar -->
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
        <form id="filterForm" method="GET" action="{{ route('admin.stock-adjustments.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
            <div class="relative lg:col-span-2">
                <label class="block text-slate-500 mb-1 uppercase font-bold tracking-wider text-[9px]">Search Material / Remarks</label>
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"></i>
                    <input type="text" id="filterSearch" name="search" value="{{ request('search') }}" class="block w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" placeholder="Search by name, code or remarks...">
                </div>
            </div>

            <div>
                <label class="block text-slate-500 mb-1 uppercase font-bold tracking-wider text-[9px]">Raw Material Filter</label>
                <select id="filterRm" name="raw_material_id" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <option value="">All Raw Materials</option>
                    @foreach($rawMaterials as $rm)
                        <option value="{{ $rm->id }}" {{ request('raw_material_id') == $rm->id ? 'selected' : '' }}>
                            {{ $rm->name }} ({{ $rm->code }}){{ $rm->brand ? ' [' . $rm->brand->name . ']' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-slate-500 mb-1 uppercase font-bold tracking-wider text-[9px]">Packing Material Filter</label>
                <select id="filterPm" name="packing_material_id" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <option value="">All Packing Materials</option>
                    @foreach($packingMaterials as $pm)
                        <option value="{{ $pm->id }}" {{ request('packing_material_id') == $pm->id ? 'selected' : '' }}>
                            {{ $pm->name }} ({{ $pm->code ?? '-' }}){{ $pm->brand ? ' [' . $pm->brand->name . ']' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Historical Log Table Container -->
    <article class="erp-card overflow-hidden" id="tableContainer">
        @include('admin.stock_adjustments._table')
    </article>
</div>

<!-- PREMIUM LOG ADJUSTMENT MODAL -->
<div id="adjustModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white border border-slate-100 rounded-3xl p-6 w-full max-w-lg shadow-2xl relative transform scale-95 transition-transform duration-300">
        
        <button type="button" onclick="closeAdjustModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition p-1.5 rounded-full hover:bg-slate-100">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2.5 bg-blue-50 rounded-2xl">
                <i data-lucide="plus-circle" class="w-5 h-5 text-blue-650"></i>
            </div>
            <div>
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Stock Movement</h3>
                <p class="text-sm font-black text-slate-800">Record Stock IN / OUT</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.stock-adjustments.store') }}" class="space-y-4 text-xs" id="stockAdjustmentForm">
            @csrf

            <div>
                <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Material Type</label>
                <div class="flex gap-4 mb-2">
                    <label class="inline-flex items-center gap-1.5 font-semibold text-slate-700 cursor-pointer">
                        <input type="radio" name="material_type" value="raw" checked onchange="toggleMaterialType(this.value)" class="text-blue-600 focus:ring-blue-500">
                        Raw Material
                    </label>
                    <label class="inline-flex items-center gap-1.5 font-semibold text-slate-700 cursor-pointer">
                        <input type="radio" name="material_type" value="packing" onchange="toggleMaterialType(this.value)" class="text-purple-600 focus:ring-purple-500">
                        Packing Material
                    </label>
                </div>
            </div>

            <!-- SEARCHABLE RAW MATERIAL DROPDOWN -->
            <div id="rawMaterialGroup" class="relative">
                <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Select Raw Material</label>
                <input type="hidden" name="raw_material_id" id="raw_material_id_input">
                
                <div class="relative">
                    <button type="button" id="raw_material_trigger" class="w-full flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-left text-slate-700 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition">
                        <span id="raw_material_label" class="text-slate-400 font-medium truncate">-- Search &amp; Select Raw Material --</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" id="raw_material_chevron"></i>
                    </button>

                    <!-- Dropdown Menu Panel -->
                    <div id="raw_material_dropdown" class="hidden absolute left-0 right-0 top-full mt-1.5 z-50 bg-white border border-slate-200 rounded-2xl shadow-2xl p-2.5 space-y-2 max-h-72 flex flex-col">
                        <!-- Search Box -->
                        <div class="relative shrink-0">
                            <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5"></i>
                            <input type="text" id="raw_material_search" placeholder="Search by name, code (e.g. EPX-BLK)..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-7 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" autocomplete="off">
                            <button type="button" class="clear-search-btn hidden absolute right-2.5 top-2 text-slate-400 hover:text-slate-600">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>

                        <!-- Options List -->
                        <div class="overflow-y-auto space-y-1 flex-1 pr-1 custom-scrollbar" id="raw_material_options">
                            @foreach($rawMaterials as $rm)
                                <div class="searchable-option p-2 rounded-xl hover:bg-blue-50/70 hover:text-blue-900 cursor-pointer transition flex items-center justify-between text-xs gap-2 select-none"
                                     data-target="raw"
                                     data-id="{{ $rm->id }}"
                                     data-name="{{ $rm->name }}"
                                     data-code="{{ $rm->code }}"
                                     data-brand="{{ $rm->brand?->name ?? '' }}"
                                     data-stock="{{ format_quantity($rm->current_stock) }}">
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-slate-800 option-name truncate">{{ $rm->name }}</div>
                                        <div class="flex items-center gap-1.5 mt-0.5 text-[10px] text-slate-400 font-mono">
                                            <span class="bg-slate-100 px-1.5 py-0.2 rounded font-bold text-slate-600 option-code">{{ $rm->code }}</span>
                                            @if($rm->brand)
                                                <span class="bg-blue-50 text-blue-700 border border-blue-200/60 px-1 py-0.2 rounded font-semibold">{{ $rm->brand->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 shrink-0">
                                        Stock: {{ format_quantity($rm->current_stock) }}
                                    </span>
                                </div>
                            @endforeach
                            <div class="no-results hidden py-6 text-center text-xs text-slate-400">
                                <i data-lucide="search-x" class="w-5 h-5 mx-auto mb-1 text-slate-300"></i>
                                <span>No raw materials found.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEARCHABLE PACKING MATERIAL DROPDOWN -->
            <div id="packingMaterialGroup" class="hidden relative">
                <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Select Packing Material</label>
                <input type="hidden" name="packing_material_id" id="packing_material_id_input">
                
                <div class="relative">
                    <button type="button" id="packing_material_trigger" class="w-full flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-left text-slate-700 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition">
                        <span id="packing_material_label" class="text-slate-400 font-medium truncate">-- Search &amp; Select Packing Material --</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" id="packing_material_chevron"></i>
                    </button>

                    <!-- Dropdown Menu Panel -->
                    <div id="packing_material_dropdown" class="hidden absolute left-0 right-0 top-full mt-1.5 z-50 bg-white border border-slate-200 rounded-2xl shadow-2xl p-2.5 space-y-2 max-h-72 flex flex-col">
                        <!-- Search Box -->
                        <div class="relative shrink-0">
                            <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5"></i>
                            <input type="text" id="packing_material_search" placeholder="Search by name, code..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-7 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition" autocomplete="off">
                            <button type="button" class="clear-search-btn hidden absolute right-2.5 top-2 text-slate-400 hover:text-slate-600">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>

                        <!-- Options List -->
                        <div class="overflow-y-auto space-y-1 flex-1 pr-1 custom-scrollbar" id="packing_material_options">
                            @foreach($packingMaterials as $pm)
                                <div class="searchable-option p-2 rounded-xl hover:bg-purple-50/70 hover:text-purple-900 cursor-pointer transition flex items-center justify-between text-xs gap-2 select-none"
                                     data-target="packing"
                                     data-id="{{ $pm->id }}"
                                     data-name="{{ $pm->name }}"
                                     data-code="{{ $pm->code ?? '-' }}"
                                     data-brand="{{ $pm->brand?->name ?? '' }}"
                                     data-stock="{{ format_quantity($pm->current_stock) }}"
                                     data-unit="{{ $pm->unit->code ?? 'PCS' }}">
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-slate-800 option-name truncate">{{ $pm->name }}</div>
                                        <div class="flex items-center gap-1.5 mt-0.5 text-[10px] text-slate-400 font-mono">
                                            <span class="bg-slate-100 px-1.5 py-0.2 rounded font-bold text-slate-600 option-code">{{ $pm->code ?? '-' }}</span>
                                            @if($pm->brand)
                                                <span class="bg-purple-50 text-purple-700 border border-purple-200/60 px-1 py-0.2 rounded font-semibold">{{ $pm->brand->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 shrink-0">
                                        Stock: {{ format_quantity($pm->current_stock) }} {{ $pm->unit->code ?? 'PCS' }}
                                    </span>
                                </div>
                            @endforeach
                            <div class="no-results hidden py-6 text-center text-xs text-slate-400">
                                <i data-lucide="search-x" class="w-5 h-5 mx-auto mb-1 text-slate-300"></i>
                                <span>No packing materials found.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Quantity (+ for Stock IN, - for Stock OUT)</label>
                <input type="number" step="0.0001" name="quantity" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" placeholder="e.g. 50 or -25.5" required>
            </div>

            <div>
                <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Reason / Remarks (Optional)</label>
                <textarea name="remarks" rows="3" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" placeholder="State reason for manual stock correction (optional)..."></textarea>
            </div>

            <div class="pt-2 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="closeAdjustModal()" class="erp-button border border-slate-200 text-slate-650 hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit" class="erp-button bg-blue-650 text-white hover:bg-blue-600">
                    Submit Correction
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    let debounceTimer;

    function reloadTable(page = 1) {
        const formData = $('#filterForm').serialize() + '&page=' + page;
        $('#tableContainer').addClass('opacity-50 pointer-events-none');
        
        $.get("{{ route('admin.stock-adjustments.index') }}", formData, function(html) {
            $('#tableContainer').html(html).removeClass('opacity-50 pointer-events-none');
            bindPagination();
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    }

    function bindPagination() {
        $('.pagination-container a').off('click').on('click', function(e) {
            e.preventDefault();
            const url = new URL($(this).attr('href'));
            const page = url.searchParams.get('page') || 1;
            reloadTable(page);
        });
    }

    $('#filterSearch').on('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            reloadTable();
        }, 300);
    });

    $('#filterRm, #filterPm').on('change', function() {
        reloadTable();
    });

    bindPagination();

    // ─────────────────────────────────────────────────────────────
    // SEARCHABLE DROPDOWN CONTROLLER
    // ─────────────────────────────────────────────────────────────

    // Toggle Dropdowns
    $('#raw_material_trigger').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const isOpen = !$('#raw_material_dropdown').hasClass('hidden');
        closeAllDropdowns();
        if (!isOpen) {
            $('#raw_material_dropdown').removeClass('hidden');
            $('#raw_material_chevron').addClass('rotate-180');
            setTimeout(() => $('#raw_material_search').focus(), 50);
        }
    });

    $('#packing_material_trigger').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const isOpen = !$('#packing_material_dropdown').hasClass('hidden');
        closeAllDropdowns();
        if (!isOpen) {
            $('#packing_material_dropdown').removeClass('hidden');
            $('#packing_material_chevron').addClass('rotate-180');
            setTimeout(() => $('#packing_material_search').focus(), 50);
        }
    });

    // Close when clicking outside of the respective group
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#rawMaterialGroup').length) {
            $('#raw_material_dropdown').addClass('hidden');
            $('#raw_material_chevron').removeClass('rotate-180');
        }
        if (!$(e.target).closest('#packingMaterialGroup').length) {
            $('#packing_material_dropdown').addClass('hidden');
            $('#packing_material_chevron').removeClass('rotate-180');
        }
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllDropdowns();
        }
    });

    function closeAllDropdowns() {
        $('#raw_material_dropdown, #packing_material_dropdown').addClass('hidden');
        $('#raw_material_chevron, #packing_material_chevron').removeClass('rotate-180');
    }

    // Live Search Filter
    $('#raw_material_search').on('input keyup', function() {
        const query = ($(this).val() || '').toLowerCase().trim();
        const $options = $('#raw_material_options .searchable-option');
        let visibleCount = 0;

        $options.each(function() {
            const name = String($(this).attr('data-name') || $(this).data('name') || '').toLowerCase();
            const code = String($(this).attr('data-code') || $(this).data('code') || '').toLowerCase();
            const brand = String($(this).attr('data-brand') || $(this).data('brand') || '').toLowerCase();
            const fullText = $(this).text().toLowerCase();

            if (!query || name.includes(query) || code.includes(query) || brand.includes(query) || fullText.includes(query)) {
                $(this).removeClass('hidden');
                visibleCount++;
            } else {
                $(this).addClass('hidden');
            }
        });

        if (visibleCount === 0) {
            $('#raw_material_options .no-results').removeClass('hidden');
        } else {
            $('#raw_material_options .no-results').addClass('hidden');
        }

        $(this).siblings('.clear-search-btn').toggleClass('hidden', query.length === 0);
    });

    $('#packing_material_search').on('input keyup', function() {
        const query = ($(this).val() || '').toLowerCase().trim();
        const $options = $('#packing_material_options .searchable-option');
        let visibleCount = 0;

        $options.each(function() {
            const name = String($(this).attr('data-name') || $(this).data('name') || '').toLowerCase();
            const code = String($(this).attr('data-code') || $(this).data('code') || '').toLowerCase();
            const brand = String($(this).attr('data-brand') || $(this).data('brand') || '').toLowerCase();
            const fullText = $(this).text().toLowerCase();

            if (!query || name.includes(query) || code.includes(query) || brand.includes(query) || fullText.includes(query)) {
                $(this).removeClass('hidden');
                visibleCount++;
            } else {
                $(this).addClass('hidden');
            }
        });

        if (visibleCount === 0) {
            $('#packing_material_options .no-results').removeClass('hidden');
        } else {
            $('#packing_material_options .no-results').addClass('hidden');
        }

        $(this).siblings('.clear-search-btn').toggleClass('hidden', query.length === 0);
    });

    // Clear Search Buttons
    $('.clear-search-btn').on('click', function() {
        const input = $(this).siblings('input');
        input.val('').trigger('input').focus();
    });

    // Option Selection
    $(document).on('click', '.searchable-option', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $opt = $(this);
        const target = $opt.attr('data-target') || $opt.data('target');
        const id = $opt.attr('data-id') || $opt.data('id');
        const name = $opt.attr('data-name') || $opt.data('name') || '';
        const code = $opt.attr('data-code') || $opt.data('code') || '';
        const stock = $opt.attr('data-stock') || $opt.data('stock') || '0';
        const unit = $opt.attr('data-unit') || $opt.data('unit') || '';

        if (target === 'raw') {
            $('#raw_material_id_input').val(id);
            $('#raw_material_label').html(`
                <div class="flex items-center justify-between w-full pr-2">
                    <span class="font-bold text-slate-900 truncate">${escapeHtml(name)} <span class="text-[10px] font-mono text-slate-400">(${escapeHtml(code)})</span></span>
                    <span class="text-[10px] font-mono font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded shrink-0">Stock: ${stock}</span>
                </div>
            `);
            $('#raw_material_options .searchable-option').removeClass('bg-blue-100/80 text-blue-900 font-bold');
            $opt.addClass('bg-blue-100/80 text-blue-900 font-bold');
            closeAllDropdowns();
        } else {
            $('#packing_material_id_input').val(id);
            $('#packing_material_label').html(`
                <div class="flex items-center justify-between w-full pr-2">
                    <span class="font-bold text-slate-900 truncate">${escapeHtml(name)} <span class="text-[10px] font-mono text-slate-400">(${escapeHtml(code)})</span></span>
                    <span class="text-[10px] font-mono font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded shrink-0">Stock: ${stock} ${unit}</span>
                </div>
            `);
            $('#packing_material_options .searchable-option').removeClass('bg-purple-100/80 text-purple-900 font-bold');
            $opt.addClass('bg-purple-100/80 text-purple-900 font-bold');
            closeAllDropdowns();
        }
    });

    // Form Validation on Submit
    $('#stockAdjustmentForm').on('submit', function(e) {
        const type = $('input[name="material_type"]:checked').val();
        if (type === 'raw') {
            const rawId = $('#raw_material_id_input').val();
            if (!rawId) {
                e.preventDefault();
                $('#raw_material_trigger').addClass('ring-2 ring-rose-500 border-rose-400');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Raw Material Required',
                        text: 'Please search and select a raw material.',
                        confirmButtonColor: '#2563eb'
                    });
                } else {
                    alert('Please search and select a raw material.');
                }
                return false;
            }
        } else if (type === 'packing') {
            const packingId = $('#packing_material_id_input').val();
            if (!packingId) {
                e.preventDefault();
                $('#packing_material_trigger').addClass('ring-2 ring-rose-500 border-rose-400');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Packing Material Required',
                        text: 'Please search and select a packing material.',
                        confirmButtonColor: '#9333ea'
                    });
                } else {
                    alert('Please search and select a packing material.');
                }
                return false;
            }
        }
    });

    function escapeHtml(text) {
        if (!text) return '';
        return $('<div>').text(text).html();
    }
});

function openAdjustModal() {
    $('#adjustModal').removeClass('hidden').find('.transform').removeClass('scale-95').addClass('scale-100');
    $('#raw_material_search, #packing_material_search').val('');
    $('#raw_material_options .searchable-option, #packing_material_options .searchable-option').removeClass('hidden');
    $('#raw_material_options .no-results, #packing_material_options .no-results').addClass('hidden');
    $('.clear-search-btn').addClass('hidden');
    $('#raw_material_trigger, #packing_material_trigger').removeClass('ring-2 ring-rose-500 border-rose-400');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeAdjustModal() {
    $('#adjustModal').addClass('hidden').find('.transform').removeClass('scale-100').addClass('scale-95');
    $('#raw_material_dropdown, #packing_material_dropdown').addClass('hidden');
    $('#raw_material_chevron, #packing_material_chevron').removeClass('rotate-180');
}

function toggleMaterialType(type) {
    $('#raw_material_dropdown, #packing_material_dropdown').addClass('hidden');
    $('#raw_material_chevron, #packing_material_chevron').removeClass('rotate-180');
    $('#raw_material_trigger, #packing_material_trigger').removeClass('ring-2 ring-rose-500 border-rose-400');

    if (type === 'raw') {
        $('#rawMaterialGroup').removeClass('hidden');
        $('#packingMaterialGroup').addClass('hidden');
        $('#packing_material_id_input').val('');
        $('#packing_material_label').html('<span class="text-slate-400 font-medium truncate">-- Search &amp; Select Packing Material --</span>');
    } else {
        $('#packingMaterialGroup').removeClass('hidden');
        $('#rawMaterialGroup').addClass('hidden');
        $('#raw_material_id_input').val('');
        $('#raw_material_label').html('<span class="text-slate-400 font-medium truncate">-- Search &amp; Select Raw Material --</span>');
    }
}
</script>
@endsection
