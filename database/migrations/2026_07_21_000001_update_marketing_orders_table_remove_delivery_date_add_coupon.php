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
            if (Schema::hasColumn('marketing_orders', 'delivery_date')) {
                $table->dropColumn('delivery_date');
            }
            if (!Schema::hasColumn('marketing_orders', 'coupon')) {
                $table->string('coupon')->nullable()->after('city');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('marketing_orders', 'delivery_date')) {
                $table->date('delivery_date')->nullable()->after('order_date');
            }
            if (Schema::hasColumn('marketing_orders', 'coupon')) {
                $table->dropColumn('coupon');
            }
        });
    }
};
