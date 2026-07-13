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
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('department_id')->constrained('departments')->onDelete('restrict');
            $table->foreignId('stock_unit_id')->constrained('units')->onDelete('restrict');
            $table->foreignId('purchase_unit_id')->constrained('units')->onDelete('restrict');
            $table->decimal('purchase_conversion', 12, 4)->default(1.0000);
            $table->decimal('opening_stock', 12, 4)->default(0.0000);
            $table->decimal('current_stock', 12, 4)->default(0.0000);
            $table->decimal('minimum_stock', 12, 4)->default(0.0000);
            $table->decimal('maximum_stock', 12, 4)->default(0.0000);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};
