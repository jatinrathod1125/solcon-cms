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
                $isPacking = (bool) $item->packing_material_id;
                $rawMaterial = $item->rawMaterial;
                $packingMaterial = $item->packingMaterial;
                $resolvedMat = $isPacking ? $packingMaterial : $rawMaterial;

                if (!$isPacking && $item->is_dynamic_color) {
                    if ($epoxyFillerColor && $rawMaterial) {
                        $brandId = $product->brand_id ?? (function_exists('currentBrand') && currentBrand() ? currentBrand()->id : null);

                        // 1. Direct match: EpoxyComponent for this brand and color
                        $directComponent = EpoxyComponent::where('epoxy_filler_color_id', $epoxyFillerColor->id)
                            ->when($brandId, fn($q) => $q->where('brand_id', $brandId))
                            ->first();

                        if (!$directComponent) {
                            $directComponent = EpoxyComponent::where('epoxy_filler_color_id', $epoxyFillerColor->id)->first();
                        }

                        // Fallback to name/code matching if component color mismatch or not found
                        if (!$directComponent || ($epoxyFillerColor->name && stripos($directComponent->name, $epoxyFillerColor->name) === false)) {
                            $byName = EpoxyComponent::when($brandId, fn($q) => $q->where('brand_id', $brandId))
                                ->where(function ($q) use ($epoxyFillerColor) {
                                    $q->where('name', 'like', "%{$epoxyFillerColor->name}%")
                                      ->orWhere('code', 'like', "%{$epoxyFillerColor->code}%");
                                })
                                ->where(function ($q) {
                                    $q->where('category', 'Pouch')
                                      ->orWhere('name', 'like', '%Filler%');
                                })
                                ->first();

                            if ($byName) {
                                $directComponent = $byName;
                            }
                        }

                        if ($directComponent && $directComponent->rawMaterial) {
                            $resolvedMat = $directComponent->rawMaterial;
                        } else {
                            // 2. Parent / Template component lookup
                            $parentComponent = EpoxyComponent::where('raw_material_id', $rawMaterial->id)
                                ->orWhere('template_material_id', $rawMaterial->id)
                                ->first();

                            if ($parentComponent) {
                                $childComponent = EpoxyComponent::where('parent_component_id', $parentComponent->id)
                                    ->where('epoxy_filler_color_id', $epoxyFillerColor->id)
                                    ->first();

                                if ($childComponent && $childComponent->rawMaterial) {
                                    $resolvedMat = $childComponent->rawMaterial;
                                } else {
                                    $mapping = EpoxyComponentMapping::where('epoxy_component_id', $parentComponent->id)
                                        ->where('epoxy_filler_color_id', $epoxyFillerColor->id)
                                        ->first();
                                    if ($mapping && $mapping->rawMaterial) {
                                        $resolvedMat = $mapping->rawMaterial;
                                    }
                                }
                            }
                        }

                        if (!$resolvedMat) {
                            // 3. Fallback to RawMaterial by color suffix or name
                            $colorSuffix = str_replace('GR-', '', $epoxyFillerColor->code);
                            $specificRm = RawMaterial::where('department_id', $deptEPX->id)
                                ->where(function ($q) use ($epoxyFillerColor, $colorSuffix) {
                                    $q->where('code', 'like', "%{$colorSuffix}%")
                                      ->orWhere('name', 'like', "%{$epoxyFillerColor->name}%");
                                })
                                ->where('name', 'like', '%Filler%')
                                ->first();

                            if ($specificRm) {
                                $resolvedMat = $specificRm;
                            } else {
                                throw ValidationException::withMessages([
                                    'color_id' => ["Color-specific ready component for '{$rawMaterial->name}' with color '{$epoxyFillerColor->name}' not configured in inventory."],
                                ]);
                            }
                        }
                    } elseif ($color && $rawMaterial) {
                        // Fallback/Legacy resolution for Grout colors
                        $colorCodeSuffix = str_replace('GR-', '', $color->code);
                        $specificRmCode = $rawMaterial->code . '-' . $colorCodeSuffix;

                        $resolvedMat = RawMaterial::where('department_id', $deptEPX->id)
                            ->where('code', $specificRmCode)
                            ->first();

                        if (!$resolvedMat) {
                            $firstWord = explode(' ', trim($color->name))[0];
                            $resolvedMat = RawMaterial::where('department_id', $deptEPX->id)
                                ->where('name', 'like', '%' . $firstWord . '%')
                                ->where('name', 'like', '%Filler%')
                                ->first();
                        }

                        if (!$resolvedMat) {
                            throw ValidationException::withMessages([
                                'color_id' => ["Color-specific raw material for color '{$color->name}' not configured in inventory."],
                            ]);
                        }
                    }
                }

                if (!$resolvedMat) {
                    throw ValidationException::withMessages([
                        'quantity' => ["Material not configured for formula item #{$item->id}."],
                    ]);
                }

                $totalQtyNeeded = (float) $item->quantity * $quantity;

                // Check stock
                $currentStock = (float) $resolvedMat->current_stock;
                if ($currentStock < $totalQtyNeeded) {
                    $matType = $isPacking ? "packing material" : "ready component";
                    throw ValidationException::withMessages([
                        'quantity' => ["Insufficient stock for {$matType} '{$resolvedMat->name}'. Required: {$totalQtyNeeded}, Available: {$currentStock}."],
                    ]);
                }

                $snapshot[] = [
                    'item_type' => $isPacking ? 'packing' : 'raw',
                    'raw_material_id' => !$isPacking ? $resolvedMat->id : null,
                    'packing_material_id' => $isPacking ? $resolvedMat->id : null,
                    'raw_material_name' => $resolvedMat->name,
                    'raw_material_code' => $resolvedMat->code,
                    'quantity' => $totalQtyNeeded,
                    'unit_code' => $item->unit ? $item->unit->code : 'PCS',
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
                if (!empty($snapItem['packing_material_id'])) {
                    StockService::recordMovement(
                        null,
                        $snapItem['quantity'],
                        'OUT',
                        null,
                        "Consumed in Epoxy assembly #{$assembly->id}",
                        null,
                        $assembly->id,
                        $snapItem['packing_material_id']
                    );
                } else {
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
