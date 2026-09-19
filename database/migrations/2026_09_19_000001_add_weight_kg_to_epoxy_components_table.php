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
        Schema::table('epoxy_components', function (Blueprint $table) {
            if (!Schema::hasColumn('epoxy_components', 'weight_kg')) {
                $table->decimal('weight_kg', 10, 3)->nullable()->after('purpose');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('epoxy_components', function (Blueprint $table) {
            if (Schema::hasColumn('epoxy_components', 'weight_kg')) {
                $table->dropColumn('weight_kg');
            }
        });
    }
};
