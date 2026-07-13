<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EpoxyComponent extends Model
{
    protected $fillable = [
        'name',
        'code',
        'category', // Bottle, Pouch, Packet, Liquid, Powder, Plastic, Accessory, Other
        'purpose', // Assembly Component, Direct Finished Product
        'unit_id',
        'is_active',
        'description',
        'raw_material_id', // Points to the ready component's RawMaterial record representing prepared stock
        'parent_component_id', // Points to generic component (e.g. 700gm Filler Pouch) for color variants
        'epoxy_filler_color_id', // Points to the filler color if color-specific
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get parent component.
     */
    public function parentComponent(): BelongsTo
    {
        return $this->belongsTo(EpoxyComponent::class, 'parent_component_id');
    }

    /**
     * Get child components.
     */
    public function childComponents(): HasMany
    {
        return $this->hasMany(EpoxyComponent::class, 'parent_component_id');
    }

    /**
     * Get raw material.
     */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }

    /**
     * Get unit.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Get color.
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(EpoxyFillerColor::class, 'epoxy_filler_color_id');
    }

    /**
     * Get formulas.
     */
    public function formulas(): HasMany
    {
        return $this->hasMany(EpoxyComponentFormula::class, 'epoxy_component_id');
    }

    /**
     * Get active formula.
     */
    public function activeFormula(): HasOne
    {
        return $this->hasOne(EpoxyComponentFormula::class, 'epoxy_component_id')->where('is_active', true);
    }
}
