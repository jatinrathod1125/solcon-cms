<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'code', 'description', 'is_active'])]
class Department extends Model
{
    protected static function booted()
    {
        static::saved(fn() => \Illuminate\Support\Facades\Cache::forget('active_departments'));
        static::deleted(fn() => \Illuminate\Support\Facades\Cache::forget('active_departments'));
    }

    public static function getActive()
    {
        // TEMP: bypass cache to test
        return self::where('is_active', true)->get();
    }
    /**
     * Get the machines for the department.
     */
    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class);
    }

    /**
     * Get the users (supervisors) assigned to the department.
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_departments');
    }

    /**
     * Get the raw materials for the department.
     */
    public function rawMaterials(): HasMany
    {
        return $this->hasMany(RawMaterial::class);
    }

    /**
     * Get the grades for the department.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
