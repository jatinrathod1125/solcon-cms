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
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->foreignId('brand_id')
                ->nullable()
                ->after('id')
                ->constrained('brands')
                ->onDelete('restrict');
        });

        if (! DB::table('raw_materials')->exists()) {
            return;
        }

        $solconBrandId = DB::table('brands')
            ->where('code', 'SOL')
            ->value('id');

        if (! $solconBrandId) {
            throw new RuntimeException('The Solcon brand record is required to backfill raw materials.');
        }

        DB::table('raw_materials')
            ->whereNull('brand_id')
            ->update(['brand_id' => $solconBrandId]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropConstrainedForeignId('brand_id');
        });
    }
};
