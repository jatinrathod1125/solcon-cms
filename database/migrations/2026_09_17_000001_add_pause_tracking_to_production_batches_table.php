<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('production_batches', function (Blueprint $table) {
            $table->dateTime('paused_at')->nullable()->after('end_time');
            $table->unsignedInteger('total_paused_seconds')->default(0)->after('paused_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_batches', function (Blueprint $table) {
            $table->dropColumn(['paused_at', 'total_paused_seconds']);
        });
    }
};
