<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'epoxy_product_id',
    'version',
    'is_active',
    'description',
    'created_by',
])]
class EpoxyFormula extends Model
{
    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the brand of the product associated with this formula.
     */
    public function getBrandAttribute(): ?Brand
    {
        return $this->product?->brand;
    }

    /**
     * Get the product this formula belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(EpoxyProduct::class, 'epoxy_product_id');
    }

    /**
     * Get the items in this formula.
     */
    public function items(): HasMany
    {
        return $this->hasMany(EpoxyFormulaItem::class, 'epoxy_formula_id');
    }

    /**
     * Creator relationship.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to include epoxy formulas for a specific brand or common formulas via EpoxyProduct relation.
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
        return $query->whereHas('product', function ($q) use ($brandId) {
            $q->forBrand($brandId);
        });
    }

    /**
     * Scope a query to include epoxy formulas for the current session brand or common formulas.
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
