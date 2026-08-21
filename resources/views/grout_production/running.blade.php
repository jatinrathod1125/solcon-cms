@extends('layouts.app')

@section('title', 'Operator Console')
@section('header-title', 'Active Grout Run')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Back Link -->
    <div>
        <a href="{{ route('grout-production.index') }}" class="inline-flex items-center text-sm text-slate-400 hover:text-white transition-colors gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Production Floor</span>
        </a>
    </div>

    <!-- Active Stage Timeline -->
    @php
        if ($batch->machine->code === 'M-01') {
            $states = [
                'Waiting' => 1,
                'Stage 1 Mixing' => 2,
                'Timer Running' => 3,
                'Ready For Packing' => 4,
                'Packing' => 5
            ];
        } else {
            $states = [
                'Waiting' => 1,
                'Stage 1 Mixing' => 2,
                'Timer Running' => 3,
                'Waiting Cement' => 4,
                'Stage 2 Mixing' => 5,
                'Ready For Packing' => 6,
                'Packing' => 7
            ];
        }
        $currentWeight = $states[$batch->status] ?? 1;
    @endphp
    <div class="bg-slate-950 border border-slate-850 rounded-2xl p-5 shadow-xl">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Stage Timeline</h3>

        <div class="relative flex items-center justify-between gap-2 overflow-x-auto pb-2">
            @foreach($states as $name => $weight)
                @php
                    $isPast = $weight < $currentWeight;
                    $isCurrent = $weight === $currentWeight;
                @endphp
                <div class="flex flex-col items-center text-center min-w-[100px] flex-1">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold border transition-all duration-200
                        {{ $isPast ? 'bg-cyan-500/10 border-cyan-500 text-cyan-400' : '' }}
                        {{ $isCurrent ? 'bg-cyan-500 border-cyan-500 text-slate-950 ring-4 ring-cyan-500/20' : '' }}
                        {{ !$isPast && !$isCurrent ? 'bg-slate-900 border-slate-800 text-slate-500' : '' }}">
                        @if($isPast)
                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                        @else
                            {{ $weight }}
                        @endif
                    </span>
                    <span class="mt-2 text-[10px] font-bold uppercase tracking-wider
                        {{ $isPast || $isCurrent ? 'text-white' : 'text-slate-500' }}">
                        {{ $name }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Main Panel -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Batch details -->
        <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-5 md:p-6 space-y-4 self-start">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-3">Run Details</h3>

            <div class="space-y-3.5 text-xs">
                <div>
                    <span class="text-slate-500 block uppercase font-bold tracking-wider text-[9px] mb-1">Batch Code</span>
                    <span class="font-mono text-sm font-bold text-cyan-400">{{ $batch->batch_no }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block uppercase font-bold tracking-wider text-[9px] mb-1">Assigned Mixer</span>
                    <span class="font-semibold text-white">{{ $batch->machine->name }} ({{ $batch->machine->code }})</span>
                </div>
                <div>
                    <span class="text-slate-500 block uppercase font-bold tracking-wider text-[9px] mb-1">Grout Color</span>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="font-semibold text-white">{{ $batch->color->name }}</span>
                        <span class="px-1.5 py-0.2 rounded bg-slate-900 border border-slate-800 text-[10px] text-slate-400 font-mono">{{ $batch->color->code }}</span>
                        @if($batch->color->brand)
                            <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                {{ $batch->color->brand->name }}
                            </span>
                        @endif
                    </div>
                </div>
                <div>
                    <span class="text-slate-500 block uppercase font-bold tracking-wider text-[9px] mb-1">Packing Specs</span>
                    <p class="text-slate-350">
                        {{ $batch->color->packing_size }} Pouches (Auto-packed on M-01)
                    </p>
                </div>
                <div>
                    <span class="text-slate-500 block uppercase font-bold tracking-wider text-[9px] mb-1">Current Operator</span>
                    <span class="font-medium text-white">{{ $batch->operator->name }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block uppercase font-bold tracking-wider text-[9px] mb-1">Start Time</span>
                    <span class="text-slate-350 font-mono">{{ ($batch->start_time ?? $batch->created_at)->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</span>
                </div>
                @if($batch->remarks)
                    <div>
                        <span class="text-slate-500 block uppercase font-bold tracking-wider text-[9px] mb-1">Operator Remarks</span>
                        <p class="text-slate-400 italic leading-relaxed">"{{ $batch->remarks }}"</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Adaptive Controller Panel -->
        <div class="md:col-span-2 bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-5 md:p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-3 mb-4 flex items-center justify-between">
                    <span>Active Workflow Stage</span>
                    <span class="text-xs text-cyan-400 font-mono uppercase tracking-normal">Status: {{ $batch->status }}</span>
                </h3>

                <!-- Stage 1 Mixing Panel -->
                @if($batch->status === 'Stage 1 Mixing')
                    <div class="space-y-5">
                        @if($batch->machine->code === 'M-01')
                            <div class="bg-blue-950/20 border border-blue-900/50 rounded-xl p-4 text-blue-300 text-xs flex gap-3">
                                <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5"></i>
                                <div>
                                    <strong class="font-semibold">Workflow Instructions (Automatic Mixer M-01):</strong>
                                    <p class="mt-1 leading-relaxed">Load ALL materials together, including Chuno, MHEC, RDP, special chemicals, Color pigments, and White/Grey Cement binder. Everything is loaded before mixing starts.</p>
                                </div>
                            </div>
                        @else
                            <div class="bg-blue-950/20 border border-blue-900/50 rounded-xl p-4 text-blue-300 text-xs flex gap-3">
                                <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5"></i>
                                <div>
                                    <strong class="font-semibold">Workflow Instructions:</strong>
                                    <p class="mt-1 leading-relaxed">Load Chuno, MHEC, RDP, special chemicals, and Color pigments into the mixer. Do NOT add cement in this stage.</p>
                                </div>
                            </div>
                        @endif

                        <div class="border border-slate-850 rounded-xl overflow-hidden bg-slate-900/10">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="bg-slate-900/60 border-b border-slate-850 text-slate-400 font-bold uppercase">
                                        <th class="p-3">Raw Material</th>
                                        <th class="p-3 text-right">Proportion Qty</th>
                                        <th class="p-3 w-20">Unit</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-850/50">
                                    @foreach($batch->formula_snapshot as $item)
                                        @if($batch->machine->code === 'M-01' || $item['mix_stage'] === 'Stage 1')
                                            <tr class="text-slate-350">
                                                <td class="p-3 font-medium text-white">{{ $item['raw_material_name'] }}</td>
                                                <td class="p-3 text-right font-mono font-bold">{{ number_format($item['quantity'], 2) }}</td>
                                                <td class="p-3 font-semibold text-slate-500">{{ $item['unit_code'] }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <form method="POST" action="{{ route('grout-production.start-timer', $batch->id) }}">
                            @csrf
                            @if($batch->machine->code !== 'M-01')
                                <button type="submit" class="flex w-full items-center justify-center py-3 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold rounded-xl shadow-lg shadow-cyan-500/20 text-sm gap-2 transition-all">
                                    <i data-lucide="play" class="w-4 h-4"></i>
                                    <span>Start Mixing (1-Hour Timer)</span>
                                </button>
                            @else
                                <!-- M-01 automatic mixes directly -->
                                <button type="submit" class="flex w-full items-center justify-center py-3 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold rounded-xl shadow-lg shadow-cyan-500/20 text-sm gap-2 transition-all">
                                    <i data-lucide="play" class="w-4 h-4"></i>
                                    <span>Start Mixing (1-Hour Timer)</span>
                                </button>
                            @endif
                        </form>
                    </div>

                <!-- Timer Running Panel -->
                @elseif($batch->status === 'Timer Running')
                    <div class="space-y-6 text-center py-6">
                        <div class="flex flex-col items-center justify-center space-y-3">
                            <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 animate-spin" style="animation-duration: 3s">
                                <i data-lucide="loader-2" class="h-6 w-6"></i>
                            </span>
                            <div>
                                <h4 class="text-sm font-bold text-white uppercase tracking-wider">Dry Mix Process Running</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Mixer dry materials are blending for 1 hour.</p>
                            </div>
                        </div>

                        <!-- Live Digital Countdown Clock -->
                        <div class="bg-slate-900 border border-slate-850 rounded-2xl py-6 max-w-xs mx-auto">
                            <span class="text-slate-500 block text-[10px] font-bold uppercase tracking-widest mb-1.5">Mixing Countdown</span>
                            <span id="countdown-clock" class="text-4xl font-mono font-extrabold text-cyan-400 tracking-wider">
                                {{ sprintf('%02d:%02d', floor($remainingSeconds / 60), $remainingSeconds % 60) }}
                            </span>
                        </div>

                        @php
                            $isM01 = $batch->machine->code === 'M-01';
                            $proceedAction = $isM01
                                ? route('grout-production.finish-mixing', $batch->id)
                                : route('grout-production.proceed-stage2', $batch->id);
                            $proceedBtnText = $isM01
                                ? 'Proceed to Packing'
                                : 'Proceed to Stage 2 (Add Cement)';
                        @endphp
                        <!-- Proceed Button (Disabled/Enabled dynamically) -->
                        <form id="proceed-stage2-form" method="POST" action="{{ $proceedAction }}" class="pt-4 max-w-sm mx-auto">
                            @csrf
                            <button type="submit" id="proceed-btn" {{ !$isTimerCompleted ? 'disabled' : '' }}
                                class="flex w-full items-center justify-center py-3 font-bold rounded-xl shadow-lg text-sm gap-2 transition-all
                                {{ !$isTimerCompleted ? 'bg-slate-900 border border-slate-850 text-slate-500 cursor-not-allowed' : 'bg-cyan-500 hover:bg-cyan-400 text-slate-950 shadow-cyan-500/20' }}">
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                <span>{{ $proceedBtnText }}</span>
                            </button>
                        </form>

                        @if(auth()->user()->canSkipTimer())
                            <div class="pt-2 max-w-sm mx-auto">
                                <button type="button" id="skip-timer-btn" class="flex w-full items-center justify-center py-2.5 bg-rose-600/10 hover:bg-rose-600 text-rose-450 hover:text-white border border-rose-600/20 hover:border-rose-600 font-bold rounded-xl text-xs gap-2 transition-all cursor-pointer">
                                    <i data-lucide="fast-forward" class="w-3.5 h-3.5"></i>
                                    <span>Emergency Skip Timer</span>
                                </button>
                            </div>
                        @endif
                    </div>

                <!-- Stage 2 Mixing Panel -->
                @elseif($batch->status === 'Stage 2 Mixing' || $batch->status === 'Waiting Cement')
                    @php
                        $cementItems = array_filter($batch->formula_snapshot, function($item) {
                            return $item['mix_stage'] === 'Stage 2';
                        });
                        $cementNames = array_map(function($item) {
                            return $item['raw_material_name'];
                        }, $cementItems);
                        $cementTypeRequired = !empty($cementNames) ? implode(', ', $cementNames) : ($batch->color->default_cement ?? 'Grey Cement');
                    @endphp
                    <div class="space-y-5">
                        <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-4 text-amber-400 text-xs flex gap-3">
                            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                            <div>
                                <strong class="font-semibold uppercase tracking-wider">Stage 2: Add Cement binding base</strong>
                                <p class="mt-1 leading-normal">
                                    Load the designated cement type from formula snapshot into the mixer.
                                    Required binding base: <strong class="text-white underline">{{ $cementTypeRequired }}</strong>.
                                </p>
                            </div>
                        </div>

                        <div class="border border-slate-850 rounded-xl overflow-hidden bg-slate-900/10">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="bg-slate-900/60 border-b border-slate-850 text-slate-400 font-bold uppercase">
                                        <th class="p-3">Raw Material</th>
                                        <th class="p-3 text-right">Proportion Qty</th>
                                        <th class="p-3 w-20">Unit</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-850/50">
                                    @foreach($batch->formula_snapshot as $item)
                                        @if($item['mix_stage'] === 'Stage 2')
                                            <tr class="text-slate-350">
                                                <td class="p-3 font-medium text-white">{{ $item['raw_material_name'] }}</td>
                                                <td class="p-3 text-right font-mono font-bold">{{ number_format($item['quantity'], 2) }}</td>
                                                <td class="p-3 font-semibold text-slate-500">{{ $item['unit_code'] }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <form method="POST" action="{{ route('grout-production.finish-mixing', $batch->id) }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center justify-center py-3 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold rounded-xl shadow-lg shadow-cyan-500/20 text-sm gap-2 transition-all">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                <span>Finish Mixing (Proceed to Packing)</span>
                            </button>
                        </form>
                    </div>

                <!-- Ready For Packing Panel -->
                @elseif($batch->status === 'Ready For Packing')
                    <div class="space-y-6 text-center py-6">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <i data-lucide="check-circle" class="h-6 w-6"></i>
                            </span>
                            <h4 class="text-sm font-bold text-white uppercase tracking-wider">Mixing Phase Completed</h4>
                            <p class="text-xs text-slate-500 leading-normal max-w-xs mx-auto">Batch is mixed, checked, and ready to be packaging-bagged.</p>
                        </div>

                        <form method="POST" action="{{ route('grout-production.start-packing', $batch->id) }}" class="max-w-xs mx-auto pt-2">
                            @csrf
                            <button type="submit" class="flex w-full items-center justify-center py-3 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold rounded-xl shadow-lg shadow-cyan-500/20 text-sm gap-2 transition-all">
                                <i data-lucide="box" class="w-4 h-4"></i>
                                <span>Start Packing Stage</span>
                            </button>
                        </form>
                    </div>

                 <!-- Packing / Completion Panel -->
                    @elseif($batch->status === 'Packing')
                        <div class="space-y-5" id="complete-batch-form-container" style="animation: page-in .35s ease both;">
                            <form method="POST" action="{{ route('grout-production.complete', $batch->id) }}" id="complete-batch-form" class="space-y-5">
                                @csrf

                                {{-- Batch Info Strip --}}
                                <div class="erp-card p-4">
                                    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                                        <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:999px;font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;background:#dbeafe;color:#2563eb;border:1px solid #bfdbfe;">
                                            <span style="width:6px;height:6px;border-radius:50%;background:#2563eb;animation:soft-pulse 2s infinite;"></span>
                                            Packing Active
                                        </span>
                                        <span style="font-size:11px;font-weight:700;color:#94a3b8;font-family:ui-monospace,monospace;">B.#{{ $batch->batch_no }}</span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-3">
                                        <div style="text-align:center;padding:10px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;">
                                            <span style="display:block;font-size:9px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;">Mixer</span>
                                            <span style="display:block;font-size:13px;font-weight:900;color:#0f172a;margin-top:2px;">{{ $batch->machine->name }}</span>
                                        </div>
                                        <div style="text-align:center;padding:10px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;">
                                            <span style="display:block;font-size:9px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;">Color</span>
                                            <span style="display:block;font-size:13px;font-weight:900;color:#2563eb;margin-top:2px;">{{ $batch->color->name }}</span>
                                        </div>
                                        <div style="text-align:center;padding:10px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;">
                                            <span style="display:block;font-size:9px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;">Specification</span>
                                            <span style="display:block;font-size:11px;font-weight:900;color:#059669;margin-top:2px;">{{ $batch->color->packing_size }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Main 2-Column Grid --}}
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                                    {{-- Left: Live Output Gauge --}}
                                    <div class="erp-card overflow-hidden" style="min-height:340px;">
                                        <div style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);padding:28px 20px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:20px;height:100%;position:relative;">
                                            {{-- Subtle radial glow --}}
                                            <div style="position:absolute;inset:0;background:radial-gradient(circle at center,rgba(56,189,248,0.04),transparent 70%);pointer-events:none;"></div>

                                            {{-- Circular Progress Ring --}}
                                            <div style="position:relative;display:flex;align-items:center;justify-content:center;">
                                                <svg width="192" height="192" style="transform:rotate(-90deg);">
                                                    <circle cx="96" cy="96" r="82" stroke-width="8" stroke="#1e293b" fill="transparent" />
                                                    <circle id="yield-progress-ring" cx="96" cy="96" r="82" stroke-width="8" stroke="url(#yield-gradient)" fill="transparent"
                                                        stroke-dasharray="515" stroke-dashoffset="515" stroke-linecap="round" style="transition:stroke-dashoffset .5s ease-out;" />
                                                    <defs>
                                                        <linearGradient id="yield-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                                            <stop offset="0%" stop-color="#2563eb" />
                                                            <stop offset="100%" stop-color="#059669" />
                                                        </linearGradient>
                                                    </defs>
                                                </svg>
                                                <div style="position:absolute;text-align:center;">
                                                    <span id="weight-calc-large" style="display:block;font-size:2.25rem;font-weight:900;color:#fff;font-family:ui-monospace,monospace;letter-spacing:-0.03em;">0</span>
                                                    <span style="display:block;font-size:11px;font-weight:800;color:#34d399;letter-spacing:.12em;margin-top:2px;">KG YIELD</span>
                                                    <span id="yield-percentage" style="display:block;font-size:10px;font-weight:600;color:#64748b;margin-top:2px;">0% of Est. Batch</span>
                                                </div>
                                            </div>

                                            {{-- Stats Row --}}
                                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;width:100%;border-top:1px solid #334155;padding-top:16px;text-align:center;">
                                                <div>
                                                    <span style="display:block;font-size:9px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.1em;">Virtual Pouches</span>
                                                    <span id="pouches-calc-large" style="display:block;font-size:1.25rem;font-weight:900;color:#60a5fa;font-family:ui-monospace,monospace;margin-top:4px;">0</span>
                                                    <span style="display:block;font-size:9px;font-weight:700;color:#475569;">PCS DEDUCTED</span>
                                                </div>
                                                <div style="border-left:1px solid #334155;">
                                                    <span style="display:block;font-size:9px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.1em;">Bag Factor</span>
                                                    <span style="display:block;font-size:13px;font-weight:900;color:#cbd5e1;margin-top:6px;">
                                                        {{ $batch->color->packing_size === '500 GM' ? '50 / 25KG Bag' : '25 / 25KG Bag' }}
                                                    </span>
                                                    <span style="display:block;font-size:9px;font-weight:700;color:#475569;">POUCH COUNT</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right: Kiosk Entry Panel --}}
                                    <div class="erp-card p-4 md:p-5 flex flex-col gap-4">

                                        {{-- Header --}}
                                        <div class="flex items-center justify-between">
                                            <span style="font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Bag Entry Console</span>
                                            <span style="display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;color:#2563eb;background:#eff6ff;border:1px solid #dbeafe;padding:3px 10px;border-radius:999px;">
                                                <i data-lucide="keyboard" class="w-3 h-3"></i> Keyboard + Touch
                                            </span>
                                        </div>

                                        {{-- Number Display --}}
                                        <div style="position:relative;">
                                            <div style="position:absolute;inset:-2px;background:linear-gradient(135deg,#2563eb,#059669);border-radius:16px;opacity:0.12;pointer-events:none;"></div>
                                            <input type="number" id="finished_bags" name="finished_bags" min="1" required
                                                style="display:block;width:100%;text-align:center;padding:14px 10px;background:#f8fafc;border:2px solid #e2e8f0;border-radius:14px;color:#0f172a;font-family:ui-monospace,monospace;font-weight:900;font-size:2.25rem;outline:none;transition:border-color .18s ease,box-shadow .18s ease;-moz-appearance:textfield;"
                                                placeholder="0" value="0">
                                        </div>

                                        {{-- Quick Presets --}}
                                        <div class="grid grid-cols-4 gap-2">
                                            @foreach([['1', '+1 Bag'], ['5', '+5 Bags'], ['10', '+10'], ['20', '+20']] as $preset)
                                                <button type="button" data-adjust="{{ $preset[0] }}"
                                                    style="padding:10px 4px;border-radius:12px;background:#f1f5f9;border:1px solid #e2e8f0;color:#475569;font-size:11px;font-weight:800;font-family:ui-monospace,monospace;cursor:pointer;transition:all .15s ease;"
                                                    onmouseover="this.style.background='#e2e8f0';this.style.color='#0f172a'"
                                                    onmouseout="this.style.background='#f1f5f9';this.style.color='#475569'"
                                                    onmousedown="this.style.transform='scale(0.95)'"
                                                    onmouseup="this.style.transform='scale(1)'">{{ $preset[1] }}</button>
                                            @endforeach
                                        </div>

                                        {{-- Numeric Keypad --}}
                                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;background:#f8fafc;padding:10px;border-radius:16px;border:1px solid #e2e8f0;">
                                            @foreach(['1','2','3','4','5','6','7','8','9'] as $num)
                                                <button type="button" onclick="pressKey('{{ $num }}')"
                                                    style="padding:12px 4px;border-radius:12px;background:#fff;border:1px solid #e2e8f0;color:#0f172a;font-weight:700;font-family:ui-monospace,monospace;font-size:1.1rem;cursor:pointer;transition:all .12s ease;display:flex;align-items:center;justify-content:center;"
                                                    onmouseover="this.style.background='#eff6ff';this.style.borderColor='#bfdbfe'"
                                                    onmouseout="this.style.background='#fff';this.style.borderColor='#e2e8f0'"
                                                    onmousedown="this.style.transform='scale(0.94)'"
                                                    onmouseup="this.style.transform='scale(1)'">{{ $num }}</button>
                                            @endforeach
                                            {{-- DEL --}}
                                            <button type="button" onclick="pressKey('DEL')"
                                                style="padding:12px 4px;border-radius:12px;background:#fff1f2;border:1px solid #fecdd3;color:#e11d48;font-weight:700;font-family:ui-monospace,monospace;cursor:pointer;transition:all .12s ease;display:flex;align-items:center;justify-content:center;"
                                                onmouseover="this.style.background='#ffe4e6'"
                                                onmouseout="this.style.background='#fff1f2'"
                                                onmousedown="this.style.transform='scale(0.94)'"
                                                onmouseup="this.style.transform='scale(1)'">
                                                <i data-lucide="delete" class="w-5 h-5"></i>
                                            </button>
                                            {{-- 0 --}}
                                            <button type="button" onclick="pressKey('0')"
                                                style="padding:12px 4px;border-radius:12px;background:#fff;border:1px solid #e2e8f0;color:#0f172a;font-weight:700;font-family:ui-monospace,monospace;font-size:1.1rem;cursor:pointer;transition:all .12s ease;display:flex;align-items:center;justify-content:center;"
                                                onmouseover="this.style.background='#eff6ff';this.style.borderColor='#bfdbfe'"
                                                onmouseout="this.style.background='#fff';this.style.borderColor='#e2e8f0'"
                                                onmousedown="this.style.transform='scale(0.94)'"
                                                onmouseup="this.style.transform='scale(1)'">0</button>
                                            {{-- CLEAR --}}
                                            <button type="button" onclick="pressKey('C')"
                                                style="padding:12px 4px;border-radius:12px;background:#fefce8;border:1px solid #fde68a;color:#d97706;font-weight:800;font-family:ui-monospace,monospace;font-size:11px;cursor:pointer;transition:all .12s ease;display:flex;align-items:center;justify-content:center;"
                                                onmouseover="this.style.background='#fef9c3'"
                                                onmouseout="this.style.background='#fefce8'"
                                                onmousedown="this.style.transform='scale(0.94)'"
                                                onmouseup="this.style.transform='scale(1)'">CLEAR</button>
                                        </div>

                                        @error('finished_bags')
                                            <p style="color:#e11d48;font-size:12px;font-weight:600;margin-top:2px;">{{ $message }}</p>
                                        @enderror

                                        {{-- Quality Remarks Accordion --}}
                                        <div style="border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;transition:border-color .15s ease;" onmouseover="this.style.borderColor='#cbd5e1'" onmouseout="this.style.borderColor='#e2e8f0'">
                                            <button type="button" id="toggle-remarks-btn"
                                                style="width:100%;display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:transparent;border:none;cursor:pointer;font-size:11px;font-weight:800;color:#64748b;text-align:left;transition:color .15s ease;"
                                                onmouseover="this.style.color='#0f172a'"
                                                onmouseout="this.style.color='#64748b'">
                                                <span style="display:flex;align-items:center;gap:8px;">
                                                    <i data-lucide="message-square" class="w-4 h-4" style="color:#2563eb;"></i>
                                                    <span>ADD QUALITY AUDIT REMARKS</span>
                                                </span>
                                                <i data-lucide="chevron-down" id="remarks-chevron" class="w-4 h-4" style="transition:transform .2s ease;"></i>
                                            </button>
                                            <div id="remarks-container" class="hidden" style="padding:12px 16px;border-top:1px solid #f1f5f9;background:#fafbfd;">
                                                <textarea name="remarks" id="remarks" rows="2"
                                                    style="display:block;width:100%;padding:10px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;color:#0f172a;font-size:12px;line-height:1.6;outline:none;transition:border-color .18s ease,box-shadow .18s ease;resize:vertical;"
                                                    onfocus="this.style.borderColor='#2563eb';this.style.boxShadow='0 0 0 3px rgba(37,99,235,.08)'"
                                                    onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'"
                                                    placeholder="Enter any quality audit notes, bag weight checks, etc."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Complete Action Button --}}
                                <button type="submit"
                                    style="display:flex;width:100%;align-items:center;justify-content:center;gap:8px;padding:14px 20px;background:linear-gradient(135deg,#059669,#0d9488);color:#fff;font-weight:800;font-size:13px;border:none;border-radius:16px;cursor:pointer;box-shadow:0 8px 20px rgba(5,150,105,.18);transition:all .18s ease;"
                                    onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 12px 28px rgba(5,150,105,.25)'"
                                    onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 20px rgba(5,150,105,.18)'"
                                    onmousedown="this.style.transform='scale(0.99)'">
                                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                                    <span>Complete Run &amp; Deduct Stock</span>
                                </button>
                            </form>
                        </div>
                    @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var status = "{{ $batch->status }}";
        var batchId = "{{ $batch->id }}";
        var remainingSeconds = parseInt("{{ $remainingSeconds }}");

        // Dynamic weight and pouch calculator
        var packingSize = "{{ $batch->color->packing_size }}";
        var pouchFactor = (packingSize === '500 GM') ? 50 : 25;

        // Input element reference
        var $input = $('#finished_bags');

        // Interactive Keypad Input Handler
        window.pressKey = function(key) {
            var currentVal = $input.val();
            
            if (currentVal === "0" || currentVal === "") {
                currentVal = "";
            }

            if (key === 'C') {
                $input.val(0);
            } else if (key === 'DEL') {
                var newVal = currentVal.substring(0, currentVal.length - 1);
                $input.val(newVal === "" ? 0 : newVal);
            } else {
                if (currentVal.length < 4) {
                    $input.val(currentVal + key);
                }
            }
            updateCalculations();
        };

        // Handle direct keyboard input on the field
        $input.on('input', function() {
            var val = parseInt($(this).val());
            if (isNaN(val) || val < 0) {
                $(this).val(0);
            }
            updateCalculations();
        });

        // Focus/blur styling for the input
        $input.on('focus', function() {
            $(this).css({ 'border-color': '#2563eb', 'box-shadow': '0 0 0 4px rgba(37,99,235,.09)' });
        }).on('blur', function() {
            $(this).css({ 'border-color': '#e2e8f0', 'box-shadow': 'none' });
        });

        // Hide number input spinners
        $('<style>').text('input[type=number]::-webkit-outer-spin-button,input[type=number]::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}').appendTo('head');

        function updateCalculations() {
            var bags = parseInt($input.val()) || 0;
            if (bags < 0) {
                bags = 0;
                $input.val(0);
            }
            var kg = bags * 25;
            var pouches = bags * pouchFactor;

            // Update display values
            $('#weight-calc-large').text(kg.toLocaleString());
            $('#pouches-calc-large').text(pouches.toLocaleString());

            // Update circular progress ring (Circumference ~ 515, estimated batch capacity = 1000 KG)
            var percentage = Math.round((kg / 1000) * 100);
            $('#yield-percentage').text(percentage + '% of Est. Batch (1000 KG)');
            var dashoffset = 515 - (Math.min(100, percentage) / 100) * 515;
            $('#yield-progress-ring').css('stroke-dashoffset', dashoffset);
        }

        $('#finished_bags').on('input', updateCalculations);

        // Touch adjustments for bag entry
        $('[data-adjust]').on('click', function(e) {
            e.preventDefault();
            var adjustVal = parseInt($(this).data('adjust')) || 0;
            var currentVal = parseInt($('#finished_bags').val()) || 0;
            var newVal = Math.max(0, currentVal + adjustVal);
            $('#finished_bags').val(newVal);
            updateCalculations();
        });

        // Toggle Remarks Accordion
        $('#toggle-remarks-btn').on('click', function(e) {
            e.preventDefault();
            $('#remarks-container').slideToggle(200);
            $('#remarks-chevron').toggleClass('rotate-180');
        });

        // Initialize calculations
        updateCalculations();

        var alertTriggered = false;
        var pollInterval;

        // 1-Hour Timer ticker
        if (status === 'Timer Running') {
            function updateClock() {
                if (remainingSeconds > 0) {
                    remainingSeconds--;
                    var mins = Math.floor(remainingSeconds / 60);
                    var secs = remainingSeconds % 60;
                    var clockStr = (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
                    $('#countdown-clock').text(clockStr);

                    if (remainingSeconds === 0) {
                        // Notify mixing timer complete
                        triggerMixCompletionAlert();
                    }
                }
            }

            setInterval(updateClock, 1000);

            // API Poll checking status
            function pollTimer() {
                if (alertTriggered) return;
                $.getJSON(`/grout-production/${batchId}/timer`, function(data) {
                    if (data.is_completed && !alertTriggered) {
                        triggerMixCompletionAlert();
                    }
                });
            }

            // Poll every 5 seconds
            pollInterval = setInterval(pollTimer, 5000);

            // Emergency Skip Timer with SweetAlert
            $('#skip-timer-btn').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Emergency Timer Skip',
                    text: 'Are you sure you want to skip the 1-hour mixing timer? An override reason is required.',
                    icon: 'warning',
                    input: 'textarea',
                    inputPlaceholder: 'Enter authorization reason for skipping the timer...',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#475569',
                    confirmButtonText: 'Yes, Skip Timer',
                    background: '#090d16',
                    color: '#f1f5f9',
                    preConfirm: (reason) => {
                        if (!reason || reason.trim().length < 3) {
                            Swal.showValidationMessage('A valid reason (minimum 3 characters) is required.');
                        }
                        return reason;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        var skipReason = result.value;
                        $.ajax({
                            url: "{{ route('grout-production.skip-timer', $batch->id) }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                reason: skipReason
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Timer Skipped',
                                    text: 'The mixing timer has been bypassed.',
                                    icon: 'success',
                                    confirmButtonColor: '#06b6d4',
                                    background: '#090d16',
                                    color: '#f1f5f9'
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                var errMsg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.';
                                Swal.fire({
                                    title: 'Error',
                                    text: errMsg,
                                    icon: 'error',
                                    confirmButtonColor: '#06b6d4',
                                    background: '#090d16',
                                    color: '#f1f5f9'
                                });
                            }
                        });
                    }
                });
            });
        }

        function triggerMixCompletionAlert() {
            if (alertTriggered) return;
            alertTriggered = true;

            if (pollInterval) {
                clearInterval(pollInterval);
            }

            var isM01 = ("{{ $batch->machine->code }}" === 'M-01');
            var alertText = isM01
                ? 'Mixing completed. Please proceed to Packing.'
                : 'Stage 1 Mixing has finished. Please Proceed to add Cement (Stage 2).';

            // SweetAlert alert
            Swal.fire({
                title: 'Mixing Completed!',
                text: alertText,
                icon: 'success',
                confirmButtonColor: '#06b6d4',
                background: '#090d16',
                color: '#f1f5f9'
            }).then(function() {
                window.location.reload();
            });

            // Browser Notification
            if (Notification.permission === 'granted') {
                new Notification('Solcon mixing completed', {
                    body: alertText
                });
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission();
            }
        }

        // Request browser notifications permission on mix timer screen
        if (status === 'Timer Running' && Notification.permission !== 'granted' && Notification.permission !== 'denied') {
            Notification.requestPermission();
        }
    });
</script>
@endsection
