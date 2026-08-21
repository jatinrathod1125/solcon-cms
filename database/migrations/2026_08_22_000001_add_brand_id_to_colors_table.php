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
        Schema::table('colors', function (Blueprint $table) {
            if (!Schema::hasColumn('colors', 'brand_id')) {
                $table->foreignId('brand_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('brands')
                    ->onDelete('restrict');
            }
        });

        // Backfill existing colors with Solcon brand if available
        $solconBrandId = DB::table('brands')->where('code', 'SOL')->value('id')
            ?? DB::table('brands')->value('id');

        if ($solconBrandId) {
            DB::table('colors')
                ->whereNull('brand_id')
                ->update(['brand_id' => $solconBrandId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colors', function (Blueprint $table) {
            if (Schema::hasColumn('colors', 'brand_id')) {
                $table->dropConstrainedForeignId('brand_id');
            }
        });
    }
};
