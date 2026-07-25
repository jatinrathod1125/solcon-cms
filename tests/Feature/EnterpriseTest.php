<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\Machine;
use App\Models\RawMaterial;
use App\Models\ProductionBatch;
use App\Models\User;
use App\Models\StockAdjustment;
use App\Models\ActivityLog;
use App\Models\StockLedger;
use App\Services\BatchNumberService;
use App\Services\FormulaService;
use App\Services\StockService;
use App\Services\ProductionService;
use App\Services\ReportService;
use App\Services\ActivityLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnterpriseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed default dataset
        $this->seed();
    }

    public function test_batch_number_service_generates_adh_code(): void
    {
        $code = BatchNumberService::generate();
        $this->assertMatchesRegularExpression('/^ADH-\d{8}-\d{4}$/', $code);
    }

    public function test_formula_service_resolves_active_recipe(): void
    {
        $grade = Grade::where('code', 'F101')->first();
        $formula = FormulaService::getActiveFormula($grade->id);
        
        $this->assertNotNull($formula);
        $this->assertTrue($formula->is_active);
        $this->assertGreaterThan(0, $formula->items->count());
    }

    public function test_stock_service_handles_adjustments_and_movement(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first()->fresh();
        $rawMaterial = RawMaterial::first();
        $initialStock = (float) $rawMaterial->current_stock;

        $this->actingAs($admin);

        // Perform stock adjustment
        $adjustment = StockService::adjustStock($rawMaterial->id, 50.0000, 'Adding cycle-count surplus');

        $this->assertInstanceOf(StockAdjustment::class, $adjustment);
        $rawMaterial->refresh();
        $this->assertEquals($initialStock + 50.0000, (float) $rawMaterial->current_stock);

        // Verify ledger entries
        $this->assertDatabaseHas('stock_ledgers', [
            'raw_material_id' => $rawMaterial->id,
            'quantity' => 50.0000,
            'transaction_type' => 'ADJUSTMENT',
        ]);

        // Verify Activity Logs
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'STOCK_ADJUSTMENT',
        ]);
    }

    public function test_stock_adjustment_without_remarks_is_allowed(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first()->fresh();
        $rawMaterial = RawMaterial::first();

        $this->actingAs($admin);

        // Perform stock adjustment without remarks
        $adjustment = StockService::adjustStock($rawMaterial->id, 10.0000);

        $this->assertInstanceOf(StockAdjustment::class, $adjustment);
        $this->assertEquals('', $adjustment->remarks);

        // HTTP POST store request without remarks
        $response = $this->post(route('admin.stock-adjustments.store'), [
            'material_type' => 'raw',
            'raw_material_id' => $rawMaterial->id,
            'quantity' => 5.0,
        ]);

        $response->assertRedirect(route('admin.stock-adjustments.index'));
        $response->assertSessionHas('success');
    }

    public function test_production_service_manages_batch_lifecycle(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first()->fresh();
        $machine = Machine::where('is_active', true)->where('department_id', $supervisor->department_id)->first();
        $grade = Grade::where('code', 'F101')->first();

        $this->actingAs($supervisor);

        // 1. Start batch
        $batch = ProductionService::startBatch($machine->id, $grade->id, 'Start test batch');
        
        $this->assertInstanceOf(ProductionBatch::class, $batch);
        $this->assertEquals('running', $batch->status);
        $this->assertMatchesRegularExpression('/^ADH-\d{8}-\d{4}$/', $batch->batch_no);

        // Verify machine availability block
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        ProductionService::startBatch($machine->id, $grade->id, 'Should collision fail');
    }

    public function test_production_service_completes_batch(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first()->fresh();
        $machine = Machine::where('is_active', true)->where('department_id', $supervisor->department_id)->first();
        $grade = Grade::where('code', 'F101')->first();

        $this->actingAs($supervisor);

        $batch = ProductionService::startBatch($machine->id, $grade->id, 'Start test batch');

        // 2. Complete batch
        $completedBatch = ProductionService::completeBatch($batch->id, 40); // 40 bags * 20kg = 800kg

        $this->assertEquals('completed', $completedBatch->status);
        $this->assertEquals(40.0, $completedBatch->output_bags);
        $this->assertEquals(800.0, $completedBatch->output_kg);

        // Verify stock ledger OUT records exist
        $this->assertDatabaseHas('stock_ledgers', [
            'batch_id' => $batch->id,
            'transaction_type' => 'OUT',
        ]);

        // Verify Activity Logs
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'BATCH_COMPLETED',
        ]);
    }

    public function test_report_service_computes_daily_summaries(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first()->fresh();
        $machine = Machine::where('is_active', true)->where('department_id', $supervisor->department_id)->first();
        $grade = Grade::where('code', 'F101')->first();

        $this->actingAs($supervisor);

        $batch = ProductionService::startBatch($machine->id, $grade->id);
        ProductionService::completeBatch($batch->id, 50);

        // Fetch daily report summaries
        $data = \App\Services\DailyReportService::getDailyReportData(now()->toDateString());

        $this->assertNotNull($data);
        $this->assertEquals(50.0, (float) $data['grandTotal']->total_bags);
        $this->assertNotEmpty($data['machineSummary']);
        $this->assertNotEmpty($data['materialSummary']);
        $this->assertNotEmpty($data['supervisorSummary']);
        $this->assertNotEmpty($data['completedBatches']);
        $this->assertEquals(1, $data['grandTotal']->total_batches);
    }
}
