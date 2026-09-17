@extends('layouts.app')

@section('title', 'Production Run Details')
@section('header-title', 'Production Batch Details')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Back to Dashboard -->
    <div class="flex items-center justify-between">
        <a href="{{ route('production.history') }}" class="inline-flex items-center text-sm text-slate-450 hover:text-cyan-400 transition-colors gap-2 group">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform"></i>
            <span>Back to History Log</span>
        </a>
        <span class="text-xs text-slate-500 font-mono">Batch #{{ $batch->batch_no }}</span>
    </div>

    <!-- Completed Status & Output Summary Card -->
    <div class="bg-gradient-to-br from-slate-950 via-slate-950 to-slate-900 border border-slate-850 rounded-2xl shadow-xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center md:text-left">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 tracking-wide uppercase">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                COMPLETED
            </span>
            <div class="flex items-center gap-2.5 flex-wrap justify-center md:justify-start">
                <h2 class="text-2xl font-extrabold text-white tracking-tight">{{ $batch->grade->name }}</h2>
                @if($batch->grade?->brand)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-amber-500/15 text-amber-400 border border-amber-500/30">
                        {{ $batch->grade->brand->name }}
                    </span>
                @endif
            </div>
            <p class="text-sm text-slate-450">Processed on Mixer Machine: <span class="text-slate-200 font-semibold">{{ $batch->machine->name }}</span> ({{ $batch->machine->code }})</p>
        </div>

        <!-- Output Quantities Card -->
        <div class="bg-white border border-slate-800 rounded-2xl p-5 flex flex-col items-center justify-center min-w-[220px] shadow-inner text-center">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Recorded Output</span>
            <div class="text-white font-mono text-3xl font-extrabold tracking-tight">
                {{ number_format($batch->output_bags, 0) }} <span class="text-sm text-slate-400 font-sans font-medium uppercase ml-0.5">Bags</span>
            </div>
            <div class="text-cyan-400 font-mono text-base font-bold mt-1.5">
                {{ number_format($batch->output_kg, 2) }} <span class="text-xs text-slate-500 font-sans font-medium uppercase">KG</span>
            </div>
        </div>
    </div>

    @if(!empty($batch->output_breakdown) && is_array($batch->output_breakdown))
    <!-- Multi-Brand & Coupon Breakdown Card (Light ERP Card) -->
    <div class="erp-card overflow-hidden">
        <div class="border-b border-slate-100 px-6 py-4 bg-slate-50/70 flex items-center justify-between flex-wrap gap-2">
            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                <i data-lucide="layers" class="w-4 h-4 text-blue-600"></i>
                <span>Packaging & Multi-Brand Output Breakdown</span>
            </h3>
            <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100 text-xs font-mono font-bold">
                {{ count($batch->output_breakdown) }} Packaging Variant(s)
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50 text-slate-500 text-xs uppercase font-extrabold tracking-wider">
                        <th class="p-3.5">Brand & Product Grade</th>
                        <th class="p-3.5">Packaging Bag</th>
                        <th class="p-3.5">Coupon Variant</th>
                        <th class="p-3.5 text-right">Bags Output</th>
                        <th class="p-3.5 text-right">Weight (KG)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($batch->output_breakdown as $split)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3.5">
                                <div class="font-extrabold text-slate-900">{{ $split['grade_name'] ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-500 font-mono mt-0.5">
                                    @if(!empty($split['brand_name']))
                                        <span class="text-amber-700 font-bold bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200 text-[10px]">{{ $split['brand_name'] }}</span> • 
                                    @endif
                                    {{ $split['grade_code'] ?? '' }}
                                </div>
                            </td>
                            <td class="p-3.5">
                                <span class="px-2.5 py-1 bg-slate-100 border border-slate-200 rounded-lg text-slate-700 font-mono text-xs font-bold">
                                    {{ $split['packing_material_name'] ?? 'Standard Bag' }}
                                </span>
                            </td>
                            <td class="p-3.5">
                                @if(!empty($split['coupon_name']) && $split['coupon_name'] !== 'No Coupon')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i data-lucide="ticket" class="w-3 h-3 mr-1"></i>
                                        {{ $split['coupon_name'] }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs italic font-medium">No Coupon</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-right font-mono font-extrabold text-blue-600">
                                {{ number_format($split['bags'] ?? 0) }} Bags
                            </td>
                            <td class="p-3.5 text-right font-mono font-extrabold text-emerald-600">
                                {{ number_format($split['kg'] ?? 0, 2) }} KG
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-slate-100 bg-slate-50/80 text-xs font-extrabold text-slate-800">
                    <tr>
                        <td colspan="3" class="p-3.5 uppercase text-slate-500">Total Recorded Output</td>
                        <td class="p-3.5 text-right font-mono text-blue-700 text-sm">
                            {{ number_format($batch->output_bags, 0) }} Bags
                        </td>
                        <td class="p-3.5 text-right font-mono text-emerald-700 text-sm">
                            {{ number_format($batch->output_kg, 2) }} KG
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    <!-- Details Log Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Log & Run Metadata (col-span-2) -->
        <div class="md:col-span-2 bg-slate-955 border border-slate-850 rounded-2xl p-6 shadow-md space-y-4">
            <h3 class="text-xs font-semibold text-slate-455 uppercase tracking-wider flex items-center gap-1.5">
                <i data-lucide="calendar" class="w-4 h-4 text-cyan-500"></i>
                <span>Production Run Logs</span>
            </h3>

            <div class="divide-y divide-slate-850/50 text-sm">
                <div class="py-2.5 flex justify-between">
                    <span class="text-slate-500">Supervisor</span>
                    <span class="text-white font-medium">{{ $batch->supervisor->name }}</span>
                </div>
                <div class="py-2.5 flex justify-between">
                    <span class="text-slate-500">Start Time</span>
                    <span class="text-white font-mono">{{ $batch->start_time->format('d M Y, h:i:s A') }}</span>
                </div>
                <div class="py-2.5 flex justify-between">
                    <span class="text-slate-500">End Time</span>
                    <span class="text-white font-mono">{{ $batch->end_time ? $batch->end_time->format('d M Y, h:i:s A') : 'N/A' }}</span>
                </div>
                <div class="py-2.5 flex justify-between">
                    <span class="text-slate-500">Duration</span>
                    <span class="text-white font-semibold">
                        @if($batch->end_time)
                            {{ $batch->duration_minutes }} minutes
                            @if($batch->total_paused_seconds > 0)
                                <span class="text-xs text-amber-400 font-normal">({{ round($batch->total_paused_seconds / 60) }} min pause excluded)</span>
                            @endif
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-850 space-y-2">
                <h4 class="text-xs font-semibold text-slate-450 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="message-square" class="w-4 h-4 text-cyan-500"></i>
                    <span>Batch Remarks / Notes</span>
                </h4>
                @if($batch->remarks)
                    <p class="text-slate-300 bg-slate-900/40 border border-slate-800 p-4 rounded-xl text-sm leading-relaxed whitespace-pre-wrap">{{ $batch->remarks }}</p>
                @else
                    <p class="text-slate-500 bg-slate-900/10 border border-slate-850 p-4 rounded-xl text-sm italic">No remarks recorded for this batch.</p>
                @endif
            </div>
        </div>

        <!-- Visual Timeline Card (col-span-1) -->
        <div class="bg-slate-955 border border-slate-850 rounded-2xl p-6 shadow-md space-y-5">
            <h3 class="text-xs font-semibold text-slate-455 uppercase tracking-wider flex items-center gap-1.5">
                <i data-lucide="git-commit" class="w-4 h-4 text-cyan-500"></i>
                <span>Progress Timeline</span>
            </h3>

            <div class="relative pl-6 border-l-2 border-slate-800 space-y-5">
                <!-- Started Node -->
                <div class="relative">
                    <span class="absolute -left-[31px] top-0.5 flex items-center justify-center w-4 h-4 rounded-full bg-cyan-500 ring-4 ring-slate-950"></span>
                    <h4 class="text-xs font-bold text-white">Batch Started</h4>
                    <p class="text-[11px] text-slate-450 mt-0.5">Initialized on Mixer <span class="text-slate-300">{{ $batch->machine->name }}</span> by <span class="text-slate-300">{{ $batch->supervisor->name }}</span>.</p>
                    <span class="block text-[9px] font-mono text-cyan-400 mt-1">{{ $batch->start_time->format('d M Y, h:i:s A') }}</span>
                </div>

                <!-- Completed Node -->
                <div class="relative">
                    <span class="absolute -left-[31px] top-0.5 flex items-center justify-center w-4 h-4 rounded-full bg-emerald-500 ring-4 ring-slate-950"></span>
                    <h4 class="text-xs font-bold text-white">Batch Completed</h4>
                    <p class="text-[11px] text-slate-455 mt-0.5">Final output logged: <span class="text-emerald-400 font-bold">{{ number_format($batch->output_bags) }} bags</span>.</p>
                    <span class="block text-[9px] font-mono text-emerald-400 mt-1">{{ $batch->end_time ? $batch->end_time->format('d M Y, h:i:s A') : 'N/A' }}</span>
                </div>

                <!-- Stock Updated Node -->
                <div class="relative">
                    <span class="absolute -left-[31px] top-0.5 flex items-center justify-center w-4 h-4 rounded-full bg-indigo-500 ring-4 ring-slate-950"></span>
                    <h4 class="text-xs font-bold text-white">Stock Deducted</h4>
                    <p class="text-[11px] text-slate-455 mt-0.5">Recipe materials deducted and stock ledger logged.</p>
                    <span class="block text-[9px] font-mono text-indigo-400 mt-1">{{ $batch->end_time ? $batch->end_time->format('d M Y, h:i:s A') : 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Formula preview (Snapshot) -->
    <div class="bg-slate-955 border border-slate-850 rounded-2xl overflow-hidden shadow-md">
        <div class="border-b border-slate-850 px-5 py-4 bg-slate-900/30 flex items-center justify-between">
            <h3 class="text-xs font-semibold text-slate-405 uppercase tracking-wider flex items-center gap-1.5">
                <i data-lucide="sheet" class="w-4 h-4 text-cyan-555"></i>
                <span>Formula Snapshot Preview (Version {{ $batch->formula->version ?? 'N/A' }})</span>
            </h3>
            <span class="px-2.5 py-0.5 rounded bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 text-[10px] font-mono font-bold">
                LOCKED SNAPSHOT
            </span>
        </div>

        <div class="p-5">
            <div class="border border-slate-850 rounded-xl overflow-hidden">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-850 bg-slate-900/60 text-slate-400 font-semibold">
                            <th class="p-3 w-16 text-center">Seq</th>
                            <th class="p-3 w-32">Material Code</th>
                            <th class="p-3">Material Name</th>
                            <th class="p-3 text-right">Recipe Quantity</th>
                            <th class="p-3 w-28">Unit</th>
                            <th class="p-3 w-32">Consumption</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-850/50 text-slate-200">
                        @if(!empty($batch->formula_snapshot))
                            @foreach($batch->formula_snapshot as $index => $item)
                                <tr class="hover:bg-slate-900/30 transition-colors">
                                    <td class="p-3 text-center font-mono text-slate-500">{{ $index + 1 }}</td>
                                    <td class="p-3 font-mono font-semibold text-cyan-400">{{ $item['raw_material_code'] }}</td>
                                    <td class="p-3 font-semibold text-white">{{ $item['raw_material_name'] }}</td>
                                    <td class="p-3 text-right font-mono font-bold text-white">{{ number_format($item['quantity'], 4) }}</td>
                                    <td class="p-3 font-semibold text-indigo-300">{{ $item['unit_code'] }}</td>
                                    <td class="p-3">
                                        @if(($item['consumption_method'] ?? 'formula') === 'output')
                                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                                Output Based
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-800 text-slate-100 border border-slate-700/50">
                                                Formula Based
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500">
                                    No formula items snapshotted for this batch.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Stock Ledger Entries -->
    @if($batch->status === 'completed')
        <div class="bg-slate-955 border border-slate-850 rounded-2xl overflow-hidden shadow-md">
            <div class="border-b border-slate-850 px-5 py-4 bg-slate-900/30 flex items-center justify-between">
                <h3 class="text-xs font-semibold text-slate-405 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="book-open" class="w-4 h-4 text-cyan-555"></i>
                    <span>Associated Stock Ledger Entries</span>
                </h3>
                <span class="px-2.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-mono font-bold">
                    POSTED
                </span>
            </div>

            <div class="p-5">
                <div class="border border-slate-850 rounded-xl overflow-hidden">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-slate-850 bg-slate-900/60 text-slate-400 font-semibold">
                                <th class="p-3">Material Name</th>
                                <th class="p-3 w-32">Type</th>
                                <th class="p-3 text-right">Deducted Qty</th>
                                <th class="p-3 text-right">Prev Stock</th>
                                <th class="p-3 text-right">Balance After</th>
                                <th class="p-3">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-850/50 text-slate-200">
                            @forelse($batch->ledgers as $ledger)
                                @php
                                    $isPacking = (bool) $ledger->packing_material_id || (bool) $ledger->packingMaterial;
                                    $matName = $isPacking ? ($ledger->packingMaterial->name ?? 'Packing Material') : ($ledger->rawMaterial->name ?? 'Raw Material');
                                    $matCode = $isPacking ? ($ledger->packingMaterial->code ?? '') : ($ledger->rawMaterial->code ?? '');
                                    $unitCode = $isPacking ? ($ledger->packingMaterial->unit->code ?? 'PCS') : ($ledger->rawMaterial->stockUnit->code ?? 'KG');
                                @endphp
                                <tr class="hover:bg-slate-900/30 transition-colors">
                                    <!-- Material Name -->
                                    <td class="p-3 font-semibold text-white">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span>{{ $matName }}</span>
                                            @if($isPacking)
                                                <span class="px-1.5 py-0.5 rounded bg-purple-500/10 text-purple-400 border border-purple-500/20 text-[10px] font-bold">Packing</span>
                                            @else
                                                <span class="px-1.5 py-0.5 rounded bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 text-[10px] font-bold">Raw</span>
                                            @endif
                                        </div>
                                        @if($matCode)
                                            <span class="block text-xs font-mono text-slate-500">{{ $matCode }}</span>
                                        @endif
                                    </td>

                                    <!-- Transaction Type -->
                                    <td class="p-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-rose-500/10 text-rose-450 border border-rose-500/20 text-xs font-bold font-mono">
                                            OUT
                                        </span>
                                    </td>

                                    <!-- Quantity -->
                                    <td class="p-3 text-right font-mono font-bold text-rose-450">
                                        -{{ format_quantity(abs($ledger->quantity)) }} {{ $unitCode }}
                                    </td>

                                    <!-- Prev Stock -->
                                    <td class="p-3 text-right font-mono text-slate-455">
                                        {{ format_quantity($ledger->previous_stock) }}
                                    </td>

                                    <!-- Balance After -->
                                    <td class="p-3 text-right font-mono font-bold text-white">
                                        {{ format_quantity($ledger->balance_after) }}
                                    </td>

                                    <!-- Date Time -->
                                    <td class="p-3 font-mono text-xs text-slate-455">
                                        {{ $ledger->created_at->format('d M Y, h:i:s A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-500 italic">
                                        No stock ledger records posted for this batch.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
