<?php

namespace App\Http\Controllers\Dispatch;

use App\Http\Controllers\Controller;
use App\Models\Dispatch;
use App\Models\MarketingOrder;
use App\Services\DispatchService;
use App\Services\DispatchLoadingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DispatchController extends Controller
{
    protected DispatchService $dispatchService;
    protected DispatchLoadingService $loadingService;

    public function __construct(DispatchService $dispatchService, DispatchLoadingService $loadingService)
    {
        $this->dispatchService = $dispatchService;
        $this->loadingService = $loadingService;
    }

    /**
     * Display dispatches listing and dashboard cards.
     */
    public function index(Request $request)
    {
        $metrics = $this->dispatchService->getDashboardMetrics();

        $query = Dispatch::with(['items', 'creator', 'releaser', 'loader'])->orderByDesc('id');

        // Search filter
        if ($search = $request->input('search')) {
            $search = strtolower(trim($search));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(dispatch_number) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(party_name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(vehicle_number) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(city) LIKE ?', ["%{$search}%"]);
            });
        }

        // Type filter
        if ($type = $request->input('dispatch_type')) {
            $query->where('dispatch_type', $type);
        }

        // Status filter
        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Release filter
        if ($request->has('is_released') && $request->input('is_released') !== '') {
            $query->where('is_released', $request->boolean('is_released'));
        }

        // Payment filter
        if ($paymentStatus = $request->input('payment_status')) {
            if ($paymentStatus === 'yes') {
                $query->where('payment_required', true);
            } elseif ($paymentStatus === 'no') {
                $query->where('payment_required', false);
            } elseif (in_array($paymentStatus, ['pending', 'partial', 'paid'])) {
                $query->where('payment_status', $paymentStatus);
            }
        }

        // Date filter
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $dispatches = $query->get();

        return view('dispatch.index', compact('dispatches', 'metrics'));
    }

    /**
     * Show form to create new dispatch planning.
     */
    public function create()
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isMarketing()) {
            abort(403, 'Only Marketing staff or Administrators can create dispatch planning.');
        }

        // Get dispatched marketing order IDs (excluding cancelled dispatches)
        $dispatchedOrderIds = \Illuminate\Support\Facades\DB::table('dispatch_items')
            ->join('dispatches', 'dispatch_items.dispatch_id', '=', 'dispatches.id')
            ->where('dispatches.status', '!=', 'cancelled')
            ->whereNotNull('dispatch_items.marketing_order_id')
            ->pluck('dispatch_items.marketing_order_id')
            ->unique();

        // Get approved marketing orders available for dispatch (not yet assigned to active dispatch)
        $approvedOrders = MarketingOrder::approved()
            ->whereIn('status', ['pending', 'in_progress'])
            ->whereNotIn('id', $dispatchedOrderIds)
            ->with(['items.grade', 'items.color', 'items.epoxyProduct', 'items.epoxyFillerColor', 'items.epoxyComponent', 'items.couponMaterial'])
            ->orderByDesc('id')
            ->get();

        return view('dispatch.create', compact('approvedOrders'));
    }

    /**
     * Store a newly created dispatch planning.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isMarketing()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Marketing staff or Administrators can create dispatch planning.'
            ], 403);
        }

        $validated = $request->validate([
            'dispatch_type' => 'required|in:factory_pickup,crossing_delivery',
            'party_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'place' => 'nullable|string|max:255',
            'full_address' => 'nullable|string',
            'google_map_url' => 'nullable|string',
            'vehicle_number' => 'required|string|max:255',
            'driver_mobile' => 'required|string|max:255',
            'expected_arrival_at' => 'nullable|date',
            'payment_required' => 'nullable|boolean',
            'is_released' => 'nullable|boolean',
            'remarks' => 'nullable|string',
            'marketing_order_ids' => 'nullable|array',
            'marketing_order_ids.*' => 'exists:marketing_orders,id',
            'items' => 'nullable|array',
        ]);

        try {
            $dispatch = $this->dispatchService->createDispatch($validated);

            return response()->json([
                'success' => true,
                'message' => 'Dispatch planning created successfully!',
                'dispatch' => $dispatch,
                'redirect_url' => route('dispatch.show', $dispatch->id),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create dispatch: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display dispatch details card.
     */
    public function show(Dispatch $dispatch)
    {
        $dispatch->load(['items.grade', 'items.color', 'items.epoxyProduct', 'items.epoxyFillerColor', 'items.epoxyComponent', 'items.couponMaterial', 'creator', 'releaser', 'loader', 'statusHistory.changer', 'loadingLogs.user']);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'dispatch' => $dispatch,
            ]);
        }

        return view('dispatch.show', compact('dispatch'));
    }

    /**
     * Show form to edit dispatch planning.
     */
    public function edit(Dispatch $dispatch)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isMarketing()) {
            abort(403, 'Only Marketing staff or Administrators can edit dispatch planning.');
        }

        if ($dispatch->status === 'completed' && !$user->isAdmin()) {
            abort(403, 'Completed dispatches can only be edited by Administrators.');
        }

        $dispatch->load(['items']);

        return view('dispatch.edit', compact('dispatch'));
    }

    /**
     * Update dispatch planning.
     */
    public function update(Request $request, Dispatch $dispatch)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isMarketing()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Marketing staff or Administrators can update dispatch planning.'
            ], 403);
        }

        $validated = $request->validate([
            'dispatch_type' => 'required|in:factory_pickup,crossing_delivery',
            'party_name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'place' => 'nullable|string|max:255',
            'full_address' => 'nullable|string',
            'google_map_url' => 'nullable|string',
            'vehicle_number' => 'required|string|max:255',
            'driver_mobile' => 'required|string|max:255',
            'expected_arrival_at' => 'nullable|date',
            'payment_required' => 'nullable|boolean',
            'is_released' => 'nullable|boolean',
            'remarks' => 'nullable|string',
        ]);

        try {
            $updatedDispatch = $this->dispatchService->updateDispatch($dispatch, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Dispatch planning updated successfully!',
                'dispatch' => $updatedDispatch,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update dispatch: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle release status (Marketing / Admin only).
     */
    public function toggleRelease(Request $request, Dispatch $dispatch)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isMarketing()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Marketing staff or Administrators can change release status.'
            ], 403);
        }

        $request->validate([
            'is_released' => 'required|boolean',
        ]);

        try {
            $updated = $this->dispatchService->toggleRelease($dispatch, $request->boolean('is_released'));

            return response()->json([
                'success' => true,
                'message' => $updated->is_released ? 'Goods successfully Released for loading!' : 'Goods Locked (Hold). Dispatch cannot be loaded.',
                'dispatch' => $updated,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to change release status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update payment requirement status (Marketing / Admin only).
     */
    public function updatePayment(Request $request, Dispatch $dispatch)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isMarketing()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Marketing staff or Administrators can manage payments.'
            ], 403);
        }

        $validated = $request->validate([
            'payment_required' => 'required|boolean',
        ]);

        try {
            $updated = $this->dispatchService->updatePayment($dispatch, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Payment details updated successfully!',
                'dispatch' => $updated,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update dispatch status.
     */
    public function updateStatus(Request $request, Dispatch $dispatch)
    {
        $validated = $request->validate([
            'status' => 'required|in:planned,waiting_for_truck,truck_arrived,loading,completed,cancelled',
            'remarks' => 'nullable|string',
        ]);

        try {
            $updated = $this->dispatchService->changeStatus($dispatch, $validated['status'], $validated['remarks'] ?? null);

            return response()->json([
                'success' => true,
                'message' => 'Dispatch status updated to ' . ucfirst(str_replace('_', ' ', $validated['status'])),
                'dispatch' => $updated,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Loading screen view for Dispatch Department.
     */
    public function loadingScreen(Dispatch $dispatch)
    {
        $dispatch->load(['items.grade', 'items.color', 'items.epoxyProduct', 'items.epoxyFillerColor', 'items.epoxyComponent', 'items.couponMaterial', 'loadingLogs.user', 'releaser']);

        return view('dispatch.loading', compact('dispatch'));
    }

    /**
     * Start loading (Dispatch Department / Admin).
     */
    public function startLoading(Request $request, Dispatch $dispatch)
    {
        try {
            $updated = $this->loadingService->startLoading($dispatch, $request->input('remarks'));

            return response()->json([
                'success' => true,
                'message' => 'Loading process started!',
                'dispatch' => $updated,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Finish loading and deduct Finished Goods stock.
     */
    public function finishLoading(Request $request, Dispatch $dispatch)
    {
        try {
            $updated = $this->loadingService->finishLoading($dispatch, $request->input('remarks'));

            return response()->json([
                'success' => true,
                'message' => 'Loading completed! Finished Goods stock has been deducted.',
                'dispatch' => $updated,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete dispatch planning.
     */
    public function destroy(Dispatch $dispatch)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isMarketing()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Marketing staff or Administrators can delete dispatches.'
            ], 403);
        }

        try {
            $this->dispatchService->deleteDispatch($dispatch);

            return response()->json([
                'success' => true,
                'message' => 'Dispatch deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reports view.
     */
    public function reports(Request $request)
    {
        $query = Dispatch::with(['items', 'creator', 'releaser', 'loader'])->orderByDesc('id');

        if ($vehicle = $request->input('vehicle')) {
            $query->where('vehicle_number', 'like', "%{$vehicle}%");
        }
        if ($driver = $request->input('driver')) {
            $query->where('driver_name', 'like', "%{$driver}%");
        }
        if ($customer = $request->input('customer')) {
            $query->where('party_name', 'like', "%{$customer}%");
        }
        if ($type = $request->input('dispatch_type')) {
            $query->where('dispatch_type', $type);
        }
        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $dispatches = $query->get();

        return view('dispatch.reports', compact('dispatches'));
    }

    /**
     * API to fetch approved orders and calculate totals for create dispatch form.
     */
    public function apiApprovedOrders()
    {
        $orders = MarketingOrder::approved()
            ->whereIn('status', ['pending', 'in_progress'])
            ->with(['items.grade', 'items.color', 'items.epoxyProduct', 'items.epoxyFillerColor', 'items.epoxyComponent', 'items.couponMaterial'])
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }

    /**
     * API endpoint to resolve and preview Google Maps embed URL dynamically.
     */
    public function previewMap(Request $request)
    {
        $url = $request->input('url');
        $embedUrl = Dispatch::resolveGoogleMapEmbedUrl($url);

        return response()->json([
            'success' => true,
            'embed_url' => $embedUrl,
        ]);
    }
}
