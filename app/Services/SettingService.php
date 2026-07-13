<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    /**
     * Get a setting by key.
     */
    public static function get(string $key, $default = null): ?string
    {
        return Setting::get($key, $default);
    }

    /**
     * Set a setting by key.
     */
    public static function set(string $key, ?string $value): Setting
    {
        return Setting::set($key, $value);
    }

    /**
     * Get all settings as an associative key-value array.
     */
    public static function all(): array
    {
        return Setting::pluck('value', 'key')->toArray();
    }

    /**
     * Update multiple settings at once.
     */
    public static function updateBulk(array $settings): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($settings) {
            foreach ($settings as $key => $value) {
                Setting::set($key, $value);
            }
        });
        \Illuminate\Support\Facades\Cache::forget('app_settings_cache');
    }
}
