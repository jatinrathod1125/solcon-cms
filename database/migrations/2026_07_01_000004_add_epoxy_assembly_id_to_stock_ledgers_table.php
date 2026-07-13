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
        Schema::table('stock_ledgers', function (Blueprint $table) {
            $table->foreignId('epoxy_assembly_id')->nullable()->after('grout_batch_id')->constrained('epoxy_assemblies')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_ledgers', function (Blueprint $table) {
            $table->dropForeign(['epoxy_assembly_id']);
            $table->dropColumn('epoxy_assembly_id');
        });
    }
};
