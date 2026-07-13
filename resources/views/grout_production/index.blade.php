@extends('layouts.app')

@section('title', 'Grout Production')
@section('header-title', 'Grout Production Floor')

@section('content')
<div class="space-y-6">
    <!-- Live Grout Machine Cards -->
    <div>
        <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Live Machine Panels</h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach(['M-01', 'M-04', 'M-05'] as $code)
                @php
                    $statusData = $liveMachineStatuses[$code] ?? null;
                    $machine = $statusData['machine'] ?? null;
                    $batch = $statusData['batch'] ?? null;
                    $remainingSecs = $statusData['remaining_seconds'] ?? 3600;
                    $isRunning = $batch !== null;
                @endphp

                @if($machine)
                    <article class="group relative overflow-hidden rounded-2xl border {{ $isRunning ? 'border-cyan-500/30 bg-slate-950 shadow-lg shadow-cyan-500/5' : 'border-slate-850 bg-slate-950' }} p-4 transition duration-200 hover:-translate-y-0.5">
                        <div class="absolute inset-y-0 left-0 w-1 {{ $isRunning ? 'bg-cyan-500' : 'bg-slate-800' }}"></div>

                        <div class="flex items-start justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $isRunning ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'bg-slate-900 text-slate-500 border border-slate-800' }}">
                                    <i data-lucide="cpu" class="h-5 w-5"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-extrabold text-white">{{ $machine?->name ?? 'Unknown Machine' }}</p>
                                    <p class="mt-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ $machine?->code ?? '-' }}</p>
                                </div>
                            </div>

                            @if($isRunning)
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                    <span class="h-1 w-1 rounded-full bg-cyan-500 animate-pulse"></span>
                                    {{ $batch->status }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider bg-slate-900 text-slate-500 border border-slate-800">
                                    Ready
                                </span>
                            @endif
                        </div>

                        <!-- Card Body / Info -->
                        @if($isRunning)
                            <div class="mt-4 grid grid-cols-2 gap-2 rounded-xl border border-slate-850 bg-slate-900/40 p-3 text-xs">
                                <div>
                                    <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500">Color</p>
                                    <p class="mt-1 font-semibold text-white truncate">{{ $batch->color?->name ?? 'No Color' }}</p>
                                </div>
                                <div>
                                    @if($code === 'M-01')
                                        <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500">Packing Mode</p>
                                        <p class="mt-1 font-semibold text-amber-400">Auto Pouches</p>
                                    @else
                                        <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500">
                                            {{ $batch->status === 'Timer Running' ? 'Mix Time Left' : 'Current Stage' }}
                                        </p>
                                        @if($batch->status === 'Timer Running')
                                            <p class="live-timer-countdown mt-1 font-mono font-bold text-cyan-400" data-seconds="{{ $remainingSecs }}" data-batch-id="{{ $batch->id }}">
                                                {{ sprintf('%02d:%02d', floor($remainingSecs / 60), $remainingSecs % 60) }}
                                            </p>
                                        @else
                                            <p class="mt-1 font-semibold text-white">{{ $batch->status }}</p>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-span-2 flex items-center justify-between border-t border-slate-850 pt-2 mt-1">
                                    <span class="font-mono text-[10px] font-bold text-slate-500">{{ $batch->batch_no }}</span>
                                    <span class="truncate text-[10px] text-slate-400">{{ $batch->operator?->name ?? 'Unassigned' }}</span>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('grout-production.running', $batch->id) }}" class="flex w-full items-center justify-center py-2 bg-slate-900 border border-slate-800 hover:border-cyan-500/40 text-slate-300 hover:text-cyan-400 rounded-xl text-xs font-bold transition-all">
                                    Open Operator Panel
                                </a>
                            </div>
                        @else
                            <div class="mt-4 flex min-h-[88px] items-center justify-between gap-3 rounded-xl border border-dashed border-slate-800 bg-slate-900/10 p-3">
                                <div>
                                    <p class="text-xs font-bold text-slate-400">Idle</p>
                                    <p class="mt-0.5 text-[10px] text-slate-500 leading-normal">Ready to start a new Grout production run.</p>
                                </div>
                                <a href="{{ route('grout-production.create', ['machine_id' => $machine->id]) }}" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-cyan-500 text-white shadow-md shadow-cyan-500/20 transition hover:scale-105" title="Start Batch">
                                    <i data-lucide="plus" class="h-4 w-4"></i>
                                </a>
                            </div>
                        @endif
                    </article>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Batch History / Filters -->
    <div class="space-y-4 pt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Production History</h2>
            <a href="{{ route('grout-production.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 transform active:scale-[0.98] shadow-lg shadow-cyan-500/10 text-sm gap-2 shrink-0 self-start sm:self-auto">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Start Grout Batch</span>
            </a>
        </div>

        <!-- Filter Card -->
        <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-5">
            <form method="GET" action="{{ route('grout-production.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                <!-- Search Batch No -->
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[10px]">Batch Number</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-xs"
                        placeholder="Search batch...">
                </div>

                <!-- Machine Filter -->
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[10px]">Machine</label>
                    <select name="machine_id"
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-slate-350 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-xs">
                        <option value="">All Machines</option>
                        @foreach($filterMachines as $m)
                            <option value="{{ $m->id }}" {{ request('machine_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->name }} ({{ $m->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[10px]">Status</label>
                    <select name="status"
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-slate-350 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-xs">
                        <option value="">All Statuses</option>
                        <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Packing" {{ request('status') === 'Packing' ? 'selected' : '' }}>Packing</option>
                        <option value="Ready For Packing" {{ request('status') === 'Ready For Packing' ? 'selected' : '' }}>Ready For Packing</option>
                        <option value="Stage 1 Mixing" {{ request('status') === 'Stage 1 Mixing' ? 'selected' : '' }}>Stage 1 Mixing</option>
                        <option value="Timer Running" {{ request('status') === 'Timer Running' ? 'selected' : '' }}>Timer Running</option>
                    </select>
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[10px]">Production Date</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-xs">
                </div>

                <div class="col-span-1 sm:col-span-2 md:col-span-4 flex justify-end gap-2 pt-2 border-t border-slate-850 mt-1">
                    @if(request()->anyFilled(['search', 'machine_id', 'status', 'date']))
                        <a href="{{ route('grout-production.index') }}" class="px-4 py-2 bg-slate-900 text-slate-400 hover:text-white rounded-xl text-xs font-semibold transition-colors">
                            Clear Filters
                        </a>
                    @endif
                    <button type="submit" class="px-5 py-2 bg-slate-900 border border-slate-800 hover:border-cyan-500/40 text-slate-300 hover:text-cyan-400 rounded-xl text-xs font-bold transition-all">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- History Table -->
        <div class="bg-slate-950 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-850 bg-slate-900/50 text-slate-400 font-semibold">
                            <th class="p-4 w-28">Batch No</th>
                            <th class="p-4">Machine</th>
                            <th class="p-4">Color</th>
                            <th class="p-4">Operator</th>
                            <th class="p-4">Finished Output</th>
                            <th class="p-4">Start Time</th>
                            <th class="p-4 w-32">Status</th>
                            <th class="p-4 w-24 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-850/50">
                        @forelse($batches as $b)
                            <tr class="hover:bg-slate-900/30 text-slate-200 transition-colors">
                                <td class="p-4 font-mono font-bold text-cyan-450">{{ $b->batch_no }}</td>
                                <td class="p-4">{{ $b->machine?->code ?? '-' }}</td>
                                <td class="p-4">
                                    <div class="font-semibold text-white">{{ $b->color?->name ?? 'No Color' }}</div>
                                    <div class="text-[10px] font-mono text-slate-500 uppercase">{{ $b->color?->code ?? '-' }}</div>
                                </td>
                                <td class="p-4 text-xs text-slate-450">{{ $b->operator?->name ?? 'Unassigned' }}</td>
                                <td class="p-4">
                                    @if($b->status === 'Completed')
                                        <span class="font-semibold text-white">{{ $b->finished_bags }} Bags</span>
                                        <span class="text-slate-500 text-xs block">({{ number_format($b->total_weight_kg, 1) }} KG)</span>
                                    @else
                                        <span class="text-slate-500 italic">-</span>
                                    @endif
                                </td>
                                <td class="p-4 text-xs text-slate-400">
                                    {{ $b->start_time ? $b->start_time->format('d M Y, h:i A') : $b->created_at->format('d M Y, h:i A') }}
                                </td>
                                <td class="p-4">
                                    @if($b->status === 'Completed')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            Completed
                                        </span>
                                    @elseif($b->status === 'Packing')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20 animate-pulse">
                                            Packing
                                        </span>
                                    @elseif($b->status === 'Timer Running')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                            Mixing Timer
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700/50">
                                            {{ $b->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    @if($b->status === 'Completed')
                                        <a href="{{ route('grout-production.show', $b->id) }}" class="p-1.5 bg-slate-900 border border-slate-800 hover:border-cyan-550/40 text-slate-400 hover:text-cyan-400 rounded-lg transition-all inline-block" title="View details">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('grout-production.running', $b->id) }}" class="p-1.5 bg-slate-900 border border-slate-800 hover:border-cyan-500/40 text-cyan-400 hover:text-cyan-300 rounded-lg transition-all inline-block" title="Operator Console">
                                            <i data-lucide="play" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <i data-lucide="folder-open" class="w-8 h-8 text-slate-700"></i>
                                        <span>No grout batches registered.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($batches->hasPages())
                <div class="px-6 py-4 border-t border-slate-850">
                    {{ $batches->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Poll for completed timers on the dashboard and trigger alerts
        function checkTimers() {
            $('.live-timer-countdown').each(function() {
                var el = $(this);
                var secs = parseInt(el.attr('data-seconds'));
                var batchId = el.attr('data-batch-id');

                if (secs > 0) {
                    secs--;
                    el.attr('data-seconds', secs);
                    var mins = Math.floor(secs / 60);
                    var remainingMinsStr = (mins < 10 ? '0' : '') + mins + ':' + (secs % 60 < 10 ? '0' : '') + (secs % 60);
                    el.text(remainingMinsStr);

                    if (secs === 0) {
                        // Alert that mixing timer has completed!
                        Swal.fire({
                            title: 'Mixing Completed!',
                            text: 'Dry Mixing stage has finished. Please check the mixer panel to proceed.',
                            icon: 'success',
                            confirmButtonColor: '#06b6d4',
                            background: '#090d16',
                            color: '#f1f5f9'
                        }).then(function() {
                            window.location.reload();
                        });
                    }
                }
            });
        }

        setInterval(checkTimers, 1000);
    });
</script>
@endsection
