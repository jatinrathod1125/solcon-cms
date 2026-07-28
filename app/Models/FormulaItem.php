<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'formula_id',
    'item_type',
    'raw_material_id',
    'packing_material_id',
    'quantity',
    'unit_id',
    'consumption_method',
    'consumption_per_unit',
    'sequence',
])]
class FormulaItem extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'consumption_per_unit' => 'decimal:4',
            'sequence' => 'integer',
        ];
    }

    /**
     * Get the formula that owns this item.
     */
    public function formula(): BelongsTo
    {
        return $this->belongsTo(Formula::class);
    }

    /**
     * Get the raw material referenced by this formula item.
     */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class);
    }

    /**
     * Get the packing material referenced by this formula item.
     */
    public function packingMaterial(): BelongsTo
    {
        return $this->belongsTo(PackingMaterial::class);
    }

    /**
     * Get the unit of measurement referenced by this formula item.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
