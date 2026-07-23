<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('packing_material_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Seed default categories
        $categories = [
            'Adhesive Bags',
            'Pouches',
            'Buckets',
            'Bottles',
            'Stickers',
            'Boxes / Cartons',
            'Barrels',
            'Epoxy Accessories',
        ];

        foreach ($categories as $category) {
            DB::table('packing_material_categories')->insert([
                'name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packing_material_categories');
    }
};
