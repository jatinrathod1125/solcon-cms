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
        Schema::create('marketing_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_order_id')->constrained('marketing_orders')->cascadeOnDelete();
            $table->string('department_code'); // TAD, GRT, EPX
            $table->foreignId('grade_id')->nullable()->constrained('grades')->nullOnDelete();
            $table->foreignId('color_id')->nullable()->constrained('colors')->nullOnDelete();
            $table->foreignId('epoxy_product_id')->nullable()->constrained('epoxy_products')->nullOnDelete();
            $table->integer('quantity_bags');
            $table->decimal('quantity_kg', 10, 2)->nullable();
            $table->string('packing')->nullable(); // e.g., "20 KG", "1 KG"
            $table->foreignId('coupon_raw_material_id')->nullable()->constrained('raw_materials')->nullOnDelete();
            $table->integer('coupon_quantity')->nullable();
            $table->boolean('is_product_available')->default(false);
            $table->boolean('is_coupon_available')->nullable(); // null = no coupon needed
            $table->string('item_status')->default('pending'); // pending, completed
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('marketing_order_id');
            $table->index('department_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_order_items');
    }
};
