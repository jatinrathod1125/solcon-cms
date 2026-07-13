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
        Schema::create('epoxy_formula_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('epoxy_formula_id')->constrained('epoxy_formulas')->onDelete('cascade');
            $table->foreignId('raw_material_id')->constrained('raw_materials')->onDelete('restrict');
            $table->decimal('quantity', 10, 4);
            $table->foreignId('unit_id')->constrained('units')->onDelete('restrict');
            $table->boolean('is_dynamic_color')->default(false);
            $table->string('material_type'); // Bottle, Pouch, Accessory, Bucket
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epoxy_formula_items');
    }
};
