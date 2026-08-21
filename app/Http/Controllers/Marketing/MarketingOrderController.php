<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\MarketingOrder;
use App\Models\MarketingOrderItem;
use App\Services\MarketingOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\EpoxyFillerColor;
use App\Models\Color;
use App\Models\EpoxyProduct;
use App\Models\EpoxyComponent;

class MarketingOrderController extends Controller
{
    protected MarketingOrderService $orderService;

    public function __construct(MarketingOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display the Kanban Board.
     */
    public function index(Request $request)
    {
        if (auth()->user()->isSupervisor()) {
            return redirect()->route('supervisor.orders');
        }

        // Refresh availability on load to ensure accurate data
        try {
            $this->orderService->refreshAllAvailability();
        } catch (\Exception $e) {
            Log::error("Failed to auto-refresh availability: " . $e->getMessage());
        }

        $user = auth()->user();

        // Get orders query
        $ordersQuery = MarketingOrder::orderBy('sort_order', 'asc')
            ->with(['items.grade.brand', 'items.color.brand', 'items.epoxyProduct', 'items.epoxyFillerColor', 'items.epoxyComponent', 'items.couponMaterial', 'creator']);

        if (function_exists('currentBrand') && currentBrand()) {
            $ordersQuery->forBrand(currentBrand());
        }

        // Non-admin users (Marketing role) only see orders created by themselves
        if (!$user->isAdmin()) {
            $ordersQuery->where('created_by', $user->id);
        }

        $orders = $ordersQuery->get();

        $lanes = [
            'pending' => $orders->where('status', 'pending'),
            'in_progress' => $orders->where('status', 'in_progress'),
            'completed' => $orders->where('status', 'completed'),
            'cancelled' => $orders->where('status', 'cancelled'),
        ];

        // Fetch products and coupons for the form modals
        $coupons = $this->orderService->getAvailableCoupons();
        
        $adhesives = $this->orderService->getProductsByDepartment('TAD');
        $grouts = $this->orderService->getProductsByDepartment('GRT');
        $epoxies = $this->orderService->getProductsByDepartment('EPX');

        return view('marketing.orders.index', compact('lanes', 'orders', 'coupons', 'adhesives', 'grouts', 'epoxies'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $coupons = $this->orderService->getAvailableCoupons();
        $adhesives = $this->orderService->getProductsByDepartment('TAD');
        $grouts = $this->orderService->getProductsByDepartment('GRT');
        $epoxies = $this->orderService->getProductsByDepartment('EPX');
        $epoxyColors = EpoxyFillerColor::where('is_active', true)->orderBy('code', 'asc')->get();
        $groutColorsQuery = Color::with('brand')
            ->where('is_active', true)
            ->where('packing_size', '!=', '500 GM');
        if (function_exists('currentBrand') && currentBrand()) {
            $groutColorsQuery->forBrand(currentBrand());
        }
        $groutColors = $groutColorsQuery->orderBy('code', 'asc')->get();

        $epoxyData = $this->getEpoxyProductsAndComponents();

        return view('marketing.orders.create', array_merge([
            'coupons' => $coupons,
            'adhesives' => $adhesives,
            'grouts' => $grouts,
            'epoxies' => $epoxies,
            'epoxyColors' => $epoxyColors,
            'groutColors' => $groutColors,
        ], $epoxyData));
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isMarketing()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Marketing staff or Administrators can create marketing orders.'
            ], 403);
        }

        $validated = $request->validate([
            'party_name' => 'required|string|max:255',
            'vehicle_number' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'remarks' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.department_code' => 'required|in:TAD,GRT,EPX',
            'items.*.grade_id' => 'nullable|required_if:items.*.department_code,TAD|exists:grades,id',
            'items.*.color_id' => 'nullable|required_if:items.*.department_code,GRT|exists:colors,id',
            'items.*.epoxy_product_id' => 'nullable|exists:epoxy_products,id',
            'items.*.epoxy_filler_color_id' => 'nullable|exists:epoxy_filler_colors,id',
            'items.*.epoxy_component_id' => 'nullable|exists:epoxy_components,id',
            'items.*.quantity_bags' => 'required|integer|min:1',
            'items.*.packing' => 'required|string|max:255',
            'items.*.coupon_code' => 'nullable|string|max:255',
            'items.*.coupon_quantity' => 'nullable|integer|min:1',
            'items.*.remarks' => 'nullable|string',
        ]);

        // Validate that each EPX item has either product or component
        foreach ($validated['items'] as $index => $itemData) {
            if ($itemData['department_code'] === 'EPX' && empty($itemData['epoxy_product_id']) && empty($itemData['epoxy_component_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => "Item #" . ($index + 1) . ": Either Epoxy Product or Component must be selected."
                ], 422);
            }
        }

        // Map manual or selected coupon_code to coupon_raw_material_id
        if (!empty($validated['items'])) {
            foreach ($validated['items'] as &$itemData) {
                if (!empty($itemData['coupon_code'])) {
                    $itemData['coupon_raw_material_id'] = $this->resolveCouponRawMaterialId($itemData['coupon_code']);
                }
            }
        }

        try {
            $order = $this->orderService->createOrder($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Marketing order generated successfully!',
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Resolve raw material ID for a coupon string (selected code/name or manually typed).
     */
    protected function resolveCouponRawMaterialId(string $couponInput): ?int
    {
        $couponInput = trim($couponInput);
        if ($couponInput === '') {
            return null;
        }

        $couponRM = \App\Models\RawMaterial::where('is_coupon', true)
            ->where(function ($q) use ($couponInput) {
                $q->where('code', $couponInput)
                  ->orWhere('name', $couponInput)
                  ->orWhere('id', $couponInput)
                  ->orWhereRaw('LOWER(code) = ?', [strtolower($couponInput)])
                  ->orWhereRaw('LOWER(name) = ?', [strtolower($couponInput)]);
            })
            ->first();

        if (!$couponRM) {
            $slug = \Illuminate\Support\Str::slug($couponInput, '-');
            $code = strtoupper($slug) ?: 'CPN-' . rand(1000, 9999);
            
            // Check if code exists to avoid duplicate constraint
            $existingCode = \App\Models\RawMaterial::where('code', $code)->first();
            if ($existingCode) {
                $code .= '-' . rand(10, 99);
            }

            $existingCoupon = \App\Models\RawMaterial::where('is_coupon', true)->first();
            $deptId = $existingCoupon ? $existingCoupon->department_id : (\App\Models\Department::first()?->id ?? 1);
            $unitId = $existingCoupon ? $existingCoupon->stock_unit_id : (\App\Models\Unit::first()?->id ?? 1);

            $couponRM = \App\Models\RawMaterial::create([
                'name' => $couponInput,
                'code' => $code,
                'department_id' => $deptId,
                'stock_unit_id' => $unitId,
                'purchase_unit_id' => $unitId,
                'is_coupon' => true,
                'is_active' => true,
                'current_stock' => 999999,
            ]);
        }

        return $couponRM->id;
    }

    /**
     * Approve a pending order (Admin only).
     */
    public function approve(MarketingOrder $order)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Administrators can approve marketing orders.'
            ], 403);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending orders can be approved.'
            ], 400);
        }

        try {
            $approvedOrder = $this->orderService->approveOrder($order);

            return response()->json([
                'success' => true,
                'message' => 'Order approved successfully! Supervisors have been notified.',
                'order' => $approvedOrder,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show order details.
     */
    public function show(MarketingOrder $order)
    {
        $order->load(['items.grade', 'items.color', 'items.epoxyProduct', 'items.epoxyFillerColor', 'items.epoxyComponent', 'items.couponMaterial', 'creator', 'approver']);
        
        // If JSON requested (e.g. from an AJAX call)
        if (request()->expectsJson()) {
            $itemsData = $order->items->map(function ($item) {
                $availResult = $this->orderService->checkItemAvailability($item);
                return [
                    'id' => $item->id,
                    'department_code' => $item->department_code,
                    'department_label' => $item->department_label,
                    'product_name' => $item->product_name,
                    'packing' => $item->packing,
                    'quantity_bags' => $item->quantity_bags,
                    'quantity_kg' => $item->quantity_kg,
                    'coupon_name' => $item->coupon_name,
                    'coupon_quantity' => $item->coupon_quantity,
                    'is_product_available' => $availResult['product_available'],
                    'is_coupon_available' => $availResult['coupon_available'],
                    'fg_stock' => $availResult['fg_stock'],
                    'coupon_stock' => $availResult['coupon_stock'],
                    'availability_text' => $item->availability_text,
                    'epoxy_filler_color_id' => $item->epoxy_filler_color_id,
                    'epoxy_component_id' => $item->epoxy_component_id,
                    'coupon_code' => $item->couponMaterial->code ?? null,
                ];
            });

            return response()->json([
                'success' => true,
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'party_name' => $order->party_name,
                    'vehicle_number' => $order->vehicle_number,
                    'city' => $order->city,
                    'order_date' => $order->order_date->format('Y-m-d'),
                    'delivery_date' => $order->delivery_date ? $order->delivery_date->format('Y-m-d') : null,
                    'priority' => $order->priority,
                    'priority_label' => $order->priority_info['label'],
                    'priority_color' => $order->priority_info['color'],
                    'status' => $order->status,
                    'status_label' => $order->status_info['label'],
                    'status_color' => $order->status_info['color'],
                    'availability' => $order->availability,
                    'availability_badge' => $order->availability_badge,
                    'remarks' => $order->remarks,
                    'created_by_name' => $order->creator->name ?? 'N/A',
                    'approved_by_name' => $order->approver->name ?? null,
                    'approved_at' => $order->approved_at ? $order->approved_at->format('Y-m-d h:i A') : null,
                    'completed_at' => $order->completed_at ? $order->completed_at->format('Y-m-d h:i A') : null,
                    'cancelled_at' => $order->cancelled_at ? $order->cancelled_at->format('Y-m-d h:i A') : null,
                    'cancel_reason' => $order->cancel_reason,
                    'items' => $itemsData,
                ]
            ]);
        }

        // Prepare data for blade view
        $coupons = $this->orderService->getAvailableCoupons();
        $adhesives = $this->orderService->getProductsByDepartment('TAD');
        $grouts = $this->orderService->getProductsByDepartment('GRT');
        $epoxies = $this->orderService->getProductsByDepartment('EPX');
        $epoxyColors = EpoxyFillerColor::where('is_active', true)->orderBy('code', 'asc')->get();
        $groutColorsQuery = Color::with('brand')
            ->where('is_active', true)
            ->where('packing_size', '!=', '500 GM');
        if (function_exists('currentBrand') && currentBrand()) {
            $groutColorsQuery->forBrand(currentBrand());
        }
        $groutColors = $groutColorsQuery->orderBy('code', 'asc')->get();

        // New dynamic Epoxy products and components
        $solititeProduct = EpoxyProduct::where('code', 'SOL')->first();
        $tilesCleanerProduct = EpoxyProduct::where('code', 'TC')->first();
        $groutAdmixProduct = EpoxyProduct::where('code', 'GA')->first();
        $spacerProduct = EpoxyProduct::where('code', 'SP')->first();
        $levelerProduct = EpoxyProduct::where('code', 'TL')->first();
        $resinKitProduct = EpoxyProduct::where('code', 'RK')->first();
        $resinKit15Product = EpoxyProduct::where('code', 'RK1')->first();
        
        $jariComponents = EpoxyComponent::where('is_active', true)->where('code', 'like', 'EPX-JARI-%')->get();
        $sbPlusComponents = EpoxyComponent::where('is_active', true)->where('code', 'like', 'EPX-SBP-%')->get();
        $sbPlusPlusComponents = EpoxyComponent::where('is_active', true)->where('code', 'like', 'EPX-SBPP-%')->get();
        $skPlusComponents = EpoxyComponent::where('is_active', true)->where('code', 'like', 'EPX-SKP-%')->get();
        
        $spacerComponents = EpoxyComponent::where('is_active', true)->where(function ($q) {
            $q->where('name', 'like', '%SPACER%')->orWhere('code', 'like', '%SP%');
        })->get();

        $levelerComponents = EpoxyComponent::where('is_active', true)->where(function ($q) {
            $q->where('name', 'like', '%CLIP%')
              ->orWhere('name', 'like', '%WEDGE%')
              ->orWhere('name', 'like', '%LEVELLING%')
              ->orWhere('name', 'like', '%TROWEL%')
              ->orWhere('name', 'like', '%PLIER%')
              ->orWhere('name', 'like', '%VACUUM%')
              ->orWhere('name', 'like', '%PLASTIC%')
              ->orWhere('name', 'like', '%STEEL%');
        })->get();

        $epoxyData = $this->getEpoxyProductsAndComponents();

        return view('marketing.orders.show', array_merge([
            'order' => $order,
            'coupons' => $coupons,
            'adhesives' => $adhesives,
            'grouts' => $grouts,
            'epoxies' => $epoxies,
            'epoxyColors' => $epoxyColors,
            'groutColors' => $groutColors,
        ], $epoxyData));
    }

    /**
     * Show the form for editing an order.
     */
    public function edit(MarketingOrder $order)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $order->created_by !== $user->id) {
            abort(403, 'Unauthorized. You can only edit orders created by yourself.');
        }

        $order->load(['items.grade', 'items.color', 'items.epoxyProduct', 'items.epoxyFillerColor', 'items.epoxyComponent', 'items.couponMaterial']);

        $coupons = $this->orderService->getAvailableCoupons();
        $adhesives = $this->orderService->getProductsByDepartment('TAD');
        $grouts = $this->orderService->getProductsByDepartment('GRT');
        $epoxies = $this->orderService->getProductsByDepartment('EPX');
        $epoxyColors = EpoxyFillerColor::where('is_active', true)->orderBy('code', 'asc')->get();
        $groutColorsQuery = Color::with('brand')
            ->where('is_active', true)
            ->where('packing_size', '!=', '500 GM');
        if (function_exists('currentBrand') && currentBrand()) {
            $groutColorsQuery->forBrand(currentBrand());
        }
        $groutColors = $groutColorsQuery->orderBy('code', 'asc')->get();

        $epoxyData = $this->getEpoxyProductsAndComponents();

        return view('marketing.orders.edit', array_merge([
            'order' => $order,
            'coupons' => $coupons,
            'adhesives' => $adhesives,
            'grouts' => $grouts,
            'epoxies' => $epoxies,
            'epoxyColors' => $epoxyColors,
            'groutColors' => $groutColors,
        ], $epoxyData));
    }

    /**
     * Update an order.
     */
    public function update(Request $request, MarketingOrder $order)
    {
        $user = auth()->user();

        // Supervisors cannot edit/update marketing orders
        if (!$user->isAdmin() && !$user->isMarketing()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Marketing staff or Administrators can edit marketing orders.'
            ], 403);
        }

        // Admin can edit anything.
        // Marketing can only edit if created by themselves and status is pending.
        if (!$user->isAdmin()) {
            if ($order->created_by !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You can only edit orders created by yourself.'
                ], 403);
            }
            if ($order->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only Administrators can edit orders that are already in progress or completed.'
                ], 403);
            }
        }

        $validated = $request->validate([
            'party_name' => 'required|string|max:255',
            'vehicle_number' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'remarks' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.department_code' => 'required|in:TAD,GRT,EPX',
            'items.*.grade_id' => 'nullable|required_if:items.*.department_code,TAD|exists:grades,id',
            'items.*.color_id' => 'nullable|required_if:items.*.department_code,GRT|exists:colors,id',
            'items.*.epoxy_product_id' => 'nullable|exists:epoxy_products,id',
            'items.*.epoxy_filler_color_id' => 'nullable|exists:epoxy_filler_colors,id',
            'items.*.epoxy_component_id' => 'nullable|exists:epoxy_components,id',
            'items.*.quantity_bags' => 'required|integer|min:1',
            'items.*.packing' => 'required|string|max:255',
            'items.*.coupon_code' => 'nullable|string|max:255',
            'items.*.coupon_quantity' => 'nullable|integer|min:1',
            'items.*.remarks' => 'nullable|string',
        ]);

        // Validate that each EPX item has either product or component
        foreach ($validated['items'] as $index => $itemData) {
            if ($itemData['department_code'] === 'EPX' && empty($itemData['epoxy_product_id']) && empty($itemData['epoxy_component_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => "Item #" . ($index + 1) . ": Either Epoxy Product or Component must be selected."
                ], 422);
            }
        }

        // Map manual or selected coupon_code to coupon_raw_material_id
        if (!empty($validated['items'])) {
            foreach ($validated['items'] as &$itemData) {
                if (!empty($itemData['coupon_code'])) {
                    $itemData['coupon_raw_material_id'] = $this->resolveCouponRawMaterialId($itemData['coupon_code']);
                }
            }
        }

        try {
            $updatedOrder = $this->orderService->updateOrder($order, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Marketing order updated successfully!',
                'order' => $updatedOrder,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Drag-and-drop status update.
     */
    public function status(Request $request, MarketingOrder $order)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'cancel_reason' => 'nullable|string',
        ]);

        $newStatus = $validated['status'];

        // Edit permission check:
        // Admin sivay koi pan completed/cancelled edit no kari sakvo joy.
        if ($order->status === 'completed' && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Administrators can move or edit completed orders.'
            ], 403);
        }

        // Supervisor or Admin can approve / move pending -> in_progress
        if ($newStatus === 'in_progress' && !$user->isAdmin() && !$user->isSupervisor()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Supervisors or Administrators can move orders to In Progress.'
            ], 403);
        }

        // Only Admin can mark complete
        if ($newStatus === 'completed') {
            if (!$user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only Administrators can mark marketing orders as Completed.'
                ], 403);
            }

            // Deduct finished goods stock & mark complete
            try {
                // Verify finished goods and coupon stock availability first
                foreach ($order->items as $item) {
                    $avail = $this->orderService->checkItemAvailability($item);
                    if (!$avail['product_available']) {
                        return response()->json([
                            'success' => false,
                            'message' => "Insufficient stock for product: {$item->product_name} ({$item->packing}). Available: {$avail['fg_stock']} bags, ordered: {$item->quantity_bags} bags."
                        ], 400);
                    }
                    if ($item->coupon_raw_material_id && !$avail['coupon_available']) {
                        return response()->json([
                            'success' => false,
                            'message' => "Insufficient coupon stock for: {$item->coupon_name}. Available: {$avail['coupon_stock']} PCS, ordered: {$item->coupon_quantity} PCS."
                        ], 400);
                    }
                }

                $this->orderService->completeOrder($order);

                return response()->json([
                    'success' => true,
                    'message' => 'Order marked as Completed! Stock successfully deducted.',
                    'order' => $order,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock deduction failed: ' . $e->getMessage()
                ], 500);
            }
        }

        if ($newStatus === 'cancelled') {
            $this->orderService->cancelOrder($order, $validated['cancel_reason'] ?? 'Cancelled via board');
            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully.',
                'order' => $order,
            ]);
        }

        // Other moves (e.g. pending -> in_progress)
        $this->orderService->changeStatus($order, $newStatus);

        return response()->json([
            'success' => true,
            'message' => "Order status updated to " . ucfirst(str_replace('_', ' ', $newStatus)),
            'order' => $order,
        ]);
    }

    /**
     * Mark Completed from detail view/modal.
     */
    public function complete(MarketingOrder $order)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Administrators can mark marketing orders as Completed.'
            ], 403);
        }

        try {
            // Verify availability
            foreach ($order->items as $item) {
                $avail = $this->orderService->checkItemAvailability($item);
                if (!$avail['product_available']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for product: {$item->product_name} ({$item->packing}). Available: {$avail['fg_stock']} bags, ordered: {$item->quantity_bags} bags."
                    ], 400);
                }
                if ($item->coupon_raw_material_id && !$avail['coupon_available']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient coupon stock for: {$item->coupon_name}. Available: {$avail['coupon_stock']} PCS, ordered: {$item->coupon_quantity} PCS."
                    ], 400);
                }
            }

            $this->orderService->completeOrder($order);

            return response()->json([
                'success' => true,
                'message' => 'Order marked as Completed! Stock successfully deducted.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reorder lanes.
     */
    public function reorder(Request $request, MarketingOrder $order)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array',
        ]);

        try {
            foreach ($validated['order_ids'] as $index => $id) {
                MarketingOrder::where('id', $id)->update(['sort_order' => $index]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order reordered successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refresh all availability.
     */
    public function refresh()
    {
        try {
            $count = $this->orderService->refreshAllAvailability();
            return response()->json([
                'success' => true,
                'message' => "Refreshed stock availability for {$count} active orders."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh availability: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel an order.
     */
    public function destroy(Request $request, MarketingOrder $order)
    {
        $user = auth()->user();

        // Supervisors cannot delete/cancel marketing orders
        if (!$user->isAdmin() && !$user->isMarketing()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Marketing staff or Administrators can delete marketing orders.'
            ], 403);
        }

        // Admin always can delete. Marketing can only delete if created by themselves and pending or in_progress.
        if (!$user->isAdmin()) {
            if ($order->created_by !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You can only delete orders created by yourself.'
                ], 403);
            }
            if (!in_array($order->status, ['pending', 'in_progress'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Marketing staff can only delete pending and in progress orders.'
                ], 403);
            }
        }

        $reason = $request->input('cancel_reason', 'Cancelled by user');

        try {
            $this->orderService->cancelOrder($order, $reason);

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get stock details for a product via API.
     */
    public function productStock(Request $request)
    {
        $validated = $request->validate([
            'department_code' => 'required|in:TAD,GRT,EPX',
            'product_id' => 'nullable|integer|required_without:component_id|prohibited_with:component_id',
            'component_id' => 'nullable|integer|exists:epoxy_components,id|required_without:product_id|prohibited_with:product_id',
            'packing' => 'nullable|string',
            'coupon_raw_material_id' => 'nullable|integer',
        ]);

        $stock = $this->orderService->getProductStock(
            $validated['department_code'],
            isset($validated['product_id']) ? (int) $validated['product_id'] : null,
            $validated['packing'] ?? null,
            !empty($validated['coupon_raw_material_id']) ? (int) $validated['coupon_raw_material_id'] : null,
            isset($validated['component_id']) ? (int) $validated['component_id'] : null,
        );

        return response()->json([
            'success' => true,
            'stock' => $stock
        ]);
    }

    /**
     * Get or auto-seed Epoxy products and components for order views.
     */
    private function getEpoxyProductsAndComponents(): array
    {
        $solititeProduct = EpoxyProduct::firstOrCreate(['code' => 'SOL'], ['name' => 'SOLITITE', 'requires_color' => 0, 'is_active' => 1]);
        $tilesCleanerProduct = EpoxyProduct::firstOrCreate(['code' => 'TC'], ['name' => 'TILES CLEANER', 'requires_color' => 0, 'is_active' => 1]);
        $groutAdmixProduct = EpoxyProduct::firstOrCreate(['code' => 'GA'], ['name' => 'GROUT ADMIX', 'requires_color' => 0, 'is_active' => 1]);
        $spacerProduct = EpoxyProduct::firstOrCreate(['code' => 'SP'], ['name' => 'SPACER', 'requires_color' => 0, 'is_active' => 1]);
        $levelerProduct = EpoxyProduct::firstOrCreate(['code' => 'TL'], ['name' => 'TILES LEVELER', 'requires_color' => 0, 'is_active' => 1]);
        $resinKitProduct = EpoxyProduct::firstOrCreate(['code' => 'RK'], ['name' => 'RESIN KIT 0.3KG', 'requires_color' => 0, 'is_active' => 1]);
        $resinKit15Product = EpoxyProduct::firstOrCreate(['code' => 'RK1'], ['name' => 'RESIN KIT 1.5KG', 'requires_color' => 0, 'is_active' => 1]);

        // Ensure Jari Components exist
        $jariList = [
            ['code' => 'EPX-JARI-SLV', 'name' => 'Jari Powder - Silver'],
            ['code' => 'EPX-JARI-CPR', 'name' => 'Jari Powder - Copper'],
            ['code' => 'EPX-JARI-GLD', 'name' => 'Jari Powder - Gold'],
            ['code' => 'EPX-JARI-RED', 'name' => 'Jari Powder - Red'],
        ];
        foreach ($jariList as $j) {
            EpoxyComponent::firstOrCreate(['code' => $j['code']], ['name' => $j['name'], 'category' => 'Powder', 'purpose' => 'Direct Finished Product', 'unit_id' => 3, 'is_active' => 1]);
        }

        // Ensure SB+ Components exist
        $sbPlusList = [
            ['code' => 'EPX-SBP-1', 'name' => 'SB+ 1 KG'],
            ['code' => 'EPX-SBP-5', 'name' => 'SB+ 5 KG'],
            ['code' => 'EPX-SBP-20', 'name' => 'SB+ 20 KG'],
        ];
        foreach ($sbPlusList as $sb) {
            EpoxyComponent::firstOrCreate(['code' => $sb['code']], ['name' => $sb['name'], 'category' => 'Powder', 'purpose' => 'Direct Finished Product', 'unit_id' => 3, 'is_active' => 1]);
        }

        // Ensure SB++ Components exist
        $sbPlusPlusList = [
            ['code' => 'EPX-SBPP-1', 'name' => 'SB++ 1 KG'],
            ['code' => 'EPX-SBPP-5', 'name' => 'SB++ 5 KG'],
            ['code' => 'EPX-SBPP-20', 'name' => 'SB++ 20 KG'],
        ];
        foreach ($sbPlusPlusList as $sb) {
            EpoxyComponent::firstOrCreate(['code' => $sb['code']], ['name' => $sb['name'], 'category' => 'Powder', 'purpose' => 'Direct Finished Product', 'unit_id' => 3, 'is_active' => 1]);
        }

        // Ensure SK+ Components exist
        $skPlusList = [
            ['code' => 'EPX-SKP-1', 'name' => 'SK+ 1 LTR'],
            ['code' => 'EPX-SKP-5', 'name' => 'SK+ 5 LTR'],
            ['code' => 'EPX-SKP-20', 'name' => 'SK+ 20 LTR'],
        ];
        foreach ($skPlusList as $sk) {
            EpoxyComponent::firstOrCreate(['code' => $sk['code']], ['name' => $sk['name'], 'category' => 'Liquid', 'purpose' => 'Direct Finished Product', 'unit_id' => 3, 'is_active' => 1]);
        }

        // Ensure Spacer Components exist
        $spacerList = [
            ['code' => 'EPX-SP-2MM', 'name' => 'SPACER 2MM'],
            ['code' => 'EPX-SP-3MM', 'name' => 'SPACER 3MM'],
            ['code' => 'EPX-SP-4MM', 'name' => 'SPACER 4MM'],
            ['code' => 'EPX-SP-5MM', 'name' => 'SPACER 5MM'],
            ['code' => 'EPX-SP-6MM', 'name' => 'SPACER 6MM'],
        ];
        foreach ($spacerList as $sp) {
            EpoxyComponent::firstOrCreate(['code' => $sp['code']], ['name' => $sp['name'], 'category' => 'Box', 'purpose' => 'Direct Finished Product', 'unit_id' => 3, 'is_active' => 1]);
        }

        // Ensure Tiles Leveler Components exist
        $levelerList = [
            ['code' => 'EPX-CLIP-2MM', 'name' => 'CLIP 2MM'],
            ['code' => 'EPX-CLIP-3MM', 'name' => 'CLIP 3MM'],
            ['code' => 'EPX-CLIP-4MM', 'name' => 'CLIP 4MM'],
            ['code' => 'EPX-WEDGE', 'name' => 'WEDGE'],
            ['code' => 'EPX-JL', 'name' => 'JACK LEVELLING'],
            ['code' => 'EPX-TROWEL', 'name' => 'TROWEL'],
            ['code' => 'EPX-PLIER', 'name' => 'PLIER'],
            ['code' => 'EPX-VAC', 'name' => 'VACUUM'],
        ];
        foreach ($levelerList as $lvl) {
            EpoxyComponent::firstOrCreate(['code' => $lvl['code']], ['name' => $lvl['name'], 'category' => 'Box', 'purpose' => 'Direct Finished Product', 'unit_id' => 3, 'is_active' => 1]);
        }

        $jariComponents = EpoxyComponent::where('is_active', true)->where('code', 'like', 'EPX-JARI-%')->get();
        $sbPlusComponents = EpoxyComponent::where('is_active', true)->where('code', 'like', 'EPX-SBP-%')->get();
        $sbPlusPlusComponents = EpoxyComponent::where('is_active', true)->where('code', 'like', 'EPX-SBPP-%')->get();
        $skPlusComponents = EpoxyComponent::where('is_active', true)->where('code', 'like', 'EPX-SKP-%')->get();

        $spacerComponents = EpoxyComponent::where('is_active', true)->where(function ($q) {
            $q->where('name', 'like', '%SPACER%')->orWhere('code', 'like', '%SP%');
        })->get();

        $levelerComponents = EpoxyComponent::where('is_active', true)->where(function ($q) {
            $q->where('name', 'like', '%CLIP%')
              ->orWhere('name', 'like', '%WEDGE%')
              ->orWhere('name', 'like', '%LEVELLING%')
              ->orWhere('name', 'like', '%TROWEL%')
              ->orWhere('name', 'like', '%PLIER%')
              ->orWhere('name', 'like', '%VACUUM%')
              ->orWhere('name', 'like', '%PLASTIC%')
              ->orWhere('name', 'like', '%STEEL%');
        })->get();

        $groutAdmixComponent = EpoxyComponent::firstOrCreate(
            ['code' => 'EPX-GA-200GM'],
            ['name' => '200GM Admix', 'category' => 'Box', 'purpose' => 'Direct Finished Product', 'unit_id' => 3, 'is_active' => 1]
        );

        $tilesCleanerComponents = collect([
            ['code' => 'EPX-TC-1LTR', 'name' => 'Tiles Cleaner 1-LTR'],
            ['code' => 'EPX-TC-5LTR', 'name' => 'Tiles Cleaner 5-LTR'],
        ])->mapWithKeys(function (array $component) {
            $model = EpoxyComponent::firstOrCreate(
                ['code' => $component['code']],
                [
                    'name' => $component['name'],
                    'category' => 'Box',
                    'purpose' => 'Direct Finished Product',
                    'unit_id' => 3,
                    'is_active' => 1,
                ]
            );

            return [$model->code => $model];
        });

        return compact(
            'solititeProduct', 'tilesCleanerProduct', 'tilesCleanerComponents', 'groutAdmixProduct', 'groutAdmixComponent', 'spacerProduct',
            'levelerProduct', 'resinKitProduct', 'resinKit15Product', 'jariComponents', 'sbPlusComponents',
            'sbPlusPlusComponents', 'skPlusComponents', 'spacerComponents', 'levelerComponents'
        );
    }
}
