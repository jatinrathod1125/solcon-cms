<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'brand_id',
    'department_id',
    'name',
    'code',
    'bag_size_id',
    'output_unit_id',
    'description',
    'is_active',
    'created_by',
    'updated_by',
])]
class Grade extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the department that owns the grade.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the bag size configuration of the grade.
     */
    public function bagSize(): BelongsTo
    {
        return $this->belongsTo(BagSize::class);
    }

    /**
     * Get the output unit of measurement for the grade.
     */
    public function outputUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'output_unit_id');
    }

    /**
     * Get the user who created the grade.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the grade.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the formulas linked to this grade.
     */
    public function formulas(): HasMany
    {
        return $this->hasMany(Formula::class);
    }

    /**
     * Get the active formula for this grade.
     */
    public function activeFormula(): HasOne
    {
        return $this->hasOne(Formula::class)->where('is_active', true);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Scope a query to include grades for a specific brand or common grades (brand_id IS NULL).
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
     * Scope a query to include grades for the current session brand or common grades.
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
     * Get all compatible grades across brands that share the exact same chemical formulation.
     */
    public function getCompatibleGrades(): array
    {
        $this->loadMissing(['brand', 'bagSize', 'activeFormula.items.rawMaterial', 'activeFormula.items.packingMaterial']);

        $extractSignature = function ($formula) {
            if (!$formula) return '';
            $chemicals = [];
            foreach ($formula->items as $item) {
                if ($item->item_type === 'packing' || !empty($item->packing_material_id)) continue;
                if ($item->rawMaterial && $item->rawMaterial->is_coupon) continue;
                if ($item->rawMaterial && stripos($item->rawMaterial->name, 'bag') !== false) continue;
                $chemicals[$item->raw_material_id] = round((float)$item->quantity, 4);
            }
            ksort($chemicals);
            return md5(json_encode($chemicals));
        };

        $mySig = $extractSignature($this->activeFormula);

        $candidates = static::with(['brand', 'bagSize', 'activeFormula.items.rawMaterial', 'activeFormula.items.packingMaterial'])
            ->where('department_id', $this->department_id)
            ->where('is_active', true)
            ->get();

        $compatible = [];
        foreach ($candidates as $candidate) {
            $candSig = $extractSignature($candidate->activeFormula);
            if ($candidate->id === $this->id || ($mySig && $candSig === $mySig)) {
                $packingMatId = null;
                $packingMatName = null;
                if ($candidate->activeFormula) {
                    foreach ($candidate->activeFormula->items as $fItem) {
                        if ($fItem->item_type === 'packing' || !empty($fItem->packing_material_id)) {
                            $packingMatId = $fItem->packing_material_id;
                            $packingMatName = $fItem->packingMaterial->name ?? 'Bag';
                            break;
                        }
                        if ($fItem->rawMaterial && stripos($fItem->rawMaterial->name, 'bag') !== false) {
                            $packingMatName = $fItem->rawMaterial->name;
                            $pm = \App\Models\PackingMaterial::where('name', 'like', '%' . $fItem->rawMaterial->name . '%')->first();
                            $packingMatId = $pm ? $pm->id : null;
                            break;
                        }
                    }
                }
                if (!$packingMatId) {
                    $pm = \App\Models\PackingMaterial::where('name', 'like', '%' . $candidate->code . '%')->first();
                    if ($pm) {
                        $packingMatId = $pm->id;
                        $packingMatName = $pm->name;
                    }
                }

                $bagVal = (float) ($candidate->bagSize->value ?? 20.0);
                $compatible[] = [
                    'id' => $candidate->id,
                    'grade_id' => $candidate->id,
                    'name' => $candidate->name,
                    'grade_name' => $candidate->name,
                    'code' => $candidate->code,
                    'grade_code' => $candidate->code,
                    'brand_id' => $candidate->brand_id,
                    'brand_name' => $candidate->brand->name ?? 'None',
                    'bag_size_id' => $candidate->bag_size_id,
                    'bag_size_name' => $candidate->bagSize->name ?? '20KG',
                    'bag_size_value' => $bagVal,
                    'bag_size' => $bagVal,
                    'packing_material_id' => $packingMatId,
                    'packing_material_name' => $packingMatName ?? ($candidate->name . ' Bag'),
                ];
            }
        }

        return $compatible;
    }
}
