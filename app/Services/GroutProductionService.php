<?php

namespace App\Services;

use App\Models\GroutProductionBatch;
use App\Models\Color;
use App\Models\Machine;
use App\Models\RawMaterial;
use App\Services\GroutFormulaService;
use App\Services\StockService;
use App\Services\ActivityLogService;
use App\Services\BatchNumberService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GroutProductionService
{
    protected $formulaService;

    public function __construct(GroutFormulaService $formulaService)
    {
        $this->formulaService = $formulaService;
    }

    /**
     * Start a new Grout production batch.
     */
    public function startBatch(int $machineId, int $colorId, ?string $remarks = null, ?string $customBatchNo = null): GroutProductionBatch
    {
        return DB::transaction(function () use ($machineId, $colorId, $remarks, $customBatchNo) {
            $user = auth()->user();
            $machine = Machine::where('id', $machineId)->lockForUpdate()->firstOrFail();
            $color = Color::findOrFail($colorId);

            // 1. Verify access control
            if ($user && $user->isSupervisor()) {
                if ($machine->department_id !== $user->department_id || $color->department_id !== $user->department_id) {
                    throw ValidationException::withMessages([
                        'machine_id' => 'Unauthorized department access.'
                    ]);
                }
            }

            // 2. Validate machine rules
            if ($machine->code === 'M-01') {
                // M-01 is used only for White and Ivory colors
                $colorCodeUpper = strtoupper($color->code);
                if (!str_contains($colorCodeUpper, 'WHT') && !str_contains($colorCodeUpper, 'IVO') && !str_contains(strtoupper($color->name), 'WHITE') && !str_contains(strtoupper($color->name), 'IVORY')) {
                    throw ValidationException::withMessages([
                        'color_id' => 'Machine M-01 is restricted to White and Ivory colors only.'
                    ]);
                }
            }

            // 3. One running job per machine
            $isRunning = GroutProductionBatch::where('machine_id', $machineId)
                ->where('status', '!=', 'Completed')
                ->exists();
            if ($isRunning) {
                throw ValidationException::withMessages([
                    'machine_id' => 'This machine is already running a production batch.'
                ]);
            }

            // 4. Color must have an active formula
            $formula = $this->formulaService->loadActiveFormula($colorId);
            if (!$formula) {
                throw ValidationException::withMessages([
                    'color_id' => 'The selected color does not have an active Grout Formula configured.'
                ]);
            }

            // 5. Create formula snapshot
            $snapshot = [];
            foreach ($formula->items as $item) {
                $snapshot[] = [
                    'raw_material_id' => $item->raw_material_id,
                    'raw_material_name' => $item->rawMaterial->name,
                    'raw_material_code' => $item->rawMaterial->code,
                    'quantity' => (float) $item->quantity,
                    'unit_code' => $item->unit->code,
                    'unit_id' => $item->unit_id,
                    'mix_stage' => $item->mix_stage,
                ];
            }

            $cleanBatchNo = trim($customBatchNo ?? '');
            if (!empty($cleanBatchNo)) {
                if (GroutProductionBatch::where('batch_no', $cleanBatchNo)->exists()) {
                    throw ValidationException::withMessages([
                        'batch_no' => ['The specified batch number already exists.'],
                    ]);
                }
                $batchNo = $cleanBatchNo;
            } else {
                $batchNo = BatchNumberService::generate('GRT', GroutProductionBatch::class);
            }
            $userId = auth()->id() ?? 1;

            $batch = GroutProductionBatch::create([
                'batch_no' => $batchNo,
                'machine_id' => $machineId,
                'color_id' => $colorId,
                'grout_formula_id' => $formula->id,
                'formula_snapshot' => $snapshot,
                'operator_id' => $userId,
                'status' => 'Waiting',
                'start_time' => now(),
                'remarks' => $remarks,
            ]);

            // Transition manual mixers straight to Stage 1 Mixing on start
            if ($machine->code !== 'M-01') {
                $batch->update([
                    'status' => 'Stage 1 Mixing',
                    'start_time' => now(),
                    'stage1_start_time' => now(),
                ]);
            } else {
                // M-01 is automatic, starts in Stage 1 Mixing and can pack
                $batch->update([
                    'status' => 'Stage 1 Mixing',
                    'start_time' => now(),
                    'stage1_start_time' => now(),
                ]);
            }

            ActivityLogService::log(
                'BATCH_CREATED',
                "Grout production batch #{$batchNo} started on machine {$machine->code} (Color: {$color->name}).",
                $userId
            );

            return $batch;
        });
    }

    /**
     * Start the 1-hour mixing timer for manual machines.
     */
    public function startTimer(int $batchId): GroutProductionBatch
    {
        return DB::transaction(function () use ($batchId) {
            $batch = GroutProductionBatch::lockForUpdate()->findOrFail($batchId);

            if ($batch->status !== 'Stage 1 Mixing') {
                throw new \Exception('Timer can only be started from Stage 1 Mixing.');
            }

            $batch->update([
                'status' => 'Timer Running',
                'timer_start_time' => now(),
                'timer_end_time' => now()->addMinutes(1),
            ]);

            ActivityLogService::log(
                'TIMER_STARTED',
                "5-Minute dry mix timer started for Grout batch #{$batch->batch_no} on machine {$batch->machine->code}.",
                auth()->id()
            );

            return $batch;
        });
    }

    /**
     * Proceed to Stage 2 after timer completes.
     */
    public function proceedToStage2(int $batchId): GroutProductionBatch
    {
        return DB::transaction(function () use ($batchId) {
            $batch = GroutProductionBatch::lockForUpdate()->findOrFail($batchId);

            // M-01 automatic packing can proceed without timer validation, but manual machines require timer completion
            if ($batch->machine->code !== 'M-01') {
                if ($batch->status !== 'Timer Running') {
                    throw new \Exception('Must start the mixing timer first.');
                }

                $timerService = app(MixTimerService::class);
                if (!$timerService->isTimerCompleted($batch)) {
                    throw new \Exception('Cannot proceed to Stage 2: 1-Hour mixing timer is still running.');
                }
            } else {
                if ($batch->status !== 'Stage 1 Mixing') {
                    throw new \Exception('Invalid state transition for machine M-01.');
                }
                $batch->update(['stage1_end_time' => now()]);
            }

            $batch->update([
                'status' => 'Stage 2 Mixing',
                'stage1_end_time' => $batch->stage1_end_time ?? now(),
                'stage2_start_time' => now(),
            ]);

            ActivityLogService::log(
                'STAGE2_STARTED',
                "Transitioned Grout batch #{$batch->batch_no} to Stage 2 Mixing.",
                auth()->id()
            );

            return $batch;
        });
    }

    /**
     * Finish Stage 2 mixing.
     */
    public function finishMixing(int $batchId): GroutProductionBatch
    {
        return DB::transaction(function () use ($batchId) {
            $batch = GroutProductionBatch::lockForUpdate()->findOrFail($batchId);

            $isM01 = $batch->machine->code === 'M-01';
            $expectedStatus = $isM01 ? 'Timer Running' : 'Stage 2 Mixing';

            if ($batch->status !== $expectedStatus) {
                throw new \Exception("Mixing can only be finished from {$expectedStatus}.");
            }

            if ($isM01) {
                $batch->update([
                    'status' => 'Ready For Packing',
                    'stage1_end_time' => now(),
                ]);
            } else {
                $batch->update([
                    'status' => 'Ready For Packing',
                    'stage2_end_time' => now(),
                ]);
            }

            ActivityLogService::log(
                'MIXING_COMPLETED',
                "Mixing completed for Grout batch #{$batch->batch_no}. Status: Ready For Packing.",
                auth()->id()
            );

            return $batch;
        });
    }

    /**
     * Start the packing stage.
     */
    public function startPacking(int $batchId): GroutProductionBatch
    {
        return DB::transaction(function () use ($batchId) {
            $batch = GroutProductionBatch::lockForUpdate()->findOrFail($batchId);

            if ($batch->status !== 'Ready For Packing' && $batch->machine->code !== 'M-01') {
                throw new \Exception('Packing can only start when mixing is completed.');
            }

            // For M-01, they can transition from Stage 1 directly if needed, or Stage 2
            if ($batch->machine->code === 'M-01' && $batch->status === 'Stage 2 Mixing') {
                $batch->update(['stage2_end_time' => now()]);
            }

            $batch->update([
                'status' => 'Packing',
                'packing_start_time' => now(),
            ]);

            ActivityLogService::log(
                'PACKING_STARTED',
                "Packing started for Grout batch #{$batch->batch_no}.",
                auth()->id()
            );

            return $batch;
        });
    }

    /**
     * Complete the Grout production batch, calculating proportional raw material consumption and deducting stock.
     */
    public function completeBatch(int $batchId, int $finishedBags, ?string $remarks = null): GroutProductionBatch
    {
        return DB::transaction(function () use ($batchId, $finishedBags, $remarks) {
            $batch = GroutProductionBatch::lockForUpdate()->findOrFail($batchId);

            if ($batch->status !== 'Packing' && $batch->status !== 'Ready For Packing' && $batch->machine->code !== 'M-01') {
                throw new \Exception('Batch must be in Packing state to complete.');
            }

            if ($finishedBags <= 0) {
                throw new \Exception('Cannot complete production without specifying a valid bag quantity.');
            }

            // For M-01, update state indicators
            if ($batch->machine->code === 'M-01') {
                if ($batch->status === 'Stage 1 Mixing') {
                    $batch->update([
                        'stage1_end_time' => now(),
                        'stage2_start_time' => now(),
                        'stage2_end_time' => now(),
                        'packing_start_time' => now(),
                    ]);
                } elseif ($batch->status === 'Stage 2 Mixing') {
                    $batch->update([
                        'stage2_end_time' => now(),
                        'packing_start_time' => now(),
                    ]);
                }
            }

            $packingService = app(PackingService::class);
            $totalWeightKg = $packingService->calculateTotalWeight($finishedBags);

            // Calculate consumption based on formula snapshot
            $snapshot = $batch->formula_snapshot;

            // Proportional scaling: Sum snapshot quantities to find base batch weight (usually 1000 KG)
            $totalBasis = array_sum(array_column($snapshot, 'quantity'));
            if ($totalBasis <= 0) {
                $totalBasis = 1000.0;
            }
            $factor = $totalWeightKg / $totalBasis;

            // 1. Verify stock levels before deduction
            foreach ($snapshot as $item) {
                $rawMat = RawMaterial::lockForUpdate()->findOrFail($item['raw_material_id']);
                $consumedQty = $item['quantity'] * $factor;

                if ((float) $rawMat->current_stock < $consumedQty) {
                    ActivityLogService::log(
                        'FAILED_STOCK_VALIDATION',
                        "Failed to complete Grout batch #{$batch->batch_no} due to insufficient stock of {$rawMat->name}.",
                        auth()->id() ?? $batch->operator_id
                    );
                    throw new \Exception("Insufficient stock for raw material: {$rawMat->name}. Required: " . number_format($consumedQty, 4) . ", Available: " . number_format($rawMat->current_stock, 4));
                }
            }

            // 2. Deduct stock & create ledger entries
            foreach ($snapshot as $item) {
                $consumedQty = $item['quantity'] * $factor;
                StockService::recordMovement(
                    $item['raw_material_id'],
                    $consumedQty,
                    'OUT',
                    null, // Adhesive batch_id is null
                    "Consumed in Grout production batch #{$batch->batch_no}",
                    $batch->id // Grout batch ID is logged here
                );
            }

            // 3. Complete batch
            $batch->update([
                'status' => 'Completed',
                'packing_end_time' => now(),
                'finished_bags' => $finishedBags,
                'total_weight_kg' => $totalWeightKg,
                'remarks' => $remarks ?? $batch->remarks,
            ]);

            // Update Finished Goods Stock
            app(\App\Services\FinishedGoodsService::class)->incrementGroutStock(
                $batch->color_id,
                $batch->color->packing_size,
                (int) $finishedBags,
                (float) $totalWeightKg
            );

            $userId = auth()->id() ?? $batch->operator_id;
            ActivityLogService::log(
                'BATCH_COMPLETED',
                "Grout production batch #{$batch->batch_no} completed. Output: {$finishedBags} bags ({$totalWeightKg} KG).",
                $userId
            );
            ActivityLogService::log(
                'STOCK_DEDUCTED',
                "Stock deducted for Grout production batch #{$batch->batch_no}.",
                $userId
            );
            ActivityLogService::log(
                'LEDGER_CREATED',
                "Stock ledger entries created for Grout production batch #{$batch->batch_no}.",
                $userId
            );

            return $batch;
        });
    }

    /**
     * Skip the mixing timer for a batch.
     */
    public function skipTimer(int $batchId, string $reason): GroutProductionBatch
    {
        return DB::transaction(function () use ($batchId, $reason) {
            $batch = GroutProductionBatch::lockForUpdate()->findOrFail($batchId);

            if ($batch->status !== 'Timer Running') {
                throw new \Exception('Timer can only be skipped when it is running.');
            }

            $user = auth()->user();
            if (!$user || !$user->canSkipTimer()) {
                throw new \Exception('Unauthorized to skip the production timer.');
            }

            if (empty(trim($reason))) {
                throw new \Exception('A reason is required to skip the mixing timer.');
            }

            $batch->update([
                'timer_skipped' => true,
                'skipped_by_id' => $user->id,
                'skip_reason' => $reason,
                'skipped_at' => now(),
                'timer_end_time' => now(), // End the timer immediately
            ]);

            ActivityLogService::log(
                'TIMER_SKIPPED',
                "Timer skipped for Grout batch #{$batch->batch_no} on machine {$batch->machine->code}. Reason: {$reason}. Skipped by: {$user->name}.",
                $user->id
            );

            return $batch;
        });
    }
}
