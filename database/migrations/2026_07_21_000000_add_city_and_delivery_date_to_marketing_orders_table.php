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
        Schema::table('marketing_orders', function (Blueprint $table) {
            $table->string('city')->nullable()->after('party_name');
            $table->date('delivery_date')->nullable()->after('order_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_orders', function (Blueprint $table) {
            $table->dropColumn(['city', 'delivery_date']);
        });
    }
};
