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
        Schema::table('dispatches', function (Blueprint $table) {
            $columnsToDrop = [];
            
            if (Schema::hasColumn('dispatches', 'payment_status')) {
                $columnsToDrop[] = 'payment_status';
            }
            if (Schema::hasColumn('dispatches', 'payment_amount')) {
                $columnsToDrop[] = 'payment_amount';
            }
            if (Schema::hasColumn('dispatches', 'payment_date')) {
                $columnsToDrop[] = 'payment_date';
            }
            if (Schema::hasColumn('dispatches', 'payment_reference')) {
                $columnsToDrop[] = 'payment_reference';
            }
            if (Schema::hasColumn('dispatches', 'payment_remarks')) {
                $columnsToDrop[] = 'payment_remarks';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispatches', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending')->nullable();
            $table->decimal('payment_amount', 12, 2)->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('payment_remarks')->nullable();
        });
    }
};
