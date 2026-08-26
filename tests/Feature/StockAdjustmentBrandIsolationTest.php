<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Department;
use App\Models\PackingMaterial;
use App\Models\PackingMaterialCategory;
use App\Models\RawMaterial;
use App\Models\StockAdjustment;
use App\Models\Unit;
use App\Models\User;
use App\Services\BrandContextService;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockAdjustmentBrandIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_stock_adjustments_index_filters_raw_and_packing_materials_by_active_brand(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->firstOrFail();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();
        $deptTAD = Department::where('code', 'TAD')->firstOrFail();
        $unitKg = Unit::firstOrCreate(['name' => 'Kilogram', 'code' => 'KG']);
        $cat = PackingMaterialCategory::firstOrCreate(['name' => 'Bags', 'code' => 'BAG']);

        // 1. Create Solcon Raw Material & Packing Material
        $solconRm = RawMaterial::create([
            'brand_id' => $solcon->id,
            'department_id' => $deptTAD->id,
            'name' => 'Solcon Polymer Powder',
            'code' => 'SOL-RM-POLY-01',
            'stock_unit_id' => $unitKg->id,
            'purchase_unit_id' => $unitKg->id,
            'is_active' => true,
        ]);

        $solconPm = PackingMaterial::create([
            'brand_id' => $solcon->id,
            'category_id' => $cat->id,
            'unit_id' => $unitKg->id,
            'name' => 'Solcon 20KG Printed Bag',
            'code' => 'SOL-PM-BAG-20K',
            'status' => 'active',
        ]);

        // 2. Create Fixora Raw Material & Packing Material
        $fixoraRm = RawMaterial::create([
            'brand_id' => $fixora->id,
            'department_id' => $deptTAD->id,
            'name' => 'Fixora Polymer Powder',
            'code' => 'FIX-RM-POLY-01',
            'stock_unit_id' => $unitKg->id,
            'purchase_unit_id' => $unitKg->id,
            'is_active' => true,
        ]);

        $fixoraPm = PackingMaterial::create([
            'brand_id' => $fixora->id,
            'category_id' => $cat->id,
            'unit_id' => $unitKg->id,
            'name' => 'Fixora 20KG Printed Bag',
            'code' => 'FIX-PM-BAG-20K',
            'status' => 'active',
        ]);

        // 3. Create Common Raw Material & Packing Material
        $commonRm = RawMaterial::create([
            'brand_id' => null,
            'department_id' => $deptTAD->id,
            'name' => 'Universal Dolomite Sand',
            'code' => 'CMN-RM-SAND-01',
            'stock_unit_id' => $unitKg->id,
            'purchase_unit_id' => $unitKg->id,
            'is_active' => true,
        ]);

        $commonPm = PackingMaterial::create([
            'brand_id' => null,
            'category_id' => $cat->id,
            'unit_id' => $unitKg->id,
            'name' => 'Plain White Inner Liner',
            'code' => 'CMN-PM-LINER-01',
            'status' => 'active',
        ]);

        // 4. Create Stock Adjustments
        $adjSolconRm = StockAdjustment::create([
            'raw_material_id' => $solconRm->id,
            'quantity' => 50,
            'remarks' => 'Solcon RM received',
            'created_by' => $admin->id,
        ]);

        $adjFixoraRm = StockAdjustment::create([
            'raw_material_id' => $fixoraRm->id,
            'quantity' => 30,
            'remarks' => 'Fixora RM received',
            'created_by' => $admin->id,
        ]);

        $adjSolconPm = StockAdjustment::create([
            'packing_material_id' => $solconPm->id,
            'quantity' => 100,
            'remarks' => 'Solcon PM received',
            'created_by' => $admin->id,
        ]);

        $adjFixoraPm = StockAdjustment::create([
            'packing_material_id' => $fixoraPm->id,
            'quantity' => 200,
            'remarks' => 'Fixora PM received',
            'created_by' => $admin->id,
        ]);

        // TEST CASE 1: When Active Brand in session is Solcon
        app(BrandContextService::class)->switch($solcon);

        $solconRes = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $solcon->id])
            ->get(route('admin.stock-adjustments.index'));

        $solconRes->assertOk();

        // Must see Solcon and Common items in modal and dropdowns
        $solconRes->assertSee('Solcon Polymer Powder');
        $solconRes->assertSee('Solcon 20KG Printed Bag');
        $solconRes->assertSee('Universal Dolomite Sand');
        $solconRes->assertSee('Plain White Inner Liner');

        // Must NOT see Fixora items
        $solconRes->assertDontSee('Fixora Polymer Powder');
        $solconRes->assertDontSee('Fixora 20KG Printed Bag');

        // Check adjustments table
        $solconRes->assertSee('Solcon RM received');
        $solconRes->assertSee('Solcon PM received');
        $solconRes->assertDontSee('Fixora RM received');
        $solconRes->assertDontSee('Fixora PM received');

        // TEST CASE 2: When Active Brand in session is Fixora
        app(BrandContextService::class)->switch($fixora);

        $fixoraRes = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $fixora->id])
            ->get(route('admin.stock-adjustments.index'));

        $fixoraRes->assertOk();

        // Must see Fixora and Common items in modal and dropdowns
        $fixoraRes->assertSee('Fixora Polymer Powder');
        $fixoraRes->assertSee('Fixora 20KG Printed Bag');
        $fixoraRes->assertSee('Universal Dolomite Sand');
        $fixoraRes->assertSee('Plain White Inner Liner');

        // Must NOT see Solcon items
        $fixoraRes->assertDontSee('Solcon Polymer Powder');
        $fixoraRes->assertDontSee('Solcon 20KG Printed Bag');

        // Check adjustments table
        $fixoraRes->assertSee('Fixora RM received');
        $fixoraRes->assertSee('Fixora PM received');
        $fixoraRes->assertDontSee('Solcon RM received');
        $fixoraRes->assertDontSee('Solcon PM received');
    }

    public function test_stock_adjustment_store_enforces_brand_boundary(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->firstOrFail();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();
        $deptTAD = Department::where('code', 'TAD')->firstOrFail();
        $unitKg = Unit::firstOrCreate(['name' => 'Kilogram', 'code' => 'KG']);

        $fixoraRm = RawMaterial::create([
            'brand_id' => $fixora->id,
            'department_id' => $deptTAD->id,
            'name' => 'Fixora Secret Compound',
            'code' => 'FIX-RM-SECRET',
            'stock_unit_id' => $unitKg->id,
            'purchase_unit_id' => $unitKg->id,
            'is_active' => true,
        ]);

        $solconRm = RawMaterial::create([
            'brand_id' => $solcon->id,
            'department_id' => $deptTAD->id,
            'name' => 'Solcon Standard Compound',
            'code' => 'SOL-RM-STD',
            'stock_unit_id' => $unitKg->id,
            'purchase_unit_id' => $unitKg->id,
            'is_active' => true,
        ]);

        // Switch to Solcon brand
        app(BrandContextService::class)->switch($solcon);

        // Attempting to adjust Fixora material while Solcon is active should fail with error
        $responseFail = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $solcon->id])
            ->post(route('admin.stock-adjustments.store'), [
                'material_type' => 'raw',
                'raw_material_id' => $fixoraRm->id,
                'quantity' => 10,
                'remarks' => 'Should fail due to brand mismatch',
            ]);

        $responseFail->assertSessionHas('error');

        // Adjusting Solcon material while Solcon is active should succeed
        $responseSuccess = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $solcon->id])
            ->post(route('admin.stock-adjustments.store'), [
                'material_type' => 'raw',
                'raw_material_id' => $solconRm->id,
                'quantity' => 25,
                'remarks' => 'Stock in 25kg Solcon RM',
            ]);

        $responseSuccess->assertRedirect(route('admin.stock-adjustments.index'));
        $responseSuccess->assertSessionHas('success');
    }
}
