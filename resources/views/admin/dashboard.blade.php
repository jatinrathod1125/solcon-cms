@extends('layouts.app')

@section('title', 'Factory overview')
@php
    $greeting = now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening');
    $firstName = explode(' ', Auth::user()->name)[0];
@endphp
@section('header-title', "Good {$greeting}, {$firstName}")

@section('content')
<div class="mx-auto max-w-[1600px] space-y-5">
    {{-- Leftover Batches Warning Alert --}}
    @php
        $leftoverQuery = \App\Models\ProductionBatch::whereIn('status', ['running', 'paused'])
            ->whereDate('start_time', '<', now()->toDateString())
            ->with(['machine', 'grade']);
        if (auth()->user()->isSupervisor()) {
            $leftoverQuery->whereHas('machine', function($q) {
                $q->where('department_id', auth()->user()->department_id);
            });
        }
        $leftoverBatches = $leftoverQuery->get();
    @endphp

    @if($leftoverBatches->isNotEmpty())
        <div class="space-y-3">
            @foreach($leftoverBatches as $leftoverBatch)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-900 dark:text-amber-200 text-xs">
                    <div class="flex items-start gap-3">
                        <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0 text-amber-500 mt-0.5 animate-bounce"></i>
                        <div>
                            <p class="font-extrabold uppercase tracking-wider text-amber-600">Leftover Production Batch Detected</p>
                            <p class="mt-1 font-semibold">
                                Batch <span class="font-bold text-amber-700 dark:text-amber-300">#{{ $leftoverBatch->batch_no }}</span> 
                                (Grade: {{ $leftoverBatch->grade->name }}) on Mixer 
                                <span class="font-bold">{{ $leftoverBatch->machine->name }}</span> has been 
                                <span class="font-extrabold uppercase text-amber-600">{{ $leftoverBatch->status }}</span> 
                                since {{ $leftoverBatch->start_time->format('d M Y, h:i A') }}.
                            </p>
                            <p class="mt-0.5 text-[10px] text-slate-500 font-medium">Please resume, complete, or cancel this batch to free up the machine.</p>
                        </div>
                    </div>
                    <div class="flex gap-2 shrink-0 self-center">
                        <a href="{{ route('production.show', $leftoverBatch->id) }}" class="px-3.5 py-2 bg-amber-650 hover:bg-amber-500 text-white font-extrabold rounded-xl transition-all shadow-md text-[10px] uppercase tracking-wider">
                            Resolve Batch
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <span class="sr-only">Factory Dashboard</span>
    <x-dashboard-action-center
        :todos="$todos"
        :todoCounters="$todoCounters"
        :liveMachines="$liveMachines"
        :kpi="$kpi"
        :lowStock="$lowStock"
        :notifications="$notifications"
        dashboardRole="admin"
        departmentName="{{ \App\Models\Setting::get('factory_name', 'Solcon Industries') }}"
    />

    <x-todo-widget :todos="$todos" :todoCounters="$todoCounters" :supervisors="$supervisors" :departments="$departments" />

    <!-- Grout Today Overview -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Grout KPIs Card -->
        <article class="erp-card p-5 space-y-4 lg:col-span-2 flex flex-col justify-between">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="package" class="w-4 h-4 text-cyan-500"></i>
                <span>Grout Production Summary (Today)</span>
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-2">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-[9px] font-extrabold uppercase tracking-[.12em] text-slate-450 block">Batches Done</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block font-mono">{{ number_format($kpi['today_grout_batches']) }}</span>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-[9px] font-extrabold uppercase tracking-[.12em] text-slate-450 block">Bags Packed</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block font-mono">{{ number_format($kpi['today_grout_bags']) }}</span>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-[9px] font-extrabold uppercase tracking-[.12em] text-slate-450 block">Total Output (KG)</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block font-mono">{{ number_format($kpi['today_grout_kg'], 1) }}</span>
                </div>
            </div>
        </article>

        <!-- Latest Grout Packing Card -->
        <article class="erp-card p-5 flex flex-col justify-between">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest flex items-center gap-2 mb-3">
                <i data-lucide="activity" class="w-4 h-4 text-emerald-500"></i>
                <span>Latest Grout Packing run</span>
            </h3>

            @if($kpi['latest_grout_packing'])
                @php $latestG = $kpi['latest_grout_packing']; @endphp
                <div class="space-y-2 mt-1">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold font-mono text-slate-900">{{ $latestG->batch_no }}</span>
                        <span class="text-slate-400 font-medium text-[10px]">{{ ($latestG->packing_end_time ?? $latestG->updated_at ?? $latestG->created_at)->diffForHumans() }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-1 text-[11px] text-slate-500 mt-2">
                        <div>Mixer: <strong class="text-slate-700 font-bold">{{ $latestG->machine->code }}</strong></div>
                        <div>Color: <strong class="text-slate-700 font-bold">{{ $latestG->color->name }}</strong></div>
                        <div class="col-span-2 mt-1">Output: <strong class="text-emerald-600 font-bold">{{ $latestG->finished_bags }} Bags ({{ number_format($latestG->total_weight_kg, 1) }} KG)</strong></div>
                    </div>
                </div>
            @else
                <div class="text-center py-6 text-xs text-slate-400">
                    No grout runs completed today
                </div>
            @endif
        </article>
    </section>

    <!-- Epoxy Today Overview -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Epoxy KPIs Card -->
        <article class="erp-card p-5 space-y-4 lg:col-span-2 flex flex-col justify-between">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="package" class="w-4 h-4 text-purple-500"></i>
                <span>Epoxy Production Summary (Today)</span>
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-2">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-[9px] font-extrabold uppercase tracking-[.12em] text-slate-450 block">Assemblies Done</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block font-mono">{{ number_format($kpi['today_epoxy_assemblies']) }}</span>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-[9px] font-extrabold uppercase tracking-[.12em] text-slate-450 block">Kits Assembled</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block font-mono">{{ number_format($kpi['today_epoxy_kits']) }}</span>
                </div>
            </div>
        </article>

        <!-- Latest Epoxy Assembly Card -->
        <article class="erp-card p-5 flex flex-col justify-between">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest flex items-center gap-2 mb-3">
                <i data-lucide="activity" class="w-4 h-4 text-purple-500"></i>
                <span>Latest Epoxy Assembly</span>
            </h3>

            @if($kpi['latest_epoxy_assembly'])
                @php $latestE = $kpi['latest_epoxy_assembly']; @endphp
                <div class="space-y-2 mt-1">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold font-mono text-purple-600">#EPX-{{ str_pad($latestE->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-slate-400 font-medium text-[10px]">{{ ($latestE->created_at ?? $latestE->updated_at)->diffForHumans() }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-1 text-[11px] text-slate-500 mt-2">
                        <div class="col-span-2">Product: <strong class="text-slate-700 font-bold">{{ $latestE->product->name }}</strong></div>
                        @if($latestE->color)
                            <div>Color: <strong class="text-slate-700 font-bold">{{ $latestE->color->name }}</strong></div>
                        @endif
                        <div class="col-span-2 mt-1">Quantity: <strong class="text-purple-600 font-bold">{{ $latestE->quantity }} kits</strong></div>
                    </div>
                </div>
            @else
                <div class="text-center py-6 text-xs text-slate-400">
                    No epoxy assemblies logged today
                </div>
            @endif
        </article>
    </section>

    <section class="erp-card overflow-hidden">
        <header class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 shadow-[0_0_0_5px_rgba(16,185,129,.1)]"></span>
                    <h3 class="text-sm font-extrabold text-slate-900">Live machine floor</h3>
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
        <div id="liveMachinesContainer" class="p-4 sm:p-5">@include('admin.dashboard.partials.machines', ['liveMachines' => $liveMachines])</div>
    </section>

    <section class="grid grid-cols-1 gap-5 xl:grid-cols-3">
        <article class="erp-card p-5 sm:p-6 xl:col-span-2">
            <div class="flex items-start justify-between"><div><p class="text-[9px] font-extrabold uppercase tracking-[.16em] text-blue-600">Production trend</p><h3 class="mt-1 text-sm font-extrabold text-slate-900">Output volume · last 7 days</h3></div><span class="rounded-xl bg-slate-100 px-3 py-2 text-[10px] font-bold text-slate-500">KG produced</span></div>
            <div class="mt-5 h-[270px]"><canvas id="productionTrend"></canvas></div>
        </article>
        <article class="erp-card overflow-hidden">
            <header class="flex items-center justify-between border-b border-slate-100 px-5 py-4"><div><h3 class="text-sm font-extrabold text-slate-900">Low stock</h3><p class="mt-1 text-[10px] font-semibold text-slate-400">Current vs minimum level</p></div><a href="{{ route('production.ledger') }}" class="text-[10px] font-extrabold text-blue-600">View ledger</a></header>
            <div class="max-h-[300px] divide-y divide-slate-100 overflow-y-auto px-5">
                @forelse($lowStock as $item)
                    @php $percent = $item['minimum_stock'] > 0 ? min(100, max(3, ($item['current_stock'] / $item['minimum_stock']) * 100)) : 0; @endphp
                    <div class="py-3.5"><div class="flex items-center justify-between gap-3"><div class="min-w-0"><p class="truncate text-xs font-extrabold text-slate-800">{{ $item['material_name'] }}</p><p class="font-mono text-[9px] font-bold text-slate-400">{{ $item['material_code'] }}</p></div><p class="shrink-0 text-[10px] font-bold {{ $item['priority'] === 'Critical' ? 'text-rose-600' : 'text-amber-600' }}">{{ number_format($item['current_stock'], 1) }} {{ $item['unit'] }}</p></div><div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $item['priority'] === 'Critical' ? 'bg-rose-500' : 'bg-amber-500' }}" style="width: {{ $percent }}%"></div></div></div>
                @empty
                    <div class="flex flex-col items-center py-12 text-center"><i data-lucide="shield-check" class="h-8 w-8 text-emerald-500"></i><p class="mt-3 text-xs font-extrabold text-slate-700">Stock levels are healthy</p></div>
                @endforelse
            </div>
        </article>
    </section>

    <section class="erp-card overflow-hidden">
        <header class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6"><div><p class="text-[9px] font-extrabold uppercase tracking-[.16em] text-blue-600">Interactive Production Calendar</p><h3 class="mt-1 text-sm font-extrabold text-slate-900">{{ now()->format('F Y') }} production</h3></div><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="calendar-days" class="h-4 w-4"></i></span></header>
        <div class="grid gap-5 p-4 sm:p-6 lg:grid-cols-12">
            @php
                $startOfMonth = now()->startOfMonth();
                $daysInMonth = now()->daysInMonth;
                $firstDayOfWeek = $startOfMonth->dayOfWeekIso;
                $todayString = now()->toDateString();
            @endphp
            <div class="lg:col-span-5 w-full min-w-0 max-w-full">
                <div class="grid grid-cols-7 text-center text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">
                    <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                </div>
                <div class="grid grid-cols-7 gap-1 sm:gap-1.5 w-full">
                    @for($i=1; $i < $firstDayOfWeek; $i++)
                        <span class="aspect-square w-full"></span>
                    @endfor
                    @for($day=1; $day <= $daysInMonth; $day++)
                        @php
                            $date = sprintf('%04d-%02d-%02d', now()->year, now()->month, $day);
                            $hasProduction = isset($calendarData[$date]);
                        @endphp
                        <button type="button" data-calendar-date="{{ $date }}"
                            class="relative flex aspect-square w-full min-w-0 items-center justify-center rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-bold transition-all touch-manipulation {{ $date === $todayString ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : ($hasProduction ? 'bg-blue-50 text-blue-700 hover:bg-blue-100 font-extrabold' : 'text-slate-500 hover:bg-slate-100') }}">
                            <span>{{ $day }}</span>
                            @if($hasProduction)
                                <span class="absolute bottom-1 h-1 w-1 rounded-full {{ $date === $todayString ? 'bg-white' : 'bg-blue-500' }}"></span>
                            @endif
                        </button>
                    @endfor
                </div>
            </div>
            <div id="calendarDetailsContainer" class="w-full min-w-0 max-w-full rounded-[20px] border border-dashed border-slate-200 bg-slate-50/50 p-3 sm:p-5 lg:col-span-7 transition-all">
                <div class="flex min-h-[180px] items-center justify-center p-6 text-center">
                    <div>
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm"><i data-lucide="calendar-days" class="h-6 w-6"></i></span>
                        <p class="mt-3 text-xs font-extrabold text-slate-600">Choose a production date</p>
                        <p class="mt-1 text-[10px] text-slate-400">Days with activity are highlighted in blue.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="erp-card overflow-hidden">
        <header class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6"><div><h3 class="text-sm font-extrabold text-slate-900">Recent factory activity</h3><p class="mt-1 text-[10px] font-semibold text-slate-400">Latest system and production events</p></div><a href="{{ route('admin.activity-logs') }}" class="erp-button erp-button-secondary hidden sm:inline-flex">View all</a></header>
        <div class="grid divide-y divide-slate-100 md:grid-cols-2 md:divide-x md:divide-y-0 xl:grid-cols-3">
            @forelse(array_slice($activities, 0, 6) as $activity)
                <div class="flex gap-3 p-4 sm:p-5"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="activity" class="h-4 w-4"></i></span><div class="min-w-0"><p class="line-clamp-2 text-[11px] font-bold leading-5 text-slate-700">{{ $activity['description'] ?? $activity['action'] ?? 'Factory activity updated' }}</p><p class="mt-1 text-[9px] font-semibold text-slate-400">{{ (isset($activity['created_at']) && $activity['created_at']) ? \Carbon\Carbon::parse($activity['created_at'])->diffForHumans() : 'Recently' }}</p></div></div>
            @empty
                <div class="col-span-full py-10 text-center text-xs font-semibold text-slate-400">Activity will appear here as your team works.</div>
            @endforelse
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
    updateTimers(); setInterval(updateTimers, 1000);

    let countdown = 30;
    function refreshMachines() {
        $('#manualMachineRefresh iconify-icon').addClass('animate-spin');
        $('#liveMachinesContainer').html(`
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-pulse">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4 shadow-sm">
                    <div class="h-4 bg-slate-200 rounded w-1/3"></div>
                    <div class="h-8 bg-slate-200 rounded w-3/4"></div>
                    <div class="h-4 bg-slate-200 rounded w-1/2"></div>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4 shadow-sm">
                    <div class="h-4 bg-slate-200 rounded w-1/3"></div>
                    <div class="h-8 bg-slate-200 rounded w-3/4"></div>
                    <div class="h-4 bg-slate-200 rounded w-1/2"></div>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4 shadow-sm">
                    <div class="h-4 bg-slate-200 rounded w-1/3"></div>
                    <div class="h-8 bg-slate-200 rounded w-3/4"></div>
                    <div class="h-4 bg-slate-200 rounded w-1/2"></div>
                </div>
            </div>
        `);
        $.get("{{ route('admin.dashboard.machines') }}", function (html) {
            $('#liveMachinesContainer').html(html); window.renderHeroicons(document.getElementById('liveMachinesContainer')); updateTimers(); countdown = 30;
        }).always(function(){ $('#manualMachineRefresh iconify-icon').removeClass('animate-spin'); });
    }
    $('#manualMachineRefresh').on('click', refreshMachines);
    setInterval(function(){ countdown--; $('#refreshTimer').text(`Refresh in ${countdown}s`); if (countdown <= 0) refreshMachines(); }, 1000);

    const data = @json($chartData['last7Days']);
    if ($('#productionTrend').length) {
        new Chart(document.getElementById('productionTrend'), {
            type: 'line',
            data: {
                labels: data.map(x => x.label),
                datasets: [{
                    data: data.map(x => x.value),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: .42,
                    pointRadius: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: { label: c => `${Number(c.raw).toLocaleString()} KG` }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 10, weight: 600 } } },
                    y: { beginAtZero: true, border: { display: false }, grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { size: 10 } } }
                }
            }
        });
    }
    $('[data-calendar-date]').on('click', function(){
        $('[data-calendar-date]').removeClass('ring-2 ring-blue-600 ring-offset-1');
        $(this).addClass('ring-2 ring-blue-600 ring-offset-1');

        const date = $(this).data('calendar-date');
        const box = $('#calendarDetailsContainer');
        box.html('<div class="flex items-center justify-center py-12 w-full"><div class="h-8 w-8 animate-spin rounded-full border-2 border-blue-100 border-t-blue-600"></div></div>');

        $.get("{{ route('admin.calendar.details') }}", {date}).done(html => {
            box.html(html);
            if (typeof lucide !== 'undefined') {
                lucide.createIcons(box[0]);
            }
            if (typeof window.renderHeroicons === 'function') {
                window.renderHeroicons(box[0]);
            }
        }).fail(() => box.html('<p class="text-xs font-bold text-rose-600 py-6 text-center">Could not load production details for this date.</p>'));

        if (window.innerWidth < 1024) {
            $('html, body').animate({
                scrollTop: box.offset().top - 80
            }, 300);
        }
    });

    // Auto-select and load today's production date data on dashboard load
    const todayBtn = $(`[data-calendar-date="${'{{ $todayString }}'}"]`);
    if (todayBtn.length) {
        todayBtn.trigger('click');
    } else {
        $('[data-calendar-date]').first().trigger('click');
    }
});
</script>
@endsection
