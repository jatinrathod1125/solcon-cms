<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\RawMaterial;
use App\Models\PackingMaterial;
use App\Models\Unit;
use App\Models\Formula;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormulasTest extends TestCase
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
        $response = $this->get('/admin/formulas');
        $response->assertRedirect('/login');
    }

    public function test_supervisor_cannot_access_formulas(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();

        $response = $this->actingAs($supervisor)->get('/admin/formulas');
        $response->assertStatus(403);
    }

    public function test_admin_can_perform_formula_crud(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $gradeF101 = Grade::where('code', 'F101')->first();
        $matCement = RawMaterial::where('code', 'OPC-53')->first();
        $matSand = RawMaterial::where('code', 'QZ-SAND')->first();
        $unitKG = Unit::where('code', 'KG')->first();

        // 1. Create (Initial seed has F101 v1)
        // Let's create F101 v2
        $response = $this->actingAs($admin)->post('/admin/formulas', [
            'grade_id' => $gradeF101->id,
            'remarks' => 'F101 Version 2 revision',
            'is_active' => true,
            'items' => [
                [
                    'item_type' => 'raw',
                    'raw_material_id' => $matCement->id,
                    'quantity' => 200.0000,
                    'unit_id' => $unitKG->id,
                    'sequence' => 1,
                ],
                [
                    'item_type' => 'raw',
                    'raw_material_id' => $matSand->id,
                    'quantity' => 800.0000,
                    'unit_id' => $unitKG->id,
                    'sequence' => 2,
                ]
            ]
        ]);

        $response->assertRedirect('/admin/formulas');
        
        // Confirm new version is 2
        $this->assertDatabaseHas('formulas', [
            'grade_id' => $gradeF101->id,
            'version' => 2,
            'is_active' => true,
        ]);

        // Confirm F101 version 1 was deactivated
        $this->assertDatabaseHas('formulas', [
            'grade_id' => $gradeF101->id,
            'version' => 1,
            'is_active' => false,
        ]);

        // 2. Read Single
        $newFormula = Formula::where('grade_id', $gradeF101->id)->where('version', 2)->first();
        $response = $this->actingAs($admin)->get("/admin/formulas/{$newFormula->id}");
        $response->assertStatus(200);
        $response->assertSee('F101 Version 2 revision');
        $response->assertSee('OPC-53');

        // 3. Update
        $response = $this->actingAs($admin)->put("/admin/formulas/{$newFormula->id}", [
            'grade_id' => $gradeF101->id,
            'remarks' => 'F101 Version 2 revised remarks',
            'is_active' => true,
            'items' => [
                [
                    'item_type' => 'raw',
                    'raw_material_id' => $matCement->id,
                    'quantity' => 210.0000,
                    'unit_id' => $unitKG->id,
                    'sequence' => 1,
                ],
                [
                    'item_type' => 'raw',
                    'raw_material_id' => $matSand->id,
                    'quantity' => 790.0000,
                    'unit_id' => $unitKG->id,
                    'sequence' => 2,
                ]
            ]
        ]);

        $response->assertRedirect('/admin/formulas');
        $this->assertDatabaseHas('formula_items', [
            'formula_id' => $newFormula->id,
            'raw_material_id' => $matCement->id,
            'quantity' => 210.0000,
        ]);

        // 4. Delete
        $response = $this->actingAs($admin)->delete("/admin/formulas/{$newFormula->id}");
        $response->assertRedirect('/admin/formulas');
        $this->assertDatabaseMissing('formulas', ['id' => $newFormula->id]);
    }

    public function test_admin_can_create_formula_with_packing_material(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $gradeF101 = Grade::where('code', 'F101')->first();
        $matCement = RawMaterial::where('code', 'OPC-53')->first();
        $packingMat = PackingMaterial::first();
        $unitKG = Unit::where('code', 'KG')->first();
        $unitPCS = Unit::where('code', 'PCS')->first() ?? $unitKG;

        if (!$packingMat) {
            $cat = \App\Models\PackingMaterialCategory::firstOrCreate(['name' => 'Bags']);
            $packingMat = PackingMaterial::create([
                'name' => 'Test Bag 20kg',
                'code' => 'BAG-20',
                'category_id' => $cat->id,
                'unit_id' => $unitPCS->id,
                'current_stock' => 100,
            ]);
        }

        $response = $this->actingAs($admin)->post('/admin/formulas', [
            'grade_id' => $gradeF101->id,
            'remarks' => 'Formula with packing material',
            'is_active' => true,
            'items' => [
                [
                    'item_type' => 'raw',
                    'raw_material_id' => $matCement->id,
                    'quantity' => 1000.0000,
                    'unit_id' => $unitKG->id,
                    'sequence' => 1,
                ],
                [
                    'item_type' => 'packing',
                    'packing_material_id' => $packingMat->id,
                    'quantity' => 50.0000,
                    'unit_id' => $unitPCS->id,
                    'sequence' => 2,
                ]
            ]
        ]);

        $response->assertRedirect('/admin/formulas');
    }

    public function test_admin_can_create_and_filter_formula_with_brand(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $brandSolcon = \App\Models\Brand::where('code', 'SOL')->first();
        
        // Ensure grade is associated with brand
        $gradeF101 = Grade::where('code', 'F101')->first();
        $gradeF101->update(['brand_id' => $brandSolcon->id]);

        $matCement = RawMaterial::where('code', 'OPC-53')->first();
        $unitKG = Unit::where('code', 'KG')->first();

        $response = $this->actingAs($admin)->post('/admin/formulas', [
            'grade_id' => $gradeF101->id,
            'remarks' => 'Brand specific formula',
            'is_active' => true,
            'items' => [
                [
                    'item_type' => 'raw',
                    'raw_material_id' => $matCement->id,
                    'quantity' => 500.0000,
                    'unit_id' => $unitKG->id,
                    'sequence' => 1,
                ]
            ]
        ]);

        $response->assertRedirect('/admin/formulas');
        $this->assertDatabaseHas('formulas', [
            'grade_id' => $gradeF101->id,
            'remarks' => 'Brand specific formula',
        ]);

        // Check index displays brand of grade
        $response = $this->actingAs($admin)->get('/admin/formulas');
        $response->assertStatus(200);
        $response->assertSee($brandSolcon->name);
    }
}
