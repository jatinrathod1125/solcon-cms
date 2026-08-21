<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Color;
use App\Models\Department;
use App\Models\FinishedGood;
use App\Models\GroutFormula;
use App\Models\GroutFormulaItem;
use App\Models\GroutProductionBatch;
use App\Models\Machine;
use App\Models\RawMaterial;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroutBrandIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_create_same_color_name_and_packing_size_for_different_brands(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $groutDept = Department::where('code', 'GRT')->firstOrFail();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();

        // 1. Create Solcon Ivory 1 KG
        $response1 = $this->actingAs($admin)->post(route('admin.grout-colors.store'), [
            'brand_id' => $solcon->id,
            'department_id' => $groutDept->id,
            'name' => 'Ivory',
            'code' => 'GRT-IVO-SOL-1K',
            'packing_size' => '1 KG',
            'default_cement' => 'White Cement',
            'is_active' => 1,
        ]);
        $response1->assertRedirect(route('admin.grout-colors.index'));

        // 2. Create Fixora Ivory 1 KG (Same Name & Same Size)
        $response2 = $this->actingAs($admin)->post(route('admin.grout-colors.store'), [
            'brand_id' => $fixora->id,
            'department_id' => $groutDept->id,
            'name' => 'Ivory',
            'code' => 'GRT-IVO-FIX-1K',
            'packing_size' => '1 KG',
            'default_cement' => 'White Cement',
            'is_active' => 1,
        ]);
        $response2->assertRedirect(route('admin.grout-colors.index'));

        // Verify both colors exist with their respective brands
        $solconColor = Color::where('code', 'GRT-IVO-SOL-1K')->first();
        $fixoraColor = Color::where('code', 'GRT-IVO-FIX-1K')->first();

        $this->assertNotNull($solconColor);
        $this->assertNotNull($fixoraColor);
        $this->assertEquals('Ivory', $solconColor->name);
        $this->assertEquals('Ivory', $fixoraColor->name);
        $this->assertEquals('1 KG', $solconColor->packing_size);
        $this->assertEquals('1 KG', $fixoraColor->packing_size);
        $this->assertEquals($solcon->id, $solconColor->brand_id);
        $this->assertEquals($fixora->id, $fixoraColor->brand_id);
    }

    public function test_brand_scoping_on_colors_formulas_and_batches(): void
    {
        $groutDept = Department::where('code', 'GRT')->firstOrFail();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();
        $admin = User::where('email', 'admin@solcon.com')->first();
        $unit = Unit::firstOrCreate(['name' => 'Kilogram', 'code' => 'KG']);

        $solconColor = Color::create([
            'brand_id' => $solcon->id,
            'department_id' => $groutDept->id,
            'name' => 'Royal White',
            'code' => 'GRT-WHT-SOL',
            'packing_size' => '1 KG',
            'default_cement' => 'White Cement',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $fixoraColor = Color::create([
            'brand_id' => $fixora->id,
            'department_id' => $groutDept->id,
            'name' => 'Royal White',
            'code' => 'GRT-WHT-FIX',
            'packing_size' => '1 KG',
            'default_cement' => 'White Cement',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $commonColor = Color::create([
            'brand_id' => null,
            'department_id' => $groutDept->id,
            'name' => 'Universal Grey',
            'code' => 'GRT-GRY-CMN',
            'packing_size' => '1 KG',
            'default_cement' => 'Grey Cement',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        // Color Scoping
        $solconColors = Color::forBrand($solcon->id)->pluck('code')->toArray();
        $this->assertContains('GRT-WHT-SOL', $solconColors);
        $this->assertContains('GRT-GRY-CMN', $solconColors);
        $this->assertNotContains('GRT-WHT-FIX', $solconColors);

        $fixoraColors = Color::forBrand($fixora->id)->pluck('code')->toArray();
        $this->assertContains('GRT-WHT-FIX', $fixoraColors);
        $this->assertContains('GRT-GRY-CMN', $fixoraColors);
        $this->assertNotContains('GRT-WHT-SOL', $fixoraColors);

        // Formulas
        $solconFormula = GroutFormula::create([
            'color_id' => $solconColor->id,
            'version' => 1,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $fixoraFormula = GroutFormula::create([
            'color_id' => $fixoraColor->id,
            'version' => 1,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $this->assertEquals($solcon->id, $solconFormula->brand->id);
        $this->assertEquals($fixora->id, $fixoraFormula->brand->id);

        $solconFormulas = GroutFormula::forBrand($solcon->id)->pluck('id')->toArray();
        $this->assertContains($solconFormula->id, $solconFormulas);
        $this->assertNotContains($fixoraFormula->id, $solconFormulas);
    }

    public function test_finished_goods_inventory_is_segregated_by_brand_for_grout(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $groutDept = Department::where('code', 'GRT')->firstOrFail();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();

        $solconColor = Color::create([
            'brand_id' => $solcon->id,
            'department_id' => $groutDept->id,
            'name' => 'Jet Black',
            'code' => 'GRT-BLK-SOL',
            'packing_size' => '1 KG',
            'default_cement' => 'Grey Cement',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $fixoraColor = Color::create([
            'brand_id' => $fixora->id,
            'department_id' => $groutDept->id,
            'name' => 'Jet Black',
            'code' => 'GRT-BLK-FIX',
            'packing_size' => '1 KG',
            'default_cement' => 'Grey Cement',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        // Increment finished good stock for Solcon
        app(\App\Services\FinishedGoodsService::class)->incrementGroutStock(
            $solconColor->id,
            $solconColor->packing_size,
            50,
            50.0
        );

        // Increment finished good stock for Fixora
        app(\App\Services\FinishedGoodsService::class)->incrementGroutStock(
            $fixoraColor->id,
            $fixoraColor->packing_size,
            30,
            30.0
        );

        $solconFG = FinishedGood::where('color_id', $solconColor->id)->first();
        $fixoraFG = FinishedGood::where('color_id', $fixoraColor->id)->first();

        $this->assertNotNull($solconFG);
        $this->assertNotNull($fixoraFG);
        $this->assertNotEquals($solconFG->id, $fixoraFG->id);
        $this->assertEquals(50, $solconFG->available_bags);
        $this->assertEquals(30, $fixoraFG->available_bags);
        $this->assertEquals($solcon->id, $solconFG->brand->id);
        $this->assertEquals($fixora->id, $fixoraFG->brand->id);
    }

    public function test_order_create_only_shows_products_for_active_brand_context(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $groutDept = Department::where('code', 'GRT')->firstOrFail();
        $adhDept = Department::where('code', 'TAD')->firstOrFail();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();

        // 1. Create a Solcon Grade and a Fixora Grade
        $bag25 = \App\Models\BagSize::first();
        $unitKg = Unit::where('code', 'KG')->first();

        $solconGrade = \App\Models\Grade::create([
            'brand_id' => $solcon->id,
            'department_id' => $adhDept->id,
            'name' => 'Solcon Super Adhesive',
            'code' => 'SOL-ADH-01',
            'bag_size_id' => $bag25->id,
            'output_unit_id' => $unitKg->id,
            'is_active' => true,
        ]);

        $fixoraGrade = \App\Models\Grade::create([
            'brand_id' => $fixora->id,
            'department_id' => $adhDept->id,
            'name' => 'Fixora Ultimate Adhesive',
            'code' => 'FIX-ADH-01',
            'bag_size_id' => $bag25->id,
            'output_unit_id' => $unitKg->id,
            'is_active' => true,
        ]);

        // 2. Create a Solcon Color and a Fixora Color
        $solconColor = Color::create([
            'brand_id' => $solcon->id,
            'department_id' => $groutDept->id,
            'name' => 'Solcon Diamond White',
            'code' => 'SOL-GRT-01',
            'packing_size' => '1 KG',
            'default_cement' => 'White Cement',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $fixoraColor = Color::create([
            'brand_id' => $fixora->id,
            'department_id' => $groutDept->id,
            'name' => 'Fixora Diamond White',
            'code' => 'FIX-GRT-01',
            'packing_size' => '1 KG',
            'default_cement' => 'White Cement',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        // 3. Switch brand to Fixora in session
        app(\App\Services\BrandContextService::class)->switch($fixora);

        $response = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $fixora->id])
            ->get(route('marketing.orders.create'));

        $response->assertOk();
        $response->assertSee('Fixora Ultimate Adhesive');
        $response->assertSee('Fixora Diamond White');
        $response->assertDontSee('Solcon Super Adhesive');
        $response->assertDontSee('Solcon Diamond White');
    }

    public function test_marketing_and_supervisor_orders_lists_filtered_by_active_brand(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $groutDept = Department::where('code', 'GRT')->firstOrFail();
        $adhDept = Department::where('code', 'TAD')->firstOrFail();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();

        $bag25 = \App\Models\BagSize::first();
        $unitKg = Unit::where('code', 'KG')->first();

        $solconGrade = \App\Models\Grade::create([
            'brand_id' => $solcon->id,
            'department_id' => $adhDept->id,
            'name' => 'Solcon Super Adhesive',
            'code' => 'SOL-ADH-TEST',
            'bag_size_id' => $bag25->id,
            'output_unit_id' => $unitKg->id,
            'is_active' => true,
        ]);

        $fixoraGrade = \App\Models\Grade::create([
            'brand_id' => $fixora->id,
            'department_id' => $adhDept->id,
            'name' => 'Fixora Ultimate Adhesive',
            'code' => 'FIX-ADH-TEST',
            'bag_size_id' => $bag25->id,
            'output_unit_id' => $unitKg->id,
            'is_active' => true,
        ]);

        // Create a Solcon Marketing Order (Approved)
        $solconOrder = \App\Models\MarketingOrder::create([
            'order_number' => 'MKT-SOL-001',
            'party_name' => 'Solcon Customer A',
            'city' => 'Ahmedabad',
            'order_date' => now(),
            'priority' => 'medium',
            'status' => 'pending',
            'created_by' => $admin->id,
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);
        $solconOrder->items()->create([
            'department_code' => 'TAD',
            'grade_id' => $solconGrade->id,
            'quantity_bags' => 10,
            'packing' => '25KG',
        ]);

        // Create a Fixora Marketing Order (Approved)
        $fixoraOrder = \App\Models\MarketingOrder::create([
            'order_number' => 'MKT-FIX-001',
            'party_name' => 'Fixora Customer B',
            'city' => 'Surat',
            'order_date' => now(),
            'priority' => 'high',
            'status' => 'pending',
            'created_by' => $admin->id,
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);
        $fixoraOrder->items()->create([
            'department_code' => 'TAD',
            'grade_id' => $fixoraGrade->id,
            'quantity_bags' => 20,
            'packing' => '25KG',
        ]);

        // Test 1: When Active Brand is Solcon
        app(\App\Services\BrandContextService::class)->switch($solcon);

        $mktSolconRes = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $solcon->id])
            ->get(route('marketing.orders.index'));
        $mktSolconRes->assertOk();
        $mktSolconRes->assertSee('MKT-SOL-001');
        $mktSolconRes->assertSee('Solcon Customer A');
        $mktSolconRes->assertDontSee('MKT-FIX-001');
        $mktSolconRes->assertDontSee('Fixora Customer B');

        $supSolconRes = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $solcon->id])
            ->get(route('supervisor.orders'));
        $supSolconRes->assertOk();
        $supSolconRes->assertSee('MKT-SOL-001');
        $supSolconRes->assertSee('Solcon Customer A');
        $supSolconRes->assertDontSee('MKT-FIX-001');
        $supSolconRes->assertDontSee('Fixora Customer B');

        // Test 2: When Active Brand is Fixora
        app(\App\Services\BrandContextService::class)->switch($fixora);

        $mktFixoraRes = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $fixora->id])
            ->get(route('marketing.orders.index'));
        $mktFixoraRes->assertOk();
        $mktFixoraRes->assertSee('MKT-FIX-001');
        $mktFixoraRes->assertSee('Fixora Customer B');
        $mktFixoraRes->assertDontSee('MKT-SOL-001');
        $mktFixoraRes->assertDontSee('Solcon Customer A');

        $supFixoraRes = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $fixora->id])
            ->get(route('supervisor.orders'));
        $supFixoraRes->assertOk();
        $supFixoraRes->assertSee('MKT-FIX-001');
        $supFixoraRes->assertSee('Fixora Customer B');
        $supFixoraRes->assertDontSee('MKT-SOL-001');
        $supFixoraRes->assertDontSee('Solcon Customer A');
    }

    public function test_finished_goods_inventory_filtered_by_active_brand(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $groutDept = Department::where('code', 'GRT')->firstOrFail();
        $adhDept = Department::where('code', 'TAD')->firstOrFail();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();

        $bag25 = \App\Models\BagSize::first();
        $unitKg = Unit::where('code', 'KG')->first();

        $solconGrade = \App\Models\Grade::create([
            'brand_id' => $solcon->id,
            'department_id' => $adhDept->id,
            'name' => 'Solcon Gold Adhesive',
            'code' => 'SOL-GLD-01',
            'bag_size_id' => $bag25->id,
            'output_unit_id' => $unitKg->id,
            'is_active' => true,
        ]);

        $fixoraGrade = \App\Models\Grade::create([
            'brand_id' => $fixora->id,
            'department_id' => $adhDept->id,
            'name' => 'Fixora Platinum Adhesive',
            'code' => 'FIX-PLT-01',
            'bag_size_id' => $bag25->id,
            'output_unit_id' => $unitKg->id,
            'is_active' => true,
        ]);

        FinishedGood::create([
            'department_id' => $adhDept->id,
            'grade_id' => $solconGrade->id,
            'packing' => '20 KG',
            'available_bags' => 100,
            'available_weight' => 2000.0,
            'minimum_stock' => 20,
            'status' => 'active',
        ]);

        FinishedGood::create([
            'department_id' => $adhDept->id,
            'grade_id' => $fixoraGrade->id,
            'packing' => '20 KG',
            'available_bags' => 200,
            'available_weight' => 4000.0,
            'minimum_stock' => 20,
            'status' => 'active',
        ]);

        // When Active Brand is Solcon
        app(\App\Services\BrandContextService::class)->switch($solcon);

        $solconRes = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $solcon->id])
            ->get(route('finished-goods.index'));

        $solconRes->assertOk();
        $solconRes->assertSee('Solcon Gold Adhesive');
        $solconRes->assertDontSee('Fixora Platinum Adhesive');

        // When Active Brand is Fixora
        app(\App\Services\BrandContextService::class)->switch($fixora);

        $fixoraRes = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $fixora->id])
            ->get(route('finished-goods.index'));

        $fixoraRes->assertOk();
        $fixoraRes->assertSee('Fixora Platinum Adhesive');
        $fixoraRes->assertDontSee('Solcon Gold Adhesive');
    }
}
