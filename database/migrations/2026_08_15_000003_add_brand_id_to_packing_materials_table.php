<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('packing_materials', function (Blueprint $table) {
            if (!Schema::hasColumn('packing_materials', 'brand_id')) {
                $table->foreignId('brand_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('brands')
                    ->onDelete('restrict');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packing_materials', function (Blueprint $table) {
            if (Schema::hasColumn('packing_materials', 'brand_id')) {
                $table->dropConstrainedForeignId('brand_id');
            }
        });
    }
};
