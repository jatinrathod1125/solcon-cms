<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\Machine;
use App\Models\RawMaterial;
use App\Models\ProductionBatch;
use App\Models\User;
use App\Models\Department;
use App\Models\EpoxyProduct;
use App\Models\EpoxyAssembly;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportsTest extends TestCase
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
        $response = $this->get('/production/reports/daily');
        $response->assertRedirect('/login');
    }

    public function test_supervisor_can_access_reports(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();

        $response = $this->actingAs($supervisor)->get('/production/reports/daily');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_daily_report_and_export_pdf(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();
        $machine = Machine::where('is_active', true)->where('department_id', $supervisor->department_id)->first();
        $grade = Grade::where('code', 'F101')->first();

        // Start and complete a batch today
        $this->actingAs($supervisor);
        $batch = \App\Services\ProductionService::startBatch($machine->id, $grade->id);

        // Complete the batch to deduct stock and log stock ledger entries
        $response = $this->actingAs($supervisor)->put("/production/{$batch->id}/complete", [
            'output_bags' => 50, // 50 bags * 20 KG = 1000 KG
        ]);
        $response->assertRedirect('/production');

        // Access Daily Report as Admin
        $todayStr = now()->toDateString();
        $response = $this->actingAs($admin)->get("/production/reports/daily?date={$todayStr}");
        $response->assertStatus(200);

        // Verify HTML displays the correct summaries
        $response->assertSee($batch->batch_no);
        $response->assertSee('50 Bags');
        $response->assertSee('1,000.00 KG');
        $response->assertSee($machine->name);

        // Verify PDF Export executes and downloads file
        $response = $this->actingAs($admin)->get("/production/reports/daily/pdf?date={$todayStr}");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_daily_report_includes_epoxy_component_preparations(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        
        // Seed some epoxy components and preparations
        $component = \App\Models\EpoxyComponent::firstOrCreate(['code' => 'EPX-HRD-100'], [
            'name' => '100gm Hardener Bottle',
            'category' => 'Bottle',
            'purpose' => 'Assembly Component',
            'unit_id' => 2, // PCS
            'is_active' => true
        ]);

        \App\Models\EpoxyComponentPreparation::create([
            'epoxy_component_id' => $component->id,
            'quantity' => 120,
            'operator_id' => $admin->id,
        ]);

        $todayStr = now()->toDateString();

        // 1. Web View
        $response = $this->actingAs($admin)->get("/production/reports/daily?date={$todayStr}&department_code=all");
        $response->assertStatus(200);
        $response->assertSee('100gm Hardener Bottle');
        $response->assertSee('120');

        // 2. PDF Download
        $response = $this->actingAs($admin)->get("/production/reports/daily/pdf?date={$todayStr}&department_code=all");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');

        // 3. WhatsApp Landscape PDF Download
        $response = $this->actingAs($admin)->get("/production/reports/daily/pdf/whatsapp?date={$todayStr}&department_code=all");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');

        // 4. Excel Download
        $response = $this->actingAs($admin)->get("/production/reports/daily/excel?date={$todayStr}&department_code=all");
        $response->assertStatus(200);
        
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
        $this->assertStringContainsString('100gm Hardener Bottle', $content);
        $this->assertStringContainsString('120', $content);
    }

    public function test_epoxy_supervisor_can_view_epoxy_assemblies_in_daily_report_without_sql_error(): void
    {
        $epxDept = Department::where('code', 'EPX')->first();
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();
        $supervisor->update(['department_id' => $epxDept->id]);
        $supervisor->departments()->sync([$epxDept->id]);

        $product = EpoxyProduct::firstOrCreate(
            ['code' => 'EPX-PROD-TEST'],
            ['name' => 'Epoxy Test Resin Kit', 'requires_color' => false, 'is_active' => true]
        );

        EpoxyAssembly::create([
            'epoxy_product_id' => $product->id,
            'color_id' => null,
            'formula_snapshot' => [],
            'quantity' => 45,
            'operator_id' => $supervisor->id,
            'remarks' => 'Test Epoxy Report Assembly',
        ]);

        $todayStr = now()->toDateString();

        // Acting as Epoxy supervisor viewing daily report
        $response = $this->actingAs($supervisor)->get("/production/reports/daily?date={$todayStr}");
        $response->assertStatus(200);
        $response->assertSee('Epoxy Test Resin Kit');
        $response->assertSee('45');

        // Verify PDF download
        $pdfResponse = $this->actingAs($supervisor)->get("/production/reports/daily/pdf?date={$todayStr}");
        $pdfResponse->assertStatus(200);
        $pdfResponse->assertHeader('Content-Type', 'application/pdf');

        // Verify Excel download
        $excelResponse = $this->actingAs($supervisor)->get("/production/reports/daily/excel?date={$todayStr}");
        $excelResponse->assertStatus(200);

        ob_start();
        $excelResponse->sendContent();
        $excelContent = ob_get_clean();
        $this->assertStringContainsString('Epoxy Test Resin Kit', $excelContent);
        $this->assertStringContainsString('45', $excelContent);
    }
}

