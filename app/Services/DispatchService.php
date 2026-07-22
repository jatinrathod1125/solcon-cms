<?php

namespace App\Services;

use App\Models\Dispatch;
use App\Models\DispatchItem;
use App\Models\DispatchStatusHistory;
use App\Models\DispatchLoadingLog;
use App\Models\MarketingOrder;
use App\Models\MarketingOrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class DispatchService
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Generate unique dispatch number: DISP-YYYYMMDD-001
     */
    public function generateDispatchNumber(): string
    {
        $today = Carbon::now('Asia/Kolkata')->format('Ymd');
        $prefix = 'DISP-' . $today . '-';

        $lastDispatch = Dispatch::where('dispatch_number', 'like', $prefix . '%')
            ->orderByDesc('dispatch_number')
            ->first();

        if ($lastDispatch) {
            $lastNum = (int) substr($lastDispatch->dispatch_number, -3);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return $prefix . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new dispatch planning.
     */
    public function createDispatch(array $data): Dispatch
    {
        return DB::transaction(function () use ($data) {
            $partyName = $data['party_name'] ?? null;
            $city = $data['city'] ?? null;

            if (!empty($data['marketing_order_ids']) && is_array($data['marketing_order_ids'])) {
                $orders = MarketingOrder::whereIn('id', $data['marketing_order_ids'])->get();
                if (empty($partyName)) {
                    $partyName = $orders->pluck('party_name')->unique()->filter()->implode(', ');
                }
                if (empty($city)) {
                    $city = $orders->pluck('city')->unique()->filter()->implode(', ');
                }
            }

            $dispatch = Dispatch::create([
                'dispatch_number' => $this->generateDispatchNumber(),
                'dispatch_type' => $data['dispatch_type'] ?? 'factory_pickup',
                'party_name' => $partyName ?: 'N/A',
                'city' => $city,
                'place' => $data['place'] ?? null,
                'full_address' => $data['full_address'] ?? null,
                'google_map_url' => $data['google_map_url'] ?? null,
                'vehicle_number' => $data['vehicle_number'] ?? null,
                'driver_name' => $data['driver_name'] ?? null,
                'driver_mobile' => $data['driver_mobile'] ?? null,
                'expected_arrival_at' => !empty($data['expected_arrival_at']) ? Carbon::parse($data['expected_arrival_at']) : null,
                'payment_required' => !empty($data['payment_required']),
                'payment_status' => !empty($data['payment_required']) ? 'pending' : null,
                'is_released' => !empty($data['is_released']),
                'released_by' => !empty($data['is_released']) ? auth()->id() : null,
                'released_at' => !empty($data['is_released']) ? now() : null,
                'status' => 'planned',
                'remarks' => $data['remarks'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // Add Items from selected Marketing Orders or manual items
            if (!empty($data['marketing_order_ids']) && is_array($data['marketing_order_ids'])) {
                MarketingOrder::whereIn('id', $data['marketing_order_ids'])
                    ->where('status', 'pending')
                    ->update(['status' => 'in_progress']);

                $orders = MarketingOrder::whereIn('id', $data['marketing_order_ids'])->with('items')->get();
                foreach ($orders as $order) {
                    foreach ($order->items as $item) {
                        $dispatch->items()->create([
                            'marketing_order_id' => $order->id,
                            'marketing_order_item_id' => $item->id,
                            'department_code' => $item->department_code,
                            'grade_id' => $item->grade_id,
                            'color_id' => $item->color_id,
                            'epoxy_product_id' => $item->epoxy_product_id,
                            'epoxy_filler_color_id' => $item->epoxy_filler_color_id,
                            'epoxy_component_id' => $item->epoxy_component_id,
                            'quantity_bags' => $item->quantity_bags,
                            'quantity_kg' => $item->calculated_weight_kg,
                            'packing' => $item->packing,
                            'coupon_raw_material_id' => $item->coupon_raw_material_id,
                            'coupon_quantity' => $item->coupon_quantity,
                        ]);
                    }
                }
            } elseif (!empty($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $itemData) {
                    $dispatch->items()->create([
                        'marketing_order_id' => $itemData['marketing_order_id'] ?? null,
                        'marketing_order_item_id' => $itemData['marketing_order_item_id'] ?? null,
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
                        'coupon_quantity' => $itemData['coupon_quantity'] ?? null,
                    ]);
                }
            }

            // Log initial status history
            $dispatch->statusHistory()->create([
                'status' => 'planned',
                'changed_by' => auth()->id(),
                'remarks' => 'Dispatch created by Marketing',
            ]);

            ActivityLogService::log(
                'DISPATCH_CREATED',
                "Dispatch {$dispatch->dispatch_number} created for party: {$dispatch->party_name}",
                auth()->id()
            );

            // Send notification to Dispatch department staff & admins
            try {
                $dispatchUsers = User::whereHas('roles', function ($q) {
                    $q->whereIn('slug', ['dispatch', 'admin']);
                })->where('is_active', true)->get();

                $typeLabel = $dispatch->dispatch_type === 'crossing_delivery' ? 'Crossing Delivery' : 'Factory Pickup';
                $loadingDate = $dispatch->expected_arrival_at ? $dispatch->expected_arrival_at->format('d M Y h:i A') : 'TBD';

                foreach ($dispatchUsers as $u) {
                    $this->notificationService->sendToUser(
                        $u,
                        "New Dispatch Created: {$dispatch->dispatch_number}",
                        "New {$typeLabel} created for {$dispatch->party_name}. Vehicle: {$dispatch->vehicle_number}. Expected: {$loadingDate}",
                        'dispatch_created',
                        null,
                        ['dispatch_id' => $dispatch->id, 'click_url' => "/dispatch/{$dispatch->id}"]
                    );
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Dispatch notification error: ' . $e->getMessage());
            }

            return $dispatch->fresh(['items', 'creator']);
        });
    }

    /**
     * Update dispatch planning.
     */
    public function updateDispatch(Dispatch $dispatch, array $data): Dispatch
    {
        return DB::transaction(function () use ($dispatch, $data) {
            $dispatch->update([
                'dispatch_type' => $data['dispatch_type'] ?? $dispatch->dispatch_type,
                'party_name' => $data['party_name'] ?? $dispatch->party_name,
                'city' => $data['city'] ?? $dispatch->city,
                'place' => $data['place'] ?? $dispatch->place,
                'full_address' => $data['full_address'] ?? $dispatch->full_address,
                'google_map_url' => $data['google_map_url'] ?? $dispatch->google_map_url,
                'vehicle_number' => $data['vehicle_number'] ?? $dispatch->vehicle_number,
                'driver_name' => $data['driver_name'] ?? $dispatch->driver_name,
                'driver_mobile' => $data['driver_mobile'] ?? $dispatch->driver_mobile,
                'expected_arrival_at' => !empty($data['expected_arrival_at']) ? Carbon::parse($data['expected_arrival_at']) : $dispatch->expected_arrival_at,
                'payment_required' => isset($data['payment_required']) ? !empty($data['payment_required']) : $dispatch->payment_required,
                'remarks' => $data['remarks'] ?? $dispatch->remarks,
            ]);

            if (isset($data['is_released'])) {
                $released = !empty($data['is_released']);
                if ($released !== $dispatch->is_released) {
                    $dispatch->update([
                        'is_released' => $released,
                        'released_by' => $released ? auth()->id() : null,
                        'released_at' => $released ? now() : null,
                    ]);
                }
            }

            ActivityLogService::log(
                'DISPATCH_UPDATED',
                "Dispatch {$dispatch->dispatch_number} updated",
                auth()->id()
            );

            return $dispatch->fresh(['items', 'creator']);
        });
    }

    /**
     * Toggle release status (Marketing / Admin only).
     */
    public function toggleRelease(Dispatch $dispatch, bool $isReleased): Dispatch
    {
        $dispatch->update([
            'is_released' => $isReleased,
            'released_by' => $isReleased ? auth()->id() : null,
            'released_at' => $isReleased ? now() : null,
        ]);

        $statusMsg = $isReleased ? 'Goods Released by Marketing' : 'Goods Locked (Unreleased) by Marketing';
        
        $dispatch->statusHistory()->create([
            'status' => $dispatch->status,
            'changed_by' => auth()->id(),
            'remarks' => $statusMsg,
        ]);

        ActivityLogService::log(
            'DISPATCH_RELEASE_TOGGLED',
            "Dispatch {$dispatch->dispatch_number} release status changed to: " . ($isReleased ? 'Released' : 'Hold'),
            auth()->id()
        );

        return $dispatch;
    }

    /**
     * Update payment requirement status.
     */
    public function updatePayment(Dispatch $dispatch, array $paymentData): Dispatch
    {
        $dispatch->update([
            'payment_required' => !empty($paymentData['payment_required']),
        ]);

        ActivityLogService::log(
            'DISPATCH_PAYMENT_UPDATED',
            "Payment details updated for dispatch {$dispatch->dispatch_number}",
            auth()->id()
        );

        return $dispatch;
    }

    /**
     * Change dispatch status.
     */
    public function changeStatus(Dispatch $dispatch, string $status, ?string $remarks = null): Dispatch
    {
        $dispatch->update(['status' => $status]);

        $dispatch->statusHistory()->create([
            'status' => $status,
            'changed_by' => auth()->id(),
            'remarks' => $remarks ?? "Status updated to " . ucfirst(str_replace('_', ' ', $status)),
        ]);

        ActivityLogService::log(
            'DISPATCH_STATUS_CHANGED',
            "Dispatch {$dispatch->dispatch_number} status changed to {$status}",
            auth()->id()
        );

        return $dispatch;
    }

    /**
     * Delete dispatch.
     */
    public function deleteDispatch(Dispatch $dispatch): void
    {
        if ($dispatch->status === 'completed' && !auth()->user()->isAdmin()) {
            throw new \Exception('Completed dispatches can only be deleted by Administrators.');
        }

        ActivityLogService::log(
            'DISPATCH_DELETED',
            "Dispatch {$dispatch->dispatch_number} deleted",
            auth()->id()
        );

        $dispatch->delete();
    }

    /**
     * Get Dashboard Metrics for Today's Dispatches.
     */
    public function getDashboardMetrics(): array
    {
        $today = Carbon::today('Asia/Kolkata');

        return [
            'todays_count' => Dispatch::whereDate('created_at', $today)->count(),
            'pending_loading' => Dispatch::whereIn('status', ['planned', 'waiting_for_truck', 'truck_arrived'])->count(),
            'completed' => Dispatch::where('status', 'completed')->count(),
            'waiting_truck' => Dispatch::where('status', 'waiting_for_truck')->count(),
        ];
    }
}
