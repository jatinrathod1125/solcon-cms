@extends('layouts.app')

@section('title', 'Bulk Component Entry')
@section('header-title', 'Epoxy Component Entry')

@section('content')
<div class="max-w-6xl mx-auto space-y-5 pb-12">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-850 pb-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('epoxy.index') }}" class="p-2 hover:bg-slate-900 rounded-xl text-slate-400 hover:text-white transition border border-slate-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <i data-lucide="boxes" class="w-5 h-5 text-slate-300"></i>
                    Log Prepared Components
                </h2>
                <p class="text-xs text-slate-400">Record prepared ready components in bulk. Raw material stocks will be deducted automatically.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs bg-slate-900 text-slate-300 border border-slate-800 px-3 py-1.5 rounded-xl font-medium flex items-center gap-1.5">
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
        <div class="bg-slate-950 border border-slate-850 p-4 rounded-xl space-y-4 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                <!-- Search Box -->
                <div class="md:col-span-4">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Search Component</label>
                    <div class="relative">
                        <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3 top-2.5 text-slate-500"></i>
                        <input type="text" id="componentSearch" placeholder="Filter by name or code..."
                            class="w-full bg-slate-900 border border-slate-800 rounded-xl pl-8 pr-3 py-1.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition">
                    </div>
                </div>

                <!-- Global Remarks -->
                <div class="md:col-span-8">
                    <label for="global_remarks" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Shift / Operator Notes (Optional)</label>
                    <input type="text" name="global_remarks" id="global_remarks" placeholder="e.g. Morning Shift Preparation..."
                        class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-1.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition">
                </div>
            </div>
        </div>

        <!-- Components Matrix Table Card -->
        <div class="bg-slate-950 border border-slate-850 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="componentTable">
                    <thead>
                        <tr class="bg-slate-900/90 border-b border-slate-850 text-[10px] uppercase font-bold tracking-wider text-slate-400">
                            <th class="py-3 px-4">Component Details</th>
                            <th class="py-3 px-3">Unit</th>
                            <th class="py-3 px-3">Formula Status</th>
                            <th class="py-3 px-3">Current Ready Stock</th>
                            <th class="py-3 px-4 w-44">Quantity Prepared</th>
                            <th class="py-3 px-4">Line Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-900 text-xs">
                        @forelse($components as $index => $comp)
                            @php
                                $hasFormula = (bool) $comp->activeFormula;
                                $stock = $comp->rawMaterial ? (float)$comp->rawMaterial->current_stock : 0;
                                $unitCode = $comp->unit ? $comp->unit->code : 'PCS';
                            @endphp
                            <tr class="component-row hover:bg-slate-900/50 transition {{ !$hasFormula ? 'opacity-50' : '' }}" 
                                data-name="{{ strtolower($comp->name) }}" 
                                data-code="{{ strtolower($comp->code) }}">
                                
                                <!-- Component Name & Code -->
                                <td class="py-2.5 px-4">
                                    <input type="hidden" name="items[{{ $index }}][epoxy_component_id]" value="{{ $comp->id }}">
                                    <div class="font-bold text-white text-xs">{{ $comp->name }}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] font-mono text-slate-400 bg-slate-900 border border-slate-800 px-1.5 py-0.2 rounded">
                                            {{ $comp->code }}
                                        </span>
                                        @if($comp->purpose)
                                            <span class="text-[10px] text-slate-400 bg-slate-900 border border-slate-800 px-1.5 py-0.2 rounded">
                                                {{ $comp->purpose }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Unit -->
                                <td class="py-2.5 px-3 font-medium text-slate-300">
                                    {{ $unitCode }}
                                </td>

                                <!-- Formula Status -->
                                <td class="py-2.5 px-3">
                                    @if($hasFormula)
                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-semibold text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-semibold text-slate-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span> No Formula
                                        </span>
                                    @endif
                                </td>

                                <!-- Current Stock Badge -->
                                <td class="py-2.5 px-3">
                                    <span class="font-medium text-slate-200 bg-slate-900 border border-slate-800 px-2.5 py-1 rounded-lg text-xs inline-block">
                                        {{ number_format($stock, 2) }} {{ $unitCode }}
                                    </span>
                                </td>

                                <!-- Quantity Input -->
                                <td class="py-2.5 px-4">
                                    @if($hasFormula)
                                        <div class="flex items-center gap-1">
                                            <button type="button" class="btn-qty-step bg-slate-900 hover:bg-slate-850 border border-slate-800 text-slate-300 w-7 h-7 rounded-lg font-bold transition flex items-center justify-center shrink-0" data-step="-10">
                                                -
                                            </button>
                                            <input type="number" name="items[{{ $index }}][quantity]" min="0" step="1" placeholder="0"
                                                class="qty-input w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white font-bold text-center focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition text-xs"
                                                data-unit="{{ $unitCode }}">
                                            <button type="button" class="btn-qty-step bg-slate-900 hover:bg-slate-850 border border-slate-800 text-slate-300 w-7 h-7 rounded-lg font-bold transition flex items-center justify-center shrink-0" data-step="10">
                                                +
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-500 italic">Formula required</span>
                                    @endif
                                </td>

                                <!-- Line Remarks -->
                                <td class="py-2.5 px-4">
                                    @if($hasFormula)
                                        <input type="text" name="items[{{ $index }}][remarks]" placeholder="Note..."
                                            class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition">
                                    @else
                                        <span class="text-slate-600">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500">
                                    No active epoxy components found in catalog.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Integrated Card Footer Bar -->
            <div class="bg-slate-900/90 border-t border-slate-850 p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Summary Stats -->
                <div class="flex items-center gap-4 text-xs">
                    <div class="flex items-center gap-2 text-slate-400">
                        <span>Selected Components:</span>
                        <span class="font-bold text-white bg-slate-950 border border-slate-800 px-2.5 py-1 rounded-md" id="selectedCount">0</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400">
                        <span>Total Quantity:</span>
                        <span class="font-bold text-blue-400 bg-slate-950 border border-slate-800 px-2.5 py-1 rounded-md" id="totalQtySum">0</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <button type="button" id="btnResetAll" class="px-4 py-2 bg-slate-950 hover:bg-slate-850 text-slate-400 hover:text-white rounded-xl text-xs font-semibold transition border border-slate-800">
                        Clear Inputs
                    </button>
                    <button type="submit" id="btnSubmitBulk" disabled
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition shadow-sm disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5">
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
                        <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 flex items-center justify-between shadow-sm text-xs">
                            <div class="flex items-center gap-2.5">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
                                <span class="font-medium">${response.message}</span>
                            </div>
                            <a href="{{ route('epoxy.index') }}" class="text-xs text-blue-400 hover:underline font-bold">
                                View Log Report &rarr;
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
                    <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800 text-rose-300 flex items-center justify-between shadow-sm text-xs">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400"></i>
                            <span class="font-medium">${errorMsg}</span>
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
