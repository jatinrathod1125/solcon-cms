<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'batch_no',
    'machine_id',
    'grade_id',
    'formula_id',
    'formula_snapshot',
    'output_breakdown',
    'supervisor_id',
    'start_time',
    'end_time',
    'paused_at',
    'total_paused_seconds',
    'output_bags',
    'output_kg',
    'status',
    'remarks',
])]
class ProductionBatch extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'paused_at' => 'datetime',
            'total_paused_seconds' => 'integer',
            'output_bags' => 'decimal:4',
            'output_kg' => 'decimal:4',
            'formula_snapshot' => 'array',
            'output_breakdown' => 'array',
        ];
    }

    /**
     * Get the machine where this batch is processed.
     */
    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    /**
     * Get the grade of adhesive produced in this batch.
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the formula version used for raw material deductions.
     */
    public function formula(): BelongsTo
    {
        return $this->belongsTo(Formula::class);
    }

    /**
     * Get the supervisor who created/run the batch.
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Get the brand of the grade associated with this production batch.
     */
    public function getBrandAttribute(): ?Brand
    {
        return $this->grade?->brand;
    }

    /**
     * Scope a query to include batches for a specific brand or common grades via Grade relation.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Brand|int|string|null  $brand
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForBrand($query, $brand = null)
    {
        $brandId = $brand instanceof Brand ? $brand->id : $brand;
        if (!$brandId) {
            return $query;
        }
        return $query->where(function ($q) use ($brandId) {
            $q->whereHas('grade', function ($gQ) use ($brandId) {
                $gQ->forBrand($brandId);
            })
            ->orWhere('output_breakdown', 'like', '%"brand_id":' . $brandId . '%')
            ->orWhere('output_breakdown', 'like', '%"brand_id":"' . $brandId . '"%')
            ->orWhere('output_breakdown', 'like', '%"brand_id": ' . $brandId . '%');
        });
    }

    /**
     * Scope a query to include batches for the current session brand.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCurrentBrand($query)
    {
        $currentBrand = function_exists('currentBrand') ? currentBrand() : null;
        return $this->scopeForBrand($query, $currentBrand?->id);
    }

    /**
     * Get the stock ledger records created by this batch.
     */
    public function ledgers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StockLedger::class, 'batch_id');
    }

    /**
     * Get the active elapsed running seconds (excluding all paused duration).
     */
    public function getElapsedSecondsAttribute(): int
    {
        if (!$this->start_time) {
            return 0;
        }

        $totalPaused = (int) ($this->total_paused_seconds ?? 0);

        if ($this->status === 'paused') {
            $pauseMoment = $this->paused_at ?? $this->updated_at ?? now();
            $diff = (int) abs($pauseMoment->diffInSeconds($this->start_time));
            return max(0, $diff - $totalPaused);
        }

        if ($this->status === 'completed') {
            $endMoment = $this->end_time ?? $this->updated_at ?? now();
            $diff = (int) abs($endMoment->diffInSeconds($this->start_time));
            return max(0, $diff - $totalPaused);
        }

        if ($this->status === 'running') {
            $diff = (int) abs(now()->diffInSeconds($this->start_time));
            return max(0, $diff - $totalPaused);
        }

        return 0;
    }

    /**
     * Get the effective virtual start time for live JS timers.
     * (start_time + total_paused_seconds) ensures (now - effective_start_time) = active elapsed seconds.
     */
    public function getEffectiveStartTimeAttribute(): \Carbon\Carbon
    {
        $base = $this->start_time ? $this->start_time->copy() : now();
        $totalPaused = (int) ($this->total_paused_seconds ?? 0);
        return $base->addSeconds($totalPaused);
    }

    /**
     * Get formatted elapsed duration string: HH:MM:SS.
     */
    public function getFormattedDurationAttribute(): string
    {
        $seconds = $this->elapsed_seconds;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
    }

    /**
     * Get active run duration in whole minutes.
     */
    public function getDurationMinutesAttribute(): int
    {
        return (int) round($this->elapsed_seconds / 60);
    }
}
