<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EpoxyComponentMapping extends Model
{
    protected $fillable = [
        'epoxy_component_id',
        'epoxy_filler_color_id',
        'raw_material_id',
    ];

    /**
     * Get component
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(EpoxyComponent::class, 'epoxy_component_id');
    }

    /**
     * Get color
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(EpoxyFillerColor::class, 'epoxy_filler_color_id');
    }

    /**
     * Get raw material representing ready stock
     */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }
}
