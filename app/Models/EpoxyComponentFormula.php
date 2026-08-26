<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EpoxyComponentFormula extends Model
{
    protected $fillable = [
        'epoxy_component_id',
        'version',
        'is_active',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'version' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the brand of the component associated with this formula.
     */
    public function getBrandAttribute(): ?Brand
    {
        return $this->component?->brand;
    }

    /**
     * Get the component this formula belongs to.
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(EpoxyComponent::class, 'epoxy_component_id');
    }

    /**
     * Get the items in this formula.
     */
    public function items(): HasMany
    {
        return $this->hasMany(EpoxyComponentFormulaItem::class, 'epoxy_component_formula_id');
    }

    /**
     * Creator relationship.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Editor relationship.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to include epoxy component formulas for a specific brand or common formulas via EpoxyComponent relation.
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
        return $query->whereHas('component', function ($q) use ($brandId) {
            $q->forBrand($brandId);
        });
    }

    /**
     * Scope a query to include epoxy component formulas for the current session brand or common formulas.
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
