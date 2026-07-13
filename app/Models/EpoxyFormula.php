<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'epoxy_product_id',
    'version',
    'is_active',
    'description',
    'created_by',
])]
class EpoxyFormula extends Model
{
    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the product this formula belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(EpoxyProduct::class, 'epoxy_product_id');
    }

    /**
     * Get the items in this formula.
     */
    public function items(): HasMany
    {
        return $this->hasMany(EpoxyFormulaItem::class, 'epoxy_formula_id');
    }

    /**
     * Creator relationship.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
