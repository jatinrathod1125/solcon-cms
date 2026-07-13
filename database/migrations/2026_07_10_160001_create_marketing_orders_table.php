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
        Schema::create('marketing_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('party_name');
            $table->string('vehicle_number')->nullable();
            $table->date('order_date');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('status')->default('pending'); // pending, in_progress, completed, cancelled
            $table->string('availability')->default('unknown'); // available, partial, unavailable, unknown
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['status', 'sort_order']);
            $table->index('party_name');
            $table->index('order_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_orders');
    }
};
