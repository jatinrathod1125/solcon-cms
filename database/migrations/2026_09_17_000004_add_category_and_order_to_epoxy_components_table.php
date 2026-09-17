<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('epoxy_components', function (Blueprint $table) {
            if (!Schema::hasColumn('epoxy_components', 'component_category_id')) {
                $table->foreignId('component_category_id')
                    ->nullable()
                    ->after('brand_id')
                    ->constrained('component_categories')
                    ->nullOnDelete();
            }
            if (!Schema::hasColumn('epoxy_components', 'display_order')) {
                $table->integer('display_order')->default(0)->after('unit_id');
            }
            if (!Schema::hasColumn('epoxy_components', 'default_packing')) {
                $table->string('default_packing')->default('Box')->after('display_order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('epoxy_components', function (Blueprint $table) {
            if (Schema::hasColumn('epoxy_components', 'component_category_id')) {
                $table->dropForeign(['component_category_id']);
                $table->dropColumn('component_category_id');
            }
            if (Schema::hasColumn('epoxy_components', 'display_order')) {
                $table->dropColumn('display_order');
            }
            if (Schema::hasColumn('epoxy_components', 'default_packing')) {
                $table->dropColumn('default_packing');
            }
        });
    }
};
