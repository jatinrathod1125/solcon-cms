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
        Schema::create('epoxy_components', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->boolean('requires_color')->default(false);
            $table->foreignId('template_material_id')->nullable()->constrained('raw_materials')->onDelete('restrict');
            $table->foreignId('bulk_material_id')->nullable()->constrained('raw_materials')->onDelete('restrict');
            $table->decimal('bulk_qty_per_unit', 12, 4)->default(0.0000);
            $table->foreignId('packaging_material_id')->nullable()->constrained('raw_materials')->onDelete('restrict');
            $table->decimal('packaging_qty_per_unit', 12, 4)->default(0.0000);
            $table->timestamps();
        });

        Schema::create('epoxy_component_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('epoxy_component_id')->constrained('epoxy_components')->onDelete('cascade');
            $table->foreignId('epoxy_filler_color_id')->nullable()->constrained('epoxy_filler_colors')->onDelete('restrict');
            $table->foreignId('raw_material_id')->constrained('raw_materials')->onDelete('restrict');
            $table->timestamps();
            
            $table->unique(['epoxy_component_id', 'epoxy_filler_color_id'], 'comp_color_mapping_unique');
        });

        Schema::create('epoxy_component_preparations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('epoxy_component_id')->constrained('epoxy_components')->onDelete('restrict');
            $table->foreignId('epoxy_filler_color_id')->nullable()->constrained('epoxy_filler_colors')->onDelete('restrict');
            $table->integer('quantity');
            $table->foreignId('operator_id')->constrained('users')->onDelete('restrict');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epoxy_component_preparations');
        Schema::dropIfExists('epoxy_component_mappings');
        Schema::dropIfExists('epoxy_components');
    }
};
