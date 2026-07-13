<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    /**
     * Display the Supervisor Dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $liveMachines = DashboardService::getLiveMachineStatus($user->department_id);

        // Fetch Todo Widget Data scoped to this supervisor
        $todos = \App\Models\Todo::with(['assignee', 'creator', 'department'])
            ->where('assigned_to', $user->id)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Fetch Marketing Orders that have shortage and merge them
        $mktService = app(\App\Services\MarketingOrderService::class);
        $mktTodos = $mktService->getDashboardMarketingTodos($user);
        $todos = $todos->concat($mktTodos);

        $todoCounters = [
            'pending' => $todos->filter(fn($t) => in_array($t->status, ['pending', 'in_progress']))->count(),
            'completed_today' => $todos->filter(fn($t) => $t->status === 'completed' && $t->completed_at && $t->completed_at->isToday())->count(),
            'overdue' => $todos->filter(fn($t) => in_array($t->status, ['pending', 'in_progress']) && $t->due_date && $t->due_date->isBefore(today()))->count(),
        ];

        $supervisors = collect();
        $departments = collect();

        return view('supervisor.dashboard', compact(
            'liveMachines',
            'todos',
            'todoCounters',
            'supervisors',
            'departments'
        ));
    }

    /**
     * Live machines status JSON / HTML AJAX refresh.
     */
    public function liveMachines()
    {
        $liveMachines = DashboardService::getLiveMachineStatus(auth()->user()->department_id);
        return view('admin.dashboard.partials.machines', compact('liveMachines'));
    }
}
