<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'value', 'description', 'is_active'])]
class BagSize extends Model
{
    protected static function booted()
    {
        static::saved(fn() => \Illuminate\Support\Facades\Cache::forget('active_bag_sizes'));
        static::deleted(fn() => \Illuminate\Support\Facades\Cache::forget('active_bag_sizes'));
    }

    public static function getActive()
    {
        return self::where('is_active', true)->get();
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
