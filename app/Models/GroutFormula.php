<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'color_id',
    'version',
    'remarks',
    'is_active',
    'created_by',
    'updated_by',
])]
class GroutFormula extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'version' => 'integer',
        ];
    }

    /**
     * Get the color that owns this formula.
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Get the items (ingredients) of this formula.
     */
    public function items(): HasMany
    {
        return $this->hasMany(GroutFormulaItem::class)->orderBy('display_order');
    }

    /**
     * Get the user who created the formula version.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the formula.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
