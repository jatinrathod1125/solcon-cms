<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add brand_id to epoxy_products
        Schema::table('epoxy_products', function (Blueprint $table) {
            if (!Schema::hasColumn('epoxy_products', 'brand_id')) {
                $table->foreignId('brand_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('brands')
                    ->onDelete('restrict');
            }
        });

        // 2. Add brand_id to epoxy_components
        Schema::table('epoxy_components', function (Blueprint $table) {
            if (!Schema::hasColumn('epoxy_components', 'brand_id')) {
                $table->foreignId('brand_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('brands')
                    ->onDelete('restrict');
            }
        });

        // 3. Add brand_id to epoxy_filler_colors
        Schema::table('epoxy_filler_colors', function (Blueprint $table) {
            if (!Schema::hasColumn('epoxy_filler_colors', 'brand_id')) {
                $table->foreignId('brand_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('brands')
                    ->onDelete('restrict');
            }
        });

        // Backfill existing records with Solcon brand if available
        $solconBrandId = DB::table('brands')->where('code', 'SOL')->value('id')
            ?? DB::table('brands')->value('id');

        if ($solconBrandId) {
            DB::table('epoxy_products')
                ->whereNull('brand_id')
                ->update(['brand_id' => $solconBrandId]);

            DB::table('epoxy_filler_colors')
                ->whereNull('brand_id')
                ->update(['brand_id' => $solconBrandId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('epoxy_filler_colors', function (Blueprint $table) {
            if (Schema::hasColumn('epoxy_filler_colors', 'brand_id')) {
                $table->dropConstrainedForeignId('brand_id');
            }
        });

        Schema::table('epoxy_components', function (Blueprint $table) {
            if (Schema::hasColumn('epoxy_components', 'brand_id')) {
                $table->dropConstrainedForeignId('brand_id');
            }
        });

        Schema::table('epoxy_products', function (Blueprint $table) {
            if (Schema::hasColumn('epoxy_products', 'brand_id')) {
                $table->dropConstrainedForeignId('brand_id');
            }
        });
    }
};
