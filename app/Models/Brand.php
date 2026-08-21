<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'code',
        'slug',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function brandProducts(): HasMany
    {
        return $this->hasMany(BrandProduct::class);
    }

    public function finishedGoods(): HasMany
    {
        return $this->hasMany(FinishedGood::class);
    }

    public function colors(): HasMany
    {
        return $this->hasMany(Color::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Constants
    public const CODE_SOLCON = 'SOL';
    public const CODE_FIXORA = 'FIX';
}
