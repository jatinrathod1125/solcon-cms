@extends('layouts.app')

@section('title', 'Supervisor Dashboard')
@php
    $greeting = now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening');
    $firstName = explode(' ', Auth::user()->name)[0];
@endphp
@section('header-title', "Good {$greeting}, {$firstName}")

@section('content')
@php
    $deptCode = auth()->user()->department ? auth()->user()->department->code : '';
    if ($deptCode === 'GRT') {
        $startBatchRoute = route('grout-production.create');
        $viewHistoryRoute = route('grout-production.index');
    } elseif ($deptCode === 'EPX') {
        $startBatchRoute = route('epoxy.create');
        $viewHistoryRoute = route('epoxy.index');
    } else {
        $startBatchRoute = route('production.create');
        $viewHistoryRoute = route('production.index');
    }

    $leftoverQuery = \App\Models\ProductionBatch::whereIn('status', ['running', 'paused'])
        ->whereDate('start_time', '<', now()->toDateString())
        ->with(['machine', 'grade']);

    if (auth()->user()->isSupervisor()) {
        $leftoverQuery->whereHas('machine', function ($q) {
            $q->where('department_id', auth()->user()->department_id);
        });
    }

    $leftoverBatches = $leftoverQuery->get();
@endphp

<div class="mx-auto max-w-[1600px] space-y-5">
    @if($leftoverBatches->isNotEmpty())
        <div class="space-y-3">
            @foreach($leftoverBatches as $leftoverBatch)
                <div class="flex flex-col justify-between gap-4 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-4 text-xs text-amber-900 sm:flex-row sm:items-center">
                    <div class="flex items-start gap-3">
                        <i data-lucide="alert-triangle" class="mt-0.5 h-5 w-5 shrink-0 animate-bounce text-amber-500"></i>
                        <div>
                            <p class="font-extrabold uppercase tracking-wider text-amber-600">Leftover Production Batch Detected</p>
                            <p class="mt-1 font-semibold">
                                Batch <span class="font-bold text-amber-700">#{{ $leftoverBatch->batch_no }}</span>
                                (Grade: {{ $leftoverBatch->grade->name }}) on Mixer
                                <span class="font-bold">{{ $leftoverBatch->machine->name }}</span> has been
                                <span class="font-extrabold uppercase text-amber-600">{{ $leftoverBatch->status }}</span>
                                since {{ $leftoverBatch->start_time->format('d M Y, h:i A') }}.
                            </p>
                            <p class="mt-0.5 text-[10px] font-medium text-slate-500">Please resume, complete, or cancel this batch to free up the machine.</p>
                        </div>
                    </div>
                    <a href="{{ route('production.show', $leftoverBatch->id) }}" class="self-center rounded-xl bg-amber-600 px-3.5 py-2 text-[10px] font-extrabold uppercase tracking-wider text-white shadow-md transition hover:bg-amber-500">
                        Resolve Batch
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    <x-dashboard-action-center
        :todos="$todos"
        :todoCounters="$todoCounters"
        :liveMachines="$liveMachines"
        dashboardRole="supervisor"
        departmentName="{{ currentDepartment()->name ?? 'Your Department' }}"
        :startBatchRoute="$startBatchRoute"
        :viewHistoryRoute="$viewHistoryRoute"
    />

    <x-todo-widget :todos="$todos" :todoCounters="$todoCounters" :supervisors="$supervisors" :departments="$departments" />

    <section class="erp-card overflow-hidden">
        <header class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 shadow-[0_0_0_5px_rgba(16,185,129,.1)]"></span>
                    <h3 class="text-sm font-extrabold text-slate-900">Live Mixer Floor ({{ currentDepartment()->name ?? 'Your Department' }})</h3>
                </div>
                <p class="mt-1 text-[10px] font-semibold text-slate-400">Automatic refresh every 30 seconds</p>
            </div>
            <div class="flex items-center gap-2">
                <span id="refreshTimer" class="text-[10px] font-bold tabular-nums text-slate-400">Refresh in 30s</span>
                <button id="manualMachineRefresh" type="button" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition hover:bg-blue-50 hover:text-blue-600" title="Refresh">
                    <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                </button>
            </div>
        </header>

        <div id="liveMachinesContainer" class="p-4 sm:p-5">
            @include('admin.dashboard.partials.machines', ['liveMachines' => $liveMachines])
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    function updateTimers() {
        $('.live-timer').each(function () {
            const start = new Date($(this).data('start'));
            if (isNaN(start)) return;
            const total = Math.max(0, Math.floor((Date.now() - start.getTime()) / 1000));
            const h = String(Math.floor(total / 3600)).padStart(2, '0');
            const m = String(Math.floor((total % 3600) / 60)).padStart(2, '0');
            const s = String(total % 60).padStart(2, '0');
            $(this).text(`${h}:${m}:${s}`);
        });
    }
    updateTimers();
    setInterval(updateTimers, 1000);

    let countdown = 30;
    function refreshMachines() {
        $('#liveMachinesContainer').html(`
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 animate-pulse">
                <div class="space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="h-4 w-1/3 rounded bg-slate-200"></div>
                    <div class="h-8 w-3/4 rounded bg-slate-200"></div>
                    <div class="h-4 w-1/2 rounded bg-slate-200"></div>
                </div>
                <div class="space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="h-4 w-1/3 rounded bg-slate-200"></div>
                    <div class="h-8 w-3/4 rounded bg-slate-200"></div>
                    <div class="h-4 w-1/2 rounded bg-slate-200"></div>
                </div>
                <div class="space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="h-4 w-1/3 rounded bg-slate-200"></div>
                    <div class="h-8 w-3/4 rounded bg-slate-200"></div>
                    <div class="h-4 w-1/2 rounded bg-slate-200"></div>
                </div>
            </div>
        `);
        $.get("{{ route('supervisor.dashboard.machines') }}", function (html) {
            $('#liveMachinesContainer').html(html);
            window.renderHeroicons(document.getElementById('liveMachinesContainer'));
            updateTimers();
            countdown = 30;
        });
    }
    $('#manualMachineRefresh').on('click', refreshMachines);
    setInterval(function () {
        countdown--;
        $('#refreshTimer').text(`Refresh in ${countdown}s`);
        if (countdown <= 0) refreshMachines();
    }, 1000);
});
</script>
@endsection
