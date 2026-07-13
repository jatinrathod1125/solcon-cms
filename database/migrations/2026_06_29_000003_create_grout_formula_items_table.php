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
        Schema::create('grout_formula_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grout_formula_id')->constrained('grout_formulas')->onDelete('cascade');
            $table->foreignId('raw_material_id')->constrained('raw_materials')->onDelete('restrict');
            $table->decimal('quantity', 10, 4);
            $table->foreignId('unit_id')->constrained('units')->onDelete('restrict');
            $table->string('mix_stage'); // Stage 1, Stage 2
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grout_formula_items');
    }
};
