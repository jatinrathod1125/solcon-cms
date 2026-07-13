<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Department;
use App\Models\Color;
use App\Models\RawMaterial;
use App\Models\Unit;
use App\Models\GroutFormula;
use App\Services\GroutFormulaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroutFormulaTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $deptGRT;
    protected $colorWhite;
    protected $matRDP;
    protected $matCement;
    protected $unitKG;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->adminUser = User::where('email', 'admin@solcon.com')->first();
        $this->deptGRT = Department::where('code', 'GRT')->first();
        $this->unitKG = Unit::where('code', 'KG')->first();

        // Setup Color
        $this->colorWhite = Color::create([
            'department_id' => $this->deptGRT->id,
            'name' => 'White Grout',
            'code' => 'GR-WHT',
            'packing_size' => '1 KG',
            'default_cement' => 'White Cement',
            'is_active' => 1,
            'created_by' => $this->adminUser->id,
        ]);

        // Setup materials (RDP is stage 1, OPC is stage 2)
        $this->matRDP = RawMaterial::updateOrCreate(['code' => 'RDP-POWDER'], [
            'name' => 'Redispersible Polymer Powder',
            'department_id' => $this->deptGRT->id,
            'stock_unit_id' => $this->unitKG->id,
            'purchase_unit_id' => $this->unitKG->id,
            'purchase_conversion' => 1,
            'opening_stock' => 1000,
            'current_stock' => 1000,
            'minimum_stock' => 100,
            'maximum_stock' => 5000,
            'is_active' => true,
        ]);

        $this->matCement = RawMaterial::where('code', 'OPC-53')->first();
    }

    public function test_formula_validation_rules_via_service(): void
    {
        $service = app(GroutFormulaService::class);

        // 1. Fails when empty
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $service->validateFormula([]);
    }

    public function test_formula_validation_stage_1_required(): void
    {
        $service = app(GroutFormulaService::class);

        // Only stage 2 present
        $items = [
            [
                'raw_material_id' => $this->matCement->id,
                'quantity' => 10,
                'unit_id' => $this->unitKG->id,
                'mix_stage' => 'Stage 2',
            ]
        ];

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $service->validateFormula($items);
    }

    public function test_formula_validation_duplicate_material_restricted(): void
    {
        $service = app(GroutFormulaService::class);

        // Duplicate materials
        $items = [
            [
                'raw_material_id' => $this->matRDP->id,
                'quantity' => 5,
                'unit_id' => $this->unitKG->id,
                'mix_stage' => 'Stage 1',
            ],
            [
                'raw_material_id' => $this->matRDP->id,
                'quantity' => 10,
                'unit_id' => $this->unitKG->id,
                'mix_stage' => 'Stage 2',
            ]
        ];

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $service->validateFormula($items);
    }

    public function test_admin_can_create_valid_grout_formula(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/grout-formulas', [
            'color_id' => $this->colorWhite->id,
            'remarks' => 'First formula version',
            'is_active' => 1,
            'items' => [
                [
                    'raw_material_id' => $this->matRDP->id,
                    'quantity' => 12.5000,
                    'unit_id' => $this->unitKG->id,
                    'mix_stage' => 'Stage 1',
                    'display_order' => 0,
                ],
                [
                    'raw_material_id' => $this->matCement->id,
                    'quantity' => 87.5000,
                    'unit_id' => $this->unitKG->id,
                    'mix_stage' => 'Stage 2',
                    'display_order' => 1,
                ]
            ]
        ]);

        $response->assertRedirect('/admin/grout-formulas');

        $this->assertDatabaseHas('grout_formulas', [
            'color_id' => $this->colorWhite->id,
            'version' => 1,
            'is_active' => 1,
        ]);

        $this->assertDatabaseHas('grout_formula_items', [
            'raw_material_id' => $this->matRDP->id,
            'quantity' => 12.5000,
            'mix_stage' => 'Stage 1',
        ]);
    }

    public function test_version_increments_and_only_one_formula_is_active(): void
    {
        // 1. Create first active formula
        $formula1 = GroutFormula::create([
            'color_id' => $this->colorWhite->id,
            'version' => 1,
            'is_active' => true,
            'created_by' => $this->adminUser->id,
        ]);

        // 2. Submit second active formula via controller
        $response = $this->actingAs($this->adminUser)->post('/admin/grout-formulas', [
            'color_id' => $this->colorWhite->id,
            'remarks' => 'Version 2',
            'is_active' => 1,
            'items' => [
                [
                    'raw_material_id' => $this->matRDP->id,
                    'quantity' => 15.0000,
                    'unit_id' => $this->unitKG->id,
                    'mix_stage' => 'Stage 1',
                    'display_order' => 0,
                ],
                [
                    'raw_material_id' => $this->matCement->id,
                    'quantity' => 85.0000,
                    'unit_id' => $this->unitKG->id,
                    'mix_stage' => 'Stage 2',
                    'display_order' => 1,
                ]
            ]
        ]);

        $response->assertRedirect('/admin/grout-formulas');

        // Check version auto-incremented to 2
        $this->assertDatabaseHas('grout_formulas', [
            'color_id' => $this->colorWhite->id,
            'version' => 2,
            'is_active' => 1,
        ]);

        // Check first formula is deactivated
        $formula1->refresh();
        $this->assertFalse($formula1->is_active);
    }

    public function test_grout_formula_supports_multiple_pigment_raw_materials(): void
    {
        $matRedPigment = RawMaterial::create([
            'code' => 'PGM-RED-01',
            'name' => 'Red Iron Oxide Pigment',
            'department_id' => $this->deptGRT->id,
            'stock_unit_id' => $this->unitKG->id,
            'purchase_unit_id' => $this->unitKG->id,
            'purchase_conversion' => 1.0000,
            'opening_stock' => 100.0000,
            'current_stock' => 100.0000,
            'minimum_stock' => 10.0000,
            'maximum_stock' => 500.0000,
            'is_active' => true,
        ]);

        $matYellowPigment = RawMaterial::create([
            'code' => 'PGM-YEL-01',
            'name' => 'Yellow Iron Oxide Pigment',
            'department_id' => $this->deptGRT->id,
            'stock_unit_id' => $this->unitKG->id,
            'purchase_unit_id' => $this->unitKG->id,
            'purchase_conversion' => 1.0000,
            'opening_stock' => 100.0000,
            'current_stock' => 100.0000,
            'minimum_stock' => 10.0000,
            'maximum_stock' => 500.0000,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->post('/admin/grout-formulas', [
            'color_id' => $this->colorWhite->id,
            'remarks' => 'Multi-pigment terracotta grout formula',
            'is_active' => 1,
            'items' => [
                [
                    'raw_material_id' => $this->matRDP->id,
                    'quantity' => 10.0000,
                    'unit_id' => $this->unitKG->id,
                    'mix_stage' => 'Stage 1',
                    'display_order' => 0,
                ],
                [
                    'raw_material_id' => $matRedPigment->id,
                    'quantity' => 2.5000,
                    'unit_id' => $this->unitKG->id,
                    'mix_stage' => 'Stage 1',
                    'display_order' => 1,
                ],
                [
                    'raw_material_id' => $matYellowPigment->id,
                    'quantity' => 1.2500,
                    'unit_id' => $this->unitKG->id,
                    'mix_stage' => 'Stage 1',
                    'display_order' => 2,
                ],
                [
                    'raw_material_id' => $this->matCement->id,
                    'quantity' => 85.0000,
                    'unit_id' => $this->unitKG->id,
                    'mix_stage' => 'Stage 2',
                    'display_order' => 3,
                ],
            ]
        ]);

        $response->assertRedirect('/admin/grout-formulas');

        $latestFormula = GroutFormula::where('color_id', $this->colorWhite->id)->latest('id')->first();
        $this->assertCount(4, $latestFormula->items);
        $this->assertDatabaseHas('grout_formula_items', [
            'grout_formula_id' => $latestFormula->id,
            'raw_material_id' => $matRedPigment->id,
            'quantity' => 2.5000,
        ]);
        $this->assertDatabaseHas('grout_formula_items', [
            'grout_formula_id' => $latestFormula->id,
            'raw_material_id' => $matYellowPigment->id,
            'quantity' => 1.2500,
        ]);
    }
}
