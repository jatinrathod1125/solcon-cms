@props(['todos', 'todoCounters', 'supervisors', 'departments'])

@php
    $todoCollection = $todos instanceof \Illuminate\Support\Collection ? $todos : collect($todos ?? []);
    $currentUserId = auth()->id();

    $pendingTasks = $todoCollection->filter(fn ($todo) => $todo->status === 'pending');
    $inProgressTasks = $todoCollection->filter(fn ($todo) => $todo->status === 'in_progress');
    $completedTasks = $todoCollection->filter(fn ($todo) => $todo->status === 'completed');
    $openTasks = $todoCollection->filter(fn ($todo) => $todo->status !== 'completed');

    $allCount = $todoCollection->count();
    $pendingCount = $todoCounters['pending'] ?? $openTasks->count();
    $completedTodayCount = $todoCounters['completed_today'] ?? $completedTasks->filter(fn ($todo) => $todo->completed_at && $todo->completed_at->isToday())->count();
    $overdueCount = $todoCounters['overdue'] ?? $openTasks->filter(fn ($todo) => $todo->due_date && $todo->due_date->isBefore(today()))->count();
    $highPriorityCount = $openTasks->filter(fn ($todo) => $todo->priority === 'high')->count();

    $lanes = [
        'pending' => ['label' => 'Pending', 'icon' => 'clock', 'items' => $pendingTasks, 'tone' => 'amber'],
        'in_progress' => ['label' => 'In Progress', 'icon' => 'activity', 'items' => $inProgressTasks, 'tone' => 'blue'],
        'completed' => ['label' => 'Completed', 'icon' => 'check-circle', 'items' => $completedTasks, 'tone' => 'emerald'],
    ];

    $priorityBadgeClasses = [
        'high' => 'border-rose-100 bg-rose-50 text-rose-700',
        'medium' => 'border-blue-100 bg-blue-50 text-blue-700',
        'low' => 'border-slate-200 bg-slate-100 text-slate-600',
    ];

    $statusBadgeClasses = [
        'pending' => 'border-amber-100 bg-amber-50 text-amber-700',
        'in_progress' => 'border-blue-100 bg-blue-50 text-blue-700',
        'completed' => 'border-emerald-100 bg-emerald-50 text-emerald-700',
    ];

    $statusLabels = [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
    ];

    $filters = [
        ['key' => 'all', 'label' => 'All', 'icon' => 'list'],
        ['key' => 'mine', 'label' => 'Mine', 'icon' => 'user'],
        ['key' => 'assigned', 'label' => 'Assigned', 'icon' => 'users'],
        ['key' => 'pending', 'label' => 'Pending', 'icon' => 'clock'],
        ['key' => 'completed', 'label' => 'Completed', 'icon' => 'check-circle'],
        ['key' => 'high', 'label' => 'High', 'icon' => 'alert-triangle'],
        ['key' => 'medium', 'label' => 'Medium', 'icon' => 'activity'],
        ['key' => 'low', 'label' => 'Low', 'icon' => 'archive'],
        ['key' => 'today', 'label' => 'Today', 'icon' => 'calendar'],
        ['key' => 'tomorrow', 'label' => 'Tomorrow', 'icon' => 'calendar-days'],
        ['key' => 'week', 'label' => 'This Week', 'icon' => 'calendar-days'],
    ];

    $timelineTasks = $openTasks
        ->filter(fn ($todo) => $todo->due_date && $todo->due_date->isToday())
        ->values()
        ->take(4);
    if ($timelineTasks->isEmpty()) {
        $timelineTasks = $openTasks->values()->take(4);
    }
    $timelineSlots = ['09:00', '11:00', '14:00', '16:00'];
@endphp

<section
    id="todoActionCenter"
    class="todo-action-center overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-xl shadow-slate-900/5"
    data-current-user="{{ $currentUserId }}"
    data-today="{{ today()->toDateString() }}"
    data-tomorrow="{{ today()->addDay()->toDateString() }}"
    data-week-end="{{ today()->endOfWeek()->toDateString() }}"
>
    <header class="border-b border-slate-100 bg-slate-50/70 px-4 py-5 sm:px-6 lg:px-7">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <p class="text-[10px] font-extrabold uppercase tracking-[.18em] text-blue-600">Today's Actions</p>
                    <span class="rounded-full bg-white px-2.5 py-1 text-[10px] font-extrabold text-slate-500 ring-1 ring-slate-200">
                        {{ $allCount }} Task{{ $allCount === 1 ? '' : 's' }}
                    </span>
                </div>
                <h2 class="mt-2 text-xl font-extrabold tracking-tight text-slate-950 sm:text-2xl">Factory Action Workspace</h2>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <label class="relative block min-w-0 sm:min-w-[280px]">
                    <span class="sr-only">Search tasks</span>
                    <i data-lucide="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input
                        id="todoSearchInput"
                        type="search"
                        placeholder="Search tasks"
                        autocomplete="off"
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-white pl-10 pr-4 text-sm font-semibold text-slate-800 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100"
                    >
                </label>
                <button type="button" onclick="openTodoDrawer()" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    <span>Create Task</span>
                </button>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-3 lg:grid-cols-5">
            <article class="rounded-2xl border border-slate-100 bg-white p-4">
                <p class="text-[9px] font-extrabold uppercase tracking-[.14em] text-slate-400">All Tasks</p>
                <p class="mt-2 text-2xl font-black text-slate-950" data-todo-count="all">{{ $allCount }}</p>
            </article>
            <article class="rounded-2xl border border-amber-100 bg-amber-50/70 p-4">
                <p class="text-[9px] font-extrabold uppercase tracking-[.14em] text-amber-700">Pending</p>
                <p class="mt-2 text-2xl font-black text-slate-950" data-todo-count="pending">{{ $pendingCount }}</p>
            </article>
            <article class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4">
                <p class="text-[9px] font-extrabold uppercase tracking-[.14em] text-emerald-700">Completed Today</p>
                <p class="mt-2 text-2xl font-black text-slate-950" data-todo-count="completed_today">{{ $completedTodayCount }}</p>
            </article>
            <article class="rounded-2xl border border-rose-100 bg-rose-50/70 p-4">
                <p class="text-[9px] font-extrabold uppercase tracking-[.14em] text-rose-700">Overdue</p>
                <p class="mt-2 text-2xl font-black text-slate-950" data-todo-count="overdue">{{ $overdueCount }}</p>
            </article>
            <article class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4">
                <p class="text-[9px] font-extrabold uppercase tracking-[.14em] text-blue-700">High Priority</p>
                <p class="mt-2 text-2xl font-black text-slate-950" data-todo-count="high">{{ $highPriorityCount }}</p>
            </article>
        </div>
    </header>

    <div class="border-b border-slate-100 px-4 py-3 sm:px-6 lg:px-7">
        <div class="flex gap-2 overflow-x-auto pb-1" role="tablist" aria-label="Task filters">
            @foreach($filters as $filter)
                <button
                    type="button"
                    data-todo-filter="{{ $filter['key'] }}"
                    aria-pressed="{{ $filter['key'] === 'all' ? 'true' : 'false' }}"
                    class="todo-filter-btn inline-flex min-h-10 shrink-0 items-center gap-2 rounded-2xl border px-3.5 py-2 text-xs font-extrabold transition focus:outline-none focus:ring-4 focus:ring-blue-100 {{ $filter['key'] === 'all' ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-slate-200 bg-white text-slate-500 hover:border-slate-300 hover:text-slate-800' }}"
                >
                    <i data-lucide="{{ $filter['icon'] }}" class="h-3.5 w-3.5"></i>
                    <span>{{ $filter['label'] }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <div class="grid gap-4 bg-slate-50/45 p-4 sm:p-5 lg:grid-cols-3 lg:p-6">
        @foreach($lanes as $laneKey => $lane)
            <section class="todo-lane rounded-[22px] border border-slate-200 bg-white p-3 shadow-sm" aria-labelledby="todo-lane-{{ $laneKey }}">
                <header class="mb-3 flex items-center justify-between gap-3 px-1">
                    <div class="flex items-center gap-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-2xl {{ $lane['tone'] === 'amber' ? 'bg-amber-50 text-amber-600' : ($lane['tone'] === 'blue' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600') }}">
                            <i data-lucide="{{ $lane['icon'] }}" class="h-4 w-4"></i>
                        </span>
                        <div>
                            <h3 id="todo-lane-{{ $laneKey }}" class="text-sm font-extrabold text-slate-900">{{ $lane['label'] }}</h3>
                            <p class="text-[10px] font-bold text-slate-400"><span data-lane-count="{{ $laneKey }}">{{ $lane['items']->count() }}</span> cards</p>
                        </div>
                    </div>
                </header>

                <div class="todo-lane-list min-h-[180px] space-y-3" data-todo-lane="{{ $laneKey }}">
                    @foreach($lane['items'] as $todo)
                        @php
                            $isCompleted = $todo->status === 'completed';
                            $isOverdue = ! $isCompleted && $todo->due_date && $todo->due_date->isBefore(today());
                            $isToday = ! $isCompleted && $todo->due_date && $todo->due_date->isToday();
                            $isTomorrow = ! $isCompleted && $todo->due_date && $todo->due_date->isTomorrow();
                            $dueClass = $isOverdue
                                ? 'border-rose-100 bg-rose-50 text-rose-700'
                                : ($isToday ? 'border-amber-100 bg-amber-50 text-amber-700' : 'border-slate-200 bg-slate-50 text-slate-600');
                            $canManage = auth()->user()->isAdmin() || ($todo->created_by === auth()->id() && $todo->assigned_to === auth()->id());
                            $searchText = strtolower(trim($todo->title . ' ' . ($todo->description ?? '') . ' ' . ($todo->department->name ?? '') . ' ' . ($todo->department->code ?? '') . ' ' . ($todo->assignee->name ?? '') . ' ' . ($todo->creator->name ?? '')));
                        @endphp

                        <article
                            class="todo-card group relative overflow-hidden rounded-[20px] border border-slate-200 bg-white p-4 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-lg focus-within:border-blue-300 {{ $isCompleted ? 'is-completed opacity-75' : '' }}"
                            tabindex="0"
                            data-id="{{ $todo->id }}"
                            data-status="{{ $todo->status }}"
                            data-priority="{{ $todo->priority }}"
                            data-due="{{ $todo->due_date ? $todo->due_date->toDateString() : '' }}"
                            data-completed-today="{{ $todo->completed_at && $todo->completed_at->isToday() ? '1' : '0' }}"
                            data-assigned-to="{{ $todo->assigned_to }}"
                            data-created-by="{{ $todo->created_by }}"
                            data-search="{{ $searchText }}"
                        >
                            <div class="todo-card-label"></div>
                            <div class="flex items-start gap-3">
                                <button type="button" class="todo-drag-handle mt-0.5 hidden h-9 w-8 shrink-0 items-center justify-center rounded-xl text-slate-300 transition hover:bg-slate-50 hover:text-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-100 sm:flex" aria-label="Drag task">
                                    <i data-lucide="menu" class="h-4 w-4"></i>
                                </button>

                                <button type="button" onclick="toggleTodoStatus({{ $todo->id }})" class="todo-check mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl border transition focus:outline-none focus:ring-4 focus:ring-blue-100 {{ $isCompleted ? 'border-emerald-200 bg-emerald-500 text-white' : 'border-slate-300 bg-white text-transparent hover:border-blue-400 hover:text-blue-500' }}" aria-label="{{ $isCompleted ? 'Mark task as pending' : 'Complete task' }}">
                                    <i data-lucide="check" class="h-4 w-4"></i>
                                </button>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <h4 class="todo-title break-words text-sm font-extrabold leading-5 text-slate-900 {{ $isCompleted ? 'line-through text-slate-400' : '' }}">{{ $todo->title }}</h4>
                                            @if($todo->description)
                                                <p class="todo-description mt-1 line-clamp-2 text-xs font-semibold leading-5 text-slate-500 {{ $isCompleted ? 'line-through text-slate-300' : '' }}">{{ $todo->description }}</p>
                                            @else
                                                <p class="todo-description mt-1 text-xs font-semibold text-slate-400">No description</p>
                                            @endif
                                        </div>
                                        <button type="button" onclick="toggleTodoPin({{ $todo->id }})" class="todo-pin-btn flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-slate-300 transition hover:bg-amber-50 hover:text-amber-500 focus:outline-none focus:ring-4 focus:ring-amber-100" aria-label="Pin task" aria-pressed="false">
                                            <i data-lucide="star" class="h-4 w-4"></i>
                                        </button>
                                    </div>

                                    <div class="mt-3 flex flex-wrap items-center gap-1.5">
                                        @if($todo->department)
                                            <span class="inline-flex items-center gap-1 rounded-full border border-blue-100 bg-blue-50 px-2.5 py-1 text-[10px] font-extrabold text-blue-700">
                                                <i data-lucide="building" class="h-3 w-3"></i>
                                                {{ $todo->department->code }}
                                            </span>
                                        @endif

                                        <span class="todo-priority-badge inline-flex items-center rounded-full border px-2.5 py-1 text-[10px] font-extrabold uppercase {{ $priorityBadgeClasses[$todo->priority] ?? $priorityBadgeClasses['medium'] }}">
                                            {{ $todo->priority }}
                                        </span>

                                        @if($todo->due_date)
                                            <span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-[10px] font-extrabold {{ $dueClass }}">
                                                <i data-lucide="calendar" class="h-3 w-3"></i>
                                                {{ $todo->due_date->format('d M') }}
                                            </span>
                                        @endif

                                        <span class="todo-status-badge inline-flex items-center rounded-full border px-2.5 py-1 text-[10px] font-extrabold {{ $statusBadgeClasses[$todo->status] ?? $statusBadgeClasses['pending'] }}">
                                            {{ $statusLabels[$todo->status] ?? ucfirst($todo->status) }}
                                        </span>
                                    </div>

                                    <div class="mt-3 flex flex-col gap-2 text-[10px] font-bold text-slate-400 sm:flex-row sm:flex-wrap sm:items-center">
                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="user" class="h-3 w-3"></i>
                                            Assigned by {{ $todo->creator->name ?? 'System' }}
                                        </span>
                                        @if(auth()->user()->isAdmin() && $todo->assignee)
                                            <span class="inline-flex items-center gap-1">
                                                <i data-lucide="users" class="h-3 w-3"></i>
                                                To {{ $todo->assignee->name }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="todo-card-actions mt-4 flex flex-wrap items-center gap-2 sm:opacity-0 sm:transition sm:group-hover:opacity-100 sm:group-focus-within:opacity-100">
                                        <button type="button" onclick="setTodoStatus({{ $todo->id }}, '{{ $isCompleted ? 'pending' : 'completed' }}')" class="todo-status-action inline-flex min-h-10 items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-[11px] font-extrabold text-slate-600 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-100">
                                            <i data-lucide="{{ $isCompleted ? 'clock' : 'check-circle' }}" class="h-3.5 w-3.5"></i>
                                            <span class="todo-status-action-label">{{ $isCompleted ? 'Reopen' : 'Complete' }}</span>
                                        </button>

                                        @if($canManage)
                                            <button type="button" onclick="editTodo(@js($todo))" class="inline-flex min-h-10 items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-[11px] font-extrabold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
                                                <i data-lucide="edit-2" class="h-3.5 w-3.5"></i>
                                                <span>Edit</span>
                                            </button>
                                            <button type="button" onclick="deleteTodo({{ $todo->id }})" class="inline-flex min-h-10 items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-[11px] font-extrabold text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-100">
                                                <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                                <span>Delete</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach

                    <div class="todo-lane-empty {{ $lane['items']->isEmpty() ? '' : 'hidden' }} rounded-[20px] border border-dashed border-slate-200 bg-slate-50 p-5 text-center">
                        <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-slate-300 shadow-sm">
                            <i data-lucide="check-circle-2" class="h-6 w-6"></i>
                        </span>
                        <p class="mt-3 text-xs font-extrabold text-slate-700">No {{ strtolower($lane['label']) }} tasks</p>
                    </div>
                </div>
            </section>
        @endforeach
    </div>

    <section class="border-t border-slate-100 bg-white px-4 py-5 sm:px-6 lg:px-7">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-blue-600">Today Timeline</p>
                <h3 class="mt-1 text-sm font-extrabold text-slate-950">Scheduled factory checkpoints</h3>
            </div>

            <div class="grid flex-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                @forelse($timelineTasks as $index => $task)
                    <article class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-[10px] font-black tabular-nums text-blue-600">{{ $timelineSlots[$index] ?? '16:00' }}</p>
                        <p class="mt-2 line-clamp-2 text-xs font-extrabold text-slate-800">{{ $task->title }}</p>
                        <p class="mt-1 text-[10px] font-bold text-slate-400">{{ $task->department->code ?? 'General' }}</p>
                    </article>
                @empty
                    <article class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-center sm:col-span-2 xl:col-span-4">
                        <p class="text-xs font-extrabold text-slate-700">Timeline is clear</p>
                    </article>
                @endforelse
            </div>
        </div>
    </section>
</section>

<div id="todoDrawer" class="fixed inset-0 z-[75] hidden" role="dialog" aria-modal="true" aria-labelledby="todoDrawerTitle">
    <button type="button" class="todo-drawer-backdrop absolute inset-0 bg-slate-950/45 backdrop-blur-sm" onclick="closeTodoDrawer()" aria-label="Close task drawer"></button>

    <aside id="todoDrawerPanel" class="absolute inset-y-0 right-0 flex w-full max-w-xl translate-x-full flex-col overflow-hidden border-l border-slate-200 bg-white shadow-2xl transition-transform duration-300">
        <header class="flex shrink-0 items-start justify-between gap-4 border-b border-slate-100 bg-slate-50 px-5 py-5 sm:px-6">
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-blue-600">Action Center</p>
                <h3 id="todoDrawerTitle" class="mt-1 text-lg font-extrabold text-slate-950">Create Task</h3>
            </div>
            <button type="button" onclick="closeTodoDrawer()" class="flex h-10 w-10 items-center justify-center rounded-2xl text-slate-400 transition hover:bg-white hover:text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-100" aria-label="Close task drawer">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </header>

        <form id="todoForm" onsubmit="saveTodo(event)" class="min-h-0 flex-1 overflow-y-auto p-5 sm:p-6">
            @csrf
            <input type="hidden" id="todo_id" name="todo_id">

            <div class="space-y-5">
                <div>
                    <label for="todo_title" class="mb-2 block text-[10px] font-extrabold uppercase tracking-[.14em] text-slate-500">Task Title</label>
                    <input type="text" id="todo_title" name="title" required placeholder="Enter task title" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                </div>

                <div>
                    <label for="todo_desc" class="mb-2 block text-[10px] font-extrabold uppercase tracking-[.14em] text-slate-500">Description</label>
                    <textarea id="todo_desc" name="description" rows="4" placeholder="Add production context" class="block w-full resize-none rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100"></textarea>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="todo_priority" class="mb-2 block text-[10px] font-extrabold uppercase tracking-[.14em] text-slate-500">Priority</label>
                        <select id="todo_priority" name="priority" required class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <div>
                        <label for="todo_due_date" class="mb-2 block text-[10px] font-extrabold uppercase tracking-[.14em] text-slate-500">Due Date</label>
                        <input type="date" id="todo_due_date" name="due_date" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    @if(auth()->user()->isAdmin())
                        <div>
                            <label for="todo_assignee" class="mb-2 block text-[10px] font-extrabold uppercase tracking-[.14em] text-slate-500">Assign To</label>
                            <select id="todo_assignee" name="assigned_to" required class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                                <option value="{{ auth()->id() }}">Self (Admin)</option>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="todo_dept" class="mb-2 block text-[10px] font-extrabold uppercase tracking-[.14em] text-slate-500">Department</label>
                            <select id="todo_dept" name="department_id" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                                <option value="">None / Corporate</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div>
                            <span class="mb-2 block text-[10px] font-extrabold uppercase tracking-[.14em] text-slate-500">Assign To</span>
                            <div class="flex min-h-12 items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-extrabold text-slate-700">{{ auth()->user()->name }}</div>
                        </div>

                        <div>
                            <span class="mb-2 block text-[10px] font-extrabold uppercase tracking-[.14em] text-slate-500">Department</span>
                            <div class="flex min-h-12 items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-extrabold text-slate-700">{{ currentDepartment()->name ?? 'Personal' }}</div>
                        </div>
                    @endif
                </div>

                <div class="rounded-[22px] border border-slate-200 bg-slate-50 p-4">
                    <label class="flex cursor-pointer items-center justify-between gap-4">
                        <span>
                            <span class="block text-sm font-extrabold text-slate-900">Pinned</span>
                            <span class="mt-0.5 block text-xs font-semibold text-slate-500">Keep this task at the top</span>
                        </span>
                        <input id="todo_pinned" name="pinned_ui" type="checkbox" value="1" class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    </label>
                </div>

                <div>
                    <span class="mb-3 block text-[10px] font-extrabold uppercase tracking-[.14em] text-slate-500">Color Label</span>
                    <div class="grid grid-cols-5 gap-2">
                        @foreach([
                            'blue' => '#2563eb',
                            'emerald' => '#10b981',
                            'amber' => '#f59e0b',
                            'rose' => '#e11d48',
                            'slate' => '#94a3b8',
                        ] as $label => $hex)
                            <label class="color-label-option flex cursor-pointer items-center justify-center rounded-2xl border border-slate-200 bg-white p-2 transition hover:border-slate-300">
                                <input type="radio" name="label_color" value="{{ $label }}" class="sr-only" {{ $label === 'blue' ? 'checked' : '' }}>
                                <span class="h-8 w-8 rounded-xl shadow-sm" style="background: {{ $hex }}"></span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>

        <footer class="shrink-0 border-t border-slate-100 bg-white p-4 shadow-[0_-16px_35px_rgba(15,23,42,.06)] sm:p-5">
                <div class="flex gap-3">
                    <button type="button" onclick="closeTodoDrawer()" class="inline-flex min-h-12 flex-1 items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-extrabold text-slate-600 transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-blue-100">
                        Cancel
                    </button>
                    <button type="submit" form="todoForm" id="todoSubmitButton" class="inline-flex min-h-12 flex-1 items-center justify-center rounded-2xl bg-blue-600 px-4 py-3 text-sm font-extrabold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100">
                        Create Task
                    </button>
                </div>
            </footer>
    </aside>
</div>

<script>
    (function () {
        const csrfToken = "{{ csrf_token() }}";
        const pinsKey = 'solcon.todo.pinned';
        const labelsKey = 'solcon.todo.labels';
        const colorMap = {
            blue: '#2563eb',
            emerald: '#10b981',
            amber: '#f59e0b',
            rose: '#e11d48',
            slate: '#94a3b8'
        };

        function readJson(key, fallback) {
            try {
                return JSON.parse(localStorage.getItem(key)) || fallback;
            } catch (error) {
                return fallback;
            }
        }

        function writeJson(key, value) {
            localStorage.setItem(key, JSON.stringify(value));
        }

        function root() {
            return document.getElementById('todoActionCenter');
        }

        function allCards() {
            return Array.from(document.querySelectorAll('.todo-card'));
        }

        function currentFilter() {
            return document.querySelector('[data-todo-filter][aria-pressed="true"]')?.dataset.todoFilter || 'all';
        }

        function updateLaneEmptyStates() {
            document.querySelectorAll('[data-todo-lane]').forEach(function (lane) {
                const visibleCards = Array.from(lane.querySelectorAll('.todo-card')).filter(function (card) {
                    return !card.classList.contains('hidden');
                });
                const empty = lane.querySelector('.todo-lane-empty');
                if (empty) {
                    empty.classList.toggle('hidden', visibleCards.length > 0);
                }
                const counter = document.querySelector(`[data-lane-count="${lane.dataset.todoLane}"]`);
                if (counter) {
                    counter.textContent = Array.from(lane.querySelectorAll('.todo-card')).length;
                }
            });
        }

        function matchesFilter(card, filter) {
            const container = root();
            const currentUser = container?.dataset.currentUser;
            const today = container?.dataset.today;
            const tomorrow = container?.dataset.tomorrow;
            const weekEnd = container?.dataset.weekEnd;
            const status = card.dataset.status;
            const priority = card.dataset.priority;
            const due = card.dataset.due;

            if (filter === 'all') return true;
            if (filter === 'mine') return card.dataset.assignedTo === currentUser;
            if (filter === 'assigned') return card.dataset.createdBy === currentUser && card.dataset.assignedTo !== currentUser;
            if (filter === 'pending') return status !== 'completed';
            if (filter === 'completed') return status === 'completed';
            if (filter === 'high' || filter === 'medium' || filter === 'low') return priority === filter;
            if (filter === 'today') return due === today;
            if (filter === 'tomorrow') return due === tomorrow;
            if (filter === 'week') return due && due >= today && due <= weekEnd;
            return true;
        }

        function applyTodoFilters() {
            const query = (document.getElementById('todoSearchInput')?.value || '').trim().toLowerCase();
            const filter = currentFilter();

            allCards().forEach(function (card) {
                const textMatch = !query || (card.dataset.search || '').includes(query);
                const filterMatch = matchesFilter(card, filter);
                card.classList.toggle('hidden', !(textMatch && filterMatch));
            });

            updateLaneEmptyStates();
        }

        function refreshCounters() {
            const cards = allCards();
            const today = root()?.dataset.today;
            const counts = {
                all: cards.length,
                pending: cards.filter(card => card.dataset.status !== 'completed').length,
                completed_today: cards.filter(card => card.dataset.status === 'completed' && card.dataset.completedToday === '1').length,
                overdue: cards.filter(card => card.dataset.status !== 'completed' && card.dataset.due && card.dataset.due < today).length,
                high: cards.filter(card => card.dataset.status !== 'completed' && card.dataset.priority === 'high').length
            };

            Object.entries(counts).forEach(function ([key, value]) {
                document.querySelectorAll(`[data-todo-count="${key}"]`).forEach(function (node) {
                    node.textContent = value;
                });
            });

            updateLaneEmptyStates();
        }

        function sortLane(lane) {
            const pins = readJson(pinsKey, []);
            const cards = Array.from(lane.querySelectorAll('.todo-card'));
            cards.sort(function (a, b) {
                const aPinned = pins.includes(String(a.dataset.id)) ? 1 : 0;
                const bPinned = pins.includes(String(b.dataset.id)) ? 1 : 0;
                if (aPinned !== bPinned) return bPinned - aPinned;
                return Number(a.dataset.id) - Number(b.dataset.id);
            });
            cards.forEach(card => lane.appendChild(card));
            const empty = lane.querySelector('.todo-lane-empty');
            if (empty) lane.appendChild(empty);
        }

        function sortAllLanes() {
            document.querySelectorAll('[data-todo-lane]').forEach(sortLane);
            updateLaneEmptyStates();
        }

        function applyPinnedState() {
            const pins = readJson(pinsKey, []);
            const labels = readJson(labelsKey, {});

            allCards().forEach(function (card) {
                const id = String(card.dataset.id);
                const pinned = pins.includes(id);
                const pinButton = card.querySelector('.todo-pin-btn');
                card.classList.toggle('is-pinned', pinned);
                card.dataset.pinned = pinned ? '1' : '0';
                if (pinButton) {
                    pinButton.setAttribute('aria-pressed', pinned ? 'true' : 'false');
                    pinButton.classList.toggle('is-active', pinned);
                }
                const labelColor = labels[id] || 'blue';
                card.dataset.labelColor = labelColor;
                card.style.setProperty('--todo-label-color', colorMap[labelColor] || colorMap.blue);
            });

            sortAllLanes();
        }

        function applyCardStatus(card, status) {
            const statusText = status === 'in_progress' ? 'In Progress' : status.charAt(0).toUpperCase() + status.slice(1);
            const statusClasses = {
                pending: 'border-amber-100 bg-amber-50 text-amber-700',
                in_progress: 'border-blue-100 bg-blue-50 text-blue-700',
                completed: 'border-emerald-100 bg-emerald-50 text-emerald-700'
            };

            card.dataset.status = status;
            card.classList.toggle('is-completed', status === 'completed');
            card.classList.toggle('opacity-75', status === 'completed');
            card.dataset.completedToday = status === 'completed' ? '1' : '0';

            card.querySelector('.todo-title')?.classList.toggle('line-through', status === 'completed');
            card.querySelector('.todo-title')?.classList.toggle('text-slate-400', status === 'completed');
            card.querySelector('.todo-description')?.classList.toggle('line-through', status === 'completed');
            card.querySelector('.todo-description')?.classList.toggle('text-slate-300', status === 'completed');

            const check = card.querySelector('.todo-check');
            if (check) {
                check.className = status === 'completed'
                    ? 'todo-check mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl border transition focus:outline-none focus:ring-4 focus:ring-blue-100 border-emerald-200 bg-emerald-500 text-white'
                    : 'todo-check mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl border transition focus:outline-none focus:ring-4 focus:ring-blue-100 border-slate-300 bg-white text-transparent hover:border-blue-400 hover:text-blue-500';
            }

            const badge = card.querySelector('.todo-status-badge');
            if (badge) {
                badge.className = `todo-status-badge inline-flex items-center rounded-full border px-2.5 py-1 text-[10px] font-extrabold ${statusClasses[status] || statusClasses.pending}`;
                badge.textContent = statusText;
            }

            const lane = document.querySelector(`[data-todo-lane="${status}"]`);
            if (lane && card.parentElement !== lane) {
                lane.appendChild(card);
            }

            sortAllLanes();
            refreshCounters();
            applyTodoFilters();
        }

        window.openTodoDrawer = function () {
            const drawer = document.getElementById('todoDrawer');
            const panel = document.getElementById('todoDrawerPanel');
            const form = document.getElementById('todoForm');
            form?.reset();
            document.getElementById('todo_id').value = '';
            document.getElementById('todoDrawerTitle').textContent = 'Create Task';
            document.getElementById('todoSubmitButton').textContent = 'Create Task';
            document.querySelector('input[name="label_color"][value="blue"]')?.click();
            drawer.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            window.setTimeout(function () {
                panel.classList.remove('translate-x-full');
                document.getElementById('todo_title')?.focus();
            }, 20);
        };

        window.closeTodoDrawer = function () {
            const drawer = document.getElementById('todoDrawer');
            const panel = document.getElementById('todoDrawerPanel');
            panel.classList.add('translate-x-full');
            window.setTimeout(function () {
                drawer.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 260);
        };

        window.openTodoModal = window.openTodoDrawer;
        window.closeTodoModal = window.closeTodoDrawer;

        window.editTodo = function (todo) {
            if (todo.is_marketing || String(todo.id).startsWith('mkt_')) {
                const mktId = todo.marketing_order_id || String(todo.id).replace('mkt_', '');
                window.location.href = `/marketing/orders?open=${mktId}`;
                return;
            }
            window.openTodoDrawer();
            const labels = readJson(labelsKey, {});
            const pins = readJson(pinsKey, []);

            document.getElementById('todo_id').value = todo.id;
            document.getElementById('todo_title').value = todo.title || '';
            document.getElementById('todo_desc').value = todo.description || '';
            document.getElementById('todo_priority').value = todo.priority || 'medium';
            document.getElementById('todo_due_date').value = todo.due_date ? new Date(todo.due_date).toISOString().split('T')[0] : '';
            document.getElementById('todo_pinned').checked = pins.includes(String(todo.id));
            document.getElementById('todoDrawerTitle').textContent = 'Edit Task';
            document.getElementById('todoSubmitButton').textContent = 'Save Changes';

            if (document.getElementById('todo_assignee')) {
                document.getElementById('todo_assignee').value = todo.assigned_to;
            }
            if (document.getElementById('todo_dept')) {
                document.getElementById('todo_dept').value = todo.department_id || '';
            }
            const label = labels[String(todo.id)] || 'blue';
            document.querySelector(`input[name="label_color"][value="${label}"]`)?.click();
        };

        window.saveTodo = function (event) {
            event.preventDefault();
            const id = document.getElementById('todo_id').value;
            const url = id ? `/todos/${id}` : '/todos';
            const method = id ? 'PUT' : 'POST';
            const submitButton = document.getElementById('todoSubmitButton');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';

            $.ajax({
                url: url,
                method: method,
                data: $('#todoForm').serialize(),
                success: function (response) {
                    const todoId = String(response.todo?.id || id);
                    const pins = readJson(pinsKey, []);
                    const labels = readJson(labelsKey, {});
                    const shouldPin = document.getElementById('todo_pinned').checked;
                    const selectedLabel = document.querySelector('input[name="label_color"]:checked')?.value || 'blue';

                    if (todoId) {
                        const nextPins = pins.filter(item => item !== todoId);
                        if (shouldPin) nextPins.push(todoId);
                        writeJson(pinsKey, nextPins);
                        labels[todoId] = selectedLabel;
                        writeJson(labelsKey, labels);
                    }

                    closeTodoDrawer();
                    Swal.fire({
                        title: 'Saved',
                        text: response.message,
                        icon: 'success',
                        confirmButtonColor: '#2563eb',
                        timer: 900,
                        showConfirmButton: false
                    }).then(function () {
                        window.location.reload();
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        title: 'Unable to save task',
                        text: xhr.responseJSON ? xhr.responseJSON.message : 'Please check the task details.',
                        icon: 'error',
                        confirmButtonColor: '#2563eb'
                    });
                },
                complete: function () {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
            });
        };

        window.setTodoStatus = function (id, status) {
            if (String(id).startsWith('mkt_')) {
                const mktId = id.replace('mkt_', '');
                if (status === 'completed' && !isAdmin) {
                    Swal.fire({
                        title: 'Permission Denied',
                        text: 'Only Administrators can mark marketing orders as Completed.',
                        icon: 'error',
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        window.location.reload();
                    });
                    return;
                }
                $.ajax({
                    url: `/marketing/orders/${mktId}/status`,
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        status: status
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Status Updated',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#2563eb',
                            timer: 900,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON?.message || 'Failed to update order status.';
                        Swal.fire('Error', msg, 'error').then(() => {
                            window.location.reload();
                        });
                    }
                });
                return;
            }
            const card = document.querySelector(`.todo-card[data-id="${id}"]`);
            $.ajax({
                url: `/todos/${id}/status`,
                method: 'POST',
                data: {
                    _token: csrfToken,
                    status: status
                },
                success: function (response) {
                    if (card) applyCardStatus(card, response.todo?.status || status);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1400,
                        timerProgressBar: true
                    });
                    Toast.fire({ icon: 'success', title: response.message });
                },
                error: function (xhr) {
                    if (card) {
                        const originalStatus = card.dataset.status;
                        const lane = document.querySelector(`[data-todo-lane="${originalStatus}"]`);
                        lane?.appendChild(card);
                    }
                    Swal.fire({
                        title: 'Unable to update task',
                        text: xhr.responseJSON ? xhr.responseJSON.message : 'Status was not changed.',
                        icon: 'error',
                        confirmButtonColor: '#2563eb'
                    });
                }
            });
        };

        window.toggleTodoStatus = function (id) {
            const card = document.querySelector(`.todo-card[data-id="${id}"]`);
            const nextStatus = card?.dataset.status === 'completed' ? 'pending' : 'completed';
            window.setTodoStatus(id, nextStatus);
        };

        window.reorderTodo = function () {
            sortAllLanes();
        };

        window.toggleTodoPin = function (id) {
            const pins = readJson(pinsKey, []);
            const key = String(id);
            const nextPins = pins.includes(key) ? pins.filter(item => item !== key) : pins.concat(key);
            writeJson(pinsKey, nextPins);
            applyPinnedState();
        };

        window.deleteTodo = function (id) {
            if (String(id).startsWith('mkt_')) {
                const mktId = id.replace('mkt_', '');
                Swal.fire({
                    title: 'Cancel this order?',
                    text: 'Are you sure you want to cancel this marketing order?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Cancel'
                }).then(function (result) {
                    if (!result.isConfirmed) return;
                    $.ajax({
                        url: `/marketing/orders/${mktId}`,
                        method: 'DELETE',
                        data: {
                            _token: csrfToken,
                            cancel_reason: 'Cancelled from Dashboard'
                        },
                        success: function (response) {
                            Swal.fire({
                                title: 'Cancelled',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#2563eb',
                                timer: 900,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Failed to cancel order.';
                            Swal.fire('Error', msg, 'error');
                        }
                    });
                });
                return;
            }
            Swal.fire({
                title: 'Delete this task?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Delete'
            }).then(function (result) {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: `/todos/${id}`,
                    method: 'DELETE',
                    data: { _token: csrfToken },
                    success: function (response) {
                        const card = document.querySelector(`.todo-card[data-id="${id}"]`);
                        if (card) {
                            card.style.transform = 'scale(.98)';
                            card.style.opacity = '0';
                            window.setTimeout(function () {
                                card.remove();
                                refreshCounters();
                                applyTodoFilters();
                            }, 180);
                        }
                        Swal.fire({
                            title: 'Deleted',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#2563eb',
                            timer: 900,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Unable to delete task',
                            text: xhr.responseJSON ? xhr.responseJSON.message : 'Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#2563eb'
                        });
                    }
                });
            });
        };

        function initFilters() {
            document.querySelectorAll('[data-todo-filter]').forEach(function (button) {
                button.addEventListener('click', function () {
                    document.querySelectorAll('[data-todo-filter]').forEach(function (btn) {
                        btn.setAttribute('aria-pressed', 'false');
                        btn.classList.remove('border-blue-200', 'bg-blue-50', 'text-blue-700');
                        btn.classList.add('border-slate-200', 'bg-white', 'text-slate-500');
                    });
                    button.setAttribute('aria-pressed', 'true');
                    button.classList.add('border-blue-200', 'bg-blue-50', 'text-blue-700');
                    button.classList.remove('border-slate-200', 'bg-white', 'text-slate-500');
                    applyTodoFilters();
                });
            });

            document.getElementById('todoSearchInput')?.addEventListener('input', applyTodoFilters);
        }

        function initSortable() {
            if (!window.Sortable) return;
            document.querySelectorAll('[data-todo-lane]').forEach(function (lane) {
                new window.Sortable(lane, {
                    group: 'solcon-todos',
                    animation: 180,
                    handle: '.todo-drag-handle',
                    draggable: '.todo-card',
                    ghostClass: 'todo-drag-ghost',
                    chosenClass: 'todo-drag-chosen',
                    delay: 160,
                    delayOnTouchOnly: true,
                    onEnd: function (event) {
                        const card = event.item;
                        const targetStatus = event.to.dataset.todoLane;
                        if (targetStatus && card.dataset.status !== targetStatus) {
                            window.setTodoStatus(card.dataset.id, targetStatus);
                        } else {
                            sortLane(event.to);
                        }
                    }
                });
            });
        }

        function initSwipeActions() {
            allCards().forEach(function (card) {
                let startX = 0;
                let startY = 0;
                let deltaX = 0;

                card.addEventListener('touchstart', function (event) {
                    if (!event.touches.length) return;
                    startX = event.touches[0].clientX;
                    startY = event.touches[0].clientY;
                    deltaX = 0;
                }, { passive: true });

                card.addEventListener('touchmove', function (event) {
                    if (!event.touches.length) return;
                    const moveX = event.touches[0].clientX;
                    const moveY = event.touches[0].clientY;
                    deltaX = moveX - startX;
                    if (Math.abs(deltaX) < 18 || Math.abs(moveY - startY) > 40) return;
                    card.style.transform = `translateX(${Math.max(-96, Math.min(96, deltaX))}px)`;
                }, { passive: true });

                card.addEventListener('touchend', function () {
                    card.style.transform = '';
                    if (deltaX > 82) {
                        window.setTodoStatus(card.dataset.id, 'completed');
                    } else if (deltaX < -82) {
                        window.deleteTodo(card.dataset.id);
                    }
                });
            });
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !document.getElementById('todoDrawer')?.classList.contains('hidden')) {
                closeTodoDrawer();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const drawer = document.getElementById('todoDrawer');
            if (drawer) {
                document.body.appendChild(drawer);
            }
            if (window.autoAnimate) {
                document.querySelectorAll('[data-todo-lane]').forEach(function (lane) {
                    window.autoAnimate(lane, { duration: 180, easing: 'ease-out' });
                });
            }
            initFilters();
            initSortable();
            initSwipeActions();
            applyPinnedState();
            refreshCounters();
        });
    })();
</script>
