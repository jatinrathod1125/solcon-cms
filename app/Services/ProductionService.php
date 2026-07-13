<?php

namespace App\Services;

use App\Models\ProductionBatch;
use App\Models\Formula;
use App\Models\Machine;
use App\Models\Grade;
use App\Models\RawMaterial;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductionService
{
    /**
     * Start a new production batch.
     */
    public static function startBatch(int $machineId, int $gradeId, ?string $remarks = null, ?string $customBatchNo = null, ?int $couponRawMaterialId = null): ProductionBatch
    {
        try {
            return DB::transaction(function () use ($machineId, $gradeId, $remarks, $customBatchNo, $couponRawMaterialId) {
                // Lock the machine row to serialize batch start attempts and prevent concurrent status check races
                $machine = Machine::where('id', $machineId)->lockForUpdate()->firstOrFail();

                // 1. Validate Supervisor Department boundary
                $user = auth()->user();
                if ($user && $user->isSupervisor()) {
                    $grade = Grade::findOrFail($gradeId);
                    if ($machine->department_id !== $user->department_id) {
                        throw ValidationException::withMessages([
                            'machine_id' => ['This machine does not belong to your assigned department.'],
                        ]);
                    }
                    if ($grade->department_id !== $user->department_id) {
                        throw ValidationException::withMessages([
                            'grade_id' => ['This grade does not belong to your assigned department.'],
                        ]);
                    }
                }

                // 2. Validate running machine (One active/paused batch per machine)
                $activeBatchExists = ProductionBatch::where('machine_id', $machineId)
                    ->whereIn('status', ['running', 'paused'])
                    ->exists();
                if ($activeBatchExists) {
                    throw ValidationException::withMessages([
                        'machine_id' => ['This machine is already running or has a paused production batch.'],
                    ]);
                }

                // 3. Load active formula via FormulaService
                $formula = FormulaService::getActiveFormula($gradeId);
                if (!$formula) {
                    throw ValidationException::withMessages([
                        'grade_id' => ['The selected grade does not have an active formula configured.'],
                    ]);
                }

                // 4. Create formula snapshot
                $snapshot = [];
                $couponFound = false;

                // Load new coupon if requested
                $newCoupon = null;
                if ($couponRawMaterialId) {
                    $newCoupon = RawMaterial::findOrFail($couponRawMaterialId);
                }

                foreach ($formula->items as $item) {
                    $isItemCoupon = $item->rawMaterial->is_coupon;

                    if ($isItemCoupon) {
                        if ($newCoupon) {
                            // Replace coupon in formula with the chosen one
                            $snapshot[] = [
                                'raw_material_id' => $newCoupon->id,
                                'raw_material_name' => $newCoupon->name,
                                'raw_material_code' => $newCoupon->code,
                                'quantity' => (float) $item->quantity,
                                'unit_code' => $newCoupon->stockUnit->code ?? 'PCS',
                                'consumption_method' => $item->consumption_method ?? 'formula',
                                'consumption_per_unit' => (float) ($item->consumption_per_unit ?? 1.0),
                            ];
                            $couponFound = true;
                        }
                        // If no newCoupon requested, we skip (remove) the coupon from the snapshot
                    } else {
                        // Standard raw material item
                        $snapshot[] = [
                            'raw_material_id' => $item->raw_material_id,
                            'raw_material_name' => $item->rawMaterial->name,
                            'raw_material_code' => $item->rawMaterial->code,
                            'quantity' => (float) $item->quantity,
                            'unit_code' => $item->unit->code,
                            'consumption_method' => $item->consumption_method ?? 'formula',
                            'consumption_per_unit' => (float) ($item->consumption_per_unit ?? 1.0),
                        ];
                    }
                }

                // If coupon requested but no coupon item was in the formula to replace
                if ($newCoupon && !$couponFound) {
                    $snapshot[] = [
                        'raw_material_id' => $newCoupon->id,
                        'raw_material_name' => $newCoupon->name,
                        'raw_material_code' => $newCoupon->code,
                        'quantity' => 1.0,
                        'unit_code' => $newCoupon->stockUnit->code ?? 'PCS',
                        'consumption_method' => 'output',
                        'consumption_per_unit' => 1.0,
                    ];
                }

                // 5. Generate or use custom batch number
                $cleanBatchNo = trim($customBatchNo ?? '');
                if (!empty($cleanBatchNo)) {
                    if (ProductionBatch::where('batch_no', $cleanBatchNo)->exists()) {
                        throw ValidationException::withMessages([
                            'batch_no' => ['The specified batch number already exists.'],
                        ]);
                    }
                    $batchNo = $cleanBatchNo;
                } else {
                    $batchNo = BatchNumberService::generate();
                }

                // 6. Save production batch with snapshot
                $userId = auth()->id() ?? 1;
                $batch = ProductionBatch::create([
                    'batch_no' => $batchNo,
                    'machine_id' => $machineId,
                    'grade_id' => $gradeId,
                    'formula_id' => $formula->id,
                    'formula_snapshot' => $snapshot,
                    'supervisor_id' => $userId,
                    'start_time' => now(),
                    'status' => 'running',
                    'remarks' => $remarks,
                ]);

                // 7. Write audit log (Batch Created)
                ActivityLogService::log(
                    'BATCH_CREATED',
                    "Production batch #{$batchNo} started on machine ID {$machineId} (Grade: {$batch->grade->name}).",
                    $userId
                );

                return $batch;
            });
        } catch (\Exception $e) {
            // Log Failed Production Attempt
            ActivityLogService::log(
                'FAILED_PRODUCTION_ATTEMPT',
                "Failed to start batch on machine ID {$machineId} for grade ID {$gradeId}. Reason: " . $e->getMessage()
            );
            throw $e;
        }
    }

    /**
     * Update the coupon of a running/paused production batch.
     */
    public static function updateBatchCoupon(ProductionBatch $batch, ?int $couponRawMaterialId): void
    {
        if ($batch->status !== 'running' && $batch->status !== 'paused') {
            throw new \Exception('Only active batches can be updated.');
        }

        DB::transaction(function () use ($batch, $couponRawMaterialId) {
            $snapshot = $batch->formula_snapshot;
            if (empty($snapshot)) {
                $snapshot = [];
            }

            $couponFound = false;
            $newCoupon = null;
            if ($couponRawMaterialId) {
                $newCoupon = RawMaterial::findOrFail($couponRawMaterialId);
            }

            $newSnapshot = [];

            foreach ($snapshot as $item) {
                $rawMaterial = RawMaterial::find($item['raw_material_id']);
                $isItemCoupon = $rawMaterial ? $rawMaterial->is_coupon : false;

                if ($isItemCoupon) {
                    if ($newCoupon) {
                        // Replace it
                        $newSnapshot[] = [
                            'raw_material_id' => $newCoupon->id,
                            'raw_material_name' => $newCoupon->name,
                            'raw_material_code' => $newCoupon->code,
                            'quantity' => (float) $item['quantity'],
                            'unit_code' => $newCoupon->stockUnit->code ?? 'PCS',
                            'consumption_method' => $item['consumption_method'] ?? 'formula',
                            'consumption_per_unit' => (float) ($item['consumption_per_unit'] ?? 1.0),
                        ];
                        $couponFound = true;
                    }
                    // If no newCoupon, we skip it (which removes it)
                } else {
                    $newSnapshot[] = $item;
                }
            }

            // If new coupon is requested but no coupon item was found to replace, append it
            if ($newCoupon && !$couponFound) {
                $newSnapshot[] = [
                    'raw_material_id' => $newCoupon->id,
                    'raw_material_name' => $newCoupon->name,
                    'raw_material_code' => $newCoupon->code,
                    'quantity' => 1.0,
                    'unit_code' => $newCoupon->stockUnit->code ?? 'PCS',
                    'consumption_method' => 'output',
                    'consumption_per_unit' => 1.0,
                ];
            }

            $batch->update([
                'formula_snapshot' => $newSnapshot
            ]);

            ActivityLogService::log(
                'BATCH_COUPON_UPDATED',
                "Coupon updated for production batch #{$batch->batch_no}. New Coupon: " . ($newCoupon ? $newCoupon->name : 'No Coupon'),
                auth()->id()
            );
        });
    }

    /**
     * Complete a running production batch, validating stock, deducting it, and recording ledger.
     */
    public static function completeBatch(int $batchId, float $outputBags, ?string $endTime = null, ?string $remarks = null): ProductionBatch
    {
        try {
            return DB::transaction(function () use ($batchId, $outputBags, $endTime, $remarks) {
                // Load and lock the batch
                $batch = ProductionBatch::lockForUpdate()->findOrFail($batchId);
                
                // Completed batches cannot be edited
                if ($batch->status !== 'running') {
                    throw new \Exception('Only running batches can be completed.');
                }

                // 1. Validate Supervisor Department boundary for completion
                $user = auth()->user();
                if ($user && $user->isSupervisor()) {
                    if ($batch->machine->department_id !== $user->department_id) {
                        throw new \Exception('Unauthorized department access.');
                    }
                }

                $bagSizeValue = (float) $batch->grade->bagSize->value;
                $outputKg = $outputBags * $bagSizeValue;

                // Load formula snapshot
                $snapshot = $batch->formula_snapshot;
                if (empty($snapshot)) {
                    $formula = Formula::with('items.rawMaterial', 'items.unit')->findOrFail($batch->formula_id);
                    $snapshot = [];
                    foreach ($formula->items as $item) {
                        $snapshot[] = [
                            'raw_material_id' => $item->raw_material_id,
                            'raw_material_name' => $item->rawMaterial->name,
                            'raw_material_code' => $item->rawMaterial->code,
                            'quantity' => (float) $item->quantity,
                            'unit_code' => $item->unit->code,
                            'consumption_method' => $item->consumption_method ?? 'formula',
                            'consumption_per_unit' => (float) ($item->consumption_per_unit ?? 1.0),
                        ];
                    }
                }

                // Helper lambda to calculate required quantity based on consumption method
                $getRequiredQty = function (array $itemData, float $actualBags): float {
                    $method = $itemData['consumption_method'] ?? 'formula';
                    if ($method === 'output') {
                        $perUnit = isset($itemData['consumption_per_unit']) ? (float) $itemData['consumption_per_unit'] : 1.0;
                        return $actualBags * $perUnit;
                    }
                    return (float) $itemData['quantity'];
                };

                // 2. Validate stock before deduction
                foreach ($snapshot as $itemData) {
                    $requiredQty = $getRequiredQty($itemData, $outputBags);
                    $rawMaterial = RawMaterial::lockForUpdate()->findOrFail($itemData['raw_material_id']);
                    if ((float) $rawMaterial->current_stock < $requiredQty) {
                        // Log failed stock validation
                        ActivityLogService::log(
                            'FAILED_STOCK_VALIDATION',
                            "Failed to complete batch #{$batch->batch_no} due to insufficient stock of {$rawMaterial->name}.",
                            auth()->id() ?? $batch->supervisor_id
                        );
                        throw new \Exception("{$rawMaterial->name} Stock is insufficient.");
                    }
                }

                // 3. Deduct stock & create ledger entries
                foreach ($snapshot as $itemData) {
                    $deductQty = $getRequiredQty($itemData, $outputBags);
                    StockService::recordMovement(
                        $itemData['raw_material_id'],
                        $deductQty,
                        'OUT',
                        $batch->id,
                        "Consumed in production batch #{$batch->batch_no}"
                    );
                }

                // 4. Update batch status
                $parsedEndTime = $endTime ? \Carbon\Carbon::parse($endTime) : now();
                $batch->update([
                    'end_time' => $parsedEndTime,
                    'output_bags' => $outputBags,
                    'output_kg' => $outputKg,
                    'status' => 'completed',
                    'remarks' => $remarks ?? $batch->remarks,
                ]);

                // Find coupon raw material in formula snapshot to track finished good coupon variant
                $couponRawMaterialId = null;
                if (!empty($batch->formula_snapshot)) {
                    foreach ($batch->formula_snapshot as $itemData) {
                        $rm = RawMaterial::find($itemData['raw_material_id']);
                        if ($rm && $rm->is_coupon) {
                            $couponRawMaterialId = $rm->id;
                            break;
                        }
                    }
                }

                // Update Finished Goods Stock
                app(\App\Services\FinishedGoodsService::class)->incrementAdhesiveStock(
                    $batch->grade_id,
                    $batch->grade->bagSize->name,
                    (int) $outputBags,
                    (float) $outputKg,
                    $couponRawMaterialId
                );

                // 5. Write audit logs
                $userId = auth()->id() ?? $batch->supervisor_id;
                ActivityLogService::log(
                    'BATCH_COMPLETED',
                    "Production batch #{$batch->batch_no} completed. Output: {$outputBags} bags ({$outputKg} KG).",
                    $userId
                );
                ActivityLogService::log(
                    'STOCK_DEDUCTED',
                    "Stock deducted for production batch #{$batch->batch_no}.",
                    $userId
                );
                ActivityLogService::log(
                    'LEDGER_CREATED',
                    "Stock ledger entries created for production batch #{$batch->batch_no}.",
                    $userId
                );

                return $batch;
            });
        } catch (\Exception $e) {
            // Log Failed Production Attempt (unless it is failed validation which is logged separately)
            if ($e->getMessage() !== 'Only running batches can be completed.' && strpos($e->getMessage(), 'Stock is insufficient') === false) {
                ActivityLogService::log(
                    'FAILED_PRODUCTION_ATTEMPT',
                    "Failed to complete batch ID {$batchId}. Reason: " . $e->getMessage()
                );
            }
            throw $e;
        }
    }

    /**
     * Cancel a running production batch.
     */
    public static function cancelBatch(int $batchId, ?string $remarks = null): ProductionBatch
    {
        return DB::transaction(function () use ($batchId, $remarks) {
            $batch = ProductionBatch::lockForUpdate()->findOrFail($batchId);

            if (!in_array($batch->status, ['running', 'paused'])) {
                throw new \Exception('Only running or paused batches can be cancelled.');
            }

            $user = auth()->user();
            if ($user && $user->isSupervisor()) {
                if ($batch->machine->department_id !== $user->department_id) {
                    throw new \Exception('Unauthorized department access.');
                }
            }

            $batch->update([
                'status' => 'cancelled',
                'remarks' => $remarks ?? $batch->remarks,
                'end_time' => now(),
            ]);

            ActivityLogService::log(
                'BATCH_CANCELLED',
                "Production batch #{$batch->batch_no} was cancelled/discarded. Remarks: " . ($remarks ?? 'None'),
                auth()->id() ?? $batch->supervisor_id
            );

            return $batch;
        });
    }

    /**
     * Pause a running production batch.
     */
    public static function pauseBatch(int $batchId): ProductionBatch
    {
        return DB::transaction(function () use ($batchId) {
            $batch = ProductionBatch::lockForUpdate()->findOrFail($batchId);

            if ($batch->status !== 'running') {
                throw new \Exception('Only running batches can be paused.');
            }

            $user = auth()->user();
            if ($user && $user->isSupervisor()) {
                if ($batch->machine->department_id !== $user->department_id) {
                    throw new \Exception('Unauthorized department access.');
                }
            }

            $batch->update([
                'status' => 'paused',
            ]);

            ActivityLogService::log(
                'BATCH_PAUSED',
                "Production batch #{$batch->batch_no} was paused/held.",
                auth()->id() ?? $batch->supervisor_id
            );

            return $batch;
        });
    }

    /**
     * Resume a paused production batch.
     */
    public static function resumeBatch(int $batchId): ProductionBatch
    {
        return DB::transaction(function () use ($batchId) {
            $batch = ProductionBatch::lockForUpdate()->findOrFail($batchId);

            if ($batch->status !== 'paused') {
                throw new \Exception('Only paused batches can be resumed.');
            }

            $user = auth()->user();
            if ($user && $user->isSupervisor()) {
                if ($batch->machine->department_id !== $user->department_id) {
                    throw new \Exception('Unauthorized department access.');
                }
            }

            $batch->update([
                'status' => 'running',
            ]);

            ActivityLogService::log(
                'BATCH_RESUMED',
                "Production batch #{$batch->batch_no} was resumed.",
                auth()->id() ?? $batch->supervisor_id
            );

            return $batch;
        });
    }
}
