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
        Schema::table('grout_production_batches', function (Blueprint $table) {
            $table->boolean('timer_skipped')->default(false);
            $table->foreignId('skipped_by_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('skip_reason')->nullable();
            $table->dateTime('skipped_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grout_production_batches', function (Blueprint $table) {
            $table->dropForeign(['skipped_by_id']);
            $table->dropColumn(['timer_skipped', 'skipped_by_id', 'skip_reason', 'skipped_at']);
        });
    }
};
