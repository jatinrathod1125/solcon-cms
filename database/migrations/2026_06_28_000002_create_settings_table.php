<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        DB::table('settings')->insert([
            [
                'key' => 'factory_name',
                'value' => 'Solcon Industries',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'factory_logo',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'report_header',
                'value' => 'Solcon Industries Daily Production Report',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'footer_text',
                'value' => 'Solcon Production Management System',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'default_bag_size',
                'value' => '20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'timezone',
                'value' => 'UTC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
