<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'code', 'description', 'is_active'])]
class Unit extends Model
{
    protected static function booted()
    {
        static::saved(fn() => \Illuminate\Support\Facades\Cache::forget('active_units'));
        static::deleted(fn() => \Illuminate\Support\Facades\Cache::forget('active_units'));
    }

    public static function getActive()
    {
        return self::where('is_active', true)->get();
    }
}
