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
        Schema::table('formula_items', function (Blueprint $table) {
            $table->unsignedBigInteger('raw_material_id')->nullable()->change();
            $table->string('item_type', 20)->default('raw')->after('raw_material_id');
            $table->foreignId('packing_material_id')->nullable()->after('item_type')->constrained('packing_materials')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formula_items', function (Blueprint $table) {
            $table->dropForeign(['packing_material_id']);
            $table->dropColumn(['item_type', 'packing_material_id']);
            $table->unsignedBigInteger('raw_material_id')->nullable(false)->change();
        });
    }
};
