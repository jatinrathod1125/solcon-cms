@extends('layouts.app')

@section('title', 'Stock Ledger')
@section('header-title', 'Stock Ledger Log')

@section('content')
<div class="space-y-6">
    <!-- Top Header Panel -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-955 border border-slate-850 p-6 rounded-2xl">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-white flex items-center gap-2">
                <i data-lucide="book-open" class="w-5 h-5 text-cyan-500"></i>
                <span></span>
            </h1>
            <p class="text-sm text-slate-450 mt-1">Audit log of all raw material inventory movements, batch consumptions, and manual adjustments.</p>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-slate-955 border border-slate-850 rounded-2xl shadow-xl overflow-hidden">
        <div class="p-5 bg-slate-950/60 border-b border-slate-850">
            <form method="GET" action="{{ route('production.ledger') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
                <!-- Search Batch Number -->
                <div>
                    <label for="search" class="block text-xs font-semibold text-slate-450 uppercase tracking-wider mb-2">Search Batch No</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </span>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="block w-full pl-9 pr-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm"
                            placeholder="e.g. ADH-2026...">
                    </div>
                </div>

                <!-- Raw Material Filter -->
                <div>
                    <label for="raw_material_id" class="block text-xs font-semibold text-slate-450 uppercase tracking-wider mb-2">Raw Material</label>
                    <select id="raw_material_id" name="raw_material_id"
                        class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
                        <option value="">All Materials</option>
                        @foreach($rawMaterials as $mat)
                            <option value="{{ $mat->id }}" {{ request('raw_material_id') == $mat->id ? 'selected' : '' }}>
                                {{ $mat->name }} ({{ $mat->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Transaction Type Filter -->
                <div>
                    <label for="transaction_type" class="block text-xs font-semibold text-slate-450 uppercase tracking-wider mb-2">Type</label>
                    <select id="transaction_type" name="transaction_type"
                        class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
                        <option value="">All Types</option>
                        <option value="IN" {{ request('transaction_type') === 'IN' ? 'selected' : '' }}>IN (Stock In)</option>
                        <option value="OUT" {{ request('transaction_type') === 'OUT' ? 'selected' : '' }}>OUT (Consumption)</option>
                        <option value="ADJUSTMENT" {{ request('transaction_type') === 'ADJUSTMENT' ? 'selected' : '' }}>ADJUSTMENT</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-xs font-semibold text-slate-455 uppercase tracking-wider mb-2">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                        class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-xs font-semibold text-slate-455 uppercase tracking-wider mb-2">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                        class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
                </div>

                <!-- Action Buttons -->
                <div class="sm:col-span-2 md:col-span-5 flex justify-end gap-2 mt-2">
                    <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-850 text-white font-semibold rounded-xl text-sm transition-colors border border-slate-800 flex items-center gap-1.5">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        <span>Apply Filters</span>
                    </button>
                    @if(request()->anyFilled(['search', 'raw_material_id', 'transaction_type', 'start_date', 'end_date']))
                        <a href="{{ route('production.ledger') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-850 text-slate-450 hover:text-white font-semibold rounded-xl text-sm transition-colors border border-slate-800 flex items-center justify-center gap-1">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            <span>Clear</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Ledger Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-850 bg-slate-900/50 text-slate-400 font-semibold">
                        <th class="p-4 w-40">Batch Number</th>
                        <th class="p-4">Raw Material</th>
                        <th class="p-4 w-28">Type</th>
                        <th class="p-4 text-right">Quantity</th>
                        <th class="p-4 text-right">Prev Stock</th>
                        <th class="p-4 text-right">Balance After</th>
                        <th class="p-4">Supervisor / Logged By</th>
                        <th class="p-4">Mixer / Machine</th>
                        <th class="p-4">Grade</th>
                        <th class="p-4">Date Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50">
                    @forelse($ledgers as $ledger)
                        <tr class="hover:bg-slate-900/30 text-slate-205 transition-colors">
                            <!-- Batch Number -->
                            <td class="p-4 font-mono font-bold">
                                @if($ledger->batch)
                                    <a href="{{ route('production.show', $ledger->batch->id) }}" class="text-cyan-400 hover:underline">
                                        {{ $ledger->batch->batch_no }}
                                    </a>
                                @elseif($ledger->groutBatch)
                                    <a href="{{ route('grout-production.show', $ledger->groutBatch->id) }}" class="text-cyan-400 hover:underline">
                                        {{ $ledger->groutBatch->batch_no }}
                                    </a>
                                @else
                                    <span class="text-slate-600">-</span>
                                @endif
                            </td>

                            <!-- Raw Material -->
                            <td class="p-4 font-semibold text-white">
                                {{ $ledger->rawMaterial->name }}
                                <span class="block text-xs font-mono text-slate-500">{{ $ledger->rawMaterial->code }}</span>
                            </td>

                            <!-- Transaction Type Badge -->
                            <td class="p-4">
                                @if($ledger->transaction_type === 'IN')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-xs font-bold font-mono">
                                        IN
                                    </span>
                                @elseif($ledger->transaction_type === 'OUT')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-rose-500/10 text-rose-450 border border-rose-500/20 text-xs font-bold font-mono">
                                        OUT
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-purple-500/10 text-purple-400 border border-purple-500/20 text-xs font-bold font-mono">
                                        ADJUST
                                    </span>
                                @endif
                            </td>

                            <!-- Quantity -->
                            <td class="p-4 text-right font-mono font-bold {{ $ledger->transaction_type === 'OUT' ? 'text-rose-455' : 'text-emerald-400' }}">
                                {{ $ledger->transaction_type === 'OUT' ? '-' : '+' }}{{ number_format(abs($ledger->quantity), 4) }} {{ $ledger->rawMaterial->stockUnit->code }}
                            </td>

                            <!-- Previous Stock -->
                            <td class="p-4 text-right font-mono text-slate-400">
                                {{ number_format($ledger->previous_stock, 4) }}
                            </td>

                            <!-- Balance After & Low Stock Alert -->
                            <td class="p-4 text-right font-mono font-bold text-white">
                                <div>{{ number_format($ledger->balance_after, 4) }}</div>
                                @if($ledger->balance_after < (float) $ledger->rawMaterial->minimum_stock)
                                    <span class="inline-flex items-center px-1.5 py-0.5 text-[9px] font-bold bg-rose-500/20 text-rose-450 border border-rose-500/35 rounded mt-0.5 uppercase tracking-wide">
                                        LOW STOCK
                                    </span>
                                @endif
                            </td>

                            <!-- Supervisor / Creator -->
                            <td class="p-4 text-slate-350">
                                {{ $ledger->batch->supervisor->name ?? $ledger->groutBatch->operator->name ?? $ledger->creator->name ?? 'N/A' }}
                            </td>

                            <!-- Mixer / Machine -->
                            <td class="p-4 text-slate-400">
                                {{ $ledger->batch->machine->name ?? $ledger->groutBatch->machine->name ?? '-' }}
                            </td>

                            <!-- Grade -->
                            <td class="p-4 text-slate-400">
                                {{ $ledger->batch->grade->name ?? $ledger->groutBatch->color->name ?? '-' }}
                            </td>

                            <!-- Date Time -->
                            <td class="p-4 font-mono text-xs text-slate-450">
                                {{ $ledger->created_at->format('d M Y, h:i:s A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i data-lucide="package-x" class="w-8 h-8 text-slate-700"></i>
                                    <span>No stock ledger entries found.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($ledgers->hasPages())
            <div class="px-6 py-4 border-t border-slate-850 bg-slate-950/20">
                {{ $ledgers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
