<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'grade_id',
    'version',
    'remarks',
    'is_active',
    'created_by',
])]
class Formula extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the grade that owns this formula.
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the items (raw materials) that make up this formula.
     */
    public function items(): HasMany
    {
        return $this->hasMany(FormulaItem::class)->orderBy('sequence');
    }

    /**
     * Get the user who created this formula version.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
