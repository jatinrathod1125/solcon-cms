<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'batch_no',
    'machine_id',
    'color_id',
    'grout_formula_id',
    'formula_snapshot',
    'operator_id',
    'status',
    'start_time',
    'timer_start_time',
    'timer_end_time',
    'stage1_start_time',
    'stage1_end_time',
    'stage2_start_time',
    'stage2_end_time',
    'packing_start_time',
    'packing_end_time',
    'finished_bags',
    'total_weight_kg',
    'remarks',
    'timer_skipped',
    'skipped_by_id',
    'skip_reason',
    'skipped_at',
])]
class GroutProductionBatch extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'formula_snapshot' => 'array',
            'start_time' => 'datetime',
            'timer_start_time' => 'datetime',
            'timer_end_time' => 'datetime',
            'stage1_start_time' => 'datetime',
            'stage1_end_time' => 'datetime',
            'stage2_start_time' => 'datetime',
            'stage2_end_time' => 'datetime',
            'packing_start_time' => 'datetime',
            'packing_end_time' => 'datetime',
            'total_weight_kg' => 'decimal:4',
            'finished_bags' => 'integer',
            'timer_skipped' => 'boolean',
            'skipped_at' => 'datetime',
        ];
    }

    /**
     * Get the user who skipped the timer.
     */
    public function skippedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'skipped_by_id');
    }

    /**
     * Get the machine where this batch is running.
     */
    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    /**
     * Get the color of this grout batch.
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Get the formula used for this batch.
     */
    public function formula(): BelongsTo
    {
        return $this->belongsTo(GroutFormula::class, 'grout_formula_id');
    }

    /**
     * Get the operator/supervisor managing this batch.
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    /**
     * Get the brand of the color associated with this batch.
     */
    public function getBrandAttribute(): ?Brand
    {
        return $this->color?->brand;
    }

    /**
     * Get the stock ledger movements created by this batch.
     */
    public function ledgers(): HasMany
    {
        return $this->hasMany(StockLedger::class, 'grout_batch_id');
    }

    /**
     * Scope a query to include grout batches for a specific brand or common batches via Color relation.
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
        return $query->whereHas('color', function ($q) use ($brandId) {
            $q->forBrand($brandId);
        });
    }

    /**
     * Scope a query to include grout batches for the current session brand or common batches.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCurrentBrand($query)
    {
        $currentBrand = function_exists('currentBrand') ? currentBrand() : null;
        return $this->scopeForBrand($query, $currentBrand?->id);
    }
}
