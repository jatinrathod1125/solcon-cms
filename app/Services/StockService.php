<?php

namespace App\Services;

use App\Models\RawMaterial;
use App\Models\StockLedger;
use App\Models\StockAdjustment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService
{
    /**
     * Record a stock ledger movement and update raw material current stock.
     */
    public static function recordMovement(
        int $rawMaterialId,
        float $quantity,
        string $transactionType,
        ?int $batchId = null,
        ?string $remarks = null,
        ?int $groutBatchId = null,
        ?int $epoxyAssemblyId = null
    ): StockLedger {
        return DB::transaction(function () use ($rawMaterialId, $quantity, $transactionType, $batchId, $remarks, $groutBatchId, $epoxyAssemblyId) {
            // Obtain a pessimistic lock on the raw material record to prevent race conditions
            $rawMaterial = RawMaterial::lockForUpdate()->findOrFail($rawMaterialId);
            $balanceBefore = (float) $rawMaterial->current_stock;

            // Determine signed quantity representation
            $quantityToStore = (float) $quantity;
            if ($transactionType === 'OUT') {
                $quantityToStore = -abs($quantity);
            } elseif ($transactionType === 'IN') {
                $quantityToStore = abs($quantity);
            }

            $balanceAfter = $balanceBefore + $quantityToStore;

            // Prevent stock from dropping below zero
            if ($balanceAfter < 0) {
                throw new \Exception("Insufficient stock for raw material '{$rawMaterial->name}'. Current available stock: " . number_format($balanceBefore, 4) . ".");
            }

            // Update Current Stock
            $rawMaterial->update([
                'current_stock' => $balanceAfter,
            ]);

            // Low Stock Check
            if ($balanceAfter < (float) $rawMaterial->minimum_stock) {
                Log::warning("Low Stock Alert: Raw Material '{$rawMaterial->name}' (Code: {$rawMaterial->code}) has current stock of {$balanceAfter} {$rawMaterial->stockUnit->code}, which is below its minimum stock of {$rawMaterial->minimum_stock}.");
            }

            // Log Ledger Entry
            return StockLedger::create([
                'raw_material_id' => $rawMaterialId,
                'batch_id' => $batchId,
                'grout_batch_id' => $groutBatchId,
                'epoxy_assembly_id' => $epoxyAssemblyId,
                'transaction_type' => $transactionType,
                'quantity' => $quantityToStore,
                'balance_after' => $balanceAfter,
                'remarks' => $remarks,
                'created_by' => auth()->id() ?? User::where('email', 'admin@solcon.com')->first()?->id ?? 1,
            ]);
        });
    }

    /**
     * Perform a manual stock adjustment.
     */
    public static function adjustStock(int $rawMaterialId, float $quantity, string $remarks): StockAdjustment
    {
        return DB::transaction(function () use ($rawMaterialId, $quantity, $remarks) {
            $userId = auth()->id() ?? User::where('email', 'admin@solcon.com')->first()?->id ?? 1;

            $adjustment = StockAdjustment::create([
                'raw_material_id' => $rawMaterialId,
                'quantity' => $quantity,
                'remarks' => $remarks,
                'created_by' => $userId,
            ]);

            // Log stock movement
            self::recordMovement(
                $rawMaterialId,
                $quantity,
                'ADJUSTMENT',
                null,
                "Adjustment: {$remarks}"
            );

            // Audit Log
            ActivityLogService::log(
                'STOCK_ADJUSTMENT',
                "Manual stock adjustment of {$quantity} for raw material ID {$rawMaterialId}. Remarks: {$remarks}",
                $userId
            );

            return $adjustment;
        });
    }
}
