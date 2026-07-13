<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'name',
    'code',
    'department_id',
    'stock_unit_id',
    'purchase_unit_id',
    'purchase_conversion',
    'opening_stock',
    'current_stock',
    'minimum_stock',
    'maximum_stock',
    'description',
    'is_active',
    'is_coupon',
])]
class RawMaterial extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchase_conversion' => 'decimal:4',
            'opening_stock' => 'decimal:4',
            'current_stock' => 'decimal:4',
            'minimum_stock' => 'decimal:4',
            'maximum_stock' => 'decimal:4',
            'is_active' => 'boolean',
            'is_coupon' => 'boolean',
        ];
    }

    /**
     * Get the department that owns the raw material.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the stock unit of measurement for the raw material.
     */
    public function stockUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'stock_unit_id');
    }

    /**
     * Get the purchase unit of measurement for the raw material.
     */
    public function purchaseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }
}
