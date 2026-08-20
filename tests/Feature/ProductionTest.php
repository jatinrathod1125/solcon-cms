<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\Machine;
use App\Models\RawMaterial;
use App\Models\Formula;
use App\Models\ProductionBatch;
use App\Models\StockLedger;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed default dataset
        $this->seed();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/production');
        $response->assertRedirect('/login');
    }

    public function test_supervisor_and_admin_can_access_production_index(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();
        $admin = User::where('email', 'admin@solcon.com')->first();

        $response = $this->actingAs($supervisor)->get('/production');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/production');
        $response->assertStatus(200);
    }

    public function test_supervisor_and_admin_can_access_production_create_form(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();
        $admin = User::where('email', 'admin@solcon.com')->first();

        $response = $this->actingAs($supervisor)->get('/production/create');
        $response->assertStatus(200);
        $response->assertViewHas(['machines', 'grades', 'batchNo', 'startTime', 'supervisors']);

        $response = $this->actingAs($admin)->get('/production/create');
        $response->assertStatus(200);
        $response->assertViewHas(['machines', 'grades', 'batchNo', 'startTime', 'supervisors']);
    }

    public function test_can_start_and_complete_production_batch(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();
        $machine = Machine::where('is_active', true)->where('department_id', $supervisor->department_id)->first();
        
        // Grade F101 has an active formula seeded
        $grade = Grade::where('code', 'F101')->first();
        
        // Get initial stock of OPC-53 (10000 KG)
        $rawMaterial = RawMaterial::where('code', 'OPC-53')->first();
        $initialStock = $rawMaterial->current_stock;

        // 1. Start Batch
        $response = $this->actingAs($supervisor)->post('/production', [
            'machine_id' => $machine->id,
            'grade_id' => $grade->id,
            'remarks' => 'Test production batch start',
        ]);

        $response->assertRedirect('/production');
        
        // Verify batch was created with status running
        $batch = ProductionBatch::where('machine_id', $machine->id)->where('status', 'running')->first();
        $this->assertNotNull($batch);
        $this->assertStringStartsWith('ADH-', $batch->batch_no);
        
        // Verify stock has NOT been deducted yet
        $rawMaterial->refresh();
        $this->assertEquals($initialStock, $rawMaterial->current_stock);
        $this->assertDatabaseMissing('stock_ledgers', [
            'batch_id' => $batch->id,
        ]);

        // 2. Complete Batch
        $response = $this->actingAs($supervisor)->put("/production/{$batch->id}/complete", [
            'output_bags' => 40, // 40 bags * 20 KG bag size = 800 KG
            'remarks' => 'Completed run successfully',
        ]);

        $response->assertRedirect('/production');

        $batch->refresh();
        $this->assertEquals('completed', $batch->status);
        $this->assertEquals(40.0000, $batch->output_bags);
        $this->assertEquals(800.0000, $batch->output_kg); // 40 bags * 20 KG = 800 KG
        $this->assertNotNull($batch->end_time);

        // Verify stock deduction occurred (F101 seeded formula has OPC-53 with 150 KG quantity)
        $rawMaterial->refresh();
        $expectedStock = $initialStock - 150.0000;
        $this->assertEquals($expectedStock, $rawMaterial->current_stock);

        // Verify Stock Ledger entry exists
        $this->assertDatabaseHas('stock_ledgers', [
            'raw_material_id' => $rawMaterial->id,
            'quantity' => -150.0000,
            'transaction_type' => 'OUT',
            'batch_id' => $batch->id,
        ]);
    }

    public function test_cannot_start_multiple_running_batches_on_same_machine(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();
        $machine = Machine::where('is_active', true)->where('department_id', $supervisor->department_id)->first();
        $grade = Grade::where('code', 'F101')->first();

        // Start first batch
        $this->actingAs($supervisor)->post('/production', [
            'machine_id' => $machine->id,
            'grade_id' => $grade->id,
        ]);

        // Try to start second batch on same machine
        $response = $this->actingAs($supervisor)->post('/production', [
            'machine_id' => $machine->id,
            'grade_id' => $grade->id,
        ]);

        $response->assertSessionHasErrors('machine_id');
    }

    public function test_cannot_start_batch_for_grade_without_active_formula(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();
        $machine = Machine::where('is_active', true)->where('department_id', $supervisor->department_id)->first();
        
        // Grade F107 has NO active formula seeded
        $grade = Grade::where('code', 'F107')->first() ?? Grade::create([
            'code' => 'F107',
            'department_id' => $supervisor->department_id,
            'name' => 'Grade F107',
            'bag_size_id' => \App\Models\BagSize::first()->id,
            'output_unit_id' => \App\Models\Unit::where('code', 'KG')->first()->id,
            'is_active' => true,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        $response = $this->actingAs($supervisor)->post('/production', [
            'machine_id' => $machine->id,
            'grade_id' => $grade->id,
        ]);

        $response->assertSessionHasErrors('grade_id');
    }

    public function test_output_based_material_consumption_deducts_actual_output_bags(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();
        $machine = Machine::where('is_active', true)->where('department_id', $supervisor->department_id)->first();
        $grade = Grade::where('code', 'F101')->first();

        // 1. Create a new formula version with formula-based ingredient AND output-based packing material
        $unitKG = \App\Models\Unit::where('code', 'KG')->first();
        $unitPCS = \App\Models\Unit::where('code', 'PCS')->first();
        $matCement = RawMaterial::where('code', 'OPC-53')->first();
        
        // Create packing material (Empty Bag)
        $matBag = RawMaterial::create([
            'code' => 'BAG-EMPTY-20',
            'name' => 'Empty 20KG Bag',
            'department_id' => $supervisor->department_id,
            'stock_unit_id' => $unitPCS->id,
            'purchase_unit_id' => $unitPCS->id,
            'purchase_conversion' => 1.0000,
            'opening_stock' => 1000.0000,
            'current_stock' => 1000.0000,
            'minimum_stock' => 100.0000,
            'maximum_stock' => 5000.0000,
            'is_active' => true,
        ]);

        $formulaResponse = $this->actingAs($admin)->post('/admin/formulas', [
            'grade_id' => $grade->id,
            'remarks' => 'Formula with Output Based Packing Material',
            'is_active' => true,
            'items' => [
                [
                    'raw_material_id' => $matCement->id,
                    'quantity' => 450.0000,
                    'unit_id' => $unitKG->id,
                    'consumption_method' => 'formula',
                    'sequence' => 1,
                ],
                [
                    'raw_material_id' => $matBag->id,
                    'quantity' => 100.0000, // Nominal formula qty = 100
                    'unit_id' => $unitPCS->id,
                    'consumption_method' => 'output', // Output based!
                    'consumption_per_unit' => 1.0000,
                    'sequence' => 2,
                ],
            ]
        ]);
        $formulaResponse->assertRedirect('/admin/formulas');

        // 2. Start batch
        $this->actingAs($supervisor)->post('/production', [
            'machine_id' => $machine->id,
            'grade_id' => $grade->id,
        ]);

        $batch = ProductionBatch::where('machine_id', $machine->id)->where('status', 'running')->first();
        $this->assertNotNull($batch);

        // 3. Complete batch with 98 Bags actual output
        $completeResponse = $this->actingAs($supervisor)->put("/production/{$batch->id}/complete", [
            'output_bags' => 98,
        ]);
        $completeResponse->assertRedirect('/production');

        // 4. Verify deductions:
        // Formula-based Cement: 450 KG deducted (exact formula quantity)
        $this->assertDatabaseHas('stock_ledgers', [
            'batch_id' => $batch->id,
            'raw_material_id' => $matCement->id,
            'quantity' => -450.0000,
        ]);

        // Output-based Empty Bag: 98 PCS deducted (actual output quantity, NOT 100)
        $this->assertDatabaseHas('stock_ledgers', [
            'batch_id' => $batch->id,
            'raw_material_id' => $matBag->id,
            'quantity' => -98.0000,
        ]);

        $matBag->refresh();
        $this->assertEquals(902.0000, $matBag->current_stock); // 1000 - 98 = 902
    }

    public function test_pause_and_resume_production_batch(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();
        $machine = Machine::where('is_active', true)->where('department_id', $supervisor->department_id)->first();
        $grade = Grade::where('code', 'F101')->first();

        // 1. Start batch
        $this->actingAs($supervisor)->post('/production', [
            'machine_id' => $machine->id,
            'grade_id' => $grade->id,
            'remarks' => 'Batch to pause',
        ]);

        $batch = ProductionBatch::where('machine_id', $machine->id)->where('status', 'running')->first();
        $this->assertNotNull($batch);

        // 2. Pause batch
        $response = $this->actingAs($supervisor)->post("/production/{$batch->id}/pause");
        $response->assertRedirect(route('production.show', $batch->id));
        $batch->refresh();
        $this->assertEquals('paused', $batch->status);

        // 3. Trying to start another batch on the same machine should fail because it has a paused batch
        $startResponse = $this->actingAs($supervisor)->post('/production', [
            'machine_id' => $machine->id,
            'grade_id' => $grade->id,
        ]);
        $startResponse->assertSessionHasErrors('machine_id');

        // 4. Resume batch
        $response = $this->actingAs($supervisor)->post("/production/{$batch->id}/resume");
        $response->assertRedirect(route('production.show', $batch->id));
        $batch->refresh();
        $this->assertEquals('running', $batch->status);
    }

    public function test_cancel_production_batch(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();
        $machine = Machine::where('is_active', true)->where('department_id', $supervisor->department_id)->first();
        $grade = Grade::where('code', 'F101')->first();

        // 1. Start batch
        $this->actingAs($supervisor)->post('/production', [
            'machine_id' => $machine->id,
            'grade_id' => $grade->id,
        ]);

        $batch = ProductionBatch::where('machine_id', $machine->id)->where('status', 'running')->first();
        $this->assertNotNull($batch);

        // 2. Cancel batch
        $response = $this->actingAs($supervisor)->post("/production/{$batch->id}/cancel", [
            'remarks' => 'Cancelled test batch',
        ]);

        $response->assertRedirect('/production');
        $batch->refresh();
        $this->assertEquals('cancelled', $batch->status);
        $this->assertEquals('Cancelled test batch', $batch->remarks);
        
        // Machine should be freed up now
        $startResponse = $this->actingAs($supervisor)->post('/production', [
            'machine_id' => $machine->id,
            'grade_id' => $grade->id,
        ]);
        $startResponse->assertRedirect('/production');
    }

    public function test_complete_batch_stores_coupon_finished_goods_separately(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();
        $machine = Machine::where('is_active', true)->where('department_id', $supervisor->department_id)->first();
        $grade = Grade::where('code', 'F101')->first();
        $coupon = RawMaterial::where('is_coupon', true)->first();

        // 1. Start and complete batch WITH coupon
        $this->actingAs($supervisor)->post('/production', [
            'machine_id' => $machine->id,
            'grade_id' => $grade->id,
            'coupon_raw_material_id' => $coupon->id,
        ]);

        $batchWithCoupon = ProductionBatch::where('machine_id', $machine->id)->where('status', 'running')->first();
        $this->actingAs($supervisor)->put("/production/{$batchWithCoupon->id}/complete", [
            'output_bags' => 10,
        ]);

        // 2. Start and complete batch WITHOUT coupon
        $this->actingAs($supervisor)->post('/production', [
            'machine_id' => $machine->id,
            'grade_id' => $grade->id,
            'coupon_raw_material_id' => '',
        ]);

        $batchWithoutCoupon = ProductionBatch::where('machine_id', $machine->id)->where('status', 'running')->first();
        $this->actingAs($supervisor)->put("/production/{$batchWithoutCoupon->id}/complete", [
            'output_bags' => 15,
        ]);

        // Verify Finished Goods records are separate
        $fgWithCoupon = \App\Models\FinishedGood::where('grade_id', $grade->id)
            ->where('coupon_raw_material_id', $coupon->id)
            ->first();

        $fgWithoutCoupon = \App\Models\FinishedGood::where('grade_id', $grade->id)
            ->whereNull('coupon_raw_material_id')
            ->first();

        $this->assertNotNull($fgWithCoupon);
        $this->assertNotNull($fgWithoutCoupon);
        $this->assertEquals(10, $fgWithCoupon->available_bags);
        $this->assertEquals(15, $fgWithoutCoupon->available_bags);

        $this->assertEquals($grade->name . ' (' . $coupon->name . ')', $fgWithCoupon->product_name);
        $this->assertEquals($grade->name, $fgWithoutCoupon->product_name);
    }

    public function test_production_create_and_index_filters_grades_by_current_brand(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $brandSolcon = \App\Models\Brand::where('code', 'SOL')->first();
        $brandFixora = \App\Models\Brand::where('code', 'FIX')->first() ?? \App\Models\Brand::where('slug', 'fixora')->first();

        $dept = \App\Models\Department::first();
        $bagSize = \App\Models\BagSize::first();
        $unit = \App\Models\Unit::first();

        // Grade 1 under Solcon
        $gradeSol = Grade::create([
            'name' => 'Solcon Grade 1',
            'code' => 'SOL-G1',
            'brand_id' => $brandSolcon->id,
            'department_id' => $dept->id,
            'bag_size_id' => $bagSize->id,
            'output_unit_id' => $unit->id,
            'is_active' => true,
        ]);
        Formula::create([
            'grade_id' => $gradeSol->id,
            'version' => 1,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        // Grade 2 under Fixora
        $gradeFix = Grade::create([
            'name' => 'Fixora Grade 1',
            'code' => 'FIX-G1',
            'brand_id' => $brandFixora->id,
            'department_id' => $dept->id,
            'bag_size_id' => $bagSize->id,
            'output_unit_id' => $unit->id,
            'is_active' => true,
        ]);
        Formula::create([
            'grade_id' => $gradeFix->id,
            'version' => 1,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        // Access production create with Fixora active in session
        $response = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $brandFixora->id])
            ->get('/production/create');

        $response->assertStatus(200);
        $response->assertSee('Fixora Grade 1');
        $response->assertDontSee('Solcon Grade 1');

        // Access production index (Quick workflow drawer) with Fixora active
        $response = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $brandFixora->id])
            ->get('/production');

        $response->assertStatus(200);
        $response->assertSee('Fixora Grade 1');
        $response->assertDontSee('Solcon Grade 1');
    }
}
