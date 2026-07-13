<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index()
    {
        $kpi = DashboardService::getKPIStats();
        $liveMachines = DashboardService::getLiveMachineStatus();
        $lowStock = DashboardService::getLowStockWidgetData();
        $alerts = DashboardService::getDashboardAlerts();
        $activities = DashboardService::getActivityTimeline();
        $chartData = DashboardService::getChartData();

        // Get calendar summaries for the current month
        $currentMonth = now()->format('Y-m');
        $calendarData = DashboardService::getCalendarData($currentMonth);

        // Fetch recent notifications for Admin
        $notifications = \App\Models\Notification::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Fetch Todo Widget Data
        $todos = \App\Models\Todo::with(['assignee', 'creator', 'department'])
            ->orderBy('sort_order', 'asc')
            ->get();

        // Fetch Marketing Orders that have shortage and merge them
        $mktService = app(\App\Services\MarketingOrderService::class);
        $mktTodos = $mktService->getDashboardMarketingTodos(auth()->user());
        $todos = $todos->concat($mktTodos);

        $todoCounters = [
            'pending' => $todos->filter(fn($t) => in_array($t->status, ['pending', 'in_progress']))->count(),
            'completed_today' => $todos->filter(fn($t) => $t->status === 'completed' && $t->completed_at && $t->completed_at->isToday())->count(),
            'overdue' => $todos->filter(fn($t) => in_array($t->status, ['pending', 'in_progress']) && $t->due_date && $t->due_date->isBefore(today()))->count(),
        ];

        $supervisors = \App\Models\User::whereHas('roles', function ($q) {
            $q->where('slug', 'supervisor');
        })->orderBy('name')->get();

        $departments = \App\Models\Department::orderBy('name')->get();

        return view('admin.dashboard', compact(
            'kpi', 
            'liveMachines', 
            'lowStock', 
            'alerts', 
            'activities', 
            'chartData',
            'calendarData',
            'notifications',
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
        $liveMachines = DashboardService::getLiveMachineStatus();
        return view('admin.dashboard.partials.machines', compact('liveMachines'));
    }

    /**
     * Fetch calendar date details for details modal/panel.
     */
    public function calendarDetails(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $details = DashboardService::getCalendarDateDetails($request->input('date'));
        return view('admin.dashboard.partials.calendar_details', compact('details'));
    }
}
