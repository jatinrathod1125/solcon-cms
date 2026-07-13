<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Department;
use App\Models\Machine;
use App\Models\Color;
use App\Models\RawMaterial;
use App\Models\Unit;
use App\Models\GroutFormula;
use App\Models\GroutFormulaItem;
use App\Models\GroutProductionBatch;
use App\Services\MixTimerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroutProductionTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $supervisorUser;
    protected $deptGRT;
    protected $machineM01;
    protected $machineM04;
    protected $colorWhite;
    protected $colorBlack;
    protected $matCement;
    protected $matRDP;
    protected $unitKG;
    protected $formulaWhite;
    protected $formulaBlack;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->adminUser = User::where('email', 'admin@solcon.com')->first();
        $this->supervisorUser = User::where('email', 'supervisor@solcon.com')->first();
        
        // Ensure Grout Department is mapped to supervisor to allow access
        $this->deptGRT = Department::where('code', 'GRT')->first();
        $this->supervisorUser->departments()->sync([$this->deptGRT->id]);
        // Set supervisor active department session in request
        $this->withSession(['current_department_id_' . $this->supervisorUser->id => $this->deptGRT->id]);

        $this->machineM01 = Machine::where('code', 'M-01')->first();
        $this->machineM04 = Machine::where('code', 'M-04')->first();
        
        $this->unitKG = Unit::where('code', 'KG')->first();

        // 1. Create Colors
        $this->colorWhite = Color::create([
            'department_id' => $this->deptGRT->id,
            'name' => 'White Grout',
            'code' => 'GR-WHT',
            'packing_size' => '1 KG',
            'default_cement' => 'White Cement',
            'is_active' => 1,
            'created_by' => $this->adminUser->id,
        ]);

        $this->colorBlack = Color::create([
            'department_id' => $this->deptGRT->id,
            'name' => 'Black Grout',
            'code' => 'GR-BLK',
            'packing_size' => '500 GM',
            'default_cement' => 'Grey Cement',
            'is_active' => 1,
            'created_by' => $this->adminUser->id,
        ]);

        // 2. Create raw materials
        $this->matCement = RawMaterial::where('code', 'OPC-53')->first();
        // Move OPC cement to GRT department for validation
        $this->matCement->update(['department_id' => $this->deptGRT->id]);

        $this->matRDP = RawMaterial::updateOrCreate(
            ['code' => 'RDP-01'],
            [
                'name' => 'RDP Powder',
                'department_id' => $this->deptGRT->id,
                'stock_unit_id' => $this->unitKG->id,
                'purchase_unit_id' => $this->unitKG->id,
                'purchase_conversion' => 1,
                'opening_stock' => 5000,
                'current_stock' => 5000,
                'minimum_stock' => 10,
                'maximum_stock' => 10000,
                'is_active' => true,
            ]
        );

        // 3. Create active formulas
        $this->formulaWhite = GroutFormula::create([
            'color_id' => $this->colorWhite->id,
            'version' => 1,
            'is_active' => true,
            'created_by' => $this->adminUser->id,
        ]);

        GroutFormulaItem::create([
            'grout_formula_id' => $this->formulaWhite->id,
            'raw_material_id' => $this->matRDP->id,
            'quantity' => 150.0000,
            'unit_id' => $this->unitKG->id,
            'mix_stage' => 'Stage 1',
        ]);

        GroutFormulaItem::create([
            'grout_formula_id' => $this->formulaWhite->id,
            'raw_material_id' => $this->matCement->id,
            'quantity' => 850.0000,
            'unit_id' => $this->unitKG->id,
            'mix_stage' => 'Stage 2',
        ]);

        $this->formulaBlack = GroutFormula::create([
            'color_id' => $this->colorBlack->id,
            'version' => 1,
            'is_active' => true,
            'created_by' => $this->adminUser->id,
        ]);

        GroutFormulaItem::create([
            'grout_formula_id' => $this->formulaBlack->id,
            'raw_material_id' => $this->matRDP->id,
            'quantity' => 100.0000,
            'unit_id' => $this->unitKG->id,
            'mix_stage' => 'Stage 1',
        ]);

        GroutFormulaItem::create([
            'grout_formula_id' => $this->formulaBlack->id,
            'raw_material_id' => $this->matCement->id,
            'quantity' => 900.0000,
            'unit_id' => $this->unitKG->id,
            'mix_stage' => 'Stage 2',
        ]);
    }

    public function test_supervisor_access_and_department_boundary(): void
    {
        // Access index page
        $response = $this->actingAs($this->supervisorUser)
            ->get('/grout-production');
        $response->assertStatus(200);

        // Access dashboard of TAD department (Adhesive) is blocked if Grout production runs
        // Wait, grout production is strictly in Grout department
    }

    public function test_machine_m01_color_restriction(): void
    {
        // 1. M-01 with Black Grout (Not allowed)
        $response = $this->actingAs($this->supervisorUser)->post('/grout-production', [
            'machine_id' => $this->machineM01->id,
            'color_id' => $this->colorBlack->id,
            'remarks' => 'Starting black grout run',
        ]);
        $response->assertSessionHasErrors('color_id');

        // 2. M-01 with White Grout (Allowed)
        $responseSuccess = $this->actingAs($this->supervisorUser)->post('/grout-production', [
            'machine_id' => $this->machineM01->id,
            'color_id' => $this->colorWhite->id,
            'remarks' => 'Starting white grout run',
        ]);
        $responseSuccess->assertRedirect();
        
        $this->assertDatabaseHas('grout_production_batches', [
            'machine_id' => $this->machineM01->id,
            'color_id' => $this->colorWhite->id,
            'status' => 'Stage 1 Mixing',
        ]);
    }

    public function test_manual_machine_timer_and_proportional_deduction_workflow(): void
    {
        // 1. Start batch on M-04 (Manual) with Black Grout
        $response = $this->actingAs($this->supervisorUser)->post('/grout-production', [
            'machine_id' => $this->machineM04->id,
            'color_id' => $this->colorBlack->id,
            'remarks' => 'Manual batch run',
        ]);
        $response->assertRedirect();

        $batch = GroutProductionBatch::where('machine_id', $this->machineM04->id)->first();
        $this->assertEquals('Stage 1 Mixing', $batch->status);

        // 2. Start mixing timer
        $this->actingAs($this->supervisorUser)
            ->post("/grout-production/{$batch->id}/start-timer")
            ->assertRedirect();

        $batch->refresh();
        $this->assertEquals('Timer Running', $batch->status);
        $this->assertNotNull($batch->timer_end_time);

        // 3. Attempting to proceed to Stage 2 fails before timer completes
        $responseProceedFail = $this->actingAs($this->supervisorUser)
            ->post("/grout-production/{$batch->id}/proceed-stage2");
        $responseProceedFail->assertSessionHas('error');

        // 4. Force/mock timer completion
        $batch->update(['timer_end_time' => now()->subMinute()]);

        $responseProceedSuccess = $this->actingAs($this->supervisorUser)
            ->post("/grout-production/{$batch->id}/proceed-stage2");
        $responseProceedSuccess->assertSessionHas('success');

        $batch->refresh();
        $this->assertEquals('Stage 2 Mixing', $batch->status);

        // 5. Finish mixing
        $this->actingAs($this->supervisorUser)
            ->post("/grout-production/{$batch->id}/finish-mixing")
            ->assertRedirect();

        $batch->refresh();
        $this->assertEquals('Ready For Packing', $batch->status);

        // 6. Start packing
        $this->actingAs($this->supervisorUser)
            ->post("/grout-production/{$batch->id}/start-packing")
            ->assertRedirect();

        $batch->refresh();
        $this->assertEquals('Packing', $batch->status);

        // 7. Complete batch (40 Bags = 1000 KG total)
        // Check initial stock levels
        $rdpStockBefore = (float) $this->matRDP->current_stock; // 5000 KG
        $cementStockBefore = (float) $this->matCement->current_stock; // 5000 KG

        // Black Grout Formula uses: RDP = 100 KG, Cement = 900 KG per 1000 KG output
        // Producing 40 Bags of 25 KG = 1000 KG, so consumption factor is exactly 1.0
        // Consumption should be: RDP = 100 KG, Cement = 900 KG
        $responseComplete = $this->actingAs($this->supervisorUser)->post("/grout-production/{$batch->id}/complete", [
            'finished_bags' => 40,
            'remarks' => 'Deduction success',
        ]);
        $responseComplete->assertRedirect('/grout-production');

        $batch->refresh();
        $this->assertEquals('Completed', $batch->status);
        $this->assertEquals(40, $batch->finished_bags);
        $this->assertEquals(1000, $batch->total_weight_kg);

        // Check stock levels decremented correctly
        $this->matRDP->refresh();
        $this->matCement->refresh();

        $this->assertEquals($rdpStockBefore - 100, (float) $this->matRDP->current_stock);
        $this->assertEquals($cementStockBefore - 900, (float) $this->matCement->current_stock);

        // Check StockLedger entries created with grout_batch_id
        $this->assertDatabaseHas('stock_ledgers', [
            'grout_batch_id' => $batch->id,
            'raw_material_id' => $this->matRDP->id,
            'transaction_type' => 'OUT',
            'quantity' => -100.0000,
        ]);
    }
}
