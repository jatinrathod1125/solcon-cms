<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Department;
use App\Models\Machine;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grout_production_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no')->unique();
            $table->foreignId('machine_id')->constrained('machines')->onDelete('restrict');
            $table->foreignId('color_id')->constrained('colors')->onDelete('restrict');
            $table->foreignId('grout_formula_id')->constrained('grout_formulas')->onDelete('restrict');
            $table->json('formula_snapshot');
            $table->foreignId('operator_id')->constrained('users')->onDelete('restrict');
            $table->enum('status', [
                'Waiting',
                'Stage 1 Mixing',
                'Timer Running',
                'Waiting Cement',
                'Stage 2 Mixing',
                'Ready For Packing',
                'Packing',
                'Completed'
            ])->default('Waiting')->index();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('timer_start_time')->nullable();
            $table->dateTime('timer_end_time')->nullable();
            $table->dateTime('stage1_start_time')->nullable();
            $table->dateTime('stage1_end_time')->nullable();
            $table->dateTime('stage2_start_time')->nullable();
            $table->dateTime('stage2_end_time')->nullable();
            $table->dateTime('packing_start_time')->nullable();
            $table->dateTime('packing_end_time')->nullable();
            $table->integer('finished_bags')->nullable();
            $table->decimal('total_weight_kg', 12, 4)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Seed Grout Department and Machines M-01, M-04, M-05
        $groutDept = Department::updateOrCreate(
            ['code' => 'GRT'],
            [
                'name' => 'Grout Dept',
                'description' => 'Production department for Grout products',
                'is_active' => true
            ]
        );

        Machine::updateOrCreate(
            ['code' => 'M-01'],
            [
                'department_id' => $groutDept->id,
                'name' => 'Automatic Packing Machine M-01',
                'description' => 'Automatic packing machine for White & Ivory grouts (500 GM & 1 KG pouches)',
                'is_active' => true
            ]
        );

        Machine::updateOrCreate(
            ['code' => 'M-04'],
            [
                'department_id' => $groutDept->id,
                'name' => 'Manual Mixing Machine M-04',
                'description' => 'Manual mixer for colored grouts with 1-hour dry mix timers',
                'is_active' => true
            ]
        );

        Machine::updateOrCreate(
            ['code' => 'M-05'],
            [
                'department_id' => $groutDept->id,
                'name' => 'Manual Mixing Machine M-05',
                'description' => 'Manual mixer for colored grouts with 1-hour dry mix timers',
                'is_active' => true
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grout_production_batches');
    }
};
