<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Department;
use App\Models\Machine;
use App\Models\Unit;
use App\Models\BagSize;
use App\Models\RawMaterial;
use App\Models\Grade;
use App\Models\Formula;
use App\Models\FormulaItem;
use App\Models\Color;
use App\Models\GroutFormula;
use App\Models\GroutFormulaItem;
use App\Models\EpoxyProduct;
use App\Models\EpoxyFormula;
use App\Models\EpoxyFormulaItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestFixtureSeeder extends Seeder
{
    /**
     * Seed extra fixtures for automated PHPUnit tests.
     */
    public function run(): void
    {
        $this->call(DatabaseSeeder::class);

        $adminUser = User::where('email', 'admin@solcon.com')->first();
        $solconBrandId = Brand::where('code', Brand::CODE_SOLCON)->valueOrFail('id');
        $deptTAD = Department::where('code', 'TAD')->first();
        $deptGRT = Department::where('code', 'GRT')->first();
        $deptEPX = Department::where('code', 'EPX')->first();

        // Machines
        Machine::updateOrCreate(['code' => 'AM-01'], ['department_id' => $deptTAD->id, 'name' => 'Adhesive Mixer 1', 'description' => 'Primary high capacity mixer for tile adhesives', 'is_active' => true]);
        Machine::updateOrCreate(['code' => 'AM-02'], ['department_id' => $deptTAD->id, 'name' => 'Adhesive Mixer 2', 'description' => 'Secondary mixer for tile adhesives', 'is_active' => true]);
        Machine::updateOrCreate(['code' => 'M-01'], ['department_id' => $deptGRT->id, 'name' => 'Grout Automatic Mixer M-01', 'is_active' => true]);
        Machine::updateOrCreate(['code' => 'M-04'], ['department_id' => $deptGRT->id, 'name' => 'Grout Manual Mixer M-04', 'is_active' => true]);
        Machine::updateOrCreate(['code' => 'M-05'], ['department_id' => $deptGRT->id, 'name' => 'Grout Manual Mixer M-05', 'is_active' => true]);

        // Units
        $unitKG = Unit::updateOrCreate(['code' => 'KG'], ['name' => 'Kilogram', 'description' => 'Base unit for weight', 'is_active' => true]);
        $unitGM = Unit::updateOrCreate(['code' => 'GM'], ['name' => 'Gram', 'description' => 'Fractional unit for weight', 'is_active' => true]);
        $unitPCS = Unit::updateOrCreate(['code' => 'PCS'], ['name' => 'Pieces', 'description' => 'Unit of count', 'is_active' => true]);

        // Bag Sizes
        $bag20 = BagSize::updateOrCreate(['name' => '20 KG Bag'], ['value' => 20.00, 'description' => 'Standard 20kg package weight', 'is_active' => true]);
        $bag25 = BagSize::updateOrCreate(['name' => '25 KG Bag'], ['value' => 25.00, 'description' => 'Standard 25kg package weight', 'is_active' => true]);

        // Raw Materials
        $matCement = RawMaterial::updateOrCreate(
            ['code' => 'OPC-53'],
            [
                'brand_id' => $solconBrandId,
                'name' => 'Ordinary Portland Cement 53 Grade',
                'department_id' => $deptTAD->id,
                'stock_unit_id' => $unitKG->id,
                'purchase_unit_id' => $unitKG->id,
                'purchase_conversion' => 1.0000,
                'opening_stock' => 5000.0000,
                'current_stock' => 5000.0000,
                'minimum_stock' => 1000.0000,
                'maximum_stock' => 20000.0000,
                'description' => 'Grey cement used as binding material',
                'is_active' => true,
            ]
        );
        $matSand = RawMaterial::updateOrCreate(
            ['code' => 'QZ-SAND'],
            [
                'brand_id' => $solconBrandId,
                'name' => 'Quartz Sand 30-80 Mesh',
                'department_id' => $deptTAD->id,
                'stock_unit_id' => $unitKG->id,
                'purchase_unit_id' => $unitKG->id,
                'purchase_conversion' => 1.0000,
                'opening_stock' => 10000.0000,
                'current_stock' => 10000.0000,
                'minimum_stock' => 2500.0000,
                'maximum_stock' => 30000.0000,
                'description' => 'Silica filler sand',
                'is_active' => true,
            ]
        );

        // Grout Raw Materials & Colors
        $colorWhite = Color::updateOrCreate(
            ['code' => 'WHT-01'],
            ['name' => 'White', 'department_id' => $deptGRT->id, 'packing_size' => 1.0, 'default_cement' => 0.0, 'is_active' => true, 'created_by' => $adminUser->id]
        );
        $matGroutPoly = RawMaterial::updateOrCreate(
            ['code' => 'GRT-POLY'],
            [
                'brand_id' => $solconBrandId,
                'name' => 'Grout Polymer Powder',
                'department_id' => $deptGRT->id,
                'stock_unit_id' => $unitKG->id,
                'purchase_unit_id' => $unitKG->id,
                'purchase_conversion' => 1.0000,
                'opening_stock' => 5000.0000,
                'current_stock' => 5000.0000,
                'minimum_stock' => 500.0000,
                'maximum_stock' => 10000.0000,
                'is_active' => true,
            ]
        );

        // Grades & Formulas
        $gradeF101 = Grade::updateOrCreate(
            ['code' => 'F101'],
            [
                'department_id' => $deptTAD->id,
                'name' => 'Standard Tile Adhesive F101',
                'bag_size_id' => $bag20->id,
                'output_unit_id' => $unitKG->id,
                'description' => 'Grey tile adhesive',
                'is_active' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ]
        );

        $formula = Formula::updateOrCreate(
            ['grade_id' => $gradeF101->id, 'version' => 1],
            ['remarks' => 'Approved formulation', 'is_active' => true, 'created_by' => $adminUser->id]
        );

        FormulaItem::updateOrCreate(
            ['formula_id' => $formula->id, 'raw_material_id' => $matCement->id],
            ['quantity' => 150.0000, 'unit_id' => $unitKG->id, 'sequence' => 1]
        );
        FormulaItem::updateOrCreate(
            ['formula_id' => $formula->id, 'raw_material_id' => $matSand->id],
            ['quantity' => 850.0000, 'unit_id' => $unitKG->id, 'sequence' => 2]
        );

        // Grout Formula
        $groutFormula = GroutFormula::updateOrCreate(
            ['color_id' => $colorWhite->id, 'version' => 1],
            ['is_active' => true, 'remarks' => 'White grout formula', 'created_by' => $adminUser->id]
        );
        GroutFormulaItem::updateOrCreate(
            ['grout_formula_id' => $groutFormula->id, 'raw_material_id' => $matGroutPoly->id],
            ['quantity' => 100.0000, 'unit_id' => $unitKG->id, 'mix_stage' => 1]
        );

        // Epoxy Materials & Products
        $epxHardener = RawMaterial::updateOrCreate(
            ['code' => 'EPX-HRD-100'],
            [
                'brand_id' => $solconBrandId,
                'name' => '100gm Hardener Bottle',
                'department_id' => $deptEPX->id,
                'stock_unit_id' => $unitPCS->id,
                'purchase_unit_id' => $unitPCS->id,
                'purchase_conversion' => 1.0000,
                'opening_stock' => 1000.0000,
                'current_stock' => 1000.0000,
                'minimum_stock' => 50.0000,
                'maximum_stock' => 5000.0000,
                'is_active' => true,
            ]
        );
        $epxResin = RawMaterial::updateOrCreate(
            ['code' => 'EPX-RSN-200'],
            [
                'brand_id' => $solconBrandId,
                'name' => '200gm Resin Bottle',
                'department_id' => $deptEPX->id,
                'stock_unit_id' => $unitPCS->id,
                'purchase_unit_id' => $unitPCS->id,
                'purchase_conversion' => 1.0000,
                'opening_stock' => 1000.0000,
                'current_stock' => 1000.0000,
                'minimum_stock' => 50.0000,
                'maximum_stock' => 5000.0000,
                'is_active' => true,
            ]
        );
        $epxFiller = RawMaterial::updateOrCreate(
            ['code' => 'EPX-FIL-700'],
            [
                'brand_id' => $solconBrandId,
                'name' => '700gm Filler Pouch',
                'department_id' => $deptEPX->id,
                'stock_unit_id' => $unitPCS->id,
                'purchase_unit_id' => $unitPCS->id,
                'purchase_conversion' => 1.0000,
                'opening_stock' => 1000.0000,
                'current_stock' => 1000.0000,
                'minimum_stock' => 50.0000,
                'maximum_stock' => 5000.0000,
                'is_active' => true,
            ]
        );

        $epxProduct = EpoxyProduct::updateOrCreate(
            ['code' => 'EPX-PRD-BKT-1K'],
            ['name' => '1 KG Bucket', 'requires_color' => true, 'is_active' => true, 'created_by' => $adminUser->id]
        );

        $epxFormula = EpoxyFormula::updateOrCreate(
            ['epoxy_product_id' => $epxProduct->id, 'version' => 1],
            ['is_active' => true, 'description' => 'Standard 1kg formulation', 'created_by' => $adminUser->id]
        );

        EpoxyFormulaItem::updateOrCreate(
            ['epoxy_formula_id' => $epxFormula->id, 'raw_material_id' => $epxHardener->id],
            ['quantity' => 1, 'unit_id' => $unitPCS->id, 'is_dynamic_color' => false, 'material_type' => 'Bottle']
        );
        EpoxyFormulaItem::updateOrCreate(
            ['epoxy_formula_id' => $epxFormula->id, 'raw_material_id' => $epxResin->id],
            ['quantity' => 1, 'unit_id' => $unitPCS->id, 'is_dynamic_color' => false, 'material_type' => 'Bottle']
        );
        EpoxyFormulaItem::updateOrCreate(
            ['epoxy_formula_id' => $epxFormula->id, 'raw_material_id' => $epxFiller->id],
            ['quantity' => 1, 'unit_id' => $unitPCS->id, 'is_dynamic_color' => true, 'material_type' => 'Pouch']
        );
    }
}
