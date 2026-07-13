<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Unit;
use App\Models\RawMaterial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $dept = Department::where('code', 'TAD')->first();
        $unitKG = Unit::where('code', 'KG')->first();

        // 1. Create
        $response = $this->actingAs($admin)->post('/admin/raw-materials', [
            'name' => 'Hydrated Lime',
            'code' => 'H-LIME',
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
            'opening_stock' => 150.5000,
            'current_stock' => 150.5000,
        ]);

        // 2. Read with Filters
        $response = $this->actingAs($admin)->get('/admin/raw-materials?search=LIME&department_id=' . $dept->id);
        $response->assertStatus(200);
        $response->assertSee('Hydrated Lime');
        $response->assertSee('H-LIME');

        // 3. Update
        $material = RawMaterial::where('code', 'H-LIME')->first();
        $response = $this->actingAs($admin)->put("/admin/raw-materials/{$material->id}", [
            'name' => 'Hydrated Lime Premium',
            'code' => 'H-LIME-P',
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
        $dept = Department::where('code', 'TAD')->first();
        $unitKG = Unit::where('code', 'KG')->first();

        // Unique Code Violation
        $response = $this->actingAs($admin)->post('/admin/raw-materials', [
            'name' => 'Cement Copy',
            'code' => 'OPC-53',
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
        
        $csvContent = "Code,Name,Department Code,Stock Unit Code,Purchase Unit Code,Purchase Conversion,Opening Stock,Minimum Stock,Maximum Stock,Active,Is Coupon,Description\n" .
                      "TEST-MAT-1,Test Material 1,TAD,KG,KG,1.0,100,20,500,1,0,Description 1\n" .
                      "OPC-53,Updated Name OPC,TAD,KG,KG,1.0,200,50,1000,1,0,Updated Description\n";
                      
        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('import.csv', $csvContent);
        
        $response = $this->actingAs($admin)->post('/admin/raw-materials/import', [
            'csv_file' => $file
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
