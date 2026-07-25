<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DispatchItem extends Model
{
    protected $fillable = [
        'dispatch_id',
        'marketing_order_id',
        'marketing_order_item_id',
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
    ];

    protected $casts = [
        'quantity_bags' => 'integer',
        'quantity_kg' => 'decimal:2',
        'coupon_quantity' => 'integer',
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
     * Get unit label: Box for Boxes, Bucket for Epoxy Buckets, Bag for Adhesive & Grout, Pcs/Pouch for components.
     */
    public function getUnitLabelAttribute(): string
    {
        $packing = strtolower($this->packing ?? '');
        if (str_contains($packing, 'box')) {
            return $this->quantity_bags == 1 ? 'Box' : 'Boxes';
        }
        if (str_contains($packing, 'pouch') || str_contains($packing, 'pckt') || str_contains($packing, 'packet')) {
            return $this->quantity_bags == 1 ? 'Pouch' : 'Pouches';
        }
        if (str_contains($packing, 'pcs') || str_contains($packing, 'piece')) {
            return $this->quantity_bags == 1 ? 'Pc' : 'Pcs';
        }
        if ($this->department_code === 'EPX' || str_contains($packing, 'bucket')) {
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

    public function dispatch(): BelongsTo
    {
        return $this->belongsTo(Dispatch::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(MarketingOrder::class, 'marketing_order_id');
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(MarketingOrderItem::class, 'marketing_order_item_id');
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function epoxyProduct(): BelongsTo
    {
        return $this->belongsTo(EpoxyProduct::class);
    }

    public function epoxyFillerColor(): BelongsTo
    {
        return $this->belongsTo(EpoxyFillerColor::class, 'epoxy_filler_color_id');
    }

    public function epoxyComponent(): BelongsTo
    {
        return $this->belongsTo(EpoxyComponent::class, 'epoxy_component_id');
    }

    public function couponMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class, 'coupon_raw_material_id');
    }

    // ─── Accessors ───────────────────────────────

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

    public function getDepartmentLabelAttribute(): string
    {
        return match ($this->department_code) {
            'TAD' => 'Adhesive',
            'GRT' => 'Grout',
            'EPX' => 'Epoxy',
            default => $this->department_code,
        };
    }

    public function getCouponNameAttribute(): string
    {
        if (!$this->coupon_raw_material_id) {
            return 'No Coupon';
        }
        return $this->couponMaterial ? $this->couponMaterial->name : 'N/A';
    }
}
