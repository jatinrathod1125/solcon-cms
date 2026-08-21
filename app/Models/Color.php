<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'brand_id',
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
     * Get the brand that owns the color.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
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
     * Scope a query to include colors for a specific brand or common colors (brand_id IS NULL).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Brand|int|string|null  $brand
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForBrand($query, $brand = null)
    {
        $brandId = $brand instanceof Brand ? $brand->id : $brand;
        if (!$brandId) {
            return $query;
        }
        return $query->where(function ($q) use ($brandId) {
            $q->where('brand_id', $brandId)
                ->orWhereNull('brand_id');
        });
    }

    /**
     * Scope a query to include colors for the current session brand or common colors.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCurrentBrand($query)
    {
        $currentBrand = function_exists('currentBrand') ? currentBrand() : null;
        return $this->scopeForBrand($query, $currentBrand?->id);
    }

    /**
     * Helper to get active colors.
     */
    public static function getActive()
    {
        return self::where('is_active', true)->orderBy('name')->get();
    }
}
