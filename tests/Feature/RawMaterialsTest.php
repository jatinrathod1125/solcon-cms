<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Department;
use App\Models\RawMaterial;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RawMaterialsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles, departments, units, bag sizes, and default accounts
        $this->seed();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/raw-materials');
        $response->assertRedirect('/login');
    }

    public function test_supervisor_cannot_access_raw_materials(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();

        $response = $this->actingAs($supervisor)->get('/admin/raw-materials');
        $response->assertStatus(403);

        $response = $this->actingAs($supervisor)->post('/admin/raw-materials', [
            'name' => 'Cement Sand',
            'code' => 'CM-SND',
        ]);
        $response->assertStatus(403);
    }

    public function test_admin_can_perform_raw_material_crud(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();
        $dept = Department::where('code', 'TAD')->first();
        $unitKG = Unit::where('code', 'KG')->first();

        // 1. Create
        $response = $this->actingAs($admin)->post('/admin/raw-materials', [
            'name' => 'Hydrated Lime',
            'code' => 'H-LIME',
            'brand_id' => $fixora->id,
            'department_id' => $dept->id,
            'stock_unit_id' => $unitKG->id,
            'purchase_unit_id' => $unitKG->id,
            'purchase_conversion' => 1.0000,
            'opening_stock' => 150.5000,
            'minimum_stock' => 50.0000,
            'maximum_stock' => 1000.0000,
            'description' => 'Additive for flexibility',
            'is_active' => true,
        ]);

        $response->assertRedirect('/admin/raw-materials');
        $this->assertDatabaseHas('raw_materials', [
            'code' => 'H-LIME',
            'name' => 'Hydrated Lime',
            'brand_id' => $fixora->id,
            'opening_stock' => 150.5000,
            'current_stock' => 150.5000,
        ]);

        // 2. Read with Filters (using Fixora header brand context)
        $response = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $fixora->id])
            ->get('/admin/raw-materials?search=LIME&department_id='.$dept->id);
        $response->assertStatus(200);
        $response->assertSee('Hydrated Lime');
        $response->assertSee('H-LIME');
        $response->assertSee('Fixora');

        // 3. Update
        $material = RawMaterial::where('code', 'H-LIME')->first();
        $response = $this->actingAs($admin)->get("/admin/raw-materials/{$material->id}/edit");
        $response->assertStatus(200);
        $response->assertSee('value="'.$fixora->id.'" selected', false);

        $response = $this->actingAs($admin)->put("/admin/raw-materials/{$material->id}", [
            'name' => 'Hydrated Lime Premium',
            'code' => 'H-LIME-P',
            'brand_id' => $solcon->id,
            'department_id' => $dept->id,
            'stock_unit_id' => $unitKG->id,
            'purchase_unit_id' => $unitKG->id,
            'purchase_conversion' => 1.0000,
            'opening_stock' => 150.5000,
            'minimum_stock' => 50.0000,
            'maximum_stock' => 1000.0000,
            'description' => 'Premium additive',
            'is_active' => false,
        ]);

        $response->assertRedirect('/admin/raw-materials');
        $this->assertDatabaseHas('raw_materials', [
            'id' => $material->id,
            'code' => 'H-LIME-P',
            'name' => 'Hydrated Lime Premium',
            'brand_id' => $solcon->id,
            'current_stock' => 150.5000,
            'is_active' => false,
        ]);

        // 4. Delete
        $response = $this->actingAs($admin)->delete("/admin/raw-materials/{$material->id}");
        $response->assertRedirect('/admin/raw-materials');
        $this->assertDatabaseMissing('raw_materials', ['id' => $material->id]);
    }

    public function test_raw_material_validation_rules(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $dept = Department::where('code', 'TAD')->first();
        $unitKG = Unit::where('code', 'KG')->first();

        // Unique Code Violation
        $response = $this->actingAs($admin)->post('/admin/raw-materials', [
            'name' => 'Cement Copy',
            'code' => 'OPC-53',
            'brand_id' => $solcon->id,
            'department_id' => $dept->id,
            'stock_unit_id' => $unitKG->id,
            'purchase_unit_id' => $unitKG->id,
            'purchase_conversion' => 1.00,
            'opening_stock' => 10.00,
            'minimum_stock' => 5.00,
            'maximum_stock' => 100.00,
            'is_active' => true,
        ]);
        $response->assertSessionHasErrors('code');

        // Maximum Stock Less Than Minimum Stock
        $response = $this->actingAs($admin)->post('/admin/raw-materials', [
            'name' => 'Faulty Material',
            'code' => 'FAULTY',
            'brand_id' => $solcon->id,
            'department_id' => $dept->id,
            'stock_unit_id' => $unitKG->id,
            'purchase_unit_id' => $unitKG->id,
            'purchase_conversion' => 1.00,
            'opening_stock' => 10.00,
            'minimum_stock' => 50.00,
            'maximum_stock' => 20.00,
            'is_active' => true,
        ]);
        $response->assertSessionHasErrors('maximum_stock');
    }

    public function test_create_form_renders_brands_dropdown(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();

        $response = $this->actingAs($admin)->get('/admin/raw-materials/create');

        $response->assertStatus(200);
        $response->assertSee($solcon->name);
        $response->assertSee($fixora->name);
    }

    public function test_null_brand_id_displays_common_in_index_and_scoping_rules(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();
        $dept = Department::where('code', 'TAD')->first();
        $unitKG = Unit::where('code', 'KG')->first();

        // Create common material (brand_id = null)
        $commonMat = RawMaterial::create([
            'name' => 'Common Sand',
            'code' => 'CMN-SND',
            'brand_id' => null,
            'department_id' => $dept->id,
            'stock_unit_id' => $unitKG->id,
            'purchase_unit_id' => $unitKG->id,
            'purchase_conversion' => 1.0,
            'opening_stock' => 100,
            'current_stock' => 100,
            'minimum_stock' => 10,
            'maximum_stock' => 500,
            'is_active' => true,
        ]);

        // Create Solcon specific material
        $solconMat = RawMaterial::create([
            'name' => 'Solcon Special Sand',
            'code' => 'SOL-SND',
            'brand_id' => $solcon->id,
            'department_id' => $dept->id,
            'stock_unit_id' => $unitKG->id,
            'purchase_unit_id' => $unitKG->id,
            'purchase_conversion' => 1.0,
            'opening_stock' => 100,
            'current_stock' => 100,
            'minimum_stock' => 10,
            'maximum_stock' => 500,
            'is_active' => true,
        ]);

        // Index page renders Common Sand without brand suffix for null brand_id
        $response = $this->actingAs($admin)->get('/admin/raw-materials?search=CMN-SND');
        $response->assertStatus(200);
        $response->assertSee('Common Sand');
        $response->assertDontSee('Common Sand -');

        // Scoping for Solcon returns Solcon material + Common material
        $solconResults = RawMaterial::forBrand($solcon->id)->pluck('code')->toArray();
        $this->assertContains('CMN-SND', $solconResults);
        $this->assertContains('SOL-SND', $solconResults);

        // Scoping for Fixora returns Common material but NOT Solcon material
        $fixoraResults = RawMaterial::forBrand($fixora->id)->pluck('code')->toArray();
        $this->assertContains('CMN-SND', $fixoraResults);
        $this->assertNotContains('SOL-SND', $fixoraResults);
    }

    public function test_create_raw_material_with_blank_brand_saves_null(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $dept = Department::where('code', 'TAD')->first();
        $unitKG = Unit::where('code', 'KG')->first();

        $response = $this->actingAs($admin)->post('/admin/raw-materials', [
            'name' => 'Blank Brand Material',
            'code' => 'BLNK-MAT',
            'brand_id' => '',
            'department_id' => $dept->id,
            'stock_unit_id' => $unitKG->id,
            'purchase_unit_id' => $unitKG->id,
            'purchase_conversion' => 1.0,
            'opening_stock' => 50.0,
            'minimum_stock' => 10.0,
            'maximum_stock' => 200.0,
            'is_active' => true,
        ]);

        $response->assertRedirect('/admin/raw-materials');
        $this->assertDatabaseHas('raw_materials', [
            'code' => 'BLNK-MAT',
            'brand_id' => null,
        ]);
    }

    public function test_future_brand_is_automatically_supported_without_code_changes(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $solcon = Brand::where('code', Brand::CODE_SOLCON)->firstOrFail();
        $fixora = Brand::where('code', Brand::CODE_FIXORA)->firstOrFail();

        // 1. Create a third brand dynamically
        $nova = Brand::create([
            'name' => 'Nova',
            'code' => 'NOV',
            'slug' => 'nova',
            'is_active' => true,
        ]);

        $dept = Department::where('code', 'TAD')->first();
        $unitKG = Unit::where('code', 'KG')->first();

        // 2. Create raw materials for each context
        $commonMat = RawMaterial::create([
            'name' => 'Common Filler',
            'code' => 'CMN-FLR',
            'brand_id' => null,
            'department_id' => $dept->id,
            'stock_unit_id' => $unitKG->id,
            'purchase_unit_id' => $unitKG->id,
            'purchase_conversion' => 1.0,
            'opening_stock' => 10,
            'current_stock' => 10,
            'minimum_stock' => 1,
            'maximum_stock' => 100,
            'is_active' => true,
        ]);

        $solconMat = RawMaterial::create([
            'name' => 'Solcon Binder',
            'code' => 'SOL-BND',
            'brand_id' => $solcon->id,
            'department_id' => $dept->id,
            'stock_unit_id' => $unitKG->id,
            'purchase_unit_id' => $unitKG->id,
            'purchase_conversion' => 1.0,
            'opening_stock' => 10,
            'current_stock' => 10,
            'minimum_stock' => 1,
            'maximum_stock' => 100,
            'is_active' => true,
        ]);

        $fixoraMat = RawMaterial::create([
            'name' => 'Fixora Binder',
            'code' => 'FIX-BND',
            'brand_id' => $fixora->id,
            'department_id' => $dept->id,
            'stock_unit_id' => $unitKG->id,
            'purchase_unit_id' => $unitKG->id,
            'purchase_conversion' => 1.0,
            'opening_stock' => 10,
            'current_stock' => 10,
            'minimum_stock' => 1,
            'maximum_stock' => 100,
            'is_active' => true,
        ]);

        $novaMat = RawMaterial::create([
            'name' => 'Nova Binder',
            'code' => 'NOV-BND',
            'brand_id' => $nova->id,
            'department_id' => $dept->id,
            'stock_unit_id' => $unitKG->id,
            'purchase_unit_id' => $unitKG->id,
            'purchase_conversion' => 1.0,
            'opening_stock' => 10,
            'current_stock' => 10,
            'minimum_stock' => 1,
            'maximum_stock' => 100,
            'is_active' => true,
        ]);

        // 3. Set current header brand context to Nova
        $response = $this->actingAs($admin)
            ->withSession(['current_brand_id_' . $admin->id => $nova->id])
            ->get('/admin/raw-materials');

        // 4. Assert Nova and Common materials are returned
        $response->assertStatus(200);
        $response->assertSee('Nova Binder');
        $response->assertSee('Common Filler');

        // 5. Assert Solcon-only and Fixora-only materials are NOT returned under Nova context
        $response->assertDontSee('Solcon Binder');
        $response->assertDontSee('Fixora Binder');

        // 6. Verify Create form automatically loads Nova in brand dropdown
        $createResponse = $this->actingAs($admin)->get('/admin/raw-materials/create');
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Nova');
    }

    public function test_brand_migration_backfills_solcon_without_changing_stock(): void
    {
        $connection = 'raw_material_brand_migration';
        $originalConnection = config('database.default');
        $migration = require database_path('migrations/2026_08_15_000001_add_brand_id_to_raw_materials_table.php');

        config([
            "database.connections.{$connection}" => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
            'database.default' => $connection,
        ]);
        DB::purge($connection);

        try {
            Schema::create('brands', function ($table) {
                $table->id();
                $table->string('code', 10)->unique();
            });
            Schema::create('raw_materials', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->decimal('opening_stock', 12, 4);
                $table->decimal('current_stock', 12, 4);
                $table->decimal('minimum_stock', 12, 4);
                $table->decimal('maximum_stock', 12, 4);
            });

            $solconBrandId = DB::table('brands')->insertGetId(['code' => Brand::CODE_SOLCON]);
            DB::table('raw_materials')->insert([
                'name' => 'Existing Cement',
                'code' => 'EXISTING-CEMENT',
                'opening_stock' => 125.5000,
                'current_stock' => 115.2500,
                'minimum_stock' => 25.0000,
                'maximum_stock' => 500.0000,
            ]);

            $migration->up();

            $material = DB::table('raw_materials')
                ->where('code', 'EXISTING-CEMENT')
                ->first(['brand_id', 'opening_stock', 'current_stock', 'minimum_stock', 'maximum_stock']);

            $this->assertSame($solconBrandId, $material->brand_id);
            $this->assertEquals(125.5, $material->opening_stock);
            $this->assertEquals(115.25, $material->current_stock);
            $this->assertEquals(25, $material->minimum_stock);
            $this->assertEquals(500, $material->maximum_stock);
        } finally {
            DB::disconnect($connection);
            DB::purge($connection);
            config(['database.default' => $originalConnection]);
        }
    }

    public function test_admin_can_export_raw_materials_csv(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        $response = $this->actingAs($admin)->get('/admin/raw-materials/export');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment; filename=raw_materials_export_', $response->headers->get('Content-Disposition'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('Code,Name,"Department Code","Stock Unit Code"', $content);
    }

    public function test_admin_can_import_raw_materials_csv(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        $csvContent = "Code,Name,Department Code,Stock Unit Code,Purchase Unit Code,Purchase Conversion,Opening Stock,Minimum Stock,Maximum Stock,Active,Is Coupon,Description\n".
                      "TEST-MAT-1,Test Material 1,TAD,KG,KG,1.0,100,20,500,1,0,Description 1\n".
                      "OPC-53,Updated Name OPC,TAD,KG,KG,1.0,200,50,1000,1,0,Updated Description\n";

        $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

        $response = $this->actingAs($admin)->post('/admin/raw-materials/import', [
            'csv_file' => $file,
        ]);

        $response->assertRedirect('/admin/raw-materials');
        $response->assertSessionHas('success');

        // Assert TEST-MAT-1 was created
        $this->assertDatabaseHas('raw_materials', [
            'code' => 'TEST-MAT-1',
            'name' => 'Test Material 1',
            'opening_stock' => 100.0,
            'current_stock' => 100.0,
        ]);

        // Assert OPC-53 was updated
        $this->assertDatabaseHas('raw_materials', [
            'code' => 'OPC-53',
            'name' => 'Updated Name OPC',
        ]);
    }
}
