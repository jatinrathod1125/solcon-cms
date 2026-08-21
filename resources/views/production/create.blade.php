@extends('layouts.app')

@section('title', 'Start New Batch')
@section('header-title', 'Start New Production Batch')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">

    {{-- ─── Back button ─── --}}
    <div>
        <a href="{{ route('production.index') }}"
            class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors group">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform"
                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Production
        </a>
    </div>

    {{-- ─── Step Indicator ─── --}}
    <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl px-6 py-4">
        <div class="step-indicator max-w-md">
            {{-- Step 1 --}}
            <div class="step">
                <div class="step-num active" id="step-num-1">1</div>
                <span class="text-sm font-bold text-slate-700 nav-label" id="step-label-1">Select Machine</span>
            </div>
            <div class="step-line" id="step-line-1"></div>
            {{-- Step 2 --}}
            <div class="step">
                <div class="step-num pending" id="step-num-2">2</div>
                <span class="text-sm font-bold text-slate-400 nav-label" id="step-label-2">Configure &amp; Start</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
    FORM — single page, step show/hide via JS
    ═══════════════════════════════════════════════════ --}}
    <form id="start-batch-form" method="POST" action="{{ route('production.store') }}">
        @csrf
        <input type="hidden" name="machine_id" id="hidden-machine-id" value="{{ old('machine_id') }}">

        {{-- ─────────────────────────────────────
        STEP 1 PANEL: Machine Grid
        ───────────────────────────────────── --}}
        <div id="step-panel-1" class="space-y-5">
            <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-6 md:p-8 space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-blue-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Select a Mixer Machine</h3>
                        <p class="text-xs text-slate-500">Tap an idle mixer to select it. Busy mixers cannot be
                            selected.</p>
                    </div>
                </div>

                {{-- Machine validation error --}}
                <div id="machine-error"
                    class="hidden flex items-center gap-2 text-sm text-red-650 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    Please select a machine before proceeding.
                </div>

                {{-- Machine cards grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4" id="machine-grid">
                    @foreach($machines as $machine)
                    @php $isRunning = $machine->status === 'running'; @endphp
                    <div data-machine-id="{{ $machine->id }}" data-machine-name="{{ $machine->name }}"
                        data-status="{{ $machine->status }}"
                        class="machine-card {{ $isRunning ? 'running opacity-60 cursor-not-allowed' : 'idle cursor-pointer select-none' }}"
                        id="machine-card-{{ $machine->id }}">

                        {{-- Status badge top-right --}}
                        @if($isRunning)
                        <span
                            class="absolute top-3 right-3 inline-flex items-center gap-1.5 rounded-full bg-blue-50 text-blue-700 px-2 py-0.5 text-[9px] font-extrabold uppercase font-mono">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-600 animate-pulse"></span> In Use
                        </span>
                        @else
                        <span
                            class="absolute top-3 right-3 inline-flex items-center gap-1.5 rounded-full bg-slate-100 text-slate-600 px-2 py-0.5 text-[9px] font-bold uppercase font-mono">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Idle
                        </span>
                        @endif

                        {{-- Machine icon --}}
                        <div
                            class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 {{ $isRunning ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z" />
                            </svg>
                        </div>

                        <p class="text-base font-bold text-slate-900 pr-16">{{ $machine->name }}</p>
                        <p class="text-xs font-mono text-slate-400 mt-0.5">{{ $machine->code }}</p>

                        @if($isRunning)
                        <p class="text-xs text-blue-500 font-medium mt-2">Currently running a batch</p>
                        @else
                        <p class="text-xs text-emerald-600 font-medium mt-2">Available to start</p>
                        @endif

                        {{-- Selected check (shown via JS) --}}
                        <div
                            class="machine-selected-check hidden absolute bottom-3 right-3 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none"
                                viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Step 1 → Next button --}}
                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="button" id="btn-next-step" class="erp-button erp-button-primary px-6">
                        Next: Configure Batch
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ─────────────────────────────────────
        STEP 2 PANEL: Configure Batch
        ───────────────────────────────────── --}}
        <div id="step-panel-2" class="hidden space-y-5">

            {{-- Selected machine summary banner --}}
            <div id="selected-machine-banner"
                class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-2xl px-5 py-3.5 shadow-sm">
                <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] text-blue-600 font-extrabold uppercase tracking-wider">Selected Mixer Machine
                    </p>
                    <p class="text-sm font-bold text-blue-900 truncate" id="banner-machine-name">—</p>
                </div>
                <button type="button" id="btn-back-step"
                    class="erp-button erp-button-secondary !py-1 px-3 !min-h-[34px] shrink-0 text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Change
                </button>
            </div>

            {{-- Configure card --}}
            <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-6 md:p-8 space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-blue-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Configure Batch</h3>
                        <p class="text-xs text-slate-500">Choose a grade; the formula preview will load automatically.
                        </p>
                    </div>
                </div>

                {{-- Generic error box --}}
                <div id="generic-error-box"
                    class="hidden flex items-start gap-2.5 bg-red-50 border border-red-200 text-red-750 text-sm rounded-xl px-4 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 mt-0.5" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <span id="generic-error-message"></span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Grade --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Adhesive
                            Grade <span class="text-blue-500 normal-case font-bold">*</span></label>
                        <select id="grade_id" name="grade_id" required
                            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-sm">
                            <option value="">— Select Grade —</option>
                            @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" data-bag-weight="{{ $grade->bag_size->weight_kg ?? '' }}"
                                data-bag-name="{{ $grade->bag_size->name ?? '' }}" {{ old('grade_id')==$grade->id ?
                                'selected' : '' }}>
                                {{ $grade->name }} ({{ $grade->code }}){{ $grade->brand ? ' [' . $grade->brand->name . ']' : '' }}
                            </option>
                            @endforeach
                        </select>
                        <p class="field-error text-xs text-red-650 mt-1 hidden" data-error-field="grade_id"></p>
                    </div>

                    {{-- Batch Number --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Batch
                            Number <span class="text-slate-500 font-normal lowercase">(auto or custom)</span></label>
                        <input type="text" name="batch_no" id="batch_no" value="{{ old('batch_no', $batchNo) }}" placeholder="{{ $batchNo }}"
                            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white font-mono focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-sm">
                        <p class="field-error text-xs text-red-650 mt-1 hidden" data-error-field="batch_no"></p>
                    </div>

                    {{-- Start Time --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Start
                            Time</label>
                        <input type="text" id="liveStartTimeInput" value="{{ $startTime->format('d M Y, h:i:s A') }}" readonly
                            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-slate-400 font-mono cursor-not-allowed text-sm">
                    </div>

                    {{-- Supervisor --}}
                    <div>
                        <label
                            class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Supervisor</label>
                        @if(isset($supervisors) && $supervisors->count() > 0)
                        <select name="supervisor_id"
                            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-sm"
                            required>
                            <option value="">— Select Supervisor —</option>
                            @foreach($supervisors as $supervisor)
                            <option value="{{ $supervisor->id }}" {{ old('supervisor_id')==$supervisor->id ? 'selected'
                                : '' }}>
                                {{ $supervisor->name }}
                            </option>
                            @endforeach
                        </select>
                        @else
                        <input type="text" value="{{ auth()->user()->name }}" readonly
                            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-slate-400 cursor-not-allowed text-sm">
                        <input type="hidden" name="supervisor_id" value="{{ auth()->id() }}">
                        @endif
                    </div>

                    {{-- Coupon Override --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Promo Coupon Override <span class="text-slate-500 font-normal lowercase">(optional)</span></label>
                        <select name="coupon_raw_material_id" id="coupon_raw_material_id"
                            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-sm">
                            <option value="">— No Coupon / Default Formula Coupon —</option>
                            @foreach($coupons as $coupon)
                            <option value="{{ $coupon->id }}" {{ old('coupon_raw_material_id') == $coupon->id ? 'selected' : '' }}>
                                {{ $coupon->name }} (Code: {{ $coupon->code }})
                            </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-450 mt-1">If selected, this coupon will override the default coupon in the formula snapshot.</p>
                    </div>

                    {{-- Remarks --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Remarks
                            <span class="text-slate-500 normal-case font-normal">(optional)</span></label>
                        <textarea name="remarks" rows="3"
                            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-sm resize-none"
                            placeholder="e.g. Shift A, Operator Rahul, Special instructions…">{{ old('remarks') }}</textarea>
                    </div>
                </div>

                {{-- ─────────────────────────────────────
                Formula Preview Panel (AJAX-loaded)
                ───────────────────────────────────── --}}
                <div id="formula-preview-panel"
                    class="hidden border border-slate-200 rounded-2xl overflow-hidden shadow-sm transition-all duration-300">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 px-5 py-4 bg-slate-50 border-b border-slate-200">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500 animate-ping inline-block"></span>
                                Active Grade Formula Snapshot
                            </h4>
                            <p class="text-xs text-slate-400 mt-0.5">This formula will be locked for this batch run.</p>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 text-blue-700 px-2.5 py-0.5 text-[9px] font-extrabold uppercase font-mono"
                                id="preview-version">Version: —</span>
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 text-slate-600 px-2.5 py-0.5 text-[9px] font-extrabold uppercase font-mono"
                                id="preview-bag-size">Bag Size: —</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-10">
                                            #</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            Material Code</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            Material Name</th>
                                        <th
                                            class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            Qty</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            Unit</th>
                                    </tr>
                                </thead>
                                <tbody id="formula-preview-items" class="divide-y divide-slate-100 bg-white">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Form actions --}}
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" id="btn-back-step-2" class="erp-button erp-button-secondary justify-center">
                        <svg xmlns="http://www.w3.org/2000/xl" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Back: Select Machine
                    </button>
                    <button type="submit" id="submit-btn" class="erp-button erp-button-primary justify-center px-8">
                        <span id="btn-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                            </svg>
                        </span>
                        <span id="btn-text">Start Batch</span>
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
    function escapeHtml(text) {
        return text ? $('<div>').text(text).html() : '';
    }

    /* ══════════════════════════════════
       MACHINE CARD SELECTION
    ══════════════════════════════════ */
    var selectedMachineId   = '{{ old('machine_id') }}';
    var selectedMachineName = '';

    function applySelection(id, name) {
        selectedMachineId   = id;
        selectedMachineName = name;
        $('#hidden-machine-id').val(id);
        $('#banner-machine-name').text(name);

        // Visual ring
        $('.machine-card').each(function () {
            var card = $(this);
            if (card.data('status') === 'running') return;
            if (card.data('machine-id') == id) {
                card.css('border-color', 'var(--brand)')
                    .css('box-shadow', '0 0 0 3px rgba(37,99,235,0.15)');
                card.find('.machine-selected-check').removeClass('hidden');
            } else {
                card.css('border-color', '').css('box-shadow', '');
                card.find('.machine-selected-check').addClass('hidden');
            }
        });

        $('#machine-error').addClass('hidden');
    }

    // Pre-select if old() set
    if (selectedMachineId) {
        var $pre = $('#machine-card-' + selectedMachineId);
        if ($pre.length) applySelection(selectedMachineId, $pre.data('machine-name'));
    }

    // Card click handler
    $(document).on('click', '.machine-card[data-status="idle"]', function () {
        applySelection($(this).data('machine-id'), $(this).data('machine-name'));
    });

    /* ══════════════════════════════════
       STEP NAVIGATION
    ══════════════════════════════════ */
    function goToStep(step) {
        if (step === 2) {
            if (!selectedMachineId) {
                $('#machine-error').removeClass('hidden');
                $('html, body').animate({ scrollTop: $('#machine-grid').offset().top - 100 }, 300);
                return;
            }
            // Update indicator
            $('#step-num-1').removeClass('active').addClass('done').html(
                '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>'
            );
            $('#step-label-1').removeClass('text-slate-700').addClass('text-emerald-700');
            $('#step-line-1').addClass('done');
            $('#step-num-2').removeClass('pending').addClass('active');
            $('#step-label-2').removeClass('text-slate-400').addClass('text-slate-700').css('font-weight', '600');

            $('#step-panel-1').addClass('hidden');
            $('#step-panel-2').removeClass('hidden');
            $('html, body').animate({ scrollTop: 0 }, 200);
        } else {
            // Back to step 1
            $('#step-num-1').removeClass('done').addClass('active').html('1');
            $('#step-label-1').removeClass('text-emerald-700').addClass('text-slate-700');
            $('#step-line-1').removeClass('done');
            $('#step-num-2').removeClass('active').addClass('pending');
            $('#step-label-2').removeClass('text-slate-700').addClass('text-slate-400').css('font-weight', '');

            $('#step-panel-2').addClass('hidden');
            $('#step-panel-1').removeClass('hidden');
            $('html, body').animate({ scrollTop: 0 }, 200);
        }
    }

    $('#btn-next-step').on('click', function () { goToStep(2); });
    $('#btn-back-step, #btn-back-step-2').on('click', function () { goToStep(1); });

    /* ══════════════════════════════════
       GRADE CHANGE → AJAX FORMULA PREVIEW
    ══════════════════════════════════ */
    var activeFormulaAjax = null;

    $('#grade_id').on('change', function () {
        var gradeId      = $(this).val();
        var $panel       = $('#formula-preview-panel');
        var $items       = $('#formula-preview-items');

        $panel.addClass('hidden');
        $items.empty();
        $('#preview-version').text('Version: —');
        $('#preview-bag-size').text('Bag Size: —');

        if (!gradeId) return;

        $panel.removeClass('hidden');
        $items.html(
            '<tr><td colspan="5" class="py-8 text-center text-slate-400">' +
            '<svg class="w-5 h-5 animate-spin inline-block mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>' +
            'Loading formula…</td></tr>'
        );

        if (activeFormulaAjax) activeFormulaAjax.abort();

        activeFormulaAjax = $.ajax({
            url: '/production/grades/' + gradeId + '/formula',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $('#preview-version').text('Version: ' + response.version);
                $('#preview-bag-size').text('Bag Size: ' + response.bag_size_name);

                var html = '';
                if (response.items && response.items.length > 0) {
                    $.each(response.items, function (i, item) {
                        html += '<tr>';
                        html += '<td class="text-center font-mono text-slate-400 text-xs">' + (i + 1) + '</td>';
                        html += '<td class="font-mono font-semibold text-blue-600 text-xs">' + escapeHtml(item.raw_material_code) + '</td>';
                        html += '<td class="font-medium text-slate-800">' + escapeHtml(item.raw_material_name) + '</td>';
                        html += '<td class="text-right font-mono font-bold text-slate-800">' + formatQuantity(item.quantity) + '</td>';
                        html += '<td class="text-slate-500 font-medium">' + escapeHtml(item.unit_code) + '</td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="5" class="py-6 text-center text-slate-400 text-sm">No formula items configured.</td></tr>';
                }
                $items.html(html);
            },
            error: function (xhr) {
                if (xhr.statusText === 'abort') return;
                var msg = 'Could not load formula. Ensure the grade has an active formula version.';
                if (xhr.responseJSON && xhr.responseJSON.error) msg = xhr.responseJSON.error;
                $items.html('<tr><td colspan="5" class="py-6 text-center text-red-500 text-sm font-medium">' + msg + '</td></tr>');
            }
        });
    });

    // Trigger on load if old value
    if ($('#grade_id').val()) $('#grade_id').trigger('change');

    /* ══════════════════════════════════
       FORM SUBMIT VALIDATION
    ══════════════════════════════════ */
    $('#start-batch-form').on('submit', function (e) {
        // Ensure machine is selected
        if (!$('#hidden-machine-id').val()) {
            e.preventDefault();
            goToStep(1);
            $('#machine-error').removeClass('hidden');
            return;
        }
        // Ensure grade is selected
        if (!$('#grade_id').val()) {
            e.preventDefault();
            Swal.fire({
                toast: true, position: 'top-end', icon: 'warning',
                title: 'Please select a grade before starting.',
                showConfirmButton: false, timer: 3000,
                customClass: { popup: '!rounded-2xl !shadow-lg' }
            });
            return;
        }

        // Loading state
        var $btn = $('#submit-btn');
        $btn.prop('disabled', true).addClass('opacity-75');
        $('#btn-icon').html('<svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>');
        $('#btn-text').text('Starting…');
    });

    /* ══════════════════════════════════
       LIVE START TIME TICKER (IST)
    ══════════════════════════════════ */
    function updateLiveStartTime() {
        try {
            const now = new Date();
            const formatter = new Intl.DateTimeFormat('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true,
                timeZone: 'Asia/Kolkata'
            });
            const parts = formatter.formatToParts(now);
            let d = '', m = '', y = '', h = '', min = '', s = '', period = '';
            parts.forEach(function(p) {
                if (p.type === 'day') d = p.value;
                if (p.type === 'month') m = p.value;
                if (p.type === 'year') y = p.value;
                if (p.type === 'hour') h = p.value;
                if (p.type === 'minute') min = p.value;
                if (p.type === 'second') s = p.value;
                if (p.type === 'dayPeriod') period = p.value.toUpperCase();
            });
            $('#liveStartTimeInput').val(`${d} ${m} ${y}, ${h}:${min}:${s} ${period}`);
        } catch (e) {}
    }
    updateLiveStartTime();
    setInterval(updateLiveStartTime, 1000);
});
</script>
@endsection
