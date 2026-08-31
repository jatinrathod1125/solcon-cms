@extends('layouts.app')

@section('title', 'Component Entry')
@section('header-title', 'Epoxy Component Entry')

@php
    $appBrand = currentBrand();
    $isFixora = ($appBrand && $appBrand->id == 2);
@endphp

@section('content')
<div class="max-w-6xl mx-auto space-y-5 pb-16">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('epoxy.index') }}" class="inline-flex items-center gap-1 text-xs text-slate-500 hover:text-slate-900 font-semibold transition">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Back to Epoxy Floor</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-xs font-semibold {{ $isFixora ? 'text-emerald-700' : 'text-orange-700' }}">Component Entry</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                <i data-lucide="boxes" class="w-6 h-6 {{ $isFixora ? 'text-emerald-600' : 'text-orange-600' }}"></i>
                Log Prepared Components
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Record ready components prepared in bulk. Raw material stocks are deducted automatically.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs bg-white text-slate-700 border border-slate-200 px-3 py-1.5 rounded-xl font-bold flex items-center gap-1.5 shadow-xs">
                <i data-lucide="layers" class="w-3.5 h-3.5 text-slate-400"></i>
                {{ $components->count() }} Components Catalog
            </span>
        </div>
    </div>

    <!-- Alert / Toast Container -->
    <div id="alertContainer" class="hidden"></div>

    <!-- Main Entry Form -->
    <form id="bulkComponentForm" method="POST" action="{{ route('epoxy.component-entry.bulk-store') }}" class="space-y-4">
        @csrf

        <!-- Control Panel Card: Search & Global Remarks -->
        <div class="bg-white border border-slate-200 p-4 rounded-2xl space-y-4 shadow-xs">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                <!-- Search Box -->
                <div class="md:col-span-4">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Search Component</label>
                    <div class="relative">
                        <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3 top-2.5 text-slate-400"></i>
                        <input type="text" id="componentSearch" placeholder="Filter by name or code..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-3 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white {{ $isFixora ? 'focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500' : 'focus:border-orange-500 focus:ring-1 focus:ring-orange-500' }} transition">
                    </div>
                </div>

                <!-- Global Remarks -->
                <div class="md:col-span-8">
                    <label for="global_remarks" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Shift / Operator Notes (Optional)</label>
                    <input type="text" name="global_remarks" id="global_remarks" placeholder="e.g. Morning Shift Preparation..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white {{ $isFixora ? 'focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500' : 'focus:border-orange-500 focus:ring-1 focus:ring-orange-500' }} transition">
                </div>
            </div>
        </div>

        <!-- Components Matrix Table Card -->
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="componentTable">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-[10px] uppercase font-bold tracking-wider text-slate-500">
                            <th class="py-3 px-4">Component Details</th>
                            <th class="py-3 px-3">Unit</th>
                            <th class="py-3 px-3">Formula Status</th>
                            <th class="py-3 px-3">Current Ready Stock</th>
                            <th class="py-3 px-4 w-44">Quantity Prepared</th>
                            <th class="py-3 px-4">Line Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($components as $index => $comp)
                            @php
                                $hasFormula = (bool) $comp->activeFormula;
                                $stock = $comp->available_stock;
                                $unitCode = $comp->unit ? $comp->unit->code : 'PCS';
                            @endphp
                            <tr class="component-row hover:bg-slate-50/70 transition {{ !$hasFormula ? 'opacity-50' : '' }}" 
                                data-name="{{ strtolower($comp->name) }}" 
                                data-code="{{ strtolower($comp->code) }}">
                                
                                <!-- Component Name & Code -->
                                <td class="py-3 px-4">
                                    <input type="hidden" name="items[{{ $index }}][epoxy_component_id]" value="{{ $comp->id }}">
                                    <div class="font-bold text-slate-900 text-xs">{{ $comp->name }}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] font-mono text-slate-500 bg-slate-100 px-1.5 py-0.2 rounded">
                                            {{ $comp->code }}
                                        </span>
                                        @if($comp->purpose)
                                            <span class="text-[10px] text-slate-500 bg-slate-100 px-1.5 py-0.2 rounded">
                                                {{ $comp->purpose }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Unit -->
                                <td class="py-3 px-3 font-semibold text-slate-700">
                                    {{ $unitCode }}
                                </td>

                                <!-- Formula Status -->
                                <td class="py-3 px-3">
                                    @if($hasFormula)
                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-semibold text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-semibold text-slate-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> No Formula
                                        </span>
                                    @endif
                                </td>

                                <!-- Current Stock Badge -->
                                <td class="py-3 px-3">
                                    <span class="font-medium text-slate-700 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-lg text-xs inline-block font-mono">
                                        {{ number_format($stock, 2) }} {{ $unitCode }}
                                    </span>
                                </td>

                                <!-- Quantity Input -->
                                <td class="py-3 px-4">
                                    @if($hasFormula)
                                        <div class="flex items-center gap-1">
                                            <button type="button" class="btn-qty-step bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 w-7 h-7 rounded-lg font-bold transition flex items-center justify-center shrink-0" data-step="-10">
                                                -
                                            </button>
                                            <input type="number" name="items[{{ $index }}][quantity]" min="0" step="1" placeholder="0"
                                                class="qty-input w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-slate-900 font-bold text-center focus:outline-none focus:bg-white {{ $isFixora ? 'focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500' : 'focus:border-orange-500 focus:ring-1 focus:ring-orange-500' }} transition text-xs font-mono"
                                                data-unit="{{ $unitCode }}">
                                            <button type="button" class="btn-qty-step bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 w-7 h-7 rounded-lg font-bold transition flex items-center justify-center shrink-0" data-step="10">
                                                +
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic">Formula required</span>
                                    @endif
                                </td>

                                <!-- Line Remarks -->
                                <td class="py-3 px-4">
                                    @if($hasFormula)
                                        <input type="text" name="items[{{ $index }}][remarks]" placeholder="Note..."
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white {{ $isFixora ? 'focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500' : 'focus:border-orange-500 focus:ring-1 focus:ring-orange-500' }} transition">
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    No active epoxy components found in catalog.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Integrated Card Footer Bar -->
            <div class="bg-slate-50 border-t border-slate-200 p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Summary Stats -->
                <div class="flex items-center gap-4 text-xs">
                    <div class="flex items-center gap-2 text-slate-600">
                        <span>Selected Components:</span>
                        <span class="font-bold text-slate-900 bg-white border border-slate-200 px-2.5 py-1 rounded-md font-mono" id="selectedCount">0</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-600">
                        <span>Total Quantity:</span>
                        <span class="font-bold {{ $isFixora ? 'text-emerald-700' : 'text-orange-700' }} bg-white border border-slate-200 px-2.5 py-1 rounded-md font-mono" id="totalQtySum">0</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                    <button type="button" id="btnResetAll" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold transition border border-slate-200">
                        Clear Inputs
                    </button>
                    <button type="submit" id="btnSubmitBulk" disabled
                        class="px-5 py-2 {{ $isFixora ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-orange-600 hover:bg-orange-700' }} text-white rounded-xl text-xs font-bold transition shadow-xs disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span id="btnSubmitText">Save All Prepared Components</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // 1. Live Search Filter
    $('#componentSearch').on('input', function() {
        const query = $(this).val().toLowerCase().trim();
        $('.component-row').each(function() {
            const name = $(this).data('name') || '';
            const code = $(this).data('code') || '';
            if (name.includes(query) || code.includes(query)) {
                $(this).removeClass('hidden');
            } else {
                $(this).addClass('hidden');
            }
        });
    });

    // 2. Quantity Increment / Decrement step buttons
    $('.btn-qty-step').click(function() {
        const step = parseInt($(this).data('step')) || 0;
        const input = $(this).siblings('.qty-input');
        let current = parseInt(input.val()) || 0;
        let updated = current + step;
        if (updated < 0) updated = 0;
        input.val(updated > 0 ? updated : '').trigger('input');
    });

    // 3. Live Counter Calculation
    $('.qty-input').on('input change', function() {
        recalculateTotals();
    });

    function recalculateTotals() {
        let count = 0;
        let sum = 0;

        $('.qty-input').each(function() {
            const val = parseInt($(this).val()) || 0;
            if (val > 0) {
                count++;
                sum += val;
            }
        });

        $('#selectedCount').text(count);
        $('#totalQtySum').text(sum.toLocaleString());

        if (count > 0) {
            $('#btnSubmitBulk').prop('disabled', false);
        } else {
            $('#btnSubmitBulk').prop('disabled', true);
        }
    }

    // 4. Reset Button
    $('#btnResetAll').click(function() {
        $('.qty-input').val('');
        $('input[name*="[remarks]"]').val('');
        $('#global_remarks').val('');
        recalculateTotals();
        $('#alertContainer').addClass('hidden').empty();
    });

    // 5. AJAX Form Submission
    $('#bulkComponentForm').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#btnSubmitBulk');
        const btnText = $('#btnSubmitText');
        const alertBox = $('#alertContainer');

        btn.prop('disabled', true);
        btnText.html('<i data-lucide="loader-2" class="w-4 h-4 animate-spin inline-block"></i> Processing...');
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    alertBox.removeClass('hidden').html(`
                        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between shadow-xs text-xs">
                            <div class="flex items-center gap-2.5">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                                <span class="font-semibold">${response.message}</span>
                            </div>
                            <a href="{{ route('epoxy.index') }}" class="text-xs text-emerald-700 hover:underline font-bold">
                                View Floor Log &rarr;
                            </a>
                        </div>
                    `);
                    
                    // Reset inputs
                    $('.qty-input').val('');
                    $('input[name*="[remarks]"]').val('');
                    $('#global_remarks').val('');
                    recalculateTotals();

                    if (typeof lucide !== 'undefined') lucide.createIcons();
                    $('html, body').animate({ scrollTop: 0 }, 'fast');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Failed to record component preparations.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                alertBox.removeClass('hidden').html(`
                    <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 flex items-center justify-between shadow-xs text-xs">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
                            <span class="font-semibold">${errorMsg}</span>
                        </div>
                    </div>
                `);

                btn.prop('disabled', false);
                btnText.text('Save All Prepared Components');
                if (typeof lucide !== 'undefined') lucide.createIcons();

                $('html, body').animate({ scrollTop: 0 }, 'fast');
            }
        });
    });
});
</script>
@endsection
