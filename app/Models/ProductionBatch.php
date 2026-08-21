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
    'supervisor_id',
    'start_time',
    'end_time',
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
            'output_bags' => 'decimal:4',
            'output_kg' => 'decimal:4',
            'formula_snapshot' => 'array',
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
        return $query->whereHas('grade', function ($q) use ($brandId) {
            $q->forBrand($brandId);
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
}
