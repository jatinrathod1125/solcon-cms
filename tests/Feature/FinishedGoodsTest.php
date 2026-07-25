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

    public function test_admin_can_manually_create_finished_good_with_existing_adhesive_grade(): void
    {
        $this->actingAs($this->admin);

        $grade = Grade::first();

        $response = $this->post(route('finished-goods.store'), [
            'department_id' => $this->deptTAD->id,
            'grade_id' => $grade->id,
            'packing' => '25 KG Bag',
            'available_bags' => 30,
            'available_weight' => 750.0,
            'minimum_stock' => 10,
            'remarks' => 'Manual initial stock',
        ]);

        $response->assertRedirect(route('finished-goods.index'));
        $response->assertSessionHas('success');

        $fg = FinishedGood::where('department_id', $this->deptTAD->id)
            ->where('grade_id', $grade->id)
            ->where('packing', '25 KG Bag')
            ->first();

        $this->assertNotNull($fg);
        $this->assertEquals(30, $fg->available_bags);
        $this->assertEquals(750.0, $fg->available_weight);
    }

    public function test_non_admin_cannot_create_finished_good(): void
    {
        $supervisorRole = \App\Models\Role::where('slug', 'supervisor')->first();
        $supervisor = User::create([
            'name' => 'Test Supervisor',
            'email' => 'testsupervisor@solcon.com',
            'password' => bcrypt('password'),
            'department_id' => $this->deptTAD->id,
            'is_active' => true,
        ]);
        if ($supervisorRole) {
            $supervisor->roles()->sync([$supervisorRole->id]);
        }
        $supervisor = $supervisor->fresh();

        $this->actingAs($supervisor);

        $grade = Grade::first();

        $response = $this->post(route('finished-goods.store'), [
            'department_id' => $this->deptTAD->id,
            'grade_id' => $grade->id,
            'packing' => '20 KG Bag',
            'available_bags' => 10,
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_grout_and_epoxy_finished_goods(): void
    {
        $this->actingAs($this->admin);

        $deptGRT = Department::where('code', 'GRT')->first();
        $color = \App\Models\Color::create([
            'department_id' => $deptGRT->id,
            'name' => 'Royal Blue Grout',
            'code' => 'GRT-BLU',
            'packing_size' => '1 KG Pouch',
            'default_cement' => 'White Cement',
            'is_active' => true,
            'created_by' => $this->admin->id,
            'updated_by' => $this->admin->id,
        ]);

        $response = $this->post(route('finished-goods.store'), [
            'department_id' => $deptGRT->id,
            'color_id' => $color->id,
            'packing' => '1 KG Pouch',
            'available_bags' => 100,
            'available_weight' => 100.0,
            'minimum_stock' => 20,
        ]);

        $response->assertRedirect(route('finished-goods.index'));
        $fgGrt = FinishedGood::where('department_id', $deptGRT->id)->where('color_id', $color->id)->first();
        $this->assertNotNull($fgGrt);
        $this->assertEquals('Royal Blue Grout', $fgGrt->product_name);

        $deptEPX = Department::where('code', 'EPX')->first();
        $epoxy = \App\Models\EpoxyProduct::create([
            'name' => 'Epoxy Resin Kit 5KG',
            'code' => 'EPX-5KG',
            'is_active' => true,
        ]);

        $response2 = $this->post(route('finished-goods.store'), [
            'department_id' => $deptEPX->id,
            'epoxy_product_id' => $epoxy->id,
            'packing' => '5 KG Bucket',
            'available_bags' => 25,
            'available_weight' => 125.0,
            'minimum_stock' => 5,
        ]);

        $response2->assertRedirect(route('finished-goods.index'));
        $fgEpx = FinishedGood::where('department_id', $deptEPX->id)->where('epoxy_product_id', $epoxy->id)->first();
        $this->assertNotNull($fgEpx);
        $this->assertEquals('Epoxy Resin Kit 5KG', $fgEpx->product_name);
    }
}
