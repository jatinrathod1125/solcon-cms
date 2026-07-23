<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EpoxyComponentFormulaItem extends Model
{
    protected $fillable = [
        'epoxy_component_formula_id',
        'raw_material_id',
        'packing_material_id',
        'quantity',
        'unit_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
    ];

    /**
     * Get the formula this item belongs to.
     */
    public function formula(): BelongsTo
    {
        return $this->belongsTo(EpoxyComponentFormula::class, 'epoxy_component_formula_id');
    }

    /**
     * Get the raw material.
     */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }

    /**
     * Get the packing material.
     */
    public function packingMaterial(): BelongsTo
    {
        return $this->belongsTo(PackingMaterial::class, 'packing_material_id');
    }

    /**
     * Get the unit.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}

