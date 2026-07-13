@extends('layouts.app')

@section('title', 'Raw Material Stock IN / OUT')
@section('header-title', 'Stock IN / OUT')

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
        <form id="filterForm" method="GET" action="{{ route('admin.stock-adjustments.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <div class="relative sm:col-span-2">
                <label class="block text-slate-500 mb-1 uppercase font-bold tracking-wider text-[9px]">Search Raw Material / Remarks</label>
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
                            {{ $rm->name }} ({{ $rm->code }})
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
    <div class="bg-white border border-slate-100 rounded-3xl p-6 w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300">
        
        <button onclick="closeAdjustModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition p-1.5 rounded-full hover:bg-slate-100">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2.5 bg-blue-50 rounded-2xl">
                <i data-lucide="plus-circle" class="w-5 h-5 text-blue-650"></i>
            </div>
            <div>
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Raw Material Stock Movement</h3>
                <p class="text-sm font-black text-slate-800">Record Stock IN / OUT</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.stock-adjustments.store') }}" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Select Raw Material</label>
                <select name="raw_material_id" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50" required>
                    <option value="">Select Raw Material</option>
                    @foreach($rawMaterials as $rm)
                        <option value="{{ $rm->id }}">
                            {{ $rm->name }} ({{ $rm->code }}) - Current: {{ number_format($rm->current_stock, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Quantity (+ for Stock IN, - for Stock OUT)</label>
                <input type="number" step="0.0001" name="quantity" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" placeholder="e.g. 50.0000 or -25.5000" required>
            </div>

            <div>
                <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Reason / Remarks</label>
                <textarea name="remarks" rows="3" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" placeholder="State reason for manual stock correction..." required></textarea>
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

    $('#filterRm').on('change', function() {
        reloadTable();
    });

    bindPagination();
});

function openAdjustModal() {
    $('#adjustModal').removeClass('hidden').find('.transform').removeClass('scale-95').addClass('scale-100');
}

function closeAdjustModal() {
    $('#adjustModal').addClass('hidden').find('.transform').removeClass('scale-100').addClass('scale-95');
}
</script>
@endsection
