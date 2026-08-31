<?php

namespace App\Services;

use App\Models\ProductionBatch;
use Illuminate\Support\Facades\DB;

class BatchNumberService
{
    /**
     * Generate a unique sequential batch number.
     * For Adhesive (ProductionBatch): Machine-wise sequential number 1, 2, 3... reset daily.
     * For other models: Standard prefixed format.
     */
    public static function generate(string $prefix = 'ADH', string $modelClass = ProductionBatch::class, ?int $machineId = null): string
    {
        return DB::transaction(function () use ($prefix, $modelClass, $machineId) {
            $today = now()->format('Ymd');
            $dateStr = now()->toDateString();

            if ($modelClass === ProductionBatch::class) {
                // Adhesive batches: 1, 2, 3... starting from 1 daily per machine
                $query = ProductionBatch::where(function ($q) use ($dateStr) {
                    $q->whereDate('created_at', $dateStr)
                      ->orWhereDate('start_time', $dateStr);
                });

                if ($machineId) {
                    $query->where('machine_id', $machineId);
                }

                $todayBatches = $query->pluck('batch_no');

                $maxNum = 0;
                foreach ($todayBatches as $bNo) {
                    if (is_numeric($bNo) && (int) $bNo > $maxNum) {
                        $maxNum = (int) $bNo;
                    }
                }

                return (string) ($maxNum + 1);
            }

            // Lock existing rows for today to serialize generation
            $modelClass::whereDate('created_at', $dateStr)
                ->lockForUpdate()
                ->first();

            $count = $modelClass::whereDate('created_at', $dateStr)->count();
            return $prefix . '-' . $today . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}
