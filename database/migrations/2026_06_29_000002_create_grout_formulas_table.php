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
        Schema::create('grout_formulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('color_id')->constrained('colors')->onDelete('cascade');
            $table->integer('version');
            $table->text('remarks')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->unique(['color_id', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grout_formulas');
    }
};
