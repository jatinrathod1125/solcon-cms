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
        if (!Schema::hasColumn('marketing_orders', 'is_edited')) {
            Schema::table('marketing_orders', function (Blueprint $table) {
                $table->boolean('is_edited')->default(false)->after('status');
            });
        }

        if (!Schema::hasColumn('marketing_order_items', 'is_edited')) {
            Schema::table('marketing_order_items', function (Blueprint $table) {
                $table->boolean('is_edited')->default(false)->after('item_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('marketing_orders', 'is_edited')) {
            Schema::table('marketing_orders', function (Blueprint $table) {
                $table->dropColumn('is_edited');
            });
        }

        if (Schema::hasColumn('marketing_order_items', 'is_edited')) {
            Schema::table('marketing_order_items', function (Blueprint $table) {
                $table->dropColumn('is_edited');
            });
        }
    }
};
