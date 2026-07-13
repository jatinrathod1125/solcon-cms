<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EpoxyComponentPreparation extends Model
{
    protected $fillable = [
        'epoxy_component_id',
        'epoxy_filler_color_id',
        'quantity',
        'operator_id',
        'remarks',
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
     * Get operator
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}
