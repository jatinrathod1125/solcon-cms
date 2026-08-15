<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'brand_id',
    'category_id',
    'name',
    'code',
    'size',
    'unit_id',
    'minimum_stock',
    'opening_stock',
    'current_stock',
    'remarks',
    'status',
])]
class PackingMaterial extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'minimum_stock' => 'decimal:4',
            'opening_stock' => 'decimal:4',
            'current_stock' => 'decimal:4',
        ];
    }

    /**
     * Get the brand that owns this packing material.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Get the category that owns this packing material.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PackingMaterialCategory::class, 'category_id');
    }

    /**
     * Get the unit of measurement.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Scope a query to include packing materials for a specific brand or common materials (brand_id IS NULL).
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
     * Scope a query to include packing materials for the current session brand or common materials.
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
     * Get stock ledgers for this packing material.
     */
    public function stockLedgers(): HasMany
    {
        return $this->hasMany(StockLedger::class, 'packing_material_id');
    }

    /**
     * Check if item is active.
     */
    public function getIsActiveAttribute(): bool
    {
        return strtolower((string)$this->status) === 'active';
    }

    /**
     * Set active status via boolean or string.
     */
    public function setIsActiveAttribute($value): void
    {
        $this->attributes['status'] = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'active' : 'inactive';
    }

    /**
     * Check if stock level is below minimum stock.
     */
    public function isLowStock(): bool
    {
        return (float) $this->current_stock < (float) $this->minimum_stock;
    }
}
