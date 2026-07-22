@extends('layouts.app')

@section('title', 'Batch Summary')
@section('header-title', 'Grout Run Summary')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Link -->
    <div>
        <a href="{{ route('grout-production.index') }}" class="inline-flex items-center text-sm text-slate-400 hover:text-white transition-colors gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Production Floor</span>
        </a>
    </div>

    <!-- Completed Info Card -->
    <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-6 md:p-8">
        <div class="flex items-center justify-between border-b border-slate-850 pb-5 mb-5">
            <div>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 mb-2">
                    <i data-lucide="check" class="w-3 h-3"></i>
                    Completed
                </span>
                <h3 class="text-xl font-bold text-white">Batch #{{ $batch->batch_no }}</h3>
            </div>
            <div class="text-right">
                <span class="text-[10px] text-slate-500 block uppercase font-bold tracking-wider mb-1">Production Date</span>
                <span class="text-sm font-mono text-white font-semibold">{{ $batch->updated_at->format('d M Y, h:i A') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-xs text-slate-350">
            <div>
                <span class="text-slate-500 block uppercase font-bold tracking-wider text-[9px] mb-1">Assigned Machine</span>
                <strong class="text-white text-sm">{{ $batch->machine->name }} ({{ $batch->machine->code }})</strong>
            </div>
            <div>
                <span class="text-slate-500 block uppercase font-bold tracking-wider text-[9px] mb-1">Grout Color</span>
                <strong class="text-white text-sm">{{ $batch->color->name }} ({{ $batch->color->code }})</strong>
            </div>
            <div>
                <span class="text-slate-500 block uppercase font-bold tracking-wider text-[9px] mb-1">Packing Configuration</span>
                <strong class="text-white text-sm">{{ $batch->color->packing_size }} Pouches</strong>
            </div>
            <div>
                <span class="text-slate-500 block uppercase font-bold tracking-wider text-[9px] mb-1">Active Operator</span>
                <strong class="text-white text-sm">{{ $batch->operator->name }}</strong>
            </div>
        </div>
    </div>

    <!-- Output summary cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Bags Card -->
        <div class="bg-slate-950 border border-slate-850 rounded-2xl p-5 shadow-xl flex items-center justify-between">
            <div>
                <span class="text-slate-500 block uppercase font-bold tracking-wider text-[10px] mb-1">Total Finished Output</span>
                <span class="text-3xl font-extrabold text-white">{{ $batch->finished_bags }} <span class="text-lg text-slate-500 font-semibold">Bags</span></span>
            </div>
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                <i data-lucide="package" class="h-6 w-6"></i>
            </span>
        </div>

        <!-- Weight Card -->
        <div class="bg-slate-950 border border-slate-850 rounded-2xl p-5 shadow-xl flex items-center justify-between">
            <div>
                <span class="text-slate-500 block uppercase font-bold tracking-wider text-[10px] mb-1">Deducted Product Weight</span>
                <span class="text-3xl font-extrabold text-white">{{ number_format($batch->total_weight_kg, 1) }} <span class="text-lg text-slate-500 font-semibold">KG</span></span>
            </div>
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                <i data-lucide="scale" class="h-6 w-6"></i>
            </span>
        </div>
    </div>

    <!-- Ingredients Consumption Ledger -->
    <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-850 bg-slate-900/40">
            <h3 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-4 h-4 text-cyan-400"></i>
                Proportional Material Consumption Details
            </h3>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto rounded-xl border border-slate-850 bg-slate-900/10">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-900/60 border-b border-slate-850 text-slate-400 font-bold uppercase text-xs">
                            <th class="p-3">Raw Material</th>
                            <th class="p-3">Mix Stage</th>
                            <th class="p-3 text-right">Consumed Quantity</th>
                            <th class="p-3 w-28">Unit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-850/50">
                        @foreach($batch->ledgers as $ledger)
                            <tr class="hover:bg-slate-900/20 text-slate-350">
                                <td class="p-3">
                                    <div class="font-semibold text-white">{{ $ledger->rawMaterial->name }}</div>
                                    <div class="text-[10px] font-mono text-cyan-455 uppercase mt-0.5">{{ $ledger->rawMaterial->code }}</div>
                                </td>
                                <td class="p-3">
                                    @php
                                        // Match from snapshot mix stage for display
                                        $snapshotItem = collect($batch->formula_snapshot)->firstWhere('raw_material_id', $ledger->raw_material_id);
                                        $stage = $snapshotItem['mix_stage'] ?? 'Stage 1';
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold border
                                        {{ $stage === 'Stage 1' ? 'bg-blue-950 text-blue-300 border-blue-900' : 'bg-emerald-950 text-emerald-300 border-emerald-900' }}">
                                        {{ $stage }}
                                    </span>
                                </td>
                                <td class="p-3 text-right font-mono font-bold text-white">
                                    {{ format_quantity(abs($ledger->quantity)) }}
                                </td>
                                <td class="p-3 text-slate-500 font-semibold">
                                    {{ $ledger->rawMaterial->stockUnit->code }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($batch->remarks)
        <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-5">
            <span class="text-slate-500 block uppercase font-bold tracking-wider text-[10px] mb-2">Remarks on Completion</span>
            <p class="text-slate-300 leading-relaxed italic text-xs">"{{ $batch->remarks }}"</p>
        </div>
    @endif
</div>
@endsection
