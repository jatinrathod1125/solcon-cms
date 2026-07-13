<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\ProductionBatch;
use App\Models\FinishedGood;
use App\Models\User;
use App\Models\Department;
use App\Models\Machine;
use App\Models\Formula;
use App\Services\ProductionService;
use App\Services\FinishedGoodsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FinishedGoodsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $deptTAD;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::where('email', 'admin@solcon.com')->first();
        $this->deptTAD = Department::where('code', 'TAD')->first();
    }

    public function test_adhesive_production_completion_auto_logs_finished_goods(): void
    {
        $this->actingAs($this->admin);

        $grade = Grade::first();
        $formula = Formula::where('grade_id', $grade->id)->first();
        $machine = Machine::where('department_id', $this->deptTAD->id)->first();
        
        $batch = ProductionBatch::create([
            'batch_no' => 'BAT-TEST-01',
            'department_id' => $this->deptTAD->id,
            'machine_id' => $machine->id,
            'grade_id' => $grade->id,
            'formula_id' => $formula->id,
            'formula_snapshot' => [],
            'start_time' => now(),
            'status' => 'running',
            'supervisor_id' => $this->admin->id,
        ]);

        ProductionService::completeBatch($batch->id, 50.00, now()->toDateTimeString(), 'Test run');

        $fg = FinishedGood::where('grade_id', $grade->id)->first();
        $this->assertNotNull($fg);
        $this->assertEquals(50, $fg->available_bags);
        $this->assertEquals(50 * $grade->bagSize->value, $fg->available_weight);
    }

    public function test_manual_stock_adjustment_increase(): void
    {
        $this->actingAs($this->admin);

        $grade = Grade::first();
        $fg = FinishedGood::create([
            'department_id' => $this->deptTAD->id,
            'grade_id' => $grade->id,
            'packing' => $grade->bagSize->name,
            'available_bags' => 10,
            'available_weight' => 200.0,
            'minimum_stock' => 5,
        ]);

        $response = $this->post(route('finished-goods.adjust', $fg->id), [
            'type' => 'increase',
            'quantity' => 15,
            'weight' => 300.0,
            'reason' => 'Damaged return',
        ]);

        $response->assertRedirect(route('finished-goods.index'));
        $fg->refresh();
        $this->assertEquals(25, $fg->available_bags);
        $this->assertEquals(500.0, $fg->available_weight);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'FINISHED_GOODS_ADJUSTED',
        ]);
    }

    public function test_manual_stock_adjustment_decrease(): void
    {
        $this->actingAs($this->admin);

        $grade = Grade::first();
        $fg = FinishedGood::create([
            'department_id' => $this->deptTAD->id,
            'grade_id' => $grade->id,
            'packing' => $grade->bagSize->name,
            'available_bags' => 20,
            'available_weight' => 400.0,
            'minimum_stock' => 5,
        ]);

        $response = $this->post(route('finished-goods.adjust', $fg->id), [
            'type' => 'decrease',
            'quantity' => 5,
            'weight' => 100.0,
            'reason' => 'Inventory count adjust',
        ]);

        $response->assertRedirect(route('finished-goods.index'));
        $fg->refresh();
        $this->assertEquals(15, $fg->available_bags);
        $this->assertEquals(300.0, $fg->available_weight);
    }

    public function test_manual_stock_adjustment_fails_to_decrease_below_zero(): void
    {
        $this->actingAs($this->admin);

        $grade = Grade::first();
        $fg = FinishedGood::create([
            'department_id' => $this->deptTAD->id,
            'grade_id' => $grade->id,
            'packing' => $grade->bagSize->name,
            'available_bags' => 5,
            'available_weight' => 100.0,
            'minimum_stock' => 5,
        ]);

        $response = $this->post(route('finished-goods.adjust', $fg->id), [
            'type' => 'decrease',
            'quantity' => 10,
            'reason' => 'Inventory count adjust',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $fg->refresh();
        $this->assertEquals(5, $fg->available_bags);
    }

    public function test_csv_export(): void
    {
        $this->actingAs($this->admin);

        $grade = Grade::first();
        FinishedGood::create([
            'department_id' => $this->deptTAD->id,
            'grade_id' => $grade->id,
            'packing' => '20 KG Bag',
            'available_bags' => 10,
            'available_weight' => 200.0,
            'minimum_stock' => 5,
        ]);

        $response = $this->get(route('finished-goods.export'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Department,Product,Packing,Quantity,Status', $response->streamedContent());
        $this->assertStringContainsString($this->deptTAD->name, $response->streamedContent());
    }

    public function test_csv_import(): void
    {
        $this->actingAs($this->admin);

        $grade = Grade::first();

        $csvData = "Department,Product,Packing,Quantity,Status\n";
        $csvData .= "TAD,{$grade->code},20 KG Bag,45,Active\n";

        $file = UploadedFile::fake()->createWithContent('import.csv', $csvData);

        $response = $this->post(route('finished-goods.import'), [
            'csv_file' => $file,
        ]);

        $response->assertRedirect(route('finished-goods.index'));
        $response->assertSessionHas('success');

        $fg = FinishedGood::where('grade_id', $grade->id)->first();
        $this->assertNotNull($fg);
        $this->assertEquals(45, $fg->available_bags);
        $this->assertEquals(45 * $grade->bagSize->value, $fg->available_weight);
    }
}
