<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'grout_formula_id',
    'raw_material_id',
    'quantity',
    'unit_id',
    'mix_stage',
    'display_order',
])]
class GroutFormulaItem extends Model
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
            'display_order' => 'integer',
        ];
    }

    /**
     * Get the formula that owns this item.
     */
    public function formula(): BelongsTo
    {
        return $this->belongsTo(GroutFormula::class, 'grout_formula_id');
    }

    /**
     * Get the raw material of this item.
     */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class);
    }

    /**
     * Get the unit of measurement of this item.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
