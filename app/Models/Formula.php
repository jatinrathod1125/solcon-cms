<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'grade_id',
    'version',
    'remarks',
    'is_active',
    'created_by',
])]
class Formula extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the brand of the grade associated with this formula.
     */
    public function getBrandAttribute(): ?Brand
    {
        return $this->grade?->brand;
    }

    /**
     * Get the grade that owns this formula.
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the items (raw materials) that make up this formula.
     */
    public function items(): HasMany
    {
        return $this->hasMany(FormulaItem::class)->orderBy('sequence');
    }

    /**
     * Get the user who created this formula version.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to include formulas for a specific brand or common formulas via Grade relation.
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
     * Scope a query to include formulas for the current session brand or common formulas.
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
