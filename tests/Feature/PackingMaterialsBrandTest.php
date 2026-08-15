<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\PackingMaterial;
use App\Models\PackingMaterialCategory;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackingMaterialsBrandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_create_form_renders_dynamic_brands_dropdown_with_blank_default(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        $response = $this->actingAs($admin)->get(route('admin.packing-materials.create'));

        $response->assertStatus(200);
        $response->assertSee('<option value=""', false);
        $response->assertSee('Solcon');
        $response->assertSee('Fixora');
    }

    public function test_creation_with_blank_brand_saves_null_brand_id(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $cat = PackingMaterialCategory::firstOrCreate(['name' => 'Test Category']);
        $unit = Unit::firstOrCreate(['name' => 'Pieces', 'code' => 'PCS']);

        $response = $this->actingAs($admin)->post(route('admin.packing-materials.store'), [
            'brand_id' => '',
            'category_id' => $cat->id,
            'name' => 'Common Pouch 100g',
            'code' => 'PCH-CMN-100',
            'unit_id' => $unit->id,
            'minimum_stock' => 10,
            'opening_stock' => 100,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.packing-materials.index'));

        $material = PackingMaterial::where('code', 'PCH-CMN-100')->first();
        $this->assertNotNull($material);
        $this->assertNull($material->brand_id);
    }

    public function test_creation_with_specific_brand_saves_brand_id(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $cat = PackingMaterialCategory::firstOrCreate(['name' => 'Test Category']);
        $unit = Unit::firstOrCreate(['name' => 'Pieces', 'code' => 'PCS']);
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.packing-materials.store'), [
            'brand_id' => $fixora->id,
            'category_id' => $cat->id,
            'name' => 'Fixora Bag 25kg',
            'code' => 'BAG-FIX-25',
            'unit_id' => $unit->id,
            'minimum_stock' => 10,
            'opening_stock' => 50,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.packing-materials.index'));

        $material = PackingMaterial::where('code', 'BAG-FIX-25')->first();
        $this->assertNotNull($material);
        $this->assertEquals($fixora->id, $material->brand_id);
    }

    public function test_null_brand_id_displays_common_and_follows_scoping_rules(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $cat = PackingMaterialCategory::firstOrCreate(['name' => 'Test Category']);
        $unit = Unit::firstOrCreate(['name' => 'Pieces', 'code' => 'PCS']);
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();

        // Solcon specific packing material
        $solconMat = PackingMaterial::create([
            'brand_id' => $solcon->id,
            'category_id' => $cat->id,
            'name' => 'Solcon Special Box',
            'code' => 'BOX-SOL-01',
            'unit_id' => $unit->id,
            'minimum_stock' => 5,
            'opening_stock' => 20,
            'current_stock' => 20,
            'status' => 'active',
        ]);

        // Common packing material (brand_id = NULL)
        $commonMat = PackingMaterial::create([
            'brand_id' => null,
            'category_id' => $cat->id,
            'name' => 'Universal Tape',
            'code' => 'TPE-UNV-01',
            'unit_id' => $unit->id,
            'minimum_stock' => 5,
            'opening_stock' => 20,
            'current_stock' => 20,
            'status' => 'active',
        ]);

        // Solcon scope should include both
        $solconResults = PackingMaterial::forBrand($solcon->id)->pluck('code')->toArray();
        $this->assertContains('BOX-SOL-01', $solconResults);
        $this->assertContains('TPE-UNV-01', $solconResults);

        // Fixora scope should include Common but NOT Solcon specific material
        $fixoraResults = PackingMaterial::forBrand($fixora->id)->pluck('code')->toArray();
        $this->assertContains('TPE-UNV-01', $fixoraResults);
        $this->assertNotContains('BOX-SOL-01', $fixoraResults);
    }

    public function test_future_brand_is_automatically_supported_without_code_changes(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $cat = PackingMaterialCategory::firstOrCreate(['name' => 'Test Category']);
        $unit = Unit::firstOrCreate(['name' => 'Pieces', 'code' => 'PCS']);

        $nova = Brand::create([
            'name' => 'Nova',
            'code' => 'NOV',
            'slug' => 'nova',
            'is_active' => true,
        ]);

        // 1. Create form dynamically lists Nova
        $createResponse = $this->actingAs($admin)->get(route('admin.packing-materials.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Nova');

        // 2. Create packing material under Nova
        $novaMat = PackingMaterial::create([
            'brand_id' => $nova->id,
            'category_id' => $cat->id,
            'name' => 'Nova Foil Pack',
            'code' => 'PAK-NOV-01',
            'unit_id' => $unit->id,
            'minimum_stock' => 5,
            'opening_stock' => 10,
            'current_stock' => 10,
            'status' => 'active',
        ]);

        // 3. Index view with Nova brand context shows Nova material
        $indexResponse = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $nova->id])
            ->get(route('admin.packing-materials.index', ['search' => 'Nova']));

        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Nova Foil Pack');
        $indexResponse->assertSee('Nova');
    }
}
