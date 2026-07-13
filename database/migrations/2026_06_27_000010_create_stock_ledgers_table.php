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
        Schema::create('stock_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raw_material_id')->constrained('raw_materials')->onDelete('restrict');
            $table->foreignId('batch_id')->nullable()->constrained('production_batches')->onDelete('restrict');
            $table->enum('transaction_type', ['IN', 'OUT', 'ADJUSTMENT']);
            $table->decimal('quantity', 12, 4);
            $table->decimal('balance_after', 12, 4);
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->index(['raw_material_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_ledgers');
    }
};
