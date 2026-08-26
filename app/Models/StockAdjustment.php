<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'raw_material_id',
    'packing_material_id',
    'quantity',
    'remarks',
    'created_by',
])]
class StockAdjustment extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
        ];
    }

    /**
     * Get the raw material associated with this stock adjustment.
     */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class);
    }

    /**
     * Get the packing material associated with this stock adjustment.
     */
    public function packingMaterial(): BelongsTo
    {
        return $this->belongsTo(PackingMaterial::class, 'packing_material_id');
    }

    /**
     * Get the user who recorded this stock adjustment.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to include stock adjustments for a specific brand or common materials.
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
            $q->whereHas('rawMaterial', function ($rmQ) use ($brandId) {
                $rmQ->forBrand($brandId);
            })->orWhereHas('packingMaterial', function ($pmQ) use ($brandId) {
                $pmQ->forBrand($brandId);
            });
        });
    }

    /**
     * Scope a query to include stock adjustments for the current session brand or common materials.
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
