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
        if (!Schema::hasColumn('component_categories', 'column_no')) {
            Schema::table('component_categories', function (Blueprint $table) {
                $table->tinyInteger('column_no')->default(4)->after('default_unit');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('component_categories', 'column_no')) {
            Schema::table('component_categories', function (Blueprint $table) {
                $table->dropColumn('column_no');
            });
        }
    }
};
