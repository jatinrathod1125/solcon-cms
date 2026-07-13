<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'epoxy_formula_id',
    'raw_material_id',
    'quantity',
    'unit_id',
    'is_dynamic_color',
    'material_type',
])]
class EpoxyFormulaItem extends Model
{
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'is_dynamic_color' => 'boolean',
        ];
    }

    /**
     * Get the formula this item belongs to.
     */
    public function formula(): BelongsTo
    {
        return $this->belongsTo(EpoxyFormula::class, 'epoxy_formula_id');
    }

    /**
     * Get the raw material.
     */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }

    /**
     * Get the unit.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
