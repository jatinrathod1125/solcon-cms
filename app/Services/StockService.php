<?php

namespace App\Services;

use App\Models\RawMaterial;
use App\Models\PackingMaterial;
use App\Models\StockLedger;
use App\Models\StockAdjustment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService
{
    /**
     * Record a stock ledger movement and update current stock for raw material or packing material.
     */
    public static function recordMovement(
        ?int $rawMaterialId = null,
        float $quantity = 0.0,
        string $transactionType = 'IN',
        ?int $batchId = null,
        ?string $remarks = null,
        ?int $groutBatchId = null,
        ?int $epoxyAssemblyId = null,
        ?int $packingMaterialId = null
    ): StockLedger {
        return DB::transaction(function () use (
            $rawMaterialId,
            $quantity,
            $transactionType,
            $batchId,
            $remarks,
            $groutBatchId,
            $epoxyAssemblyId,
            $packingMaterialId
        ) {
            if (!$rawMaterialId && !$packingMaterialId) {
                throw new \InvalidArgumentException("Either Raw Material ID or Packing Material ID must be provided for stock movement.");
            }

            // Determine signed quantity representation
            $quantityToStore = (float) $quantity;
            if ($transactionType === 'OUT') {
                $quantityToStore = -abs($quantity);
            } elseif ($transactionType === 'IN') {
                $quantityToStore = abs($quantity);
            }

            $userId = auth()->id() ?? User::where('email', 'admin@solcon.com')->first()?->id ?? 1;

            if ($packingMaterialId) {
                $packingMaterial = PackingMaterial::lockForUpdate()->findOrFail($packingMaterialId);
                $balanceBefore = (float) $packingMaterial->current_stock;
                $balanceAfter = $balanceBefore + $quantityToStore;

                if ($balanceAfter < 0) {
                    throw new \Exception("Insufficient stock for packing material '{$packingMaterial->name}'. Current available stock: " . format_quantity($balanceBefore) . ".");
                }

                $packingMaterial->update(['current_stock' => $balanceAfter]);

                if ($balanceAfter < (float) $packingMaterial->minimum_stock) {
                    Log::warning("Low Stock Alert: Packing Material '{$packingMaterial->name}' (Code: {$packingMaterial->code}) current stock is {$balanceAfter}, below minimum of {$packingMaterial->minimum_stock}.");
                }

                return StockLedger::create([
                    'packing_material_id' => $packingMaterialId,
                    'batch_id' => $batchId,
                    'grout_batch_id' => $groutBatchId,
                    'epoxy_assembly_id' => $epoxyAssemblyId,
                    'transaction_type' => $transactionType,
                    'quantity' => $quantityToStore,
                    'balance_after' => $balanceAfter,
                    'remarks' => $remarks,
                    'created_by' => $userId,
                ]);
            } else {
                $rawMaterial = RawMaterial::lockForUpdate()->findOrFail($rawMaterialId);
                $balanceBefore = (float) $rawMaterial->current_stock;
                $balanceAfter = $balanceBefore + $quantityToStore;

                if ($balanceAfter < 0) {
                    throw new \Exception("Insufficient stock for raw material '{$rawMaterial->name}'. Current available stock: " . format_quantity($balanceBefore) . ".");
                }

                $rawMaterial->update(['current_stock' => $balanceAfter]);

                if ($balanceAfter < (float) $rawMaterial->minimum_stock) {
                    Log::warning("Low Stock Alert: Raw Material '{$rawMaterial->name}' (Code: {$rawMaterial->code}) has current stock of {$balanceAfter} {$rawMaterial->stockUnit->code}, which is below its minimum stock of {$rawMaterial->minimum_stock}.");
                }

                return StockLedger::create([
                    'raw_material_id' => $rawMaterialId,
                    'batch_id' => $batchId,
                    'grout_batch_id' => $groutBatchId,
                    'epoxy_assembly_id' => $epoxyAssemblyId,
                    'transaction_type' => $transactionType,
                    'quantity' => $quantityToStore,
                    'balance_after' => $balanceAfter,
                    'remarks' => $remarks,
                    'created_by' => $userId,
                ]);
            }
        });
    }

    /**
     * Perform a manual stock adjustment for raw material or packing material.
     */
    public static function adjustStock(
        ?int $rawMaterialId,
        float $quantity,
        ?string $remarks = null,
        ?int $packingMaterialId = null
    ): StockAdjustment {
        return DB::transaction(function () use ($rawMaterialId, $quantity, $remarks, $packingMaterialId) {
            $userId = auth()->id() ?? User::where('email', 'admin@solcon.com')->first()?->id ?? 1;

            if (!$rawMaterialId && !$packingMaterialId) {
                throw new \InvalidArgumentException("Either Raw Material ID or Packing Material ID must be provided for stock adjustment.");
            }

            $adjustment = StockAdjustment::create([
                'raw_material_id' => $rawMaterialId,
                'packing_material_id' => $packingMaterialId,
                'quantity' => $quantity,
                'remarks' => $remarks ?? '',
                'created_by' => $userId,
            ]);

            $movementRemarks = $remarks ? "Adjustment: {$remarks}" : "Manual Stock Adjustment";

            if ($packingMaterialId) {
                self::recordMovement(
                    null,
                    $quantity,
                    'ADJUSTMENT',
                    null,
                    $movementRemarks,
                    null,
                    null,
                    $packingMaterialId
                );

                ActivityLogService::log(
                    'STOCK_ADJUSTMENT',
                    "Manual stock adjustment of {$quantity} for packing material ID {$packingMaterialId}." . ($remarks ? " Remarks: {$remarks}" : ""),
                    $userId
                );
            } else {
                self::recordMovement(
                    $rawMaterialId,
                    $quantity,
                    'ADJUSTMENT',
                    null,
                    $movementRemarks
                );

                ActivityLogService::log(
                    'STOCK_ADJUSTMENT',
                    "Manual stock adjustment of {$quantity} for raw material ID {$rawMaterialId}." . ($remarks ? " Remarks: {$remarks}" : ""),
                    $userId
                );
            }

            return $adjustment;
        });
    }
}

