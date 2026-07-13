<?php

namespace App\Services;

use App\Models\GroutProductionBatch;
use Carbon\Carbon;

class MixTimerService
{
    /**
     * Get the remaining seconds for a batch's active mixing timer.
     *
     * @param GroutProductionBatch $batch
     * @return int
     */
    public function getRemainingSeconds(GroutProductionBatch $batch): int
    {
        if ($batch->status === 'Timer Running' && $batch->timer_end_time) {
            $diff = now()->diffInSeconds($batch->timer_end_time, false);
            return $diff > 0 ? $diff : 0;
        }

        // If it's already transitioned past Timer Running, remaining time is 0
        if (in_array($batch->status, ['Waiting Cement', 'Stage 2 Mixing', 'Ready For Packing', 'Packing', 'Completed'])) {
            return 0;
        }

        // Default: 1 hour (3600 seconds)
        return 3600;
    }

    /**
     * Determine if the mixing timer is completed.
     *
     * @param GroutProductionBatch $batch
     * @return bool
     */
    public function isTimerCompleted(GroutProductionBatch $batch): bool
    {
        if (in_array($batch->status, ['Waiting Cement', 'Stage 2 Mixing', 'Ready For Packing', 'Packing', 'Completed'])) {
            return true;
        }

        if ($batch->status === 'Timer Running' && $batch->timer_end_time) {
            return now()->greaterThanOrEqualTo($batch->timer_end_time);
        }

        return false;
    }
}
