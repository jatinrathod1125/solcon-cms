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
        Schema::create('epoxy_assemblies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('epoxy_product_id')->constrained('epoxy_products')->onDelete('restrict');
            $table->foreignId('color_id')->nullable()->constrained('colors')->onDelete('restrict');
            $table->json('formula_snapshot');
            $table->integer('quantity');
            $table->foreignId('operator_id')->constrained('users')->onDelete('restrict');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epoxy_assemblies');
    }
};
