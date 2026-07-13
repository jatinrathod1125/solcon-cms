<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'value'
    ];

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, $default = null): ?string
    {
        $settings = \Illuminate\Support\Facades\Cache::rememberForever('app_settings_cache', function () {
            return self::pluck('value', 'key')->toArray();
        });

        return array_key_exists($key, $settings) ? $settings[$key] : $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, ?string $value): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        \Illuminate\Support\Facades\Cache::forget('app_settings_cache');
        return $setting;
    }
}
