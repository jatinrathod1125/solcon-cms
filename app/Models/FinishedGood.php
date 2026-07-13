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
     * Accessor for Product Name based on department
     */
    public function getProductNameAttribute(): string
    {
        if ($this->department_id && $this->department) {
            $deptCode = strtoupper($this->department->code);
            if ($deptCode === 'TAD') {
                $name = $this->grade ? $this->grade->name : 'N/A';
                if ($this->coupon_raw_material_id && $this->couponMaterial) {
                    return $name . ' (' . $this->couponMaterial->name . ')';
                }
                return $name . ' (No Coupon)';
            }
            if ($deptCode === 'GRT') {
                return $this->color ? $this->color->name : 'N/A';
            }
            if ($deptCode === 'EPX' || $deptCode === 'EP') {
                if ($this->epoxy_component_id) {
                    return $this->epoxyComponent ? $this->epoxyComponent->name : 'N/A';
                }
                $prodName = $this->epoxyProduct ? $this->epoxyProduct->name : 'N/A';
                if ($this->epoxy_filler_color_id && $this->epoxyFillerColor) {
                    return $prodName . ' (' . $this->epoxyFillerColor->name . ')';
                }
                if ($this->color_id && $this->color) {
                    return $prodName . ' (' . $this->color->name . ')';
                }
                return $prodName;
            }
        }
        return 'N/A';
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
