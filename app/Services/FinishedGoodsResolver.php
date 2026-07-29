<?php

namespace App\Services;

use App\Models\DispatchItem;
use App\Models\EpoxyComponent;
use App\Models\EpoxyProduct;
use App\Models\FinishedGood;
use App\Models\MarketingOrderItem;
use Illuminate\Database\Eloquent\Builder;

/**
 * Resolves the one finished-goods row that represents an order or dispatch item.
 *
 * All stock reads and deductions must use this class.  In particular, direct
 * epoxy products such as Grout Admix and Tiles Cleaner are stocked by component,
 * not by their legacy epoxy_product_id.
 */
class FinishedGoodsResolver
{
    /** @var array<string, string> */
    private const COMPONENT_PACKINGS = [
        'EPX-GA-200GM' => '200GM',
        'EPX-TC-1LTR' => '1-LTR',
        'EPX-TC-5LTR' => '5-LTR',
    ];

    public function findForOrderItem(MarketingOrderItem $item): ?FinishedGood
    {
        return $this->findForAttributes(
            $item->department_code,
            $item->grade_id,
            $item->color_id,
            $item->epoxy_product_id,
            $item->epoxy_filler_color_id,
            $item->epoxy_component_id,
            $item->coupon_raw_material_id,
            $item->packing,
        );
    }

    public function findForDispatchItem(DispatchItem $item): ?FinishedGood
    {
        return $this->findForAttributes(
            $item->department_code,
            $item->grade_id,
            $item->color_id,
            $item->epoxy_product_id,
            $item->epoxy_filler_color_id,
            $item->epoxy_component_id,
            $item->coupon_raw_material_id,
            $item->packing,
        );
    }

    public function findForAttributes(
        ?string $departmentCode,
        ?int $gradeId,
        ?int $colorId,
        ?int $epoxyProductId,
        ?int $epoxyFillerColorId,
        ?int $epoxyComponentId,
        ?int $couponRawMaterialId,
        ?string $packing,
    ): ?FinishedGood {
        $departmentCode = strtoupper((string) $departmentCode);
        $query = FinishedGood::query()->whereHas('department', function (Builder $query) use ($departmentCode) {
            $query->where('code', $departmentCode);
        });

        $componentId = null;

        switch ($departmentCode) {
            case 'TAD':
                if (!$gradeId) {
                    return null;
                }

                $query->where('grade_id', $gradeId);
                $couponRawMaterialId
                    ? $query->where('coupon_raw_material_id', $couponRawMaterialId)
                    : $query->whereNull('coupon_raw_material_id');
                break;

            case 'GRT':
                if (!$colorId) {
                    return null;
                }

                $query->where('color_id', $colorId);
                break;

            case 'EPX':
                $componentId = $this->resolveEpoxyComponentId($epoxyComponentId, $epoxyProductId, $packing);

                if ($componentId) {
                    $query->where('epoxy_component_id', $componentId);
                } elseif ($epoxyProductId) {
                    $query->where('epoxy_product_id', $epoxyProductId);
                    $epoxyFillerColorId
                        ? $query->where('epoxy_filler_color_id', $epoxyFillerColorId)
                        : $query->whereNull('epoxy_filler_color_id');
                } else {
                    return null;
                }
                break;

            default:
                return null;
        }

        return $this->matchPacking($query, $packing, $componentId !== null);
    }

    /**
     * Return the standard Finished Goods packing for direct, component-backed SKUs.
     */
    public static function packingForComponent(EpoxyComponent $component): ?string
    {
        return self::COMPONENT_PACKINGS[strtoupper($component->code)] ?? null;
    }

    private function resolveEpoxyComponentId(?int $componentId, ?int $productId, ?string $packing): ?int
    {
        if ($componentId) {
            return $componentId;
        }

        $productCode = $productId
            ? EpoxyProduct::whereKey($productId)->value('code')
            : null;

        $componentCode = match (strtoupper((string) $productCode)) {
            'GA' => 'EPX-GA-200GM',
            'TC' => $this->tilesCleanerComponentCode($packing),
            // Old orders in the supplied database reference deleted product IDs.
            // Only apply this fallback when the product record itself is missing.
            '' => $this->legacyComponentCode($packing),
            default => null,
        };

        return $componentCode
            ? EpoxyComponent::where('code', $componentCode)->value('id')
            : null;
    }

    private function legacyComponentCode(?string $packing): ?string
    {
        return match ($this->normalisePacking($packing)) {
            '200GM' => 'EPX-GA-200GM',
            '1LTR' => 'EPX-TC-1LTR',
            '5LTR' => 'EPX-TC-5LTR',
            default => null,
        };
    }

    private function tilesCleanerComponentCode(?string $packing): ?string
    {
        return match ($this->normalisePacking($packing)) {
            '1LTR' => 'EPX-TC-1LTR',
            '5LTR' => 'EPX-TC-5LTR',
            default => null,
        };
    }

    private function matchPacking(Builder $query, ?string $packing, bool $isComponentStock): ?FinishedGood
    {
        $candidates = $query->orderBy('id')->get();
        if ($candidates->isEmpty()) {
            return null;
        }

        if (!$packing) {
            return $candidates->count() === 1 ? $candidates->first() : null;
        }

        $exact = $candidates->first(fn (FinishedGood $good) => $good->packing === $packing);
        if ($exact) {
            return $exact;
        }

        $normalisedPacking = $this->normalisePacking($packing);
        $normalised = $candidates->first(fn (FinishedGood $good) => $this->normalisePacking($good->packing) === $normalisedPacking);
        if ($normalised) {
            return $normalised;
        }

        // Fallback: extract the weight+unit suffix (e.g. "0.3KG" from "RESIN KIT 0.3KG")
        // and compare against the order item packing.  This handles products whose
        // finished-goods packing was stored with the product name as prefix.
        $orderWeight = $this->extractWeight($packing);
        if ($orderWeight) {
            $weightMatch = $candidates->first(fn (FinishedGood $good) => $this->extractWeight($good->packing) === $orderWeight);
            if ($weightMatch) {
                return $weightMatch;
            }
        }

        // A component ID identifies one direct SKU.  This deliberately supports
        // existing rows created with the old generic packing values (Box/1 Unit),
        // but never guesses between two stock rows.
        return $isComponentStock && $candidates->count() === 1
            ? $candidates->first()
            : null;
    }

    private function normalisePacking(?string $packing): string
    {
        return strtoupper((string) preg_replace('/[\s_-]+/', '', trim((string) $packing)));
    }

    /**
     * Extract the weight+unit portion from a packing string.
     * e.g. "RESIN KIT 0.3KG" → "0.3KG", "1.5KG" → "1.5KG", "5KG" → "5KG".
     */
    private function extractWeight(?string $packing): ?string
    {
        if (!$packing) {
            return null;
        }

        if (preg_match('/(\d+(?:\.\d+)?\s*(?:KG|LTR|GM|L|G))/i', $packing, $matches)) {
            return strtoupper(preg_replace('/\s+/', '', $matches[1]));
        }

        return null;
    }
}
