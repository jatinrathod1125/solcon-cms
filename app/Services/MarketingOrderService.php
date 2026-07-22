<?php

namespace App\Services;

use App\Models\MarketingOrder;
use App\Models\MarketingOrderItem;
use App\Models\FinishedGood;
use App\Models\RawMaterial;
use App\Models\Grade;
use App\Models\Color;
use App\Models\EpoxyProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class MarketingOrderService
{
    protected FinishedGoodsService $finishedGoodsService;

    public function __construct(FinishedGoodsService $finishedGoodsService)
    {
        $this->finishedGoodsService = $finishedGoodsService;
    }

    /**
     * Generate a unique order number: MKT-YYYYMMDD-XXX
     */
    public function generateOrderNumber(): string
    {
        $today = Carbon::now('Asia/Kolkata')->format('Ymd');
        $prefix = 'MKT-' . $today . '-';

        $lastOrder = MarketingOrder::where('order_number', 'like', $prefix . '%')
            ->orderByDesc('order_number')
            ->first();

        if ($lastOrder) {
            $lastNum = (int) substr($lastOrder->order_number, -3);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return $prefix . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new marketing order with items.
     */
    public function createOrder(array $data): MarketingOrder
    {
        return DB::transaction(function () use ($data) {
            // Create the order
            $order = MarketingOrder::create([
                'order_number' => $this->generateOrderNumber(),
                'party_name' => $data['party_name'],
                'vehicle_number' => $data['vehicle_number'] ?? null,
                'city' => $data['city'] ?? null,
                'order_date' => $data['order_date'] ?? Carbon::now('Asia/Kolkata')->toDateString(),
                'delivery_date' => $data['delivery_date'] ?? null,
                'priority' => $data['priority'] ?? 'medium',
                'status' => 'pending',
                'availability' => 'unknown',
                'remarks' => $data['remarks'] ?? null,
                'created_by' => auth()->id(),
                'sort_order' => MarketingOrder::where('status', 'pending')->max('sort_order') + 1,
            ]);

            // Create order items
            if (!empty($data['items'])) {
                foreach ($data['items'] as $itemData) {
                    $couponQty = null;
                    if (!empty($itemData['coupon_raw_material_id'])) {
                        $couponQty = $itemData['coupon_quantity'] ?? $itemData['quantity_bags'];
                    }

                    $order->items()->create([
                        'department_code' => $itemData['department_code'],
                        'grade_id' => $itemData['grade_id'] ?? null,
                        'color_id' => $itemData['color_id'] ?? null,
                        'epoxy_product_id' => $itemData['epoxy_product_id'] ?? null,
                        'epoxy_filler_color_id' => $itemData['epoxy_filler_color_id'] ?? null,
                        'epoxy_component_id' => $itemData['epoxy_component_id'] ?? null,
                        'quantity_bags' => $itemData['quantity_bags'],
                        'quantity_kg' => $itemData['quantity_kg'] ?? null,
                        'packing' => $itemData['packing'] ?? null,
                        'coupon_raw_material_id' => $itemData['coupon_raw_material_id'] ?? null,
                        'coupon_quantity' => $couponQty,
                        'remarks' => $itemData['remarks'] ?? null,
                    ]);
                }
            }

            // Refresh availability
            $this->refreshOrderAvailability($order);

            // Populate summary coupon column on order from all items (Adhesive, Epoxy, Grout)
            $couponSummary = $order->items()
                ->with('couponMaterial')
                ->get()
                ->map(fn($i) => $i->coupon_name)
                ->filter(fn($c) => $c && $c !== 'No Coupon' && $c !== 'N/A')
                ->unique()
                ->implode(', ');
            $order->update(['coupon' => $couponSummary ?: null]);

            // Log activity
            ActivityLogService::log(
                'MARKETING_ORDER_CREATED',
                "Marketing order {$order->order_number} created for party: {$order->party_name}",
                auth()->id()
            );

            return $order->fresh(['items.grade', 'items.color', 'items.epoxyProduct', 'items.couponMaterial', 'creator']);
        });
    }

    /**
     * Update an existing marketing order.
     */
    public function updateOrder(MarketingOrder $order, array $data): MarketingOrder
    {
        return DB::transaction(function () use ($order, $data) {
            // Update order header
            $order->update([
                'party_name' => $data['party_name'] ?? $order->party_name,
                'vehicle_number' => $data['vehicle_number'] ?? $order->vehicle_number,
                'city' => $data['city'] ?? $order->city,
                'order_date' => $data['order_date'] ?? $order->order_date,
                'priority' => $data['priority'] ?? $order->priority,
                'remarks' => $data['remarks'] ?? $order->remarks,
                'is_edited' => true,
            ]);

            // If items provided, compare existing items to flag ONLY modified/new products
            if (isset($data['items'])) {
                // Key existing items by a unique product signature
                $existingItemsMap = $order->items->mapWithKeys(function ($item) {
                    $key = sprintf(
                        '%s_%s_%s_%s_%s_%s',
                        $item->department_code,
                        $item->grade_id ?? 0,
                        $item->color_id ?? 0,
                        $item->epoxy_product_id ?? 0,
                        $item->epoxy_filler_color_id ?? 0,
                        $item->epoxy_component_id ?? 0
                    );
                    return [$key => [
                        'quantity_bags' => (int) $item->quantity_bags,
                        'packing' => (string) ($item->packing ?? ''),
                        'coupon_raw_material_id' => $item->coupon_raw_material_id ?? null,
                        'was_edited' => (bool) $item->is_edited,
                    ]];
                });

                $order->items()->delete();

                foreach ($data['items'] as $itemData) {
                    $couponQty = null;
                    if (!empty($itemData['coupon_raw_material_id'])) {
                        $couponQty = $itemData['coupon_quantity'] ?? $itemData['quantity_bags'];
                    }

                    $productKey = sprintf(
                        '%s_%s_%s_%s_%s_%s',
                        $itemData['department_code'],
                        $itemData['grade_id'] ?? 0,
                        $itemData['color_id'] ?? 0,
                        $itemData['epoxy_product_id'] ?? 0,
                        $itemData['epoxy_filler_color_id'] ?? 0,
                        $itemData['epoxy_component_id'] ?? 0
                    );

                    $isItemChanged = true;
                    if ($existingItemsMap->has($productKey)) {
                        $old = $existingItemsMap->get($productKey);
                        $newQty = (int) $itemData['quantity_bags'];
                        $newPacking = (string) ($itemData['packing'] ?? '');
                        $newCoupon = $itemData['coupon_raw_material_id'] ?? null;

                        if (
                            $old['quantity_bags'] === $newQty &&
                            $old['packing'] === $newPacking &&
                            $old['coupon_raw_material_id'] == $newCoupon
                        ) {
                            $isItemChanged = $old['was_edited'];
                        }
                    }

                    $order->items()->create([
                        'department_code' => $itemData['department_code'],
                        'grade_id' => $itemData['grade_id'] ?? null,
                        'color_id' => $itemData['color_id'] ?? null,
                        'epoxy_product_id' => $itemData['epoxy_product_id'] ?? null,
                        'epoxy_filler_color_id' => $itemData['epoxy_filler_color_id'] ?? null,
                        'epoxy_component_id' => $itemData['epoxy_component_id'] ?? null,
                        'quantity_bags' => $itemData['quantity_bags'],
                        'quantity_kg' => $itemData['quantity_kg'] ?? null,
                        'packing' => $itemData['packing'] ?? null,
                        'coupon_raw_material_id' => $itemData['coupon_raw_material_id'] ?? null,
                        'coupon_quantity' => $couponQty,
                        'is_edited' => $isItemChanged,
                        'remarks' => $itemData['remarks'] ?? null,
                    ]);
                }
            }

            // Refresh availability
            $this->refreshOrderAvailability($order);

            // Populate summary coupon column on order from all items (Adhesive, Epoxy, Grout)
            $couponSummary = $order->items()
                ->with('couponMaterial')
                ->get()
                ->map(fn($i) => $i->coupon_name)
                ->filter(fn($c) => $c && $c !== 'No Coupon' && $c !== 'N/A')
                ->unique()
                ->implode(', ');
            $order->update(['coupon' => $couponSummary ?: null]);

            ActivityLogService::log(
                'MARKETING_ORDER_UPDATED',
                "Marketing order {$order->order_number} updated",
                auth()->id()
            );

            return $order->fresh(['items.grade', 'items.color', 'items.epoxyProduct', 'items.couponMaterial', 'creator']);
        });
    }

    /**
     * Change order status (for drag-drop).
     */
    public function changeStatus(MarketingOrder $order, string $newStatus): MarketingOrder
    {
        return DB::transaction(function () use ($order, $newStatus) {
            $oldStatus = $order->status;
            $updateData = ['status' => $newStatus];

            // Set sort_order for the new lane
            $maxSort = MarketingOrder::where('status', $newStatus)->max('sort_order');
            $updateData['sort_order'] = ($maxSort ?? 0) + 1;

            if ($newStatus === 'in_progress' && !$order->approved_by) {
                $updateData['approved_by'] = auth()->id();
                $updateData['approved_at'] = now();
            }

            if ($newStatus === 'cancelled') {
                $updateData['cancelled_at'] = now();
            }

            $order->update($updateData);

            ActivityLogService::log(
                'MARKETING_ORDER_STATUS_CHANGED',
                "Marketing order {$order->order_number} status changed from {$oldStatus} to {$newStatus}",
                auth()->id()
            );

            return $order->fresh();
        });
    }

    /**
     * Approve an order (Admin only). Sets status to in_progress.
     */
    public function approveOrder(MarketingOrder $order): MarketingOrder
    {
        return DB::transaction(function () use ($order) {
            $maxSort = MarketingOrder::where('status', 'in_progress')->max('sort_order');

            $order->update([
                'status' => 'in_progress',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'sort_order' => ($maxSort ?? 0) + 1,
            ]);

            ActivityLogService::log(
                'MARKETING_ORDER_APPROVED',
                "Marketing order {$order->order_number} approved for party: {$order->party_name}",
                auth()->id()
            );

            // Send notification to all supervisors
            try {
                $notificationService = app(\App\Services\NotificationService::class);
                $supervisors = \App\Models\User::whereHas('roles', function ($q) {
                    $q->where('slug', 'supervisor');
                })->where('is_active', true)->get();

                foreach ($supervisors as $supervisor) {
                    $notificationService->sendToUser(
                        $supervisor,
                        'New Order Approved: ' . $order->order_number,
                        "Order for {$order->party_name} ({$order->city}) has been approved. Priority: " . ucfirst($order->priority),
                        'marketing_order_approved',
                        null,
                        ['order_id' => $order->id, 'click_url' => '/supervisor/orders']
                    );
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send order approval notification: ' . $e->getMessage());
            }

            return $order->fresh(['items.grade', 'items.color', 'items.epoxyProduct', 'items.couponMaterial', 'creator']);
        });
    }

    /**
     * Complete an order and deduct finished goods.
     */
    public function completeOrder(MarketingOrder $order): MarketingOrder
    {
        return DB::transaction(function () use ($order) {
            // Deduct finished goods for each item
            foreach ($order->items as $item) {
                $this->deductFinishedGoods($item, $order->order_number);
                $item->update(['item_status' => 'completed']);
            }

            $order->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            ActivityLogService::log(
                'MARKETING_ORDER_COMPLETED',
                "Marketing order {$order->order_number} completed. Finished goods deducted for party: {$order->party_name}",
                auth()->id()
            );

            return $order->fresh(['items']);
        });
    }

    /**
     * Deduct finished goods for a single order item.
     */
    protected function deductFinishedGoods(MarketingOrderItem $item, string $orderNumber): void
    {
        // Find matching finished good
        $fgQuery = FinishedGood::query();

        switch ($item->department_code) {
            case 'TAD':
                $fgQuery->where('grade_id', $item->grade_id)
                        ->where('coupon_raw_material_id', $item->coupon_raw_material_id);
                break;
            case 'GRT':
                $fgQuery->where('color_id', $item->color_id);
                break;
            case 'EPX':
                if ($item->epoxy_component_id) {
                    $fgQuery->where('epoxy_component_id', $item->epoxy_component_id);
                } else {
                    $fgQuery->where('epoxy_product_id', $item->epoxy_product_id);
                    if ($item->epoxy_filler_color_id) {
                        $fgQuery->where('epoxy_filler_color_id', $item->epoxy_filler_color_id);
                    }
                }
                break;
        }

        if ($item->packing) {
            $fgQuery->where('packing', $item->packing);
        }

        $finishedGood = $fgQuery->first();

        if ($finishedGood && $finishedGood->available_bags >= $item->quantity_bags) {
            $this->finishedGoodsService->adjustStock(
                $finishedGood->id,
                'decrease',
                $item->quantity_bags,
                $item->quantity_kg,
                "Marketing Order {$orderNumber}",
                "Auto-deducted for order {$orderNumber}, Party: {$item->order->party_name}"
            );
        }
    }

    /**
     * Check and cache availability for a single order item.
     */
    public function checkItemAvailability(MarketingOrderItem $item): array
    {
        // Check product availability in finished goods
        $fgQuery = FinishedGood::query();

        switch ($item->department_code) {
            case 'TAD':
                $fgQuery->where('grade_id', $item->grade_id)
                        ->where('coupon_raw_material_id', $item->coupon_raw_material_id);
                break;
            case 'GRT':
                $fgQuery->where('color_id', $item->color_id);
                break;
            case 'EPX':
                if ($item->epoxy_component_id) {
                    $fgQuery->where('epoxy_component_id', $item->epoxy_component_id);
                } else {
                    $fgQuery->where('epoxy_product_id', $item->epoxy_product_id);
                    if ($item->epoxy_filler_color_id) {
                        $fgQuery->where('epoxy_filler_color_id', $item->epoxy_filler_color_id);
                    }
                }
                break;
        }

        if ($item->packing) {
            $fgQuery->where('packing', $item->packing);
        }

        $fg = $fgQuery->first();
        $fgStock = $fg ? (int) $fg->available_bags : 0;
        $productAvailable = $fgStock >= $item->quantity_bags;

        // Check coupon availability
        $couponAvailable = null;
        $couponStock = null;

        if ($item->coupon_raw_material_id) {
            $coupon = RawMaterial::find($item->coupon_raw_material_id);
            $couponStock = $coupon ? (int) $coupon->current_stock : 0;
            $couponQty = $item->coupon_quantity ?? $item->quantity_bags;
            $couponAvailable = $couponStock >= $couponQty;
        }

        // Update cached values
        $item->update([
            'is_product_available' => $productAvailable,
            'is_coupon_available' => $couponAvailable,
        ]);

        return [
            'product_available' => $productAvailable,
            'coupon_available' => $couponAvailable,
            'fg_stock' => $fgStock,
            'coupon_stock' => $couponStock,
        ];
    }

    /**
     * Refresh availability for all items in an order.
     */
    public function refreshOrderAvailability(MarketingOrder $order): string
    {
        $order->load('items');
        $allAvailable = true;
        $noneAvailable = true;

        foreach ($order->items as $item) {
            $result = $this->checkItemAvailability($item);

            $productOk = $result['product_available'];
            $couponOk = $result['coupon_available'] === null ? true : $result['coupon_available'];

            if ($productOk && $couponOk) {
                $noneAvailable = false;
            } else {
                $allAvailable = false;
            }
        }

        if ($order->items->isEmpty()) {
            $availability = 'unknown';
        } elseif ($allAvailable) {
            $availability = 'available';
        } elseif ($noneAvailable) {
            $availability = 'unavailable';
        } else {
            $availability = 'partial';
        }

        $order->update(['availability' => $availability]);

        return $availability;
    }

    /**
     * Refresh availability for all pending/in-progress orders.
     */
    public function refreshAllAvailability(): int
    {
        $orders = MarketingOrder::whereIn('status', ['pending', 'in_progress'])
            ->with('items')
            ->get();

        foreach ($orders as $order) {
            $this->refreshOrderAvailability($order);
        }

        return $orders->count();
    }

    /**
     * Get all coupon raw materials.
     */
    public function getAvailableCoupons(): Collection
    {
        return RawMaterial::where('is_coupon', true)
            ->where('is_active', true)
            ->select('id', 'name', 'code', 'current_stock', 'stock_unit_id')
            ->with('stockUnit:id,code')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get products by department code.
     */
    public function getProductsByDepartment(string $deptCode): Collection
    {
        return match ($deptCode) {
            'TAD' => Grade::where('is_active', true)
                ->with('bagSize:id,name,value')
                ->select('id', 'name', 'code', 'bag_size_id')
                ->orderBy('name')
                ->get(),
            'GRT' => Color::where('is_active', true)
                ->select('id', 'name', 'code', 'packing_size')
                ->orderBy('name')
                ->get(),
            'EPX' => EpoxyProduct::where('is_active', true)
                ->select('id', 'name', 'code')
                ->orderBy('name')
                ->get(),
            default => collect(),
        };
    }

    /**
     * Get finished goods stock for a specific product.
     */
    public function getProductStock(string $deptCode, int $productId, ?string $packing = null, ?int $couponRawMaterialId = null): array
    {
        $query = FinishedGood::query();

        switch ($deptCode) {
            case 'TAD':
                $query->where('grade_id', $productId)
                      ->where('coupon_raw_material_id', $couponRawMaterialId);
                break;
            case 'GRT':
                $query->where('color_id', $productId);
                break;
            case 'EPX':
                $query->where('epoxy_product_id', $productId);
                break;
        }

        if ($packing) {
            $query->where('packing', $packing);
        }

        $fg = $query->first();

        return [
            'available_bags' => $fg ? (int) $fg->available_bags : 0,
            'available_weight' => $fg ? (float) $fg->available_weight : 0,
            'status' => $fg ? $fg->formatted_status : 'No Record',
        ];
    }

    /**
     * Cancel an order.
     */
    public function cancelOrder(MarketingOrder $order, ?string $reason = null): MarketingOrder
    {
        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancel_reason' => $reason,
        ]);

        ActivityLogService::log(
            'MARKETING_ORDER_CANCELLED',
            "Marketing order {$order->order_number} cancelled. Reason: {$reason}",
            auth()->id()
        );

        return $order->fresh();
    }

    /**
     * Get marketing orders represented as virtual todo items for the dashboard.
     * Shows only active orders (pending, in_progress) that are NOT fully available.
     */
    public function getDashboardMarketingTodos($user): Collection
    {
        $query = MarketingOrder::whereIn('status', ['pending', 'in_progress'])
            ->whereIn('availability', ['unavailable', 'partial'])
            ->with(['items.grade', 'items.color', 'items.epoxyProduct', 'creator']);

        if (!$user->isAdmin()) {
            // Supervisor: Filter by their assigned department codes
            $deptIds = $user->departmentIds();
            $deptCodes = \App\Models\Department::whereIn('id', $deptIds)->pluck('code')->toArray();
            
            $query->whereHas('items', function ($q) use ($deptCodes) {
                $q->whereIn('department_code', $deptCodes);
            });
        }

        return $query->get()->map(function ($order) use ($user) {
            $todo = new \App\Models\Todo();
            
            // Set properties
            $todo->id = 'mkt_' . $order->id; // prefix to avoid collissions
            $todo->title = "Prod. Shortage: " . $order->order_number . " (" . $order->party_name . ")";
            $todo->description = "Shortage: " . $order->items_summary . ($order->vehicle_number ? " | Vehicle: " . $order->vehicle_number : "");
            $todo->priority = $order->priority;
            $todo->status = $order->status;
            $todo->due_date = $order->order_date;
            $todo->completed_at = $order->completed_at;
            $todo->created_by = $order->created_by;
            
            // If supervisor viewing, set assigned_to as supervisor user id to appear in filters
            $todo->assigned_to = !$user->isAdmin() ? $user->id : null;
            
            // Flag and original ID
            $todo->is_marketing = true;
            $todo->marketing_order_id = $order->id;

            // Mapped relationships
            $firstItem = $order->items->first();
            if ($firstItem) {
                $dept = \App\Models\Department::where('code', $firstItem->department_code)->first();
                if ($dept) {
                    $todo->department_id = $dept->id;
                    $todo->setRelation('department', $dept);
                }
            }

            $todo->setRelation('creator', $order->creator);
            $todo->setRelation('assignee', $user); // mock assignee to prevent null errors

            return $todo;
        });
    }
}
