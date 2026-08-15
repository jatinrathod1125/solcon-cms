<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'name',
    'code',
    'brand_id',
    'department_id',
    'stock_unit_id',
    'purchase_unit_id',
    'purchase_conversion',
    'opening_stock',
    'current_stock',
    'minimum_stock',
    'maximum_stock',
    'description',
    'is_active',
    'is_coupon',
])]
class RawMaterial extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchase_conversion' => 'decimal:4',
            'opening_stock' => 'decimal:4',
            'current_stock' => 'decimal:4',
            'minimum_stock' => 'decimal:4',
            'maximum_stock' => 'decimal:4',
            'is_active' => 'boolean',
            'is_coupon' => 'boolean',
        ];
    }

    /**
     * Get the department that owns the raw material.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the brand that owns the raw material.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the stock unit of measurement for the raw material.
     */
    public function stockUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'stock_unit_id');
    }

    /**
     * Get the purchase unit of measurement for the raw material.
     */
    public function purchaseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }

    /**
     * Scope a query to include raw materials for a specific brand or common materials (brand_id IS NULL).
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
     * Scope a query to include raw materials for the current session brand or common materials.
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
