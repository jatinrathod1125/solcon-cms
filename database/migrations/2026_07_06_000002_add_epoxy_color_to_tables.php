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
        Schema::table('epoxy_assemblies', function (Blueprint $table) {
            $table->foreignId('epoxy_filler_color_id')->nullable()->after('color_id')->constrained('epoxy_filler_colors')->onDelete('restrict');
        });

        Schema::table('finished_goods', function (Blueprint $table) {
            $table->foreignId('epoxy_filler_color_id')->nullable()->after('color_id')->constrained('epoxy_filler_colors')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finished_goods', function (Blueprint $table) {
            $table->dropForeign(['epoxy_filler_color_id']);
            $table->dropColumn('epoxy_filler_color_id');
        });

        Schema::table('epoxy_assemblies', function (Blueprint $table) {
            $table->dropForeign(['epoxy_filler_color_id']);
            $table->dropColumn('epoxy_filler_color_id');
        });
    }
};
