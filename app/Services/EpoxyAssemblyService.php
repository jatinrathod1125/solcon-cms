<?php

namespace App\Services;

use App\Models\EpoxyProduct;
use App\Models\EpoxyFormula;
use App\Models\EpoxyAssembly;
use App\Models\Color;
use App\Models\EpoxyFillerColor;
use App\Models\EpoxyComponent;
use App\Models\RawMaterial;
use App\Models\Department;
use App\Models\User;
use App\Models\FinishedGood;
use App\Models\EpoxyComponentPreparation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EpoxyAssemblyService
{
    /**
     * Assemble an Epoxy product manually.
     */
    public static function assembleProduct(
        int $productId,
        int $quantity,
        ?int $colorId = null,
        ?string $remarks = null,
        ?int $epoxyFillerColorId = null
    ): EpoxyAssembly {
        return DB::transaction(function () use ($productId, $quantity, $colorId, $remarks, $epoxyFillerColorId) {
            $product = EpoxyProduct::findOrFail($productId);
            $formula = $product->activeFormula;

            if (!$formula) {
                throw ValidationException::withMessages([
                    'product_id' => ["The selected product does not have an active formula version configured."],
                ]);
            }

            if ($quantity <= 0) {
                throw ValidationException::withMessages([
                    'quantity' => ["Quantity must be greater than zero."],
                ]);
            }

            $color = null;
            $epoxyFillerColor = null;

            if ($product->requires_color) {
                if ($epoxyFillerColorId) {
                    $epoxyFillerColor = EpoxyFillerColor::findOrFail($epoxyFillerColorId);
                } elseif ($colorId) {
                    $color = Color::findOrFail($colorId);
                } else {
                    throw ValidationException::withMessages([
                        'color_id' => ["A color is required for this product."],
                    ]);
                }
            }

            $deptEPX = Department::where('code', 'EPX')->firstOrFail();
            $snapshot = [];

            // 1. Resolve and scale formula items, validating ready components stock
            foreach ($formula->items as $item) {
                $rawMaterial = $item->rawMaterial;
                $resolvedRm = $rawMaterial;

                if ($item->is_dynamic_color) {
                    if ($epoxyFillerColor) {
                        // Find parent component
                        $parentComponent = EpoxyComponent::where('raw_material_id', $rawMaterial->id)->first();
                        if (!$parentComponent) {
                            throw ValidationException::withMessages([
                                'color_id' => ["Epoxy Component for template '{$rawMaterial->name}' not configured."],
                            ]);
                        }

                        // Find child component matching color
                        $childComponent = EpoxyComponent::where('parent_component_id', $parentComponent->id)
                            ->where('epoxy_filler_color_id', $epoxyFillerColor->id)
                            ->first();

                        if (!$childComponent || !$childComponent->raw_material_id) {
                            throw ValidationException::withMessages([
                                'color_id' => ["Color-specific ready component for '{$parentComponent->name}' with color '{$epoxyFillerColor->name}' not configured in inventory."],
                            ]);
                        }

                        $resolvedRm = $childComponent->rawMaterial;
                    } elseif ($color) {
                        // Fallback/Legacy resolution for Grout colors
                        $colorCodeSuffix = str_replace('GR-', '', $color->code);
                        $specificRmCode = $rawMaterial->code . '-' . $colorCodeSuffix;

                        $resolvedRm = RawMaterial::where('department_id', $deptEPX->id)
                            ->where('code', $specificRmCode)
                            ->first();

                        if (!$resolvedRm) {
                            $firstWord = explode(' ', trim($color->name))[0];
                            $resolvedRm = RawMaterial::where('department_id', $deptEPX->id)
                                ->where('name', 'like', '%' . $firstWord . '%')
                                ->where('name', 'like', '%Filler%')
                                ->first();
                        }

                        if (!$resolvedRm) {
                            throw ValidationException::withMessages([
                                'color_id' => ["Color-specific raw material for color '{$color->name}' not configured in inventory."],
                            ]);
                        }
                    }
                }

                $totalQtyNeeded = (float) $item->quantity * $quantity;

                // Check stock
                $currentStock = (float) $resolvedRm->current_stock;
                if ($currentStock < $totalQtyNeeded) {
                    throw ValidationException::withMessages([
                        'quantity' => ["Insufficient stock for ready component '{$resolvedRm->name}'. Required: {$totalQtyNeeded}, Available: {$currentStock}."],
                    ]);
                }

                $snapshot[] = [
                    'raw_material_id' => $resolvedRm->id,
                    'raw_material_name' => $resolvedRm->name,
                    'raw_material_code' => $resolvedRm->code,
                    'quantity' => $totalQtyNeeded,
                    'unit_code' => $item->unit->code,
                    'material_type' => $item->material_type,
                    'is_dynamic_color' => $item->is_dynamic_color,
                ];
            }

            $operatorId = auth()->id() ?? User::where('email', 'admin@solcon.com')->first()?->id ?? 1;

            // 2. Save assembly record
            $assembly = EpoxyAssembly::create([
                'epoxy_product_id' => $productId,
                'color_id' => $color ? $color->id : null,
                'epoxy_filler_color_id' => $epoxyFillerColor ? $epoxyFillerColor->id : null,
                'formula_snapshot' => $snapshot,
                'quantity' => $quantity,
                'operator_id' => $operatorId,
                'remarks' => $remarks,
            ]);

            // 3. Deduct stock & create ledger entries
            foreach ($snapshot as $snapItem) {
                StockService::recordMovement(
                    $snapItem['raw_material_id'],
                    $snapItem['quantity'],
                    'OUT',
                    null,
                    "Consumed in Epoxy assembly #{$assembly->id}",
                    null,
                    $assembly->id
                );
            }

            // Update Finished Goods Stock
            // Derive a proper packing value: for products whose name contains
            // a weight suffix (e.g. "RESIN KIT 0.3KG"), use the weight portion
            // so that it matches the packing value stored on order items.
            $packing = $product->name;
            if (preg_match('/(\d+(?:\.\d+)?\s*(?:KG|LTR|GM))/i', $product->name, $m)) {
                $packing = strtoupper(preg_replace('/\s+/', '', $m[1]));
            }

            app(\App\Services\FinishedGoodsService::class)->incrementEpoxyStock(
                $productId,
                $color ? $color->id : null,
                $packing,
                (int) $quantity,
                $epoxyFillerColor ? $epoxyFillerColor->id : null
            );

            // 4. Create Activity Log
            $colorText = "";
            if ($epoxyFillerColor) {
                $colorText = " (Color: {$epoxyFillerColor->name})";
            } elseif ($color) {
                $colorText = " (Color: {$color->name})";
            }
            ActivityLogService::log(
                'EPOXY_ASSEMBLED',
                "Manually assembled {$quantity} units of Epoxy Product: {$product->name}{$colorText}. Stock deducted.",
                $operatorId
            );

            return $assembly;
        });
    }

    /**
     * Prepare an Epoxy component, consuming its formula ingredients.
     */
    public static function prepareComponent(
        int $componentId,
        int $quantity,
        ?string $remarks = null
    ): void {
        DB::transaction(function () use ($componentId, $quantity, $remarks) {
            $component = EpoxyComponent::findOrFail($componentId);
            $formula = $component->activeFormula;

            if ($quantity <= 0) {
                throw ValidationException::withMessages([
                    'quantity' => ["Quantity must be greater than zero."],
                ]);
            }

            if (!$formula) {
                throw ValidationException::withMessages([
                    'epoxy_component_id' => ["The selected component '{$component->name}' does not have an active formula version configured."],
                ]);
            }

            $deductions = [];

            // 1. Validate and prepare deductions for formula items
            foreach ($formula->items as $item) {
                $neededQty = (float) $item->quantity * $quantity;
                $remarksText = "Formula consumed to prepare {$quantity} units of component: {$component->name}";

                if ($item->packing_material_id) {
                    $lockedPm = \App\Models\PackingMaterial::lockForUpdate()->findOrFail($item->packing_material_id);
                    if ((float) $lockedPm->current_stock < $neededQty) {
                        throw ValidationException::withMessages([
                            'quantity' => ["Insufficient stock for packing material '{$lockedPm->name}'. Required: {$neededQty}, Available: {$lockedPm->current_stock}."],
                        ]);
                    }
                    $deductions[] = [
                        'rm_id' => null,
                        'pm_id' => $lockedPm->id,
                        'qty' => $neededQty,
                        'remarks' => $remarksText,
                    ];
                } elseif ($item->raw_material_id) {
                    $lockedRm = RawMaterial::lockForUpdate()->findOrFail($item->raw_material_id);
                    if ((float) $lockedRm->current_stock < $neededQty) {
                        throw ValidationException::withMessages([
                            'quantity' => ["Insufficient stock for raw material '{$lockedRm->name}'. Required: {$neededQty}, Available: {$lockedRm->current_stock}."],
                        ]);
                    }
                    $deductions[] = [
                        'rm_id' => $lockedRm->id,
                        'pm_id' => null,
                        'qty' => $neededQty,
                        'remarks' => $remarksText,
                    ];
                }
            }

            // 2. Perform OUT movements for ingredients
            foreach ($deductions as $ded) {
                StockService::recordMovement(
                    $ded['rm_id'],
                    $ded['qty'],
                    'OUT',
                    null,
                    $ded['remarks'],
                    null,
                    null,
                    $ded['pm_id']
                );
            }

            $operatorId = auth()->id() ?? User::where('email', 'admin@solcon.com')->first()?->id ?? 1;

            // 3. Increment Destination Stock based on purpose
            if ($component->purpose === 'Direct Finished Product') {
                $deptEPX = Department::where('code', 'EPX')->firstOrFail();
                $packing = FinishedGoodsResolver::packingForComponent($component) ?? '1 Unit';
                
                // Find or create Finished Goods record
                $finishedGood = FinishedGood::firstOrCreate([
                    'department_id' => $deptEPX->id,
                    'epoxy_component_id' => $component->id,
                ], [
                    'packing' => $packing,
                    'available_bags' => 0,
                    'available_weight' => 0.0000,
                    'minimum_stock' => 0,
                    'status' => 'Active',
                ]);

                $finishedGood->increment('available_bags', $quantity);
                $finishedGood->increment('available_weight', (float) $quantity);
                $finishedGood->update(['last_production_date' => now()]);
            } else {
                // Assembly Component: Increase raw material stock of ready component
                StockService::recordMovement(
                    $component->raw_material_id,
                    $quantity,
                    'IN',
                    null,
                    "Prepared component stock increment: {$component->name}"
                );
            }

            // 4. Save preparation log
            EpoxyComponentPreparation::create([
                'epoxy_component_id' => $component->id,
                'epoxy_filler_color_id' => $component->epoxy_filler_color_id,
                'quantity' => $quantity,
                'operator_id' => $operatorId,
                'remarks' => $remarks,
            ]);

            // 5. Activity Log
            $purposeText = $component->purpose === 'Direct Finished Product' ? "Direct Finished Product" : "Assembly Component";
            ActivityLogService::log(
                'EPOXY_COMPONENT_PREPARED',
                "Prepared {$quantity} units of {$component->name} ({$purposeText}). Ingredients deducted.",
                $operatorId
            );
        });
    }
}
