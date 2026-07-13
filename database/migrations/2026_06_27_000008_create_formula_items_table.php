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
        Schema::create('formula_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formula_id')->constrained('formulas')->onDelete('cascade');
            $table->foreignId('raw_material_id')->constrained('raw_materials')->onDelete('restrict');
            $table->decimal('quantity', 12, 4);
            $table->foreignId('unit_id')->constrained('units')->onDelete('restrict');
            $table->integer('sequence')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formula_items');
    }
};
