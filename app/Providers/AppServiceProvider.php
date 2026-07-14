<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (request()->header('x-forwarded-proto') === 'https') {
        URL ::forceScheme('https');
    }

        Schema::defaultStringLength(191);

        if (class_exists(\Illuminate\Support\Facades\Schema::class) && Schema::hasTable('settings')) {
            try {
                $timezone = \App\Models\Setting::get('timezone', 'Asia/Kolkata');
                if (!$timezone || $timezone === 'UTC') {
                    $timezone = 'Asia/Kolkata';
                }
                config(['app.timezone' => $timezone]);
                date_default_timezone_set($timezone);

                // Share UI Settings globally
                view()->composer('*', function ($view) {
                    $view->with('uiSettings', [
                        'theme' => \App\Models\Setting::get('ui_theme', 'light'),
                        'primary_color' => \App\Models\Setting::get('ui_primary_color', 'indigo'),
                        'sidebar_style' => \App\Models\Setting::get('ui_sidebar_style', 'dark'),
                        'compact_mode' => \App\Models\Setting::get('ui_compact_mode', 'disable'),
                        'table_density' => \App\Models\Setting::get('ui_table_density', 'normal'),
                    ]);
                });
            } catch (\Exception $e) {
                // Keep default timezone and settings if DB fails
            }
        }
    }
}
