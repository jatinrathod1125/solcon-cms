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
        Schema::table('formula_items', function (Blueprint $table) {
            $table->string('consumption_method', 20)->default('formula')->after('unit_id');
            $table->decimal('consumption_per_unit', 12, 4)->default(1.0000)->after('consumption_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formula_items', function (Blueprint $table) {
            $table->dropColumn(['consumption_method', 'consumption_per_unit']);
        });
    }
};
