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
}
