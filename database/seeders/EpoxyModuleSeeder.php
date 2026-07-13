<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Unit;
use App\Models\RawMaterial;
use App\Models\EpoxyFillerColor;
use App\Models\EpoxyComponent;
use App\Models\EpoxyComponentFormula;
use App\Models\EpoxyComponentFormulaItem;
use App\Models\EpoxyProduct;
use App\Models\EpoxyFormula;
use App\Models\EpoxyFormulaItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class EpoxyModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deptEPX = Department::where('code', 'EPX')->firstOrFail();
        $unitKG = Unit::where('code', 'KG')->firstOrFail();
        $unitPCS = Unit::where('code', 'PCS')->firstOrFail();
        $adminUser = User::where('email', 'admin@solcon.com')->first() ?? User::first();

        // 1. Seed Epoxy Filler Colors
        $colors = [
            ['code' => 'WHT', 'name' => 'White'],
            ['code' => 'BLK', 'name' => 'Black'],
            ['code' => 'GRY', 'name' => 'Grey'],
            ['code' => 'CHO', 'name' => 'Chocolate'],
            ['code' => 'RED', 'name' => 'Red'],
            ['code' => 'GRN', 'name' => 'Green'],
        ];

        $colorModels = [];
        foreach ($colors as $c) {
            $colorModels[$c['code']] = EpoxyFillerColor::updateOrCreate(
                ['code' => $c['code']],
                [
                    'name' => $c['name'],
                    'is_active' => true,
                    'created_by' => $adminUser->id,
                    'updated_by' => $adminUser->id,
                ]
            );
        }

        // 2. Seed Bulk and Packaging Raw Materials
        $bulkMaterials = [
            ['code' => 'EPX-BLK-HRD', 'name' => 'Bulk Hardener', 'stock_unit_id' => $unitKG->id, 'opening_stock' => 10000.0, 'current_stock' => 10000.0],
            ['code' => 'EPX-BLK-RSN', 'name' => 'Bulk Resin', 'stock_unit_id' => $unitKG->id, 'opening_stock' => 10000.0, 'current_stock' => 10000.0],
            ['code' => 'EPX-BLK-FIL', 'name' => 'Bulk Filler', 'stock_unit_id' => $unitKG->id, 'opening_stock' => 20000.0, 'current_stock' => 20000.0],
            ['code' => 'EPX-BLK-ACID', 'name' => 'Bulk Chemical Acid', 'stock_unit_id' => $unitKG->id, 'opening_stock' => 5000.0, 'current_stock' => 5000.0],
            ['code' => 'EPX-BLK-SPK-GLD', 'name' => 'Bulk Gold Sparkle', 'stock_unit_id' => $unitKG->id, 'opening_stock' => 1000.0, 'current_stock' => 1000.0],
            ['code' => 'EPX-BLK-SPK-RED', 'name' => 'Bulk Red Sparkle', 'stock_unit_id' => $unitKG->id, 'opening_stock' => 1000.0, 'current_stock' => 1000.0],
            ['code' => 'EPX-BLK-SPK-SLV', 'name' => 'Bulk Silver Sparkle', 'stock_unit_id' => $unitKG->id, 'opening_stock' => 1000.0, 'current_stock' => 1000.0],
            ['code' => 'EPX-BLK-SPK-CPR', 'name' => 'Bulk Copper Sparkle', 'stock_unit_id' => $unitKG->id, 'opening_stock' => 1000.0, 'current_stock' => 1000.0],
        ];

        $bulkModels = [];
        foreach ($bulkMaterials as $bm) {
            $bulkModels[$bm['code']] = RawMaterial::updateOrCreate(
                ['code' => $bm['code']],
                [
                    'name' => $bm['name'],
                    'department_id' => $deptEPX->id,
                    'stock_unit_id' => $bm['stock_unit_id'],
                    'purchase_unit_id' => $bm['stock_unit_id'],
                    'purchase_conversion' => 1.0,
                    'opening_stock' => $bm['opening_stock'],
                    'current_stock' => $bm['current_stock'],
                    'minimum_stock' => 100.0,
                    'is_active' => true,
                ]
            );
        }

        $pkgMaterials = [
            ['code' => 'EPX-PKG-BTL-100', 'name' => 'Empty 100gm Bottle'],
            ['code' => 'EPX-PKG-BTL-200', 'name' => 'Empty 200gm Bottle'],
            ['code' => 'EPX-PKG-PCH-700', 'name' => 'Empty 700gm Pouch'],
            ['code' => 'EPX-PKG-CAN-1L', 'name' => 'Empty 1L Canister'],
            ['code' => 'EPX-PKG-PCH-50', 'name' => 'Empty 50gm Pouch'],
        ];

        $pkgModels = [];
        foreach ($pkgMaterials as $pm) {
            $pkgModels[$pm['code']] = RawMaterial::updateOrCreate(
                ['code' => $pm['code']],
                [
                    'name' => $pm['name'],
                    'department_id' => $deptEPX->id,
                    'stock_unit_id' => $unitPCS->id,
                    'purchase_unit_id' => $unitPCS->id,
                    'purchase_conversion' => 1.0,
                    'opening_stock' => 5000.0,
                    'current_stock' => 5000.0,
                    'minimum_stock' => 50.0,
                    'is_active' => true,
                ]
            );
        }

        // Standard accessory materials for kits
        $stdMaterials = [
            ['code' => 'EPX-BKT-1K', 'name' => '1KG Empty Bucket'],
            ['code' => 'EPX-GLV', 'name' => 'Gloves Pair'],
            ['code' => 'EPX-BLD', 'name' => 'Scraper Blade'],
            ['code' => 'EPX-CPN', 'name' => 'Promo Coupon'],
        ];

        $stdModels = [];
        foreach ($stdMaterials as $sm) {
            $stdModels[$sm['code']] = RawMaterial::updateOrCreate(
                ['code' => $sm['code']],
                [
                    'name' => $sm['name'],
                    'department_id' => $deptEPX->id,
                    'stock_unit_id' => $unitPCS->id,
                    'purchase_unit_id' => $unitPCS->id,
                    'purchase_conversion' => 1.0,
                    'opening_stock' => 5000.0,
                    'current_stock' => 5000.0,
                    'minimum_stock' => 50.0,
                    'is_active' => true,
                ]
            );
        }

        // 3. Seed Base / Generic Components
        $baseFillerComponent = EpoxyComponent::updateOrCreate(
            ['code' => 'EPX-FIL-700'],
            [
                'name' => '700gm Filler Pouch (Base)',
                'category' => 'Pouch',
                'purpose' => 'Assembly Component',
                'unit_id' => $unitPCS->id,
                'is_active' => true,
                'description' => 'Generic base component for 700gm filler pouches',
            ]
        );

        // Auto-create raw material for base component if needed
        $baseRm = RawMaterial::updateOrCreate(
            ['code' => $baseFillerComponent->code],
            [
                'name' => $baseFillerComponent->name,
                'department_id' => $deptEPX->id,
                'stock_unit_id' => $baseFillerComponent->unit_id,
                'purchase_unit_id' => $baseFillerComponent->unit_id,
                'purchase_conversion' => 1.0,
                'opening_stock' => 100.0,
                'current_stock' => 100.0,
                'is_active' => true,
            ]
        );
        $baseFillerComponent->update(['raw_material_id' => $baseRm->id]);

        // 4. Seed Child / Color-specific Components
        $readyFillerColors = ['WHT', 'BLK', 'GRY', 'CHO', 'RED', 'GRN'];
        $componentModels = [];

        foreach ($readyFillerColors as $cc) {
            $colorName = $colorModels[$cc]->name;
            $code = "EPX-FIL-700-{$cc}";
            $name = "700gm {$colorName} Filler Pouch";

            // Create ready component raw material representing prepared stock
            $readyRm = RawMaterial::updateOrCreate(
                ['code' => $code],
                [
                    'name' => "Ready {$name}",
                    'department_id' => $deptEPX->id,
                    'stock_unit_id' => $unitPCS->id,
                    'purchase_unit_id' => $unitPCS->id,
                    'purchase_conversion' => 1.0,
                    'opening_stock' => 200.0,
                    'current_stock' => 200.0,
                    'minimum_stock' => 10.0,
                    'is_active' => true,
                ]
            );

            // Create the Epoxy Component record
            $comp = EpoxyComponent::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'category' => 'Pouch',
                    'purpose' => 'Assembly Component',
                    'unit_id' => $unitPCS->id,
                    'is_active' => true,
                    'description' => "Ready packaged {$name} for bucket assembly.",
                    'raw_material_id' => $readyRm->id,
                    'parent_component_id' => $baseFillerComponent->id,
                    'epoxy_filler_color_id' => $colorModels[$cc]->id,
                ]
            );

            $componentModels[$code] = $comp;

            // Seed active formula for color component
            $formula = EpoxyComponentFormula::updateOrCreate(
                ['epoxy_component_id' => $comp->id, 'version' => 1],
                ['is_active' => true, 'description' => "Standard recipe for {$name}", 'created_by' => $adminUser->id]
            );

            EpoxyComponentFormulaItem::updateOrCreate(
                ['epoxy_component_formula_id' => $formula->id, 'raw_material_id' => $bulkModels['EPX-BLK-FIL']->id],
                ['quantity' => 0.7000, 'unit_id' => $unitKG->id]
            );

            EpoxyComponentFormulaItem::updateOrCreate(
                ['epoxy_component_formula_id' => $formula->id, 'raw_material_id' => $pkgModels['EPX-PKG-PCH-700']->id],
                ['quantity' => 1.0, 'unit_id' => $unitPCS->id]
            );
        }

        // 5. Hardener Component
        $hardenerRm = RawMaterial::updateOrCreate(
            ['code' => 'EPX-HRD-100'],
            [
                'name' => 'Ready 100gm Hardener Bottle',
                'department_id' => $deptEPX->id,
                'stock_unit_id' => $unitPCS->id,
                'purchase_unit_id' => $unitPCS->id,
                'purchase_conversion' => 1.0,
                'opening_stock' => 100.0,
                'current_stock' => 100.0,
                'is_active' => true,
            ]
        );

        $compHardener = EpoxyComponent::updateOrCreate(
            ['code' => 'EPX-HRD-100'],
            [
                'name' => '100gm Hardener Bottle',
                'category' => 'Bottle',
                'purpose' => 'Assembly Component',
                'unit_id' => $unitPCS->id,
                'is_active' => true,
                'raw_material_id' => $hardenerRm->id,
            ]
        );

        $formulaHardener = EpoxyComponentFormula::updateOrCreate(
            ['epoxy_component_id' => $compHardener->id, 'version' => 1],
            ['is_active' => true, 'description' => 'Recipe for 100gm Hardener', 'created_by' => $adminUser->id]
        );

        EpoxyComponentFormulaItem::updateOrCreate(
            ['epoxy_component_formula_id' => $formulaHardener->id, 'raw_material_id' => $bulkModels['EPX-BLK-HRD']->id],
            ['quantity' => 0.1000, 'unit_id' => $unitKG->id]
        );

        EpoxyComponentFormulaItem::updateOrCreate(
            ['epoxy_component_formula_id' => $formulaHardener->id, 'raw_material_id' => $pkgModels['EPX-PKG-BTL-100']->id],
            ['quantity' => 1.0, 'unit_id' => $unitPCS->id]
        );

        // 6. Resin Component
        $resinRm = RawMaterial::updateOrCreate(
            ['code' => 'EPX-RSN-200'],
            [
                'name' => 'Ready 200gm Resin Bottle',
                'department_id' => $deptEPX->id,
                'stock_unit_id' => $unitPCS->id,
                'purchase_unit_id' => $unitPCS->id,
                'purchase_conversion' => 1.0,
                'opening_stock' => 100.0,
                'current_stock' => 100.0,
                'is_active' => true,
            ]
        );

        $compResin = EpoxyComponent::updateOrCreate(
            ['code' => 'EPX-RSN-200'],
            [
                'name' => '200gm Resin Bottle',
                'category' => 'Bottle',
                'purpose' => 'Assembly Component',
                'unit_id' => $unitPCS->id,
                'is_active' => true,
                'raw_material_id' => $resinRm->id,
            ]
        );

        $formulaResin = EpoxyComponentFormula::updateOrCreate(
            ['epoxy_component_id' => $compResin->id, 'version' => 1],
            ['is_active' => true, 'description' => 'Recipe for 200gm Resin', 'created_by' => $adminUser->id]
        );

        EpoxyComponentFormulaItem::updateOrCreate(
            ['epoxy_component_formula_id' => $formulaResin->id, 'raw_material_id' => $bulkModels['EPX-BLK-RSN']->id],
            ['quantity' => 0.2000, 'unit_id' => $unitKG->id]
        );

        EpoxyComponentFormulaItem::updateOrCreate(
            ['epoxy_component_formula_id' => $formulaResin->id, 'raw_material_id' => $pkgModels['EPX-PKG-BTL-200']->id],
            ['quantity' => 1.0, 'unit_id' => $unitPCS->id]
        );

        // 7. Dynamic Direct Finished Good Component: Sparkle Chemical Acid
        $compAcid = EpoxyComponent::updateOrCreate(
            ['code' => 'EPX-ACID'],
            [
                'name' => 'Ready 1L Acid Canister',
                'category' => 'Liquid',
                'purpose' => 'Direct Finished Product',
                'unit_id' => $unitPCS->id,
                'is_active' => true,
                'description' => 'Clean chemical acid for direct retail sale.',
            ]
        );

        $formulaAcid = EpoxyComponentFormula::updateOrCreate(
            ['epoxy_component_id' => $compAcid->id, 'version' => 1],
            ['is_active' => true, 'description' => 'Acid preparation formula', 'created_by' => $adminUser->id]
        );

        EpoxyComponentFormulaItem::updateOrCreate(
            ['epoxy_component_formula_id' => $formulaAcid->id, 'raw_material_id' => $bulkModels['EPX-BLK-ACID']->id],
            ['quantity' => 1.0000, 'unit_id' => $unitKG->id]
        );

        EpoxyComponentFormulaItem::updateOrCreate(
            ['epoxy_component_formula_id' => $formulaAcid->id, 'raw_material_id' => $pkgModels['EPX-PKG-CAN-1L']->id],
            ['quantity' => 1.0, 'unit_id' => $unitPCS->id]
        );

        // 8. Dynamic Products & Formulas Setup
        $epxProduct = EpoxyProduct::where('code', 'EPX-PRD-BKT-1K')->first() ?? EpoxyProduct::create([
            'code' => 'EPX-PRD-BKT-1K',
            'name' => '1 KG Epoxy Tile Joint Kit',
            'requires_color' => true,
            'is_active' => true,
            'created_by' => $adminUser->id,
        ]);

        $epxFormula = EpoxyFormula::where('epoxy_product_id', $epxProduct->id)->where('version', 1)->first() ?? EpoxyFormula::create([
            'epoxy_product_id' => $epxProduct->id,
            'version' => 1,
            'is_active' => true,
            'description' => 'Standard 1kg epoxy pack',
            'created_by' => $adminUser->id,
        ]);

        EpoxyFormulaItem::where('epoxy_formula_id', $epxFormula->id)->delete();

        EpoxyFormulaItem::create([
            'epoxy_formula_id' => $epxFormula->id,
            'raw_material_id' => $hardenerRm->id,
            'quantity' => 1.0,
            'unit_id' => $unitPCS->id,
            'is_dynamic_color' => false,
            'material_type' => 'Bottle',
        ]);

        EpoxyFormulaItem::create([
            'epoxy_formula_id' => $epxFormula->id,
            'raw_material_id' => $resinRm->id,
            'quantity' => 1.0,
            'unit_id' => $unitPCS->id,
            'is_dynamic_color' => false,
            'material_type' => 'Bottle',
        ]);

        EpoxyFormulaItem::create([
            'epoxy_formula_id' => $epxFormula->id,
            'raw_material_id' => $baseRm->id,
            'quantity' => 1.0,
            'unit_id' => $unitPCS->id,
            'is_dynamic_color' => true,
            'material_type' => 'Pouch',
        ]);

        EpoxyFormulaItem::create([
            'epoxy_formula_id' => $epxFormula->id,
            'raw_material_id' => $stdModels['EPX-BKT-1K']->id,
            'quantity' => 1.0,
            'unit_id' => $unitPCS->id,
            'is_dynamic_color' => false,
            'material_type' => 'Bucket',
        ]);

        EpoxyFormulaItem::create([
            'epoxy_formula_id' => $epxFormula->id,
            'raw_material_id' => $stdModels['EPX-GLV']->id,
            'quantity' => 1.0,
            'unit_id' => $unitPCS->id,
            'is_dynamic_color' => false,
            'material_type' => 'Accessory',
        ]);

        EpoxyFormulaItem::create([
            'epoxy_formula_id' => $epxFormula->id,
            'raw_material_id' => $stdModels['EPX-BLD']->id,
            'quantity' => 1.0,
            'unit_id' => $unitPCS->id,
            'is_dynamic_color' => false,
            'material_type' => 'Accessory',
        ]);

        EpoxyFormulaItem::create([
            'epoxy_formula_id' => $epxFormula->id,
            'raw_material_id' => $stdModels['EPX-CPN']->id,
            'quantity' => 1.0,
            'unit_id' => $unitPCS->id,
            'is_dynamic_color' => false,
            'material_type' => 'Accessory',
        ]);

        // 9. Seed Sparkle Components (Direct Finished Products)
        $sparkles = [
            ['code' => 'EPX-SPK-50-GLD', 'name' => '50gm Gold Sparkle Pouch', 'bulk_code' => 'EPX-BLK-SPK-GLD'],
            ['code' => 'EPX-SPK-50-RED', 'name' => '50gm Red Sparkle Pouch', 'bulk_code' => 'EPX-BLK-SPK-RED'],
            ['code' => 'EPX-SPK-50-SLV', 'name' => '50gm Silver Sparkle Pouch', 'bulk_code' => 'EPX-BLK-SPK-SLV'],
            ['code' => 'EPX-SPK-50-CPR', 'name' => '50gm Copper Sparkle Pouch', 'bulk_code' => 'EPX-BLK-SPK-CPR'],
        ];

        foreach ($sparkles as $spk) {
            $compSpk = EpoxyComponent::updateOrCreate(
                ['code' => $spk['code']],
                [
                    'name' => $spk['name'],
                    'category' => 'Pouch',
                    'purpose' => 'Direct Finished Product',
                    'unit_id' => $unitPCS->id,
                    'is_active' => true,
                    'description' => "Prepared 50gm pouch of {$spk['name']} for direct finished product.",
                ]
            );

            // Create active formula
            $formulaSpk = EpoxyComponentFormula::updateOrCreate(
                ['epoxy_component_id' => $compSpk->id, 'version' => 1],
                ['is_active' => true, 'description' => "Recipe for 50gm Pouch", 'created_by' => $adminUser->id]
            );

            // Deduct 50gm (0.0500 KG) of Bulk Sparkle
            EpoxyComponentFormulaItem::updateOrCreate(
                ['epoxy_component_formula_id' => $formulaSpk->id, 'raw_material_id' => $bulkModels[$spk['bulk_code']]->id],
                ['quantity' => 0.0500, 'unit_id' => $unitKG->id]
            );

            // Deduct 1 empty 50gm pouch
            EpoxyComponentFormulaItem::updateOrCreate(
                ['epoxy_component_formula_id' => $formulaSpk->id, 'raw_material_id' => $pkgModels['EPX-PKG-PCH-50']->id],
                ['quantity' => 1.0, 'unit_id' => $unitPCS->id]
            );
        }
    }
}
