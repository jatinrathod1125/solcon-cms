<?php

namespace App\Services;

use App\Models\Dispatch;
use App\Models\DispatchItem;
use App\Models\FinishedGood;
use App\Models\MarketingOrder;
use App\Models\MarketingOrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DispatchLoadingService
{
    protected FinishedGoodsService $finishedGoodsService;

    public function __construct(FinishedGoodsService $finishedGoodsService)
    {
        $this->finishedGoodsService = $finishedGoodsService;
    }

    /**
     * Start loading process.
     */
    public function startLoading(Dispatch $dispatch, ?string $remarks = null): Dispatch
    {
        if (!$dispatch->is_released) {
            throw new \Exception("Goods are not released by Marketing. Loading cannot proceed.");
        }

        if (in_array($dispatch->status, ['completed', 'cancelled'])) {
            throw new \Exception("Cannot start loading for a {$dispatch->status} dispatch.");
        }

        return DB::transaction(function () use ($dispatch, $remarks) {
            $dispatch->update(['status' => 'loading']);

            $dispatch->loadingLogs()->create([
                'status' => 'loading',
                'user_id' => auth()->id(),
                'remarks' => $remarks ?? 'Started loading process',
            ]);

            $dispatch->statusHistory()->create([
                'status' => 'loading',
                'changed_by' => auth()->id(),
                'remarks' => 'Loading started by Dispatch staff',
            ]);

            ActivityLogService::log(
                'DISPATCH_LOADING_STARTED',
                "Loading started for dispatch {$dispatch->dispatch_number}",
                auth()->id()
            );

            return $dispatch->fresh(['items', 'loadingLogs', 'statusHistory']);
        });
    }

    /**
     * Finish loading process and deduct Finished Goods stock.
     */
    public function finishLoading(Dispatch $dispatch, ?string $remarks = null): Dispatch
    {
        if (!$dispatch->is_released) {
            throw new \Exception("Goods are not released by Marketing. Dispatch cannot be completed.");
        }

        if ($dispatch->status === 'completed') {
            throw new \Exception("This dispatch is already completed.");
        }

        return DB::transaction(function () use ($dispatch, $remarks) {
            // 1. Verify stock availability for ALL items before proceeding with deduction
            foreach ($dispatch->items as $item) {
                $stockInfo = $item->stock_info;
                if (!$stockInfo['is_available']) {
                    throw new \Exception("Cannot complete loading! Insufficient finished goods stock for '{$item->product_name}'. Required: {$stockInfo['required_bags']} {$item->unit_label}, Available in Finished Goods: {$stockInfo['available_bags']} {$item->unit_label}.");
                }
            }

            // 2. Deduct Finished Goods Stock for every item in the dispatch
            foreach ($dispatch->items as $item) {
                $this->deductFinishedGoodsStock($item, $dispatch->dispatch_number, $dispatch->party_name);

                // If linked to marketing order item, mark item as completed
                if ($item->marketing_order_item_id) {
                    MarketingOrderItem::where('id', $item->marketing_order_item_id)->update(['item_status' => 'completed']);
                }
            }

            // 2. Check and update status of linked Marketing Orders
            $orderIds = $dispatch->items()->whereNotNull('marketing_order_id')->pluck('marketing_order_id')->unique();
            foreach ($orderIds as $orderId) {
                $order = MarketingOrder::with('items')->find($orderId);
                if ($order) {
                    $pendingItems = $order->items()->where('item_status', '!=', 'completed')->count();
                    if ($pendingItems === 0) {
                        $order->update([
                            'status' => 'completed',
                            'completed_at' => now(),
                        ]);
                    } elseif ($order->status === 'pending') {
                        $order->update(['status' => 'in_progress']);
                    }
                }
            }

            // 3. Update Dispatch Status to Completed
            $dispatch->update([
                'status' => 'completed',
                'loaded_by' => auth()->id(),
                'loaded_at' => now(),
            ]);

            // 4. Record Loading Log & Status History
            $dispatch->loadingLogs()->create([
                'status' => 'completed',
                'user_id' => auth()->id(),
                'remarks' => $remarks ?? 'Loading completed and stock deducted successfully.',
            ]);

            $dispatch->statusHistory()->create([
                'status' => 'completed',
                'changed_by' => auth()->id(),
                'remarks' => 'Dispatch completed by Dispatch staff. Finished Goods stock deducted.',
            ]);

            ActivityLogService::log(
                'DISPATCH_COMPLETED',
                "Dispatch {$dispatch->dispatch_number} completed. Finished Goods stock deducted for party: {$dispatch->party_name}",
                auth()->id()
            );

            return $dispatch->fresh(['items', 'loadingLogs', 'statusHistory', 'loader']);
        });
    }

    /**
     * Deduct finished goods stock for a single dispatch item.
     */
    protected function deductFinishedGoodsStock(DispatchItem $item, string $dispatchNumber, string $partyName): void
    {
        $fgQuery = FinishedGood::query();

        switch ($item->department_code) {
            case 'TAD':
                $fgQuery->where('grade_id', $item->grade_id);
                if ($item->coupon_raw_material_id) {
                    $fgQuery->where('coupon_raw_material_id', $item->coupon_raw_material_id);
                }
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

        $finishedGood = null;
        if ($item->orderItem) {
            $finishedGood = $item->orderItem->findFinishedGood();
        }

        if (!$finishedGood) {
            if ($item->packing) {
                $exactMatch = (clone $fgQuery)->where('packing', $item->packing)->first();
                $finishedGood = $exactMatch ?: $fgQuery->first();
            } else {
                $finishedGood = $fgQuery->first();
            }
        }

        if ($finishedGood) {
            $this->finishedGoodsService->adjustStock(
                $finishedGood->id,
                'decrease',
                $item->quantity_bags,
                $item->quantity_kg,
                "Dispatch {$dispatchNumber}",
                "Dispatched to {$partyName} via Dispatch #{$dispatchNumber}"
            );
        }
    }
}
