<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinishedGood extends Model
{
    protected $fillable = [
        'department_id',
        'grade_id',
        'color_id',
        'epoxy_filler_color_id',
        'epoxy_product_id',
        'epoxy_component_id',
        'coupon_raw_material_id',
        'packing',
        'available_bags',
        'available_weight',
        'minimum_stock',
        'last_production_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'available_bags' => 'integer',
        'available_weight' => 'decimal:4',
        'minimum_stock' => 'integer',
        'last_production_date' => 'datetime',
    ];

    /**
     * Get the department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the coupon raw material.
     */
    public function couponMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class, 'coupon_raw_material_id');
    }

    /**
     * Get the grade (Adhesive).
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the color (Grout).
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Get the epoxy filler color.
     */
    public function epoxyFillerColor(): BelongsTo
    {
        return $this->belongsTo(EpoxyFillerColor::class, 'epoxy_filler_color_id');
    }

    /**
     * Get the epoxy product (Epoxy).
     */
    public function epoxyProduct(): BelongsTo
    {
        return $this->belongsTo(EpoxyProduct::class);
    }

    /**
     * Get the epoxy component.
     */
    public function epoxyComponent(): BelongsTo
    {
        return $this->belongsTo(EpoxyComponent::class, 'epoxy_component_id');
    }

    /**
     * Accessor for Product Name based on relations
     */
    public function getProductNameAttribute(): string
    {
        if ($this->grade) {
            $name = $this->grade->name;
            if ($this->coupon_raw_material_id && $this->couponMaterial) {
                return $name . ' (' . $this->couponMaterial->name . ')';
            }
            return $name;
        }

        if ($this->epoxyComponent) {
            return $this->epoxyComponent->name;
        }

        if ($this->epoxyProduct) {
            $prodName = $this->epoxyProduct->name;
            if ($this->epoxy_filler_color_id && $this->epoxyFillerColor) {
                return $prodName . ' (' . $this->epoxyFillerColor->name . ')';
            }
            if ($this->color_id && $this->color) {
                return $prodName . ' (' . $this->color->name . ')';
            }
            return $prodName;
        }

        if ($this->color) {
            return $this->color->name;
        }

        return 'N/A';
    }

    /**
     * Get the brand associated with this finished good (via Grade or Color).
     */
    public function getBrandAttribute(): ?Brand
    {
        if ($this->grade) {
            return $this->grade->brand;
        }
        if ($this->color) {
            return $this->color->brand;
        }
        return null;
    }

    /**
     * Scope a query to include finished goods for a specific brand context.
     * Includes:
     * - Grades matching brand (or brand_id IS NULL)
     * - Colors matching brand (or brand_id IS NULL)
     * - Epoxy items (which are shared across brands or have no grade/color)
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
            $q->whereHas('grade', fn ($gQ) => $gQ->forBrand($brandId))
              ->orWhereHas('color', fn ($cQ) => $cQ->forBrand($brandId))
              ->orWhere(function ($eQ) {
                  $eQ->whereNull('grade_id')->whereNull('color_id');
              });
        });
    }

    /**
     * Scope a query to include finished goods for the current session brand.
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
     * Accessor for dynamic status checking
     */
    public function getFormattedStatusAttribute(): string
    {
        if ($this->available_bags <= 0) {
            return 'Out of Stock';
        }
        if ($this->available_bags <= $this->minimum_stock) {
            return 'Low Stock';
        }
        return 'Active';
    }
}
