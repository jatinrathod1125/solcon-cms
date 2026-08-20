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
    'bag_size_id',
    'output_unit_id',
    'description',
    'is_active',
    'created_by',
    'updated_by',
])]
class Grade extends Model
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
     * Get the department that owns the grade.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the bag size configuration of the grade.
     */
    public function bagSize(): BelongsTo
    {
        return $this->belongsTo(BagSize::class);
    }

    /**
     * Get the output unit of measurement for the grade.
     */
    public function outputUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'output_unit_id');
    }

    /**
     * Get the user who created the grade.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the grade.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the formulas linked to this grade.
     */
    public function formulas(): HasMany
    {
        return $this->hasMany(Formula::class);
    }

    /**
     * Get the active formula for this grade.
     */
    public function activeFormula(): HasOne
    {
        return $this->hasOne(Formula::class)->where('is_active', true);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Scope a query to include grades for a specific brand or common grades (brand_id IS NULL).
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
     * Scope a query to include grades for the current session brand or common grades.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCurrentBrand($query)
    {
        $currentBrand = function_exists('currentBrand') ? currentBrand() : null;
        return $this->scopeForBrand($query, $currentBrand?->id);
    }
}
