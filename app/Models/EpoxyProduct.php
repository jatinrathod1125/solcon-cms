<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'name',
    'code',
    'requires_color',
    'is_active',
    'description',
    'created_by',
    'updated_by',
])]
class EpoxyProduct extends Model
{
    protected function casts(): array
    {
        return [
            'requires_color' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the formulas for this product.
     */
    public function formulas(): HasMany
    {
        return $this->hasMany(EpoxyFormula::class);
    }

    /**
     * Get the active formula for this product.
     */
    public function activeFormula(): HasOne
    {
        return $this->hasOne(EpoxyFormula::class)->where('is_active', true);
    }

    /**
     * Creator relationship.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
