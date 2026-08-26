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
        Schema::table('marketing_order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('marketing_order_items', 'epoxy_component_id')) {
                $table->foreignId('epoxy_component_id')
                    ->nullable()
                    ->after('epoxy_product_id')
                    ->constrained('epoxy_components')
                    ->nullOnDelete();
            }
            if (!Schema::hasColumn('marketing_order_items', 'epoxy_filler_color_id')) {
                $table->foreignId('epoxy_filler_color_id')
                    ->nullable()
                    ->after('epoxy_product_id')
                    ->constrained('epoxy_filler_colors')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_order_items', function (Blueprint $table) {
            if (Schema::hasColumn('marketing_order_items', 'epoxy_component_id')) {
                $table->dropConstrainedForeignId('epoxy_component_id');
            }
            if (Schema::hasColumn('marketing_order_items', 'epoxy_filler_color_id')) {
                $table->dropConstrainedForeignId('epoxy_filler_color_id');
            }
        });
    }
};
