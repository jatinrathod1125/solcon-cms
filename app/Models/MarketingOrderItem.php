<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingOrderItem extends Model
{
    protected $fillable = [
        'marketing_order_id',
        'department_code',
        'grade_id',
        'color_id',
        'epoxy_product_id',
        'quantity_bags',
        'quantity_kg',
        'packing',
        'coupon_raw_material_id',
        'coupon_quantity',
        'is_product_available',
        'is_coupon_available',
        'item_status',
        'remarks',
    ];

    protected $casts = [
        'quantity_bags' => 'integer',
        'quantity_kg' => 'decimal:2',
        'coupon_quantity' => 'integer',
        'is_product_available' => 'boolean',
        'is_coupon_available' => 'boolean',
    ];

    // ─── Relationships ───────────────────────────

    /**
     * Get the parent order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(MarketingOrder::class, 'marketing_order_id');
    }

    /**
     * Get the grade (Adhesive/TAD).
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the color (Grout/GRT).
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Get the epoxy product (EPX).
     */
    public function epoxyProduct(): BelongsTo
    {
        return $this->belongsTo(EpoxyProduct::class);
    }

    /**
     * Get the coupon raw material.
     */
    public function couponMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class, 'coupon_raw_material_id');
    }

    // ─── Accessors ───────────────────────────────

    /**
     * Get the product name based on department code.
     */
    public function getProductNameAttribute(): string
    {
        return match ($this->department_code) {
            'TAD' => $this->grade ? $this->grade->name : 'N/A',
            'GRT' => $this->color ? $this->color->name : 'N/A',
            'EPX' => $this->epoxyProduct ? $this->epoxyProduct->name : 'N/A',
            default => 'N/A',
        };
    }

    /**
     * Get the department label.
     */
    public function getDepartmentLabelAttribute(): string
    {
        return match ($this->department_code) {
            'TAD' => 'Adhesive',
            'GRT' => 'Grout',
            'EPX' => 'Epoxy',
            default => $this->department_code,
        };
    }

    /**
     * Get the coupon display name.
     */
    public function getCouponNameAttribute(): string
    {
        if (!$this->coupon_raw_material_id) {
            return 'No Coupon';
        }
        return $this->couponMaterial ? $this->couponMaterial->name : 'N/A';
    }

    /**
     * Get the item availability status text.
     */
    public function getAvailabilityTextAttribute(): string
    {
        $productOk = $this->is_product_available;
        $couponOk = $this->is_coupon_available;

        if ($couponOk === null) {
            // No coupon needed
            return $productOk ? 'Available' : 'Product Not Available';
        }

        if ($productOk && $couponOk) {
            return 'Available';
        }
        if (!$productOk && !$couponOk) {
            return 'Not Available';
        }
        if (!$productOk) {
            return 'Product Not Available';
        }
        return 'Coupon Not Available';
    }
}
