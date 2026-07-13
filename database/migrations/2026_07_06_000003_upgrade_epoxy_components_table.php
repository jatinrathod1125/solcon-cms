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
        // 1. Upgrade epoxy_components table
        Schema::table('epoxy_components', function (Blueprint $table) {
            $table->string('category')->nullable(); // Bottle, Pouch, Packet, Liquid, Powder, Plastic, Accessory, Other
            $table->string('purpose')->default('Assembly Component'); // Assembly Component, Direct Finished Product
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('restrict');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->foreignId('raw_material_id')->nullable()->constrained('raw_materials')->onDelete('restrict');
            $table->foreignId('parent_component_id')->nullable()->constrained('epoxy_components')->onDelete('cascade');
            $table->foreignId('epoxy_filler_color_id')->nullable()->constrained('epoxy_filler_colors')->onDelete('restrict');
        });

        // 2. Create epoxy_component_formulas table
        Schema::create('epoxy_component_formulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('epoxy_component_id')->constrained('epoxy_components')->onDelete('cascade');
            $table->integer('version')->default(1);
            $table->boolean('is_active')->default(false);
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });

        // 3. Create epoxy_component_formula_items table
        Schema::create('epoxy_component_formula_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('epoxy_component_formula_id', 'ep_comp_form_id_fk')
                ->constrained('epoxy_component_formulas')
                ->onDelete('cascade');
            $table->foreignId('raw_material_id')->constrained('raw_materials')->onDelete('restrict');
            $table->decimal('quantity', 12, 4);
            $table->foreignId('unit_id')->constrained('units')->onDelete('restrict');
            $table->timestamps();
        });

        // 4. Add epoxy_component_id to finished_goods table
        Schema::table('finished_goods', function (Blueprint $table) {
            $table->foreignId('epoxy_component_id')->nullable()->constrained('epoxy_components')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finished_goods', function (Blueprint $table) {
            $table->dropForeign(['epoxy_component_id']);
            $table->dropColumn('epoxy_component_id');
        });

        Schema::dropIfExists('epoxy_component_formula_items');
        Schema::dropIfExists('epoxy_component_formulas');

        Schema::table('epoxy_components', function (Blueprint $table) {
            $table->dropForeign(['epoxy_filler_color_id']);
            $table->dropForeign(['parent_component_id']);
            $table->dropForeign(['raw_material_id']);
            $table->dropForeign(['unit_id']);
            $table->dropColumn([
                'category',
                'purpose',
                'unit_id',
                'is_active',
                'description',
                'raw_material_id',
                'parent_component_id',
                'epoxy_filler_color_id'
            ]);
        });
    }
};
