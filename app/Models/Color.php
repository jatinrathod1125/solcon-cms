<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'department_id',
    'name',
    'code',
    'packing_size',
    'default_cement',
    'is_active',
    'description',
    'created_by',
    'updated_by',
])]
class Color extends Model
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
        ];
    }

    /**
     * Get the department that owns the color.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user who created the color.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the color.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the formulas linked to this color.
     */
    public function formulas(): HasMany
    {
        return $this->hasMany(GroutFormula::class);
    }

    /**
     * Get the active formula for this color.
     */
    public function activeFormula(): HasOne
    {
        return $this->hasOne(GroutFormula::class)->where('is_active', true);
    }

    /**
     * Helper to get active colors.
     */
    public static function getActive()
    {
        return self::where('is_active', true)->orderBy('name')->get();
    }
}
