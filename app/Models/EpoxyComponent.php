<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EpoxyComponent extends Model
{
    protected $fillable = [
        'brand_id',
        'name',
        'code',
        'category', // Bottle, Pouch, Packet, Liquid, Powder, Plastic, Accessory, Other
        'purpose', // Assembly Component, Direct Finished Product
        'unit_id',
        'is_active',
        'description',
        'raw_material_id', // Points to the ready component's RawMaterial record representing prepared stock
        'parent_component_id', // Points to generic component (e.g. 700gm Filler Pouch) for color variants
        'epoxy_filler_color_id', // Points to the filler color if color-specific
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the brand that owns this epoxy component.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Get parent component.
     */
    public function parentComponent(): BelongsTo
    {
        return $this->belongsTo(EpoxyComponent::class, 'parent_component_id');
    }

    /**
     * Get child components.
     */
    public function childComponents(): HasMany
    {
        return $this->hasMany(EpoxyComponent::class, 'parent_component_id');
    }

    /**
     * Get raw material.
     */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }

    /**
     * Get unit.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Get color.
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(EpoxyFillerColor::class, 'epoxy_filler_color_id');
    }

    /**
     * Get formulas.
     */
    public function formulas(): HasMany
    {
        return $this->hasMany(EpoxyComponentFormula::class, 'epoxy_component_id');
    }

    /**
     * Get active formula.
     */
    public function activeFormula(): HasOne
    {
        return $this->hasOne(EpoxyComponentFormula::class, 'epoxy_component_id')->where('is_active', true);
    }

    /**
     * Get finished goods stock entries.
     */
    public function finishedGoods(): HasMany
    {
        return $this->hasMany(FinishedGood::class, 'epoxy_component_id');
    }

    /**
     * Scope a query to include epoxy components for a specific brand or common components (brand_id IS NULL).
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
     * Scope a query to include epoxy components for the current session brand or common components.
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
     * Get total available ready stock across finished goods & raw material.
     */
    public function getAvailableStockAttribute(): float
    {
        $fgStock = (float) $this->finishedGoods->sum('available_bags');
        if ($fgStock > 0) {
            return $fgStock;
        }
        return $this->rawMaterial ? (float) $this->rawMaterial->current_stock : 0.0;
    }
}
