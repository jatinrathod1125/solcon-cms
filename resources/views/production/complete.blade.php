@extends('layouts.app')

@section('title', 'Complete Production Batch')
@section('header-title', 'Complete Batch Run')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Flatpickr Light Theme overrides */
    .flatpickr-calendar {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12) !important;
        border-radius: 16px !important;
        color: #0f172a !important;
    }
    .flatpickr-day.selected {
        background: #2563eb !important;
        border-color: #2563eb !important;
    }
</style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-5 px-1 sm:px-0">
    <!-- Top Navigation & Batch Badge -->
    <div class="flex items-center justify-between">
        <a href="{{ route('production.show', $batch->id) }}" class="inline-flex items-center text-xs sm:text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors gap-1.5 group">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform text-slate-400 group-hover:text-blue-600"></i>
            <span>Back to Active Batch</span>
        </a>
        <span class="text-xs font-mono font-bold text-slate-600 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm">
            Batch #{{ $batch->batch_no }}
        </span>
    </div>

    <!-- Main Completion Card (Clean Light ERP Card) -->
    <div class="erp-card p-5 sm:p-7 md:p-8 space-y-6">
        <!-- Card Header -->
        <div class="flex items-start gap-3.5 border-b border-slate-100 pb-5">
            <div class="p-2.5 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-sm shrink-0 mt-0.5">
                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">Complete Production Batch</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Finalize output packaging, multi-brand distribution, and coupon allocation.</p>
            </div>
        </div>

        <form id="complete-batch-form" method="POST" action="{{ route('production.complete', $batch->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Form Error Box (Generic) -->
            <div id="generic-error-box" class="hidden p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs sm:text-sm flex items-start gap-2.5">
                <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5 text-rose-500"></i>
                <span id="generic-error-message" class="font-semibold"></span>
            </div>

            <!-- Readonly Batch Info Summary (Crisp Light Theme Card) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 bg-slate-50/80 p-4 sm:p-5 rounded-2xl border border-slate-200/80">
                <div class="flex flex-col">
                    <span class="text-[10px] uppercase font-extrabold tracking-wider text-slate-400 flex items-center gap-1.5">
                        <i data-lucide="tag" class="w-3.5 h-3.5 text-blue-500"></i>
                        Chemical Base Grade
                    </span>
                    <div class="flex items-center gap-2 flex-wrap mt-1.5">
                        <span class="text-slate-900 font-extrabold text-sm sm:text-base">{{ $batch->grade->name }}</span>
                        @if($batch->grade?->brand)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                                {{ $batch->grade->brand->name }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] uppercase font-extrabold tracking-wider text-slate-400 flex items-center gap-1.5">
                        <i data-lucide="cpu" class="w-3.5 h-3.5 text-blue-500"></i>
                        Mixer Machine
                    </span>
                    <div class="mt-1.5">
                        <span class="text-slate-900 font-bold text-sm">{{ $batch->machine->name }}</span>
                        <span class="text-slate-400 font-mono text-xs font-bold ml-1">({{ $batch->machine->code }})</span>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] uppercase font-extrabold tracking-wider text-slate-400 flex items-center gap-1.5">
                        <i data-lucide="clock" class="w-3.5 h-3.5 text-blue-500"></i>
                        Run Started At
                    </span>
                    <span class="text-blue-700 font-mono text-xs sm:text-sm font-bold mt-1.5">
                        {{ $batch->start_time->format('d M Y, h:i A') }}
                    </span>
                </div>
            </div>

            <!-- Packaging & Multi-Brand Split Section -->
            <div class="space-y-3 pt-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-1">
                    <div>
                        <h4 class="text-sm sm:text-base font-extrabold text-slate-900 flex items-center gap-2">
                            <i data-lucide="layers" class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600"></i>
                            <span>Packaging & Brand Output Breakdown</span>
                        </h4>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Distribute this mixer run across compatible brands (e.g. Solcon / Fixora) and coupon variants.
                        </p>
                    </div>
                    <button type="button" id="add-split-btn" class="w-full sm:w-auto px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-extrabold transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-blue-600/20 active:scale-95">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        <span>Add Packaging Split</span>
                    </button>
                </div>

                <!-- Desktop Table Header (Clean Light Gray Surface) -->
                <div class="hidden md:grid md:grid-cols-12 md:gap-3 text-[11px] uppercase font-extrabold tracking-wider text-slate-500 px-4 py-2.5 bg-slate-100/90 rounded-xl border border-slate-200">
                    <div class="col-span-4">Brand & Product Grade</div>
                    <div class="col-span-2">Packaging Bag</div>
                    <div class="col-span-3">Promo Coupon</div>
                    <div class="col-span-2 text-right">Bags & Weight</div>
                    <div class="col-span-1 text-center">Action</div>
                </div>

                <!-- Dynamic Split Items Container (Cards on Mobile / Clean Rows on Desktop) -->
                <div id="split-items-container" class="space-y-3">
                    <!-- Populated dynamically via JS -->
                </div>

                <!-- Total Output Summary Card (Pleasing Light Emerald Surface) -->
                <div class="bg-gradient-to-r from-emerald-50/80 via-teal-50/40 to-slate-50 border border-emerald-200/80 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                    <div class="text-xs font-extrabold uppercase tracking-wider text-emerald-800 flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                            <i data-lucide="calculator" class="w-4 h-4"></i>
                        </span>
                        <span>Total Recorded Output</span>
                    </div>
                    <div class="flex items-center justify-around w-full sm:w-auto sm:justify-end gap-6 sm:gap-8">
                        <div class="text-center sm:text-right">
                            <span class="text-[10px] text-slate-500 uppercase block font-extrabold tracking-wider">Total Bags</span>
                            <div class="flex items-baseline justify-center sm:justify-end gap-1">
                                <span id="total-split-bags" class="font-mono text-slate-900 text-2xl font-extrabold">0</span>
                                <span class="text-slate-500 text-xs font-bold">Bags</span>
                            </div>
                        </div>
                        <div class="h-9 w-px bg-emerald-200/60 hidden sm:block"></div>
                        <div class="text-center sm:text-right">
                            <span class="text-[10px] text-emerald-700 uppercase block font-extrabold tracking-wider">Total Weight</span>
                            <div class="flex items-baseline justify-center sm:justify-end gap-1">
                                <span id="total-split-kg" class="font-mono text-emerald-600 text-2xl font-extrabold">0.00</span>
                                <span class="text-emerald-700 text-xs font-bold">KG</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Inputs to bind total values for backend validation -->
            <input type="hidden" id="output_bags" name="output_bags" value="0">
            <input type="hidden" id="output_kg" name="output_kg" value="0">

            <!-- End Time & Remarks Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 pt-2 border-t border-slate-100">
                <!-- End Time Section -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Batch End Time <span class="text-blue-600">*</span>
                        </label>
                        <!-- Auto / Custom Toggle -->
                        <div class="inline-flex items-center bg-slate-100 p-0.5 rounded-lg border border-slate-200 text-[11px] font-bold">
                            <button type="button" id="btn-mode-auto" class="px-2.5 py-1 rounded-md transition-all bg-white text-emerald-700 shadow-sm flex items-center gap-1.5 cursor-pointer">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Real-Time (Auto)
                            </button>
                            <button type="button" id="btn-mode-manual" class="px-2.5 py-1 rounded-md transition-all text-slate-500 hover:text-slate-700 cursor-pointer">
                                Custom / Past
                            </button>
                        </div>
                    </div>

                    <!-- Auto Live Time Box (Default) -->
                    <div id="auto-time-box" class="p-3 bg-emerald-50/70 border border-emerald-200/80 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 shrink-0">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                            </div>
                            <span id="live-clock-display" class="font-mono font-extrabold text-slate-900 text-sm">--:--:--</span>
                        </div>
                    </div>

                    <!-- Manual Time Picker Box (Hidden by default) -->
                    <div id="manual-time-box" class="hidden">
                        <div class="relative">
                            <input type="text" id="end_time_picker" placeholder="Select exact end date & time..."
                                class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 hover:border-slate-300 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 rounded-xl text-slate-800 font-mono text-sm transition-all cursor-pointer outline-none">
                            <i data-lucide="calendar" class="w-4 h-4 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>
                        <p class="text-[10px] text-slate-500 font-medium mt-1">
                            Only use this if you are recording a past batch completed earlier.
                        </p>
                    </div>

                    <input type="hidden" id="end_time" name="end_time" value="auto">
                    <p class="field-error text-rose-600 text-xs mt-1.5 hidden font-semibold" data-error-field="end_time"></p>
                </div>

                <!-- Remarks -->
                <div>
                    <label for="remarks" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Production Remarks / Notes
                    </label>
                    <textarea id="remarks" name="remarks" rows="2"
                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 hover:border-slate-300 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 rounded-xl text-slate-800 placeholder-slate-400 transition-all text-sm outline-none"
                        placeholder="e.g. Completed standard run. Bagging done for Solcon and Fixora.">{{ old('remarks', $batch->remarks) }}</textarea>
                    <p class="field-error text-rose-600 text-xs mt-1.5 hidden font-semibold" data-error-field="remarks"></p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('production.show', $batch->id) }}"
                    class="w-full sm:w-auto px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-sm transition-all text-center cursor-pointer shadow-sm">
                    Cancel
                </a>
                <button type="submit" id="submit-btn"
                    class="w-full sm:w-auto px-7 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl transition-all duration-200 shadow-md shadow-emerald-600/20 text-sm flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                    <span id="btn-icon">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                    </span>
                    <span id="btn-text">Complete Batch</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
        // Live ticking clock in Indian Standard Time (IST)
        function updateLiveClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
            $('#live-clock-display').text(timeStr);
        }
        updateLiveClock();
        setInterval(updateLiveClock, 1000);

        let fpInstance = null;
        function initFlatpickr() {
            if (!fpInstance) {
                fpInstance = flatpickr("#end_time_picker", {
                    enableTime: true,
                    enableSeconds: true,
                    dateFormat: "Y-m-d H:i:S",
                    altInput: true,
                    altFormat: "d M Y, h:i:S K",
                    time_24hr: false,
                    defaultDate: new Date(),
                    onChange: function(selectedDates, dateStr) {
                        $('#end_time').val(dateStr);
                    }
                });
            }
        }

        // Mode Switching
        $('#btn-mode-auto').on('click', function() {
            $(this).addClass('bg-white text-emerald-700 shadow-sm').removeClass('text-slate-500');
            $('#btn-mode-manual').removeClass('bg-white text-emerald-700 shadow-sm').addClass('text-slate-500');
            $('#auto-time-box').removeClass('hidden');
            $('#manual-time-box').addClass('hidden');
            $('#end_time').val('auto');
            $('.field-error[data-error-field="end_time"]').addClass('hidden').text('');
        });

        $('#btn-mode-manual').on('click', function() {
            $(this).addClass('bg-white text-emerald-700 shadow-sm').removeClass('text-slate-500');
            $('#btn-mode-auto').removeClass('bg-white text-emerald-700 shadow-sm').addClass('text-slate-500');
            $('#auto-time-box').addClass('hidden');
            $('#manual-time-box').removeClass('hidden');
            initFlatpickr();
            if (fpInstance) {
                fpInstance.setDate(new Date(), true);
            }
            if (window.lucide) {
                lucide.createIcons();
            }
        });

        const compatibleGrades = @json($compatibleGrades ?? []);
        const availableCoupons = @json($availableCoupons ?? []);
        const oldBreakdown = @json(old('split_breakdown', []));
        const batchGradeId = {{ $batch->grade_id ?? 'null' }};
        const plannedBags = {{ $batch->planned_bags ?? 0 }};

        let rowIndex = 0;

        function addSplitRow(data = {}) {
            const idx = rowIndex++;
            const selectedGradeId = data.grade_id || batchGradeId;
            const selectedCouponId = data.coupon_raw_material_id || '';
            const bags = data.bags !== undefined ? data.bags : (idx === 0 && plannedBags > 0 ? plannedBags : '');

            let gradeObj = compatibleGrades.find(g => g.id == selectedGradeId) || compatibleGrades[0] || {};

            let gradeOptions = compatibleGrades.map(g => {
                let isSel = (g.id == selectedGradeId) ? 'selected' : '';
                let brandTag = g.brand_name ? `[${g.brand_name}] ` : '';
                return `<option value="${g.id}" data-bagsize="${g.bag_size}" data-packingid="${g.packing_material_id || ''}" data-packingname="${g.packing_material_name || 'Standard Bag'}" ${isSel}>${brandTag}${g.name} (${g.code})</option>`;
            }).join('');

            let couponOptions = `<option value="">No Coupon (Without Token)</option>` + 
                availableCoupons.map(c => {
                    let isSel = (c.id == selectedCouponId) ? 'selected' : '';
                    return `<option value="${c.id}" ${isSel}>${c.name} (${c.code})</option>`;
                }).join('');

            let bagSize = gradeObj.bag_size || 20;
            let packingId = gradeObj.packing_material_id || '';
            let packingName = gradeObj.packing_material_name || 'Standard Bag';
            let initialKg = (parseFloat(bags) > 0 ? (bags * bagSize).toFixed(2) : '0.00');

            let rowHtml = `
                <div class="split-item bg-white hover:bg-slate-50/60 border border-slate-200 hover:border-slate-300 rounded-2xl p-3.5 sm:p-4 transition-all space-y-3 md:space-y-0 md:grid md:grid-cols-12 md:gap-3 md:items-center shadow-sm" data-index="${idx}">
                    <!-- Mobile Top Header Bar -->
                    <div class="flex items-center justify-between md:hidden pb-2.5 border-b border-slate-100">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-100 text-xs font-extrabold">
                            <i data-lucide="package" class="w-3.5 h-3.5"></i>
                            <span>Packaging Split #<span class="split-num">${$('#split-items-container .split-item').length + 1}</span></span>
                        </span>
                        <button type="button" class="remove-split-btn text-slate-400 hover:text-rose-600 hover:bg-rose-50 p-1.5 rounded-lg transition-colors cursor-pointer" title="Remove Split">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <!-- Grade Selector (col-span-4) -->
                    <div class="md:col-span-4">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1 md:hidden">Brand & Product Grade</label>
                        <select name="split_breakdown[${idx}][grade_id]" class="grade-select w-full px-3 py-2.5 bg-slate-50 border border-slate-200 hover:border-slate-300 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 rounded-xl text-slate-800 text-xs font-bold cursor-pointer transition-all outline-none">
                            ${gradeOptions}
                        </select>
                        <input type="hidden" name="split_breakdown[${idx}][bag_size]" class="bag-size-input" value="${bagSize}">
                    </div>

                    <!-- Packaging Bag Badge (col-span-2) -->
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1 md:hidden">Packaging Bag</label>
                        <div class="flex items-center">
                            <span class="packing-badge px-3 py-2.5 bg-slate-100 border border-slate-200/80 rounded-xl text-slate-700 font-mono text-xs font-bold truncate w-full block text-center md:text-left shadow-2xs" title="${packingName}">
                                ${packingName}
                            </span>
                            <input type="hidden" name="split_breakdown[${idx}][packing_material_id]" class="packing-id-input" value="${packingId}">
                        </div>
                    </div>

                    <!-- Coupon Variant Selector (col-span-3) -->
                    <div class="md:col-span-3">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1 md:hidden">Coupon Option</label>
                        <select name="split_breakdown[${idx}][coupon_raw_material_id]" class="coupon-select w-full px-3 py-2.5 bg-slate-50 border border-slate-200 hover:border-slate-300 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 rounded-xl text-slate-800 text-xs font-bold cursor-pointer transition-all outline-none">
                            ${couponOptions}
                        </select>
                    </div>

                    <!-- Bags Input & Weight (col-span-2) -->
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1 md:hidden">Output Bags & Weight</label>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <input type="number" min="1" step="1" name="split_breakdown[${idx}][bags]" class="bags-input w-full px-3 py-2 bg-slate-50 border border-slate-200 hover:border-slate-300 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 rounded-xl text-slate-900 font-mono text-sm font-extrabold text-right transition-all outline-none" placeholder="0" value="${bags}">
                            </div>
                            <div class="md:hidden flex items-center justify-end font-mono text-emerald-700 text-xs font-extrabold bg-emerald-50 px-2.5 py-2 rounded-xl border border-emerald-200 shrink-0 min-w-[85px] text-right">
                                <span class="row-kg-display-mobile">${initialKg}</span>&nbsp;KG
                            </div>
                        </div>
                        <div class="hidden md:flex justify-end items-center gap-1 mt-1 text-[11px] font-mono font-bold text-emerald-600">
                            <span class="row-kg-display">${initialKg}</span> KG
                        </div>
                        <input type="hidden" name="split_breakdown[${idx}][kg]" class="row-kg-input" value="${initialKg}">
                    </div>

                    <!-- Desktop Delete Button (col-span-1) -->
                    <div class="hidden md:flex md:col-span-1 justify-center">
                        <button type="button" class="remove-split-btn p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors rounded-xl cursor-pointer" title="Remove this split">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            `;

            $('#split-items-container').append(rowHtml);
            if (window.lucide) {
                lucide.createIcons();
            }
            updateSplitNumbers();
            recalcSplit();
        }

        function updateSplitNumbers() {
            $('#split-items-container .split-item').each(function(i) {
                $(this).find('.split-num').text(i + 1);
            });
        }

        function recalcSplit() {
            let totalBags = 0;
            let totalKg = 0;

            $('#split-items-container .split-item').each(function() {
                let $item = $(this);
                let bags = parseFloat($item.find('.bags-input').val()) || 0;
                let bagSize = parseFloat($item.find('.bag-size-input').val()) || 20;
                let rowKg = bags * bagSize;

                $item.find('.row-kg-display').text(rowKg.toFixed(2));
                $item.find('.row-kg-display-mobile').text(rowKg.toFixed(2));
                $item.find('.row-kg-input').val(rowKg.toFixed(2));

                totalBags += bags;
                totalKg += rowKg;
            });

            $('#total-split-bags').text(totalBags);
            $('#total-split-kg').text(totalKg.toFixed(2));
            $('#output_bags').val(totalBags);
            $('#output_kg').val(totalKg.toFixed(2));
        }

        // Initialize rows
        if (oldBreakdown && Array.isArray(oldBreakdown) && oldBreakdown.length > 0) {
            oldBreakdown.forEach(function(item) {
                addSplitRow(item);
            });
        } else {
            addSplitRow();
        }

        // Event: Add Row
        $('#add-split-btn').on('click', function() {
            addSplitRow();
        });

        // Event: Remove Row
        $('#split-items-container').on('click', '.remove-split-btn', function() {
            if ($('#split-items-container .split-item').length <= 1) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'At least one packaging split row is required.',
                    showConfirmButton: false,
                    timer: 2500,
                    background: '#ffffff',
                    color: '#0f172a'
                });
                return;
            }
            $(this).closest('.split-item').remove();
            updateSplitNumbers();
            recalcSplit();
        });

        // Event: Grade change updates packing and bag size
        $('#split-items-container').on('change', '.grade-select', function() {
            let $item = $(this).closest('.split-item');
            let $selected = $(this).find('option:selected');
            let bagSize = parseFloat($selected.data('bagsize')) || 20;
            let packingId = $selected.data('packingid') || '';
            let packingName = $selected.data('packingname') || 'Standard Bag';

            $item.find('.bag-size-input').val(bagSize);
            $item.find('.packing-id-input').val(packingId);
            $item.find('.packing-badge').text(packingName).attr('title', packingName);

            recalcSplit();
        });

        // Event: Bags input change
        $('#split-items-container').on('input keyup change', '.bags-input', function() {
            recalcSplit();
        });

        // Submit form with SweetAlert confirmation and AJAX
        $('#complete-batch-form').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);
            var $submitBtn = $('#submit-btn');
            var $btnIcon = $('#btn-icon');
            var $btnText = $('#btn-text');
            var $genericErrorBox = $('#generic-error-box');

            recalcSplit();

            var totalBags = parseFloat($('#output_bags').val()) || 0;
            var totalKg = parseFloat($('#output_kg').val()) || 0;

            if (totalBags <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Output',
                    text: 'Total bags produced must be greater than zero. Please enter bags for at least one split row.',
                    confirmButtonColor: '#e11d48',
                    background: '#ffffff',
                    color: '#0f172a'
                });
                return;
            }

            // Build human-friendly breakdown HTML summary for confirmation
            let breakdownList = '';
            $('#split-items-container .split-item').each(function() {
                let $r = $(this);
                let bags = parseFloat($r.find('.bags-input').val()) || 0;
                if (bags > 0) {
                    let gradeText = $r.find('.grade-select option:selected').text().trim();
                    let couponText = $r.find('.coupon-select option:selected').text().trim();
                    let packingText = $r.find('.packing-badge').text().trim();
                    breakdownList += `
                        <div class="flex justify-between items-center py-2 border-b border-slate-200 text-xs text-left">
                            <div class="pr-2">
                                <span class="font-extrabold text-slate-800">${gradeText}</span>
                                <div class="text-slate-500 text-[11px] mt-0.5">${packingText} • ${couponText}</div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-mono text-blue-600 font-extrabold text-sm">${bags} Bags</span>
                            </div>
                        </div>
                    `;
                }
            });

            // Confirm Completion via SweetAlert (Light Theme)
            Swal.fire({
                title: 'Finalize Production Batch?',
                html: `
                    <div class="text-left mb-3">
                        <div class="text-xs text-slate-500 mb-2 font-medium">Please review the split packaging breakdown before completing:</div>
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-200">
                            ${breakdownList}
                            <div class="flex justify-between items-center pt-2.5 font-extrabold text-sm">
                                <span class="text-slate-600">Total Batch Output:</span>
                                <span class="text-emerald-600 font-mono text-base">${totalBags} Bags (${totalKg.toFixed(2)} KG)</span>
                            </div>
                        </div>
                    </div>
                    <small class="text-slate-500 font-medium">Packaging bags & coupon stocks will be deducted, and finished goods inventory will be updated.</small>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, complete & finalize',
                cancelButtonText: 'Cancel',
                background: '#ffffff',
                color: '#0f172a'
            }).then(function(result) {
                if (result.isConfirmed) {
                    // Reset validation errors
                    $('.field-error').addClass('hidden').text('');
                    $genericErrorBox.addClass('hidden');
                    $('#generic-error-message').text('');

                    // Set loading state
                    $submitBtn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
                    $btnIcon.html('<i data-lucide="loader" class="w-4 h-4 animate-spin"></i>');
                    $btnText.text('Completing batch...');
                    if (window.lucide) {
                        lucide.createIcons();
                    }

                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        data: $form.serialize(),
                        success: function(response) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: response.message || 'Production batch completed successfully!',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                background: '#ffffff',
                                color: '#0f172a',
                                iconColor: '#059669'
                            }).then(function() {
                                window.location.href = response.redirect_url;
                            });
                        },
                        error: function(xhr) {
                            // Restore button state
                            $submitBtn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                            $btnIcon.html('<i data-lucide="check-circle" class="w-4 h-4"></i>');
                            $btnText.text('Complete Batch');
                            if (window.lucide) {
                                lucide.createIcons();
                            }

                            if (xhr.status === 422) {
                                var response = xhr.responseJSON;

                                // Show field errors
                                if (response.errors) {
                                    $.each(response.errors, function(field, messages) {
                                        var $errLabel = $('.field-error[data-error-field="' + field + '"]');
                                        if ($errLabel.length) {
                                            $errLabel.text(messages[0]).removeClass('hidden');
                                        }
                                    });
                                }

                                var message = response.message || 'Validation failed. Please correct the fields.';
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: message,
                                    showConfirmButton: false,
                                    timer: 3500,
                                    background: '#ffffff',
                                    color: '#0f172a',
                                    iconColor: '#e11d48'
                                });
                            } else {
                                var msg = 'An unexpected server error occurred.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                $genericErrorBox.removeClass('hidden');
                                $('#generic-error-message').text(msg);

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Completion Failed',
                                    text: msg,
                                    confirmButtonColor: '#e11d48',
                                    background: '#ffffff',
                                    color: '#0f172a'
                                });
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
