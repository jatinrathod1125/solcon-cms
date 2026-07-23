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
        Schema::create('packing_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('packing_material_categories')->onDelete('restrict');
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('size')->nullable();
            $table->foreignId('unit_id')->constrained('units')->onDelete('restrict');
            $table->decimal('minimum_stock', 12, 4)->default(0.0000);
            $table->decimal('opening_stock', 12, 4)->default(0.0000);
            $table->decimal('current_stock', 12, 4)->default(0.0000);
            $table->text('remarks')->nullable();
            $table->string('status')->default('active'); // active / inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packing_materials');
    }
};
