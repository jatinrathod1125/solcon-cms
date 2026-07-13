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
        Schema::create('finished_goods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->onDelete('restrict');
            $table->foreignId('grade_id')->nullable()->constrained('grades')->onDelete('restrict');
            $table->foreignId('color_id')->nullable()->constrained('colors')->onDelete('restrict');
            $table->foreignId('epoxy_product_id')->nullable()->constrained('epoxy_products')->onDelete('restrict');
            $table->string('packing');
            $table->integer('available_bags')->default(0);
            $table->decimal('available_weight', 12, 4)->default(0.0000);
            $table->integer('minimum_stock')->default(20);
            $table->dateTime('last_production_date')->nullable();
            $table->string('status')->default('active');
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->unique(['grade_id', 'color_id', 'epoxy_product_id', 'packing'], 'finished_goods_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finished_goods');
    }
};
