<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Department;
use App\Models\EpoxyAssembly;
use App\Models\EpoxyComponent;
use App\Models\EpoxyComponentFormula;
use App\Models\EpoxyFillerColor;
use App\Models\EpoxyFormula;
use App\Models\FinishedGood;
use App\Models\MarketingOrder;
use App\Models\RawMaterial;
use App\Models\Unit;
use App\Models\User;
use App\Services\BrandContextService;
use App\Services\FinishedGoodsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EpoxyBrandIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_create_epoxy_products_components_and_colors_with_brand_scoping(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();

        // 1. Create Solcon Epoxy Product
        $resProduct1 = $this->actingAs($admin)->post(route('admin.epoxy-products.store'), [
            'brand_id' => $solcon->id,
            'name' => 'Solcon Epoxy Kit 1KG',
            'code' => 'SOL-EPX-1K',
            'requires_color' => 1,
            'is_active' => 1,
        ]);
        $resProduct1->assertRedirect(route('admin.epoxy-products.index'));

        // 2. Create Fixora Epoxy Product
        $resProduct2 = $this->actingAs($admin)->post(route('admin.epoxy-products.store'), [
            'brand_id' => $fixora->id,
            'name' => 'Fixora Epoxy Kit 1KG',
            'code' => 'FIX-EPX-1K',
            'requires_color' => 1,
            'is_active' => 1,
        ]);
        $resProduct2->assertRedirect(route('admin.epoxy-products.index'));

        // 3. Create Solcon Epoxy Filler Color
        $resColor1 = $this->actingAs($admin)->post(route('admin.epoxy-colors.store'), [
            'brand_id' => $solcon->id,
            'name' => 'Jet Black',
            'code' => 'EPX-BLK-SOL',
            'is_active' => 1,
        ]);
        $resColor1->assertRedirect(route('admin.epoxy-colors.index'));

        // 4. Create Fixora Epoxy Filler Color
        $resColor2 = $this->actingAs($admin)->post(route('admin.epoxy-colors.store'), [
            'brand_id' => $fixora->id,
            'name' => 'Jet Black',
            'code' => 'EPX-BLK-FIX',
            'is_active' => 1,
        ]);
        $resColor2->assertRedirect(route('admin.epoxy-colors.index'));

        // 5. Create Solcon Epoxy Component
        $unitKg = Unit::where('code', 'KG')->first();
        $resComp1 = $this->actingAs($admin)->post(route('admin.epoxy-components.store'), [
            'brand_id' => $solcon->id,
            'name' => 'Solcon Resin Part A',
            'code' => 'SOL-EPX-PART-A',
            'category' => 'Bottle',
            'purpose' => 'Assembly Component',
            'unit_id' => $unitKg->id,
            'is_active' => 1,
        ]);
        $resComp1->assertRedirect(route('admin.epoxy-components.index'));

        // 6. Create Fixora Epoxy Component
        $resComp2 = $this->actingAs($admin)->post(route('admin.epoxy-components.store'), [
            'brand_id' => $fixora->id,
            'name' => 'Fixora Resin Part A',
            'code' => 'FIX-EPX-PART-A',
            'category' => 'Bottle',
            'purpose' => 'Assembly Component',
            'unit_id' => $unitKg->id,
            'is_active' => 1,
        ]);
        $resComp2->assertRedirect(route('admin.epoxy-components.index'));

        // Verify product, component, and color scoping
        $solconProducts = \App\Models\EpoxyProduct::forBrand($solcon->id)->pluck('code')->toArray();
        $this->assertContains('SOL-EPX-1K', $solconProducts);
        $this->assertNotContains('FIX-EPX-1K', $solconProducts);

        $fixoraProducts = \App\Models\EpoxyProduct::forBrand($fixora->id)->pluck('code')->toArray();
        $this->assertContains('FIX-EPX-1K', $fixoraProducts);
        $this->assertNotContains('SOL-EPX-1K', $fixoraProducts);

        $solconColors = EpoxyFillerColor::forBrand($solcon->id)->pluck('code')->toArray();
        $this->assertContains('EPX-BLK-SOL', $solconColors);
        $this->assertNotContains('EPX-BLK-FIX', $solconColors);

        $fixoraColors = EpoxyFillerColor::forBrand($fixora->id)->pluck('code')->toArray();
        $this->assertContains('EPX-BLK-FIX', $fixoraColors);
        $this->assertNotContains('EPX-BLK-SOL', $fixoraColors);

        $solconComponents = EpoxyComponent::forBrand($solcon->id)->pluck('code')->toArray();
        $this->assertContains('SOL-EPX-PART-A', $solconComponents);
        $this->assertNotContains('FIX-EPX-PART-A', $solconComponents);
    }

    public function test_epoxy_assembly_component_creates_raw_material_with_same_brand(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();
        $unitKg = Unit::where('code', 'KG')->first();

        // Create component for Fixora brand with Assembly Component purpose
        $this->actingAs($admin)->post(route('admin.epoxy-components.store'), [
            'brand_id' => $fixora->id,
            'name' => 'Fixora Hardener 200g',
            'code' => 'FIX-HRD-200',
            'category' => 'Bottle',
            'purpose' => 'Assembly Component',
            'unit_id' => $unitKg->id,
            'is_active' => 1,
        ]);

        $component = EpoxyComponent::where('code', 'FIX-HRD-200')->first();
        $this->assertNotNull($component);
        $this->assertEquals($fixora->id, $component->brand_id);
        $this->assertNotNull($component->raw_material_id);

        $rm = RawMaterial::find($component->raw_material_id);
        $this->assertNotNull($rm);
        $this->assertEquals($fixora->id, $rm->brand_id);
        $this->assertEquals('EPX', $rm->department->code);
    }

    public function test_epoxy_product_component_formula_and_assembly_brand_scoping(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();

        $solconProduct = \App\Models\EpoxyProduct::create([
            'brand_id' => $solcon->id,
            'name' => 'Solcon Tile Epoxy 5KG',
            'code' => 'SOL-EPX-5K',
            'requires_color' => false,
            'is_active' => true,
        ]);

        $fixoraProduct = \App\Models\EpoxyProduct::create([
            'brand_id' => $fixora->id,
            'name' => 'Fixora Tile Epoxy 5KG',
            'code' => 'FIX-EPX-5K',
            'requires_color' => false,
            'is_active' => true,
        ]);

        $solconFormula = EpoxyFormula::create([
            'epoxy_product_id' => $solconProduct->id,
            'version' => 1,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $fixoraFormula = EpoxyFormula::create([
            'epoxy_product_id' => $fixoraProduct->id,
            'version' => 1,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $this->assertEquals($solcon->id, $solconFormula->brand->id);
        $this->assertEquals($fixora->id, $fixoraFormula->brand->id);

        $solconFormulas = EpoxyFormula::forBrand($solcon->id)->pluck('id')->toArray();
        $this->assertContains($solconFormula->id, $solconFormulas);
        $this->assertNotContains($fixoraFormula->id, $solconFormulas);

        // EpoxyAssembly
        $solconAssembly = EpoxyAssembly::create([
            'epoxy_product_id' => $solconProduct->id,
            'quantity' => 10,
            'formula_snapshot' => [],
            'operator_id' => $admin->id,
            'created_by' => $admin->id,
        ]);

        $fixoraAssembly = EpoxyAssembly::create([
            'epoxy_product_id' => $fixoraProduct->id,
            'quantity' => 20,
            'formula_snapshot' => [],
            'operator_id' => $admin->id,
            'created_by' => $admin->id,
        ]);

        $this->assertEquals($solcon->id, $solconAssembly->brand->id);
        $this->assertEquals($fixora->id, $fixoraAssembly->brand->id);

        $solconAssemblies = EpoxyAssembly::forBrand($solcon->id)->pluck('id')->toArray();
        $this->assertContains($solconAssembly->id, $solconAssemblies);
        $this->assertNotContains($fixoraAssembly->id, $solconAssemblies);
    }

    public function test_epoxy_finished_goods_inventory_is_segregated_by_brand(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();

        $solconProduct = \App\Models\EpoxyProduct::create([
            'brand_id' => $solcon->id,
            'name' => 'Solcon Epoxy 1KG Bucket',
            'code' => 'SOL-EPX-BKT-1K',
            'requires_color' => false,
            'is_active' => true,
        ]);

        $fixoraProduct = \App\Models\EpoxyProduct::create([
            'brand_id' => $fixora->id,
            'name' => 'Fixora Epoxy 1KG Bucket',
            'code' => 'FIX-EPX-BKT-1K',
            'requires_color' => false,
            'is_active' => true,
        ]);

        // Increment finished good stock for Solcon
        app(FinishedGoodsService::class)->incrementEpoxyStock(
            $solconProduct->id,
            null,
            '1 KG',
            40,
            null
        );

        // Increment finished good stock for Fixora
        app(FinishedGoodsService::class)->incrementEpoxyStock(
            $fixoraProduct->id,
            null,
            '1 KG',
            25,
            null
        );

        $solconFG = FinishedGood::where('epoxy_product_id', $solconProduct->id)->first();
        $fixoraFG = FinishedGood::where('epoxy_product_id', $fixoraProduct->id)->first();

        $this->assertNotNull($solconFG);
        $this->assertNotNull($fixoraFG);
        $this->assertNotEquals($solconFG->id, $fixoraFG->id);
        $this->assertEquals(40, $solconFG->available_bags);
        $this->assertEquals(25, $fixoraFG->available_bags);
        $this->assertEquals($solcon->id, $solconFG->brand->id);
        $this->assertEquals($fixora->id, $fixoraFG->brand->id);

        // When viewing finished goods for Solcon
        $solconFGs = FinishedGood::forBrand($solcon->id)->whereNotNull('epoxy_product_id')->pluck('id')->toArray();
        $this->assertContains($solconFG->id, $solconFGs);
        $this->assertNotContains($fixoraFG->id, $solconFGs);

        // When viewing finished goods for Fixora
        $fixoraFGs = FinishedGood::forBrand($fixora->id)->whereNotNull('epoxy_product_id')->pluck('id')->toArray();
        $this->assertContains($fixoraFG->id, $fixoraFGs);
        $this->assertNotContains($solconFG->id, $fixoraFGs);
    }

    public function test_marketing_orders_epoxy_products_filtered_by_active_brand(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();

        $solconProduct = \App\Models\EpoxyProduct::create([
            'brand_id' => $solcon->id,
            'name' => 'Solcon Epoxy Master',
            'code' => 'SOL-EPX-MKT',
            'requires_color' => false,
            'is_active' => true,
        ]);

        $fixoraProduct = \App\Models\EpoxyProduct::create([
            'brand_id' => $fixora->id,
            'name' => 'Fixora Epoxy Master',
            'code' => 'FIX-EPX-MKT',
            'requires_color' => false,
            'is_active' => true,
        ]);

        // Solcon Order with Epoxy Product
        $solconOrder = MarketingOrder::create([
            'order_number' => 'MKT-EPX-SOL-01',
            'party_name' => 'Solcon Epoxy Dealer',
            'city' => 'Rajkot',
            'order_date' => now(),
            'priority' => 'medium',
            'status' => 'pending',
            'created_by' => $admin->id,
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);
        $solconOrder->items()->create([
            'department_code' => 'EPX',
            'epoxy_product_id' => $solconProduct->id,
            'quantity_bags' => 15,
            'packing' => '1KG',
        ]);

        // Fixora Order with Epoxy Product
        $fixoraOrder = MarketingOrder::create([
            'order_number' => 'MKT-EPX-FIX-01',
            'party_name' => 'Fixora Epoxy Dealer',
            'city' => 'Vadodara',
            'order_date' => now(),
            'priority' => 'high',
            'status' => 'pending',
            'created_by' => $admin->id,
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);
        $fixoraOrder->items()->create([
            'department_code' => 'EPX',
            'epoxy_product_id' => $fixoraProduct->id,
            'quantity_bags' => 30,
            'packing' => '1KG',
        ]);

        // Switch to Solcon
        app(BrandContextService::class)->switch($solcon);

        $solconMktRes = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $solcon->id])
            ->get(route('marketing.orders.index'));
        $solconMktRes->assertOk();
        $solconMktRes->assertSee('MKT-EPX-SOL-01');
        $solconMktRes->assertSee('Solcon Epoxy Dealer');
        $solconMktRes->assertDontSee('MKT-EPX-FIX-01');
        $solconMktRes->assertDontSee('Fixora Epoxy Dealer');

        // Switch to Fixora
        app(BrandContextService::class)->switch($fixora);

        $fixoraMktRes = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $fixora->id])
            ->get(route('marketing.orders.index'));
        $fixoraMktRes->assertOk();
        $fixoraMktRes->assertSee('MKT-EPX-FIX-01');
        $fixoraMktRes->assertSee('Fixora Epoxy Dealer');
        $fixoraMktRes->assertDontSee('MKT-EPX-SOL-01');
        $fixoraMktRes->assertDontSee('Solcon Epoxy Dealer');
    }

    public function test_epoxy_component_formula_create_displays_common_and_brand_raw_and_packing_materials(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();
        $deptEPX = Department::where('code', 'EPX')->firstOrFail();
        $unitKg = \App\Models\Unit::firstOrCreate(['name' => 'Kilogram', 'code' => 'KG']);
        $cat = \App\Models\PackingMaterialCategory::firstOrCreate(['name' => 'Bags', 'code' => 'BAG']);

        // 1. Create Common Raw Material & Packing Material (brand_id is NULL)
        $commonRm = RawMaterial::create([
            'brand_id' => null,
            'department_id' => $deptEPX->id,
            'name' => 'Universal Epoxy Hardening Resin',
            'code' => 'CMN-EPX-RES-01',
            'stock_unit_id' => $unitKg->id,
            'purchase_unit_id' => $unitKg->id,
            'is_active' => true,
        ]);

        $commonPm = \App\Models\PackingMaterial::create([
            'brand_id' => null,
            'category_id' => $cat->id,
            'unit_id' => $unitKg->id,
            'name' => 'Universal Outer Pouch Foil',
            'code' => 'CMN-PM-FOIL-01',
            'status' => 'active',
        ]);

        // 2. Create Solcon Raw Material & Packing Material (brand_id is Solcon)
        $solconRm = RawMaterial::create([
            'brand_id' => $solcon->id,
            'department_id' => $deptEPX->id,
            'name' => 'Solcon Special Epoxy Hardener',
            'code' => 'SOL-EPX-HRD-01',
            'stock_unit_id' => $unitKg->id,
            'purchase_unit_id' => $unitKg->id,
            'is_active' => true,
        ]);

        $solconPm = \App\Models\PackingMaterial::create([
            'brand_id' => $solcon->id,
            'category_id' => $cat->id,
            'unit_id' => $unitKg->id,
            'name' => 'Solcon 700gm Printed Pouch',
            'code' => 'SOL-PM-PCH-700',
            'status' => 'active',
        ]);

        // 3. Create Fixora Raw Material & Packing Material (brand_id is Fixora)
        $fixoraRm = RawMaterial::create([
            'brand_id' => $fixora->id,
            'department_id' => $deptEPX->id,
            'name' => 'Fixora Special Epoxy Hardener',
            'code' => 'FIX-EPX-HRD-01',
            'stock_unit_id' => $unitKg->id,
            'purchase_unit_id' => $unitKg->id,
            'is_active' => true,
        ]);

        $fixoraPm = \App\Models\PackingMaterial::create([
            'brand_id' => $fixora->id,
            'category_id' => $cat->id,
            'unit_id' => $unitKg->id,
            'name' => 'Fixora 700gm Printed Pouch',
            'code' => 'FIX-PM-PCH-700',
            'status' => 'active',
        ]);

        // Test with Solcon active brand
        app(BrandContextService::class)->switch($solcon);

        $solconResponse = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $solcon->id])
            ->get(route('admin.epoxy-component-formulas.create'));

        $solconResponse->assertOk();
        // Common RM & PM + Solcon RM & PM must be present
        $solconResponse->assertSee('Universal Epoxy Hardening Resin');
        $solconResponse->assertSee('Universal Outer Pouch Foil');
        $solconResponse->assertSee('Solcon Special Epoxy Hardener');
        $solconResponse->assertSee('Solcon 700gm Printed Pouch');
        // Fixora RM & PM must NOT be present
        $solconResponse->assertDontSee('Fixora Special Epoxy Hardener');
        $solconResponse->assertDontSee('Fixora 700gm Printed Pouch');

        // Test with Fixora active brand
        app(BrandContextService::class)->switch($fixora);

        $fixoraResponse = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $fixora->id])
            ->get(route('admin.epoxy-component-formulas.create'));

        $fixoraResponse->assertOk();
        // Common RM & PM + Fixora RM & PM must be present
        $fixoraResponse->assertSee('Universal Epoxy Hardening Resin');
        $fixoraResponse->assertSee('Universal Outer Pouch Foil');
        $fixoraResponse->assertSee('Fixora Special Epoxy Hardener');
        $fixoraResponse->assertSee('Fixora 700gm Printed Pouch');
        // Solcon RM & PM must NOT be present
        $fixoraResponse->assertDontSee('Solcon Special Epoxy Hardener');
        $fixoraResponse->assertDontSee('Solcon 700gm Printed Pouch');
    }
}
