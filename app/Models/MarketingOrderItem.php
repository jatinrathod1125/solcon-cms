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
        'epoxy_filler_color_id',
        'epoxy_component_id',
        'quantity_bags',
        'quantity_kg',
        'packing',
        'coupon_raw_material_id',
        'coupon_quantity',
        'is_product_available',
        'is_coupon_available',
        'item_status',
        'is_edited',
        'remarks',
    ];

    protected $casts = [
        'quantity_bags' => 'integer',
        'quantity_kg' => 'decimal:2',
        'coupon_quantity' => 'integer',
        'is_product_available' => 'boolean',
        'is_coupon_available' => 'boolean',
        'is_edited' => 'boolean',
    ];

    protected $appends = [
        'product_name',
        'department_label',
        'coupon_name',
        'calculated_weight_kg',
        'unit_label',
        'stock_info',
    ];

    /**
     * Find matching FinishedGood record in warehouse stock.
     */
    public function findFinishedGood(): ?FinishedGood
    {
        $query = FinishedGood::query();

        if ($this->department_code) {
            $query->whereHas('department', function ($q) {
                $q->where('code', $this->department_code);
            });
        }

        switch ($this->department_code) {
            case 'TAD':
                if ($this->grade_id) {
                    $query->where('grade_id', $this->grade_id);
                }
                break;
            case 'GRT':
                if ($this->color_id) {
                    $query->where('color_id', $this->color_id);
                }
                break;
            case 'EPX':
                if ($this->epoxy_component_id) {
                    $query->where('epoxy_component_id', $this->epoxy_component_id);
                } else {
                    if ($this->epoxy_product_id) {
                        $query->where('epoxy_product_id', $this->epoxy_product_id);
                    }
                    if ($this->epoxy_filler_color_id) {
                        $query->where('epoxy_filler_color_id', $this->epoxy_filler_color_id);
                    }
                }
                break;
        }

        if ($this->coupon_raw_material_id) {
            $query->where('coupon_raw_material_id', $this->coupon_raw_material_id);
        }

        if (!empty($this->packing)) {
            $exactMatch = (clone $query)->where('packing', $this->packing)->first();
            if ($exactMatch) {
                return $exactMatch;
            }
        }

        return $query->first();
    }

    /**
     * Stock availability info attribute.
     */
    public function getStockInfoAttribute(): array
    {
        $finishedGood = $this->findFinishedGood();
        $availableBags = $finishedGood ? (int) $finishedGood->available_bags : 0;
        $requiredBags = (int) $this->quantity_bags;
        $isAvailable = $availableBags >= $requiredBags;

        return [
            'available_bags' => $availableBags,
            'required_bags' => $requiredBags,
            'is_available' => $isAvailable,
            'label' => $isAvailable ? 'Available' : 'Not Available',
            'stock_text' => "Stock: {$availableBags} " . $this->unit_label,
            'badge_class' => $isAvailable ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200',
        ];
    }

    /**
     * Get unit label: Buckets for Epoxy / Buckets, Bags for Adhesive & Grout.
     */
    public function getUnitLabelAttribute(): string
    {
        if ($this->department_code === 'EPX' || str_contains(strtolower($this->packing ?? ''), 'bucket')) {
            return $this->quantity_bags == 1 ? 'Bucket' : 'Buckets';
        }
        return $this->quantity_bags == 1 ? 'Bag' : 'Bags';
    }

    /**
     * Calculate total weight in KG based on quantity_kg or quantity_bags * packing size.
     * Grout (GRT) is always 25 KG per bag.
     */
    public function getCalculatedWeightKgAttribute(): float
    {
        if (!empty($this->quantity_kg) && (float)$this->quantity_kg > 0) {
            return (float) $this->quantity_kg;
        }

        $bags = (int) $this->quantity_bags;

        // Grout is always 25 KG per bag
        if ($this->department_code === 'GRT') {
            return $bags * 25;
        }

        if ($this->department_code === 'TAD') {
            if (!empty($this->packing) && preg_match('/(\d+(?:\.\d+)?)/', $this->packing, $matches)) {
                $pkgSize = (float) $matches[1];
                if ($pkgSize > 0) {
                    return $bags * $pkgSize;
                }
            }
            return $bags * 20;
        }

        if (!empty($this->packing) && preg_match('/(\d+(?:\.\d+)?)/', $this->packing, $matches)) {
            $pkgSize = (float) $matches[1];
            if ($pkgSize > 0) {
                return $bags * $pkgSize;
            }
        }

        return $bags * 1;
    }

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
     * Get the epoxy filler color.
     */
    public function epoxyFillerColor(): BelongsTo
    {
        return $this->belongsTo(EpoxyFillerColor::class, 'epoxy_filler_color_id');
    }

    /**
     * Get the epoxy component.
     */
    public function epoxyComponent(): BelongsTo
    {
        return $this->belongsTo(EpoxyComponent::class, 'epoxy_component_id');
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
            'EPX' => $this->epoxyComponent 
                ? $this->epoxyComponent->name 
                : ($this->epoxyProduct 
                    ? ($this->epoxyFillerColor 
                        ? $this->epoxyProduct->name . ' (' . $this->epoxyFillerColor->name . ')' 
                        : $this->epoxyProduct->name) 
                    : 'N/A'),
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
