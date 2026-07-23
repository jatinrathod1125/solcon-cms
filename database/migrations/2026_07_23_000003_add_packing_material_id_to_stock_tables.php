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
            $table->unsignedBigInteger('raw_material_id')->nullable()->change();
            $table->foreignId('packing_material_id')->nullable()->after('raw_material_id')->constrained('packing_materials')->onDelete('restrict');
            $table->index(['packing_material_id', 'created_at']);
        });

        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->unsignedBigInteger('raw_material_id')->nullable()->change();
            $table->foreignId('packing_material_id')->nullable()->after('raw_material_id')->constrained('packing_materials')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->dropForeign(['packing_material_id']);
            $table->dropColumn('packing_material_id');
            $table->unsignedBigInteger('raw_material_id')->nullable(false)->change();
        });

        Schema::table('stock_ledgers', function (Blueprint $table) {
            $table->dropForeign(['packing_material_id']);
            $table->dropColumn('packing_material_id');
            $table->unsignedBigInteger('raw_material_id')->nullable(false)->change();
        });
    }
};
