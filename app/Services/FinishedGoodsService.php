<?php

namespace App\Services;

use App\Models\FinishedGood;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Color;
use App\Models\EpoxyProduct;
use Illuminate\Support\Facades\DB;

class FinishedGoodsService
{
    public function incrementAdhesiveStock(int $gradeId, string $packing, int $bags, float $weight, ?int $couponRawMaterialId = null): FinishedGood
    {
        return DB::transaction(function () use ($gradeId, $packing, $bags, $weight, $couponRawMaterialId) {
            $dept = Department::where('code', 'TAD')->firstOrFail();
            $grade = Grade::findOrFail($gradeId);

            // Fetch or create with pessimistic lock
            $finishedGood = FinishedGood::where('grade_id', $gradeId)
                ->where('packing', $packing)
                ->where('coupon_raw_material_id', $couponRawMaterialId)
                ->lockForUpdate()
                ->first();

            if (!$finishedGood) {
                $finishedGood = FinishedGood::create([
                    'department_id' => $dept->id,
                    'grade_id' => $gradeId,
                    'coupon_raw_material_id' => $couponRawMaterialId,
                    'packing' => $packing,
                    'available_bags' => 0,
                    'available_weight' => 0.0000,
                    'minimum_stock' => 20,
                    'status' => 'active',
                ]);
            }

            $newBags = $finishedGood->available_bags + $bags;
            $newWeight = $finishedGood->available_weight + $weight;
            $status = $this->determineStatus($newBags, $finishedGood->minimum_stock);

            $finishedGood->update([
                'available_bags' => $newBags,
                'available_weight' => $newWeight,
                'last_production_date' => now(),
                'status' => $status,
            ]);

            return $finishedGood;
        });
    }

    /**
     * Increment Grout Finished Good stock.
     */
    public function incrementGroutStock(int $colorId, string $packing, int $bags, float $weight): FinishedGood
    {
        return DB::transaction(function () use ($colorId, $packing, $bags, $weight) {
            $dept = Department::where('code', 'GRT')->firstOrFail();
            $color = Color::findOrFail($colorId);

            $finishedGood = FinishedGood::where('color_id', $colorId)
                ->where('packing', $packing)
                ->lockForUpdate()
                ->first();

            if (!$finishedGood) {
                $finishedGood = FinishedGood::create([
                    'department_id' => $dept->id,
                    'color_id' => $colorId,
                    'packing' => $packing,
                    'available_bags' => 0,
                    'available_weight' => 0.0000,
                    'minimum_stock' => 20,
                    'status' => 'active',
                ]);
            }

            $newBags = $finishedGood->available_bags + $bags;
            $newWeight = $finishedGood->available_weight + $weight;
            $status = $this->determineStatus($newBags, $finishedGood->minimum_stock);

            $finishedGood->update([
                'available_bags' => $newBags,
                'available_weight' => $newWeight,
                'last_production_date' => now(),
                'status' => $status,
            ]);

            return $finishedGood;
        });
    }

    /**
     * Increment Epoxy Finished Good stock.
     */
    public function incrementEpoxyStock(int $productId, ?int $colorId, string $packing, int $quantity, ?int $epoxyFillerColorId = null): FinishedGood
    {
        return DB::transaction(function () use ($productId, $colorId, $packing, $quantity, $epoxyFillerColorId) {
            $dept = Department::where('code', 'EPX')->firstOrFail();
            $product = EpoxyProduct::findOrFail($productId);

            $finishedGood = FinishedGood::where('epoxy_product_id', $productId)
                ->where('color_id', $colorId)
                ->where('epoxy_filler_color_id', $epoxyFillerColorId)
                ->where('packing', $packing)
                ->lockForUpdate()
                ->first();

            if (!$finishedGood) {
                $finishedGood = FinishedGood::create([
                    'department_id' => $dept->id,
                    'epoxy_product_id' => $productId,
                    'color_id' => $colorId,
                    'epoxy_filler_color_id' => $epoxyFillerColorId,
                    'packing' => $packing,
                    'available_bags' => 0,
                    'available_weight' => 0.0000,
                    'minimum_stock' => 20,
                    'status' => 'active',
                ]);
            }

            $weight = $this->calculateEpoxyWeight($product->code, $quantity);

            $newBags = $finishedGood->available_bags + $quantity;
            $newWeight = $finishedGood->available_weight + $weight;
            $status = $this->determineStatus($newBags, $finishedGood->minimum_stock);

            $finishedGood->update([
                'available_bags' => $newBags,
                'available_weight' => $newWeight,
                'last_production_date' => now(),
                'status' => $status,
            ]);

            return $finishedGood;
        });
    }

    /**
     * Calculate Epoxy weight based on product code
     */
    private function calculateEpoxyWeight(string $code, int $quantity): float
    {
        $codeUpper = strtoupper($code);
        if (str_contains($codeUpper, '5K') || str_contains($codeUpper, '5KG')) {
            return (float) $quantity * 5.0;
        }
        if (str_contains($codeUpper, '1K') || str_contains($codeUpper, '1KG')) {
            return (float) $quantity * 1.0;
        }
        if (str_contains($codeUpper, 'FIL') || str_contains($codeUpper, 'FILLER')) {
            return (float) $quantity * 0.7;
        }
        return (float) $quantity * 1.0;
    }

    /**
     * Perform a manual stock adjustment for a Finished Good.
     */
    public function adjustStock(int $id, string $type, int $bags, ?float $weight, string $reason, ?string $remarks): FinishedGood
    {
        return DB::transaction(function () use ($id, $type, $bags, $weight, $reason, $remarks) {
            $finishedGood = FinishedGood::lockForUpdate()->findOrFail($id);

            // Determine default bag size weight
            $defaultBagWeight = 1.0;
            $deptCode = strtoupper($finishedGood->department->code);
            if ($deptCode === 'TAD' && $finishedGood->grade && $finishedGood->grade->bagSize) {
                $defaultBagWeight = (float) $finishedGood->grade->bagSize->value;
            } elseif ($deptCode === 'GRT' && $finishedGood->color) {
                $defaultBagWeight = 20.0;
                $packingLower = strtolower($finishedGood->packing);
                if (preg_match('/(\d+)\s*kg/', $packingLower, $matches)) {
                    $defaultBagWeight = (float) $matches[1];
                }
            } elseif (($deptCode === 'EPX' || $deptCode === 'EP') && $finishedGood->epoxyProduct) {
                $defaultBagWeight = $this->calculateEpoxyWeight($finishedGood->epoxyProduct->code, 1);
            }

            if ($type === 'increase') {
                $newBags = $finishedGood->available_bags + $bags;
                $weightChange = $weight !== null ? $weight : ($bags * $defaultBagWeight);
                $newWeight = $finishedGood->available_weight + $weightChange;
            } else { // decrease
                if ($bags > $finishedGood->available_bags) {
                    throw new \Exception("Cannot decrease stock below 0. Available: {$finishedGood->available_bags} bags.");
                }
                $newBags = $finishedGood->available_bags - $bags;
                if ($weight !== null) {
                    $newWeight = max(0, $finishedGood->available_weight - $weight);
                } else {
                    $weightChange = ($finishedGood->available_bags > 0) 
                        ? ($finishedGood->available_weight / $finishedGood->available_bags) * $bags 
                        : ($bags * $defaultBagWeight);
                    $newWeight = max(0, $finishedGood->available_weight - $weightChange);
                }
            }

            $status = $this->determineStatus($newBags, $finishedGood->minimum_stock);

            $finishedGood->update([
                'available_bags' => $newBags,
                'available_weight' => $newWeight,
                'status' => $status,
                'remarks' => $remarks ?: $finishedGood->remarks,
            ]);

            // Log activity log
            \App\Services\ActivityLogService::log(
                'FINISHED_GOODS_ADJUSTED',
                "Manual stock adjustment ({$type}) of {$bags} units for product: {$finishedGood->product_name} ({$finishedGood->packing}). Reason: {$reason}.",
                auth()->id()
            );

            return $finishedGood;
        });
    }

    /**
     * Helper to determine status
     */
    private function determineStatus(int $available, int $minimum): string
    {
        if ($available <= 0) {
            return 'out_of_stock';
        }
        if ($available <= $minimum) {
            return 'low_stock';
        }
        return 'active';
    }
}
