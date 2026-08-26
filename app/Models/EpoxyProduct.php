<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'brand_id',
    'name',
    'code',
    'requires_color',
    'is_active',
    'description',
    'created_by',
    'updated_by',
])]
class EpoxyProduct extends Model
{
    protected function casts(): array
    {
        return [
            'requires_color' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the brand that owns this epoxy product.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Get the formulas for this product.
     */
    public function formulas(): HasMany
    {
        return $this->hasMany(EpoxyFormula::class);
    }

    /**
     * Get the active formula for this product.
     */
    public function activeFormula(): HasOne
    {
        return $this->hasOne(EpoxyFormula::class)->where('is_active', true);
    }

    /**
     * Creator relationship.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to include epoxy products for a specific brand or common products (brand_id IS NULL).
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
            $q->where('brand_id', $brandId)
                ->orWhereNull('brand_id');
        });
    }

    /**
     * Scope a query to include epoxy products for the current session brand or common products.
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
