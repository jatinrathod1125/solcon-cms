<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['department_id', 'name', 'code', 'description', 'is_active'])]
class Machine extends Model
{
    protected static function booted()
    {
        static::saved(fn() => \Illuminate\Support\Facades\Cache::forget('active_machines'));
        static::deleted(fn() => \Illuminate\Support\Facades\Cache::forget('active_machines'));
    }

    public static function getActive()
    {
        // return \Illuminate\Support\Facades\Cache::rememberForever('active_machines', function() {
            return self::where('is_active', true)->get();
        // });
    }
    /**
     * Get the department that owns the machine.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
