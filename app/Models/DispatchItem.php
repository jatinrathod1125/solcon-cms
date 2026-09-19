<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\FinishedGoodsResolver;

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
        return app(FinishedGoodsResolver::class)->findForDispatchItem($this);
    }

    /**
     * Check if this item represents a 700gm filler pouch stored in raw materials.
     */
    public function isRawMaterialFillerPouch(): bool
    {
        if ($this->epoxy_component_id && $this->epoxyComponent?->raw_material_id) {
            $name = $this->epoxyComponent->name ?? '';
            $packing = $this->packing ?? '';
            return str_contains($name, 'Filler Pouch') || str_contains($packing, '700');
        }
        return false;
    }

    /**
     * Stock availability info attribute.
     */
    public function getStockInfoAttribute(): array
    {
        if ($this->isRawMaterialFillerPouch()) {
            $rawMaterial = $this->epoxyComponent?->rawMaterial;
            $availableBags = $rawMaterial ? (int) $rawMaterial->current_stock : 0;
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
        if (str_contains($packing, 'pouch') || str_contains($packing, 'pckt') || str_contains($packing, 'packet') || str_contains($packing, '700')) {
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
     * Filler Pouches (700gm) are always strictly 0.7 KG per pouch (e.g. 60 pouches = 42 KG).
     */
    public function getCalculatedWeightKgAttribute(): float
    {
        $bags = (int) $this->quantity_bags;

        // 1. Grout (GRT) is always strictly 25 KG per bag
        if ($this->department_code === 'GRT') {
            return (float) ($bags * 25.0);
        }

        // 2. Check 700gm Filler Pouch FIRST across packing, product name, or linked component
        $packingUpper = strtoupper((string) ($this->packing ?? ''));
        $nameUpper = strtoupper((string) ($this->product_name ?? ''));
        $compNameUpper = strtoupper((string) ($this->epoxyComponent?->name ?? ''));

        $isFillerPouch700 = str_contains($packingUpper, '700GM') 
            || str_contains($packingUpper, '700 GM')
            || str_contains($nameUpper, '700GM')
            || str_contains($nameUpper, '700 GM')
            || str_contains($compNameUpper, '700GM')
            || str_contains($compNameUpper, '700 GM');

        if ($isFillerPouch700) {
            return (float) ($bags * 0.7);
        }

        // 3. Generic grams detection in packing/name/component (e.g., 500gm = 0.5kg, 200gm = 0.2kg)
        $combinedText = $packingUpper . ' ' . $nameUpper . ' ' . $compNameUpper;
        if (preg_match('/(\d+(?:\.\d+)?)\s*(?:GM|GRAM)/i', $combinedText, $gmMatches)) {
            $gmVal = (float) $gmMatches[1];
            if ($gmVal > 0) {
                return (float) ($bags * ($gmVal / 1000.0));
            }
        }

        // 4. If linked to an Epoxy Component with custom unit weight
        if ($this->epoxy_component_id && $this->epoxyComponent) {
            $compWeight = (float) ($this->epoxyComponent->weight_kg ?? 0);
            if ($compWeight > 0) {
                return (float) ($bags * $compWeight);
            }
        }

        // 5. If quantity_kg was stored explicitly and is positive
        if (!empty($this->quantity_kg) && (float) $this->quantity_kg > 0) {
            return (float) $this->quantity_kg;
        }

        // 6. Adhesive (TAD) default packing 20 KG
        if ($this->department_code === 'TAD') {
            if (!empty($this->packing) && preg_match('/(\d+(?:\.\d+)?)/', $this->packing, $matches)) {
                $pkgSize = (float) $matches[1];
                if ($pkgSize > 0) {
                    return (float) ($bags * $pkgSize);
                }
            }
            return (float) ($bags * 20.0);
        }

        // 7. General packing extraction
        if (!empty($this->packing) && preg_match('/(\d+(?:\.\d+)?)/', $this->packing, $matches)) {
            $pkgSize = (float) $matches[1];
            if ($pkgSize > 0) {
                return (float) ($bags * $pkgSize);
            }
        }

        return (float) ($bags * 1.0);
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
        $name = match ($this->department_code) {
            'TAD' => $this->grade?->name,
            'GRT' => $this->color?->name,
            'EPX' => $this->epoxyComponent?->name
            ?? ($this->epoxyProduct
                ? ($this->epoxyFillerColor
                    ? $this->epoxyProduct->name . ' (' . $this->epoxyFillerColor->name . ')'
                    : $this->epoxyProduct->name)
                : null),
            default => null,
        };

        if ($name) {
            return $name;
        }

        if ($this->epoxyComponent?->name) {
            return $this->epoxyComponent->name;
        }
        if ($this->epoxyProduct?->name) {
            return $this->epoxyProduct->name;
        }
        if ($this->grade?->name) {
            return $this->grade->name;
        }
        if ($this->color?->name) {
            return $this->color->name;
        }

        if (!empty($this->packing)) {
            $packingUpper = strtoupper(trim($this->packing));
            if (str_contains($packingUpper, 'ADMIX') || str_contains($packingUpper, '200GM')) {
                return '200GM ADMIX';
            }
            if (str_contains($packingUpper, 'CLEANER') || str_contains($packingUpper, '1-LTR') || str_contains($packingUpper, '5-LTR')) {
                return 'TILES CLEANER (' . $this->packing . ')';
            }
            return $this->packing;
        }

        return 'N/A';
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
