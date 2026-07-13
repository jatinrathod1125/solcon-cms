@props([
    'todos' => collect(),
    'todoCounters' => [],
    'liveMachines' => [],
    'kpi' => [],
    'lowStock' => [],
    'notifications' => collect(),
    'dashboardRole' => 'admin',
    'departmentName' => null,
    'startBatchRoute' => null,
    'viewHistoryRoute' => null,
])

@php
    $user = auth()->user();
    $firstName = explode(' ', $user->name)[0];
    $greeting = now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening');

    $todoCollection = $todos instanceof \Illuminate\Support\Collection ? $todos : collect($todos ?? []);
    $notificationCollection = $notifications instanceof \Illuminate\Support\Collection ? $notifications : collect($notifications ?? []);
    $machineCollection = collect($liveMachines ?? []);

    $pendingTasks = $todoCollection->filter(fn ($todo) => $todo->status !== 'completed');
    $completedTasks = $todoCollection->filter(fn ($todo) => $todo->status === 'completed');
    $highPriorityTasks = $pendingTasks->filter(fn ($todo) => $todo->priority === 'high')->count();
    $todayTasks = $pendingTasks->filter(fn ($todo) => $todo->due_date && $todo->due_date->isToday());
    $overdueTasks = $todoCounters['overdue'] ?? $pendingTasks->filter(fn ($todo) => $todo->due_date && $todo->due_date->isBefore(today()))->count();

    $runningMixers = $machineCollection->filter(function ($machine) {
        $status = strtolower($machine['status'] ?? 'idle');
        return ! in_array($status, ['idle', 'completed'], true);
    })->count();
    $idleMixers = $machineCollection->filter(fn ($machine) => strtolower($machine['status'] ?? 'idle') === 'idle')->count();
    $completedMixers = $machineCollection->filter(fn ($machine) => strtolower($machine['status'] ?? '') === 'completed')->count();

    $lowStockCount = $kpi['low_stock_items'] ?? (is_countable($lowStock) ? count($lowStock) : 0);
    $pendingProduction = $kpi['grout_running'] ?? $runningMixers;
    $mixingCompleteNotifications = $notificationCollection->filter(function ($notification) {
        $type = strtolower($notification->type ?? '');
        $title = strtolower($notification->title ?? '');
        return str_contains($type, 'mixing_complete') || str_contains($title, 'mixing complete');
    })->count();

    $summaryCards = [
        ['label' => 'High Priority Tasks', 'value' => $highPriorityTasks, 'meta' => 'needs focus', 'icon' => 'alert-triangle', 'tone' => 'rose'],
        ['label' => 'Pending Production', 'value' => $pendingProduction, 'meta' => 'active queues', 'icon' => 'activity', 'tone' => 'blue'],
        ['label' => 'Running Mixers', 'value' => $runningMixers, 'meta' => $idleMixers . ' idle', 'icon' => 'cpu', 'tone' => 'emerald'],
        ['label' => 'Low Stock Alerts', 'value' => $lowStockCount, 'meta' => 'materials', 'icon' => 'archive', 'tone' => $lowStockCount > 0 ? 'amber' : 'slate'],
        ['label' => 'Mixing Complete', 'value' => $mixingCompleteNotifications, 'meta' => 'notifications', 'icon' => 'bell', 'tone' => $mixingCompleteNotifications > 0 ? 'emerald' : 'slate'],
        ['label' => 'Today Schedule', 'value' => $todayTasks->count(), 'meta' => 'task due dates', 'icon' => 'calendar', 'tone' => 'blue'],
    ];

    $toneClasses = [
        'blue' => 'bg-blue-50 text-blue-700 border-blue-100',
        'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'amber' => 'bg-amber-50 text-amber-700 border-amber-100',
        'rose' => 'bg-rose-50 text-rose-700 border-rose-100',
        'slate' => 'bg-slate-50 text-slate-600 border-slate-100',
    ];

    $quickActions = [
        ['label' => 'Create Task', 'icon' => 'plus', 'type' => 'button', 'tone' => 'blue'],
        ['label' => 'Start Adhesive', 'icon' => 'play', 'href' => route('production.create'), 'tone' => 'blue'],
        ['label' => 'Start Grout', 'icon' => 'play', 'href' => route('grout-production.create'), 'tone' => 'cyan'],
        ['label' => 'Epoxy Entry', 'icon' => 'package', 'href' => route('epoxy.component-entry'), 'tone' => 'violet'],
        ['label' => 'Finished Goods', 'icon' => 'archive', 'href' => route('finished-goods.index'), 'tone' => 'emerald'],
    ];

    $actionToneClasses = [
        'blue' => 'bg-blue-600 text-white shadow-blue-600/20 hover:bg-blue-500',
        'cyan' => 'bg-cyan-600 text-white shadow-cyan-600/20 hover:bg-cyan-500',
        'violet' => 'bg-violet-600 text-white shadow-violet-600/20 hover:bg-violet-500',
        'emerald' => 'bg-emerald-600 text-white shadow-emerald-600/20 hover:bg-emerald-500',
    ];

    $scheduleSlots = ['09:00', '11:00', '14:00', '16:00'];
    $scheduleItems = $todayTasks->values()->take(4);
@endphp

<section class="action-center-surface overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-xl shadow-slate-900/5">
    <div class="grid gap-0 xl:grid-cols-[1.1fr_.9fr]">
        <div class="relative overflow-hidden bg-slate-950 px-5 py-6 text-white sm:px-7 lg:px-8">
            <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full border-[46px] border-blue-500/15"></div>
            <div class="pointer-events-none absolute bottom-0 right-0 h-32 w-56 bg-blue-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-400/10 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                Factory online
                            </span>
                            <span class="text-[11px] font-bold text-slate-300">{{ now()->format('l, d F Y') }}</span>
                        </div>
                        <h2 class="mt-4 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Good {{ $greeting }}, {{ $firstName }}</h2>
                        <p class="mt-2 max-w-2xl text-sm font-semibold leading-6 text-slate-300">Today's Production Summary</p>
                    </div>

                    <div class="grid grid-cols-3 gap-2 rounded-2xl border border-white/10 bg-white/5 p-2 text-center backdrop-blur">
                        <div class="rounded-xl bg-white/10 px-3 py-2">
                            <p class="text-lg font-black tabular-nums text-white">{{ $runningMixers }}</p>
                            <p class="text-[9px] font-extrabold uppercase tracking-wider text-slate-300">Running</p>
                        </div>
                        <div class="rounded-xl bg-white/10 px-3 py-2">
                            <p class="text-lg font-black tabular-nums text-white">{{ $idleMixers }}</p>
                            <p class="text-[9px] font-extrabold uppercase tracking-wider text-slate-300">Idle</p>
                        </div>
                        <div class="rounded-xl bg-white/10 px-3 py-2">
                            <p class="text-lg font-black tabular-nums text-white">{{ $completedMixers }}</p>
                            <p class="text-[9px] font-extrabold uppercase tracking-wider text-slate-300">Done</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($summaryCards as $card)
                        <article class="rounded-2xl border border-white/10 bg-white/95 p-4 text-slate-900 shadow-lg shadow-slate-950/10">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-[10px] font-extrabold uppercase tracking-[.13em] text-slate-400">{{ $card['label'] }}</p>
                                    <p class="mt-2 text-2xl font-black tracking-tight text-slate-950">{{ $card['value'] }}</p>
                                    <p class="mt-0.5 text-[10px] font-bold text-slate-400">{{ $card['meta'] }}</p>
                                </div>
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border {{ $toneClasses[$card['tone']] }}">
                                    <i data-lucide="{{ $card['icon'] }}" class="h-5 w-5"></i>
                                </span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-0 border-t border-slate-200 xl:border-l xl:border-t-0">
            <div class="border-b border-slate-100 p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-blue-600">Quick Actions</p>
                        <h3 class="mt-1 text-sm font-extrabold text-slate-950">Factory shortcuts</h3>
                    </div>
                    @if($departmentName)
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-extrabold text-slate-600">{{ $departmentName }}</span>
                    @endif
                </div>

                <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2">
                    @foreach($quickActions as $action)
                        @if(($action['type'] ?? 'link') === 'button')
                            <button type="button" onclick="openTodoDrawer()" class="action-center-button inline-flex min-h-12 items-center justify-between gap-3 rounded-2xl px-4 py-3 text-left text-xs font-extrabold shadow-lg transition focus:outline-none focus:ring-4 focus:ring-blue-100 {{ $actionToneClasses[$action['tone']] }}">
                                <span class="inline-flex items-center gap-2"><i data-lucide="{{ $action['icon'] }}" class="h-4 w-4"></i>{{ $action['label'] }}</span>
                                <i data-lucide="arrow-right" class="h-4 w-4 opacity-75"></i>
                            </button>
                        @else
                            <a href="{{ $action['href'] }}" class="action-center-button inline-flex min-h-12 items-center justify-between gap-3 rounded-2xl px-4 py-3 text-left text-xs font-extrabold shadow-lg transition focus:outline-none focus:ring-4 focus:ring-blue-100 {{ $actionToneClasses[$action['tone']] }}">
                                <span class="inline-flex items-center gap-2"><i data-lucide="{{ $action['icon'] }}" class="h-4 w-4"></i>{{ $action['label'] }}</span>
                                <i data-lucide="arrow-right" class="h-4 w-4 opacity-75"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="grid gap-0 md:grid-cols-2 xl:grid-cols-1 2xl:grid-cols-2">
                <article class="border-b border-slate-100 p-5 sm:p-6 md:border-b-0 md:border-r xl:border-b xl:border-r-0 2xl:border-b-0 2xl:border-r">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-slate-400">Today's Schedule</p>
                            <h3 class="mt-1 text-sm font-extrabold text-slate-950">{{ $todayTasks->count() }} due task{{ $todayTasks->count() === 1 ? '' : 's' }}</h3>
                        </div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                            <i data-lucide="calendar" class="h-5 w-5"></i>
                        </span>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse($scheduleItems as $index => $task)
                            <div class="flex gap-3">
                                <span class="w-12 pt-0.5 text-[10px] font-black tabular-nums text-slate-400">{{ $scheduleSlots[$index] ?? '16:00' }}</span>
                                <div class="min-w-0 flex-1 border-l border-slate-200 pl-3">
                                    <p class="truncate text-xs font-extrabold text-slate-800">{{ $task->title }}</p>
                                    <p class="mt-0.5 text-[10px] font-semibold text-slate-400">{{ $task->department->code ?? 'General' }}{{ $task->priority === 'high' ? ' | High priority' : '' }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-4 text-center">
                                <p class="text-xs font-extrabold text-slate-700">No scheduled tasks today</p>
                                <p class="mt-1 text-[10px] font-semibold text-slate-400">{{ $overdueTasks }} overdue task{{ $overdueTasks === 1 ? '' : 's' }}</p>
                            </div>
                        @endforelse
                    </div>
                </article>

                <article class="p-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-slate-400">Notifications</p>
                            <h3 class="mt-1 text-sm font-extrabold text-slate-950">{{ $notificationCollection->count() }} recent</h3>
                        </div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                            <i data-lucide="bell" class="h-5 w-5"></i>
                        </span>
                    </div>

                    <div class="mt-4 space-y-2">
                        @forelse($notificationCollection->take(3) as $notification)
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="line-clamp-1 text-xs font-extrabold text-slate-800">{{ $notification->title }}</p>
                                    <span class="shrink-0 text-[9px] font-bold text-slate-400">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mt-1 line-clamp-2 text-[10px] font-semibold leading-4 text-slate-500">{{ $notification->body }}</p>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-4 text-center">
                                <p class="text-xs font-extrabold text-slate-700">No active notifications</p>
                                <p class="mt-1 text-[10px] font-semibold text-slate-400">Factory status is clear</p>
                            </div>
                        @endforelse
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
