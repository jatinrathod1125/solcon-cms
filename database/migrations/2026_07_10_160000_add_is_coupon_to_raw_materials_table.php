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
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->boolean('is_coupon')->default(false)->after('is_active');
        });

        // Mark existing coupon raw materials
        \App\Models\RawMaterial::where('code', 'like', '%COUPON%')
            ->orWhere('code', 'like', '%CPN%')
            ->update(['is_coupon' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropColumn('is_coupon');
        });
    }
};
