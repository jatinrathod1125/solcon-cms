<?php

namespace App\Services;

use App\Models\ProductionBatch;

class BatchNumberService
{
    /**
     * Generate a unique sequential batch number.
     */
    public static function generate(string $prefix = 'ADH', string $modelClass = ProductionBatch::class): string
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($prefix, $modelClass) {
            $today = now()->format('Ymd');
            $dateStr = now()->toDateString();

            // Lock existing rows for today to serialize generation
            $modelClass::whereDate('created_at', $dateStr)
                ->lockForUpdate()
                ->first();

            $count = $modelClass::whereDate('created_at', $dateStr)->count();
            return $prefix . '-' . $today . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}
