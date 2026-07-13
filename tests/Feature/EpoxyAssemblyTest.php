<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Department;
use App\Models\Color;
use App\Models\RawMaterial;
use App\Models\Unit;
use App\Models\EpoxyProduct;
use App\Models\EpoxyFormula;
use App\Models\EpoxyFormulaItem;
use App\Models\EpoxyAssembly;
use App\Services\DailyReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EpoxyAssemblyTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $supervisorUser;
    protected $otherSupervisorUser;
    protected $deptEPX;
    protected $deptTAD;
    protected $unitPCS;
    protected $product1K;
    protected $productKit;
    protected $colorBlack;
    protected $colorWhite;
    protected $rmHardener;
    protected $rmResin;
    protected $rmFillerGeneric;
    protected $rmFillerBlack;
    protected $rmFillerWhite;
    protected $rmBucket;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->seed(\Database\Seeders\EpoxyModuleSeeder::class);

        $this->adminUser = User::where('email', 'admin@solcon.com')->first();
        $this->supervisorUser = User::where('email', 'supervisor@solcon.com')->first();
        
        $this->deptEPX = Department::where('code', 'EPX')->first();
        $this->deptTAD = Department::where('code', 'TAD')->first();

        // Assign supervisor user to Epoxy Dept
        $this->supervisorUser->update(['department_id' => $this->deptEPX->id]);
        $this->supervisorUser->departments()->sync([$this->deptEPX->id]);

        // Create another supervisor belonging to TAD
        $this->otherSupervisorUser = User::factory()->create([
            'email' => 'other_supervisor@solcon.com',
            'is_active' => true,
            'department_id' => $this->deptTAD->id,
        ]);
        $this->otherSupervisorUser->departments()->sync([$this->deptTAD->id]);

        $this->unitPCS = Unit::where('code', 'PCS')->first();

        // Create Colors (Grout Color Master reuse)
        $this->colorBlack = Color::create([
            'department_id' => $this->deptEPX->id,
            'name' => 'Black Grout',
            'code' => 'GR-BLK',
            'packing_size' => '1 KG',
            'default_cement' => 'Grey Cement',
            'is_active' => true,
            'created_by' => $this->adminUser->id,
        ]);

        $this->colorWhite = Color::create([
            'department_id' => $this->deptEPX->id,
            'name' => 'White Grout',
            'code' => 'GR-WHT',
            'packing_size' => '1 KG',
            'default_cement' => 'White Cement',
            'is_active' => true,
            'created_by' => $this->adminUser->id,
        ]);

        // Fetch Raw Materials created by EpoxyModuleSeeder
        $this->rmHardener = RawMaterial::where('code', 'EPX-HRD-100')->first();
        $this->rmResin = RawMaterial::where('code', 'EPX-RSN-200')->first();
        $this->rmFillerGeneric = RawMaterial::where('code', 'EPX-FIL-700')->first();
        $this->rmFillerBlack = RawMaterial::where('code', 'EPX-FIL-700-BLK')->first();
        $this->rmFillerWhite = RawMaterial::where('code', 'EPX-FIL-700-WHT')->first();
        $this->rmBucket = RawMaterial::where('code', 'EPX-BKT-1K')->first();

        // Configure stock levels for testing
        $this->rmHardener->update(['opening_stock' => 100, 'current_stock' => 100]);
        $this->rmResin->update(['opening_stock' => 100, 'current_stock' => 100]);
        $this->rmFillerGeneric->update(['opening_stock' => 100, 'current_stock' => 100]);
        $this->rmFillerBlack->update(['name' => '700gm Black Filler', 'opening_stock' => 10, 'current_stock' => 10]);
        $this->rmFillerWhite->update(['opening_stock' => 1, 'current_stock' => 1]);
        $this->rmBucket->update(['opening_stock' => 100, 'current_stock' => 100]);

        // Fetch Products created by EpoxyModuleSeeder
        $this->product1K = EpoxyProduct::where('code', 'EPX-PRD-BKT-1K')->first();
        $this->productKit = EpoxyProduct::where('code', 'EPX-PRD-KIT-1K')->first();
    }

    /**
     * Test authorization boundaries on Epoxy Floor.
     */
    public function test_supervisor_access_boundaries_on_epoxy_floor()
    {
        // 1. Authorized Epoxy supervisor
        $response = $this->actingAs($this->supervisorUser)
            ->withSession(['current_department_id_' . $this->supervisorUser->id => $this->deptEPX->id])
            ->get(route('epoxy.index'));
        $response->assertStatus(200);

        // 2. Unauthorized TAD supervisor
        $response = $this->actingAs($this->otherSupervisorUser)
            ->withSession(['current_department_id_' . $this->otherSupervisorUser->id => $this->deptTAD->id])
            ->get(route('epoxy.index'));
        $response->assertStatus(403);

        // 3. Admin user always allowed
        $response = $this->actingAs($this->adminUser)
            ->get(route('epoxy.index'));
        $response->assertStatus(200);
    }

    /**
     * Test AJAX dynamic formula scaling preview.
     */
    public function test_ajax_formula_scaling_preview()
    {
        $response = $this->actingAs($this->supervisorUser)
            ->get(route('epoxy.formula-preview', [
                'product' => $this->product1K->id,
                'quantity' => 5,
                'color_id' => $this->colorBlack->id
            ]));

        $response->assertStatus(200)
            ->assertJsonPath('product_name', '1 KG Bucket')
            ->assertJsonPath('quantity', 5)
            ->assertJsonPath('color_name', 'Black Grout');

        // Black filler in stock is 10, needed is 5 * 1 = 5. Status: Available.
        // Hardener in stock is 100, needed is 5. Status: Available.
        $response->assertJsonFragment([
            'name' => '700gm Black Filler',
            'code' => 'EPX-FIL-700-BLK',
            'quantity' => 5,
            'status' => 'Available',
        ]);
    }

    /**
     * Test successful manual assembly of a product with dynamic color resolution.
     */
    public function test_successful_manual_assembly_and_stock_deduction()
    {
        $initialHardenerStock = $this->rmHardener->current_stock;
        $initialBlackFillerStock = $this->rmFillerBlack->current_stock;

        $response = $this->actingAs($this->supervisorUser)
            ->post(route('epoxy.store'), [
                'epoxy_product_id' => $this->product1K->id,
                'quantity' => 3,
                'color_id' => $this->colorBlack->id,
                'remarks' => 'Batch 3 runs Black Grout assembled.',
            ]);

        $response->assertRedirect(route('epoxy.index'));
        $response->assertSessionHas('success');

        // Check DB records
        $assembly = EpoxyAssembly::first();
        $this->assertNotNull($assembly);
        $this->assertEquals($this->product1K->id, $assembly->epoxy_product_id);
        $this->assertEquals($this->colorBlack->id, $assembly->color_id);
        $this->assertEquals(3, $assembly->quantity);
        $this->assertEquals($this->supervisorUser->id, $assembly->operator_id);

        // Verify stock decreased
        $this->assertEquals($initialHardenerStock - 3, $this->rmHardener->fresh()->current_stock);
        $this->assertEquals($initialBlackFillerStock - 3, $this->rmFillerBlack->fresh()->current_stock);

        // Verify stock ledger entries
        $ledgers = $assembly->ledgers;
        $this->assertCount(7, $ledgers);
        foreach ($ledgers as $ledger) {
            $this->assertEquals('OUT', $ledger->transaction_type);
            $this->assertEquals(-3, (float)$ledger->quantity);
            $this->assertEquals($assembly->id, $ledger->epoxy_assembly_id);
        }

        // Verify Activity Log
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'EPOXY_ASSEMBLED',
            'user_id' => $this->supervisorUser->id,
        ]);
    }

    /**
     * Test validation checks during manual assembly when raw materials are out of stock.
     */
    public function test_assembly_failed_due_to_insufficient_stock()
    {
        // White filler in stock is only 1. We try to assemble 2.
        $response = $this->actingAs($this->supervisorUser)
            ->from(route('epoxy.create'))
            ->post(route('epoxy.store'), [
                'epoxy_product_id' => $this->product1K->id,
                'quantity' => 2,
                'color_id' => $this->colorWhite->id,
            ]);

        $response->assertRedirect(route('epoxy.create'));
        $response->assertSessionHasErrors(['quantity']);

        // Stock should remain unchanged
        $this->assertEquals(1, $this->rmFillerWhite->fresh()->current_stock);
        $this->assertEquals(0, EpoxyAssembly::count());
    }

    /**
     * Test daily reports integration for Epoxy manual assemblies.
     */
    public function test_daily_report_integration_for_epoxy()
    {
        // Assemble 3 Black Grout Kits
        EpoxyAssembly::create([
            'epoxy_product_id' => $this->product1K->id,
            'color_id' => $this->colorBlack->id,
            'quantity' => 3,
            'formula_snapshot' => [],
            'operator_id' => $this->supervisorUser->id,
        ]);

        $date = now()->format('Y-m-d');
        $reportData = DailyReportService::getDailyReportData($date);

        $this->assertTrue($reportData['showEpoxy']);
        $this->assertEquals(1, $reportData['epoxyGrandTotal']->total_assemblies);
        $this->assertEquals(3, $reportData['epoxyGrandTotal']->total_kits);
        $this->assertCount(1, $reportData['epoxyProductSummary']);
        $this->assertEquals(3, $reportData['epoxyProductSummary']->first()->total_kits);
    }
}
