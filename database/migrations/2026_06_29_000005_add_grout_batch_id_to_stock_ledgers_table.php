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
        Schema::table('stock_ledgers', function (Blueprint $table) {
            $table->foreignId('grout_batch_id')
                ->nullable()
                ->after('batch_id')
                ->constrained('grout_production_batches')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_ledgers', function (Blueprint $table) {
            $table->dropForeign(['grout_batch_id']);
            $table->dropColumn('grout_batch_id');
        });
    }
};
