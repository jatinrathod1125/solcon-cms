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
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no')->unique();
            $table->foreignId('machine_id')->constrained('machines')->onDelete('restrict');
            $table->foreignId('grade_id')->constrained('grades')->onDelete('restrict');
            $table->foreignId('formula_id')->constrained('formulas')->onDelete('restrict');
            $table->foreignId('supervisor_id')->constrained('users')->onDelete('restrict');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->decimal('output_bags', 12, 4)->nullable();
            $table->decimal('output_kg', 12, 4)->nullable();
            $table->enum('status', ['running', 'completed'])->default('running')->index();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batches');
    }
};
