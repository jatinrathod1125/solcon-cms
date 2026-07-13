<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EpoxyComponentFormula extends Model
{
    protected $fillable = [
        'epoxy_component_id',
        'version',
        'is_active',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'version' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the component this formula belongs to.
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(EpoxyComponent::class, 'epoxy_component_id');
    }

    /**
     * Get the items in this formula.
     */
    public function items(): HasMany
    {
        return $this->hasMany(EpoxyComponentFormulaItem::class, 'epoxy_component_formula_id');
    }

    /**
     * Creator relationship.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Editor relationship.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
