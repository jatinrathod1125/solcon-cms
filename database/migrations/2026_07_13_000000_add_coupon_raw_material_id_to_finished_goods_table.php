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
        Schema::table('finished_goods', function (Blueprint $table) {
            $table->foreignId('coupon_raw_material_id')
                ->nullable()
                ->after('epoxy_component_id')
                ->constrained('raw_materials')
                ->onDelete('restrict');

            $table->dropUnique('finished_goods_unique');
            $table->unique(
                ['grade_id', 'color_id', 'epoxy_product_id', 'packing', 'coupon_raw_material_id'],
                'finished_goods_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finished_goods', function (Blueprint $table) {
            $table->dropUnique('finished_goods_unique');
            $table->unique(
                ['grade_id', 'color_id', 'epoxy_product_id', 'packing'],
                'finished_goods_unique'
            );
            $table->dropConstrainedForeignId('coupon_raw_material_id');
        });
    }
};
