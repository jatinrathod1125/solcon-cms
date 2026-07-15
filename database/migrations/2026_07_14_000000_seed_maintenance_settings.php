<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->insertOrIgnore([
            [
                'key' => 'maintenance_mode',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'maintenance_unlock_password',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['maintenance_mode', 'maintenance_unlock_password'])->delete();
    }
};
