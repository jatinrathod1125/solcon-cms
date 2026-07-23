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

    /**
     * Display approved orders for supervisors.
     */
    public function orders()
    {
        $orders = \App\Models\MarketingOrder::approved()
            ->orderByDesc('approved_at')
            ->with(['items.grade', 'items.color', 'items.epoxyProduct', 'items.epoxyFillerColor', 'items.epoxyComponent', 'items.couponMaterial', 'creator', 'approver'])
            ->get();

        return view('supervisor.orders', compact('orders'));
    }

    /**
     * Mark an approved order as Ready to Dispatch and set dispatch status to completed (Admin only).
     */
    public function readyToDispatch(\App\Models\MarketingOrder $order)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->back()->with('error', 'Only Administrators can mark orders as Ready to Dispatch.');
        }

        if ($order->status === 'completed') {
            return redirect()->back()->with('warning', 'Order is already completed.');
        }

        // Check if order quantity matches completed production quantity (finished goods stock)
        if (!$order->isProductionReady()) {
            return redirect()->back()->with('error', 'Order quantity does not match completed production stock! Cannot mark Ready to Dispatch.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
                // Check if a dispatch already exists for this order
                $dispatchItem = \App\Models\DispatchItem::where('marketing_order_id', $order->id)->first();
                $dispatch = $dispatchItem ? $dispatchItem->dispatch : null;

                if (!$dispatch) {
                    $dispatchService = app(\App\Services\DispatchService::class);
                    $dispatch = $dispatchService->createDispatch([
                        'dispatch_type' => 'factory_pickup',
                        'party_name' => $order->party_name,
                        'city' => $order->city,
                        'vehicle_number' => $order->vehicle_number ?: 'Factory Pickup / Direct',
                        'driver_mobile' => 'N/A',
                        'payment_required' => false,
                        'is_released' => true,
                        'remarks' => "Auto-created via Ready to Dispatch for Order {$order->order_number}",
                        'marketing_order_ids' => [$order->id],
                    ]);
                } else {
                    $dispatch->update([
                        'is_released' => true,
                        'released_by' => auth()->id(),
                        'released_at' => now(),
                    ]);
                }

                // Complete loading and stock deduction
                $loadingService = app(\App\Services\DispatchLoadingService::class);
                $loadingService->finishLoading($dispatch, "Marked Ready to Dispatch & Completed by Admin for Order {$order->order_number}");
            });

            return redirect()->route('dispatch.index')
                ->with('success', "Order #{$order->order_number} marked as Ready to Dispatch! Status set to Completed on Dispatch board.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to complete dispatch: ' . $e->getMessage());
        }
    }
}
