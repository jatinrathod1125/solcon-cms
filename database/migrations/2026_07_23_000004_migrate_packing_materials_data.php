<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $unitPcs = DB::table('units')->where('code', 'PCS')->first()?->id;
        if (!$unitPcs) {
            $unitPcs = DB::table('units')->insertGetId([
                'name' => 'Pieces',
                'code' => 'PCS',
                'description' => 'Pieces unit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $categories = DB::table('packing_material_categories')->pluck('id', 'name')->toArray();
        if (empty($categories)) {
            $defaultCatNames = [
                'Adhesive Bags', 'Pouches', 'Buckets', 'Bottles',
                'Stickers', 'Boxes / Cartons', 'Barrels', 'Epoxy Accessories'
            ];
            foreach ($defaultCatNames as $idx => $cname) {
                $cid = DB::table('packing_material_categories')->insertGetId([
                    'name' => $cname,
                    'sort_order' => $idx + 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $categories[$cname] = $cid;
            }
        }

        $packingData = [
            'Adhesive Bags' => [
                ['name' => 'F101 Bag', 'code' => 'BAG-F101'],
                ['name' => 'F107 Bag', 'code' => 'BAG-F107'],
                ['name' => 'F121 Bag', 'code' => 'BAG-F121'],
                ['name' => 'F115 Bag', 'code' => 'BAG-F115'],
                ['name' => 'F133 Bag', 'code' => 'BAG-F133'],
                ['name' => 'B01 Bag', 'code' => 'BAG-B01'],
            ],
            'Pouches' => [
                ['name' => 'Grout 1Kg Pouch', 'code' => 'PCH-GRT-1KG', 'size' => '1Kg'],
                ['name' => 'Grout 500gm Pouch', 'code' => 'PCH-GRT-500G', 'size' => '500gm'],
                ['name' => 'Filler Pouch', 'code' => 'PCH-FLR'],
                ['name' => 'Spacer Pouch 2mm', 'code' => 'PCH-SPC-2MM', 'size' => '2mm'],
                ['name' => 'Spacer Pouch 3mm', 'code' => 'PCH-SPC-3MM', 'size' => '3mm'],
                ['name' => 'Spacer Pouch 4mm', 'code' => 'PCH-SPC-4MM', 'size' => '4mm'],
                ['name' => 'Spacer Pouch 5mm', 'code' => 'PCH-SPC-5MM', 'size' => '5mm'],
                ['name' => 'Clip Pouch 2mm', 'code' => 'PCH-CLP-2MM', 'size' => '2mm'],
                ['name' => 'Clip Pouch 3mm', 'code' => 'PCH-CLP-3MM', 'size' => '3mm'],
                ['name' => 'Clip Pouch 4mm', 'code' => 'PCH-CLP-4MM', 'size' => '4mm'],
                ['name' => 'Wedge Pouch', 'code' => 'PCH-WDG'],
                ['name' => 'Jari Pouch', 'code' => 'PCH-JRI'],
            ],
            'Buckets' => [
                ['name' => '1Kg Bucket', 'code' => 'BKT-1KG', 'size' => '1Kg'],
                ['name' => '5Kg Bucket', 'code' => 'BKT-5KG', 'size' => '5Kg'],
            ],
            'Bottles' => [
                ['name' => '100gm Bottle', 'code' => 'BTL-100G', 'size' => '100gm'],
                ['name' => '200gm Bottle', 'code' => 'BTL-200G', 'size' => '200gm'],
                ['name' => '500gm Bottle', 'code' => 'BTL-500G', 'size' => '500gm'],
                ['name' => '1Kg Bottle', 'code' => 'BTL-1KG', 'size' => '1Kg'],
                ['name' => 'Tile Power 1L Bottle', 'code' => 'BTL-TP-1L', 'size' => '1L'],
                ['name' => 'Tile Power 5L Bottle', 'code' => 'BTL-TP-5L', 'size' => '5L'],
                ['name' => 'SBR 1L Bottle', 'code' => 'BTL-SBR-1L', 'size' => '1L'],
                ['name' => 'SBR 5L Bottle', 'code' => 'BTL-SBR-5L', 'size' => '5L'],
            ],
            'Stickers' => [
                ['name' => '100gm Sticker', 'code' => 'STK-100G', 'size' => '100gm'],
                ['name' => '200gm Sticker', 'code' => 'STK-200G', 'size' => '200gm'],
                ['name' => '500gm Sticker', 'code' => 'STK-500G', 'size' => '500gm'],
                ['name' => '1Kg Sticker', 'code' => 'STK-1KG', 'size' => '1Kg'],
                ['name' => 'Tile Power 1L Sticker', 'code' => 'STK-TP-1L', 'size' => '1L'],
                ['name' => 'Tile Power 5L Sticker', 'code' => 'STK-TP-5L', 'size' => '5L'],
                ['name' => 'Grout Admix Sticker', 'code' => 'STK-GA'],
                ['name' => 'Soltite 1.8Kg Sticker', 'code' => 'STK-SLT-1.8KG', 'size' => '1.8Kg'],
                ['name' => 'Soltite 900gm Sticker', 'code' => 'STK-SLT-900G', 'size' => '900gm'],
                ['name' => 'Soltite 450gm Sticker', 'code' => 'STK-SLT-450G', 'size' => '450gm'],
            ],
            'Boxes / Cartons' => [
                ['name' => 'Grout Admix Box', 'code' => 'BOX-GA'],
                ['name' => 'Sample Box', 'code' => 'BOX-SMP'],
                ['name' => 'Tile Cleaner 1L Box', 'code' => 'BOX-TC-1L', 'size' => '1L'],
                ['name' => 'Tile Cleaner 5L Box', 'code' => 'BOX-TC-5L', 'size' => '5L'],
                ['name' => 'Epoxy 1Kg Box', 'code' => 'BOX-EPX-1KG', 'size' => '1Kg'],
                ['name' => 'Epoxy 5Kg Box', 'code' => 'BOX-EPX-5KG', 'size' => '5Kg'],
                ['name' => 'Small Grout Box', 'code' => 'BOX-GRT-SM'],
                ['name' => 'Big Grout Box', 'code' => 'BOX-GRT-BG'],
                ['name' => 'Jari Box', 'code' => 'BOX-JRI'],
                ['name' => 'Soltite 1.8Kg Box', 'code' => 'BOX-SLT-1.8KG', 'size' => '1.8Kg'],
                ['name' => 'Soltite 900gm Box', 'code' => 'BOX-SLT-900G', 'size' => '900gm'],
                ['name' => 'Soltite 450gm Box', 'code' => 'BOX-SLT-450G', 'size' => '450gm'],
            ],
            'Barrels' => [
                ['name' => 'Acid Barrel', 'code' => 'BRL-ACD'],
                ['name' => 'SBR Barrel 50 Ltr', 'code' => 'BRL-SBR-50L', 'size' => '50 Ltr'],
            ],
            'Epoxy Accessories' => [
                ['name' => 'Sponge', 'code' => 'ACC-SPG'],
                ['name' => 'Blade', 'code' => 'ACC-BLD'],
                ['name' => 'Hand Gloves', 'code' => 'ACC-GLV'],
            ],
        ];

        // Map existing raw_materials items to target new items
        $existingRawMaterials = DB::table('raw_materials')->get();
        $migratedIds = [];

        foreach ($packingData as $catName => $items) {
            $catId = $categories[$catName] ?? 1;

            foreach ($items as $item) {
                // Check if existing raw material matches
                $matchedRm = $existingRawMaterials->first(function ($rm) use ($item) {
                    $rmNameLower = strtolower($rm->name);
                    $itemNameLower = strtolower($item['name']);

                    if ($rmNameLower === $itemNameLower) return true;
                    if (str_replace('empty ', '', $rmNameLower) === $itemNameLower) return true;
                    if (str_replace('bag ', '', $rmNameLower) === $itemNameLower) return true;
                    if (str_contains($rmNameLower, 'f-101') && str_contains($itemNameLower, 'f101')) return true;
                    if (str_contains($rmNameLower, 'f-107') && str_contains($itemNameLower, 'f107')) return true;
                    if (str_contains($rmNameLower, 'f-121') && str_contains($itemNameLower, 'f121')) return true;
                    if (str_contains($rmNameLower, 'f-115') && str_contains($itemNameLower, 'f115')) return true;
                    if (str_contains($rmNameLower, 'f-133') && str_contains($itemNameLower, 'f133')) return true;
                    if (str_contains($rmNameLower, 'f-147') && str_contains($itemNameLower, 'f147')) return true;
                    if (str_contains($rmNameLower, '1kg pouch') && str_contains($itemNameLower, '1kg pouch')) return true;
                    if (str_contains($rmNameLower, '500 pouch') && str_contains($itemNameLower, '500gm pouch')) return true;
                    if (str_contains($rmNameLower, 'filler pouch') && str_contains($itemNameLower, 'filler pouch')) return true;
                    if (str_contains($rmNameLower, 'spounch') && str_contains($itemNameLower, 'sponge')) return true;
                    if (str_contains($rmNameLower, 'blade') && str_contains($itemNameLower, 'blade')) return true;
                    if (str_contains($rmNameLower, 'gloves') && str_contains($itemNameLower, 'gloves')) return true;
                    if (str_contains($rmNameLower, '1kg bucket') && str_contains($itemNameLower, '1kg bucket')) return true;
                    if (str_contains($rmNameLower, '5kg bucket') && str_contains($itemNameLower, '5kg bucket')) return true;
                    if (str_contains($rmNameLower, '100gm bottle') && str_contains($itemNameLower, '100gm bottle')) return true;
                    if (str_contains($rmNameLower, '200gm bottle') && str_contains($itemNameLower, '200gm bottle')) return true;
                    if (str_contains($rmNameLower, '500gm bottle') && str_contains($itemNameLower, '500gm bottle')) return true;
                    if (str_contains($rmNameLower, '1kg bottle') && str_contains($itemNameLower, '1kg bottle')) return true;

                    return false;
                });

                $openingStock = $matchedRm ? (float) $matchedRm->opening_stock : 0.0;
                $currentStock = $matchedRm ? (float) $matchedRm->current_stock : 0.0;
                $minimumStock = $matchedRm ? (float) $matchedRm->minimum_stock : 0.0;
                $unitId = $matchedRm ? $matchedRm->stock_unit_id : $unitPcs;

                $newPmId = DB::table('packing_materials')->insertGetId([
                    'category_id' => $catId,
                    'name' => $item['name'],
                    'code' => $item['code'],
                    'size' => $item['size'] ?? null,
                    'unit_id' => $unitId,
                    'minimum_stock' => $minimumStock,
                    'opening_stock' => $openingStock,
                    'current_stock' => $currentStock,
                    'remarks' => $matchedRm ? ($matchedRm->description ?? '') : '',
                    'status' => ($matchedRm && !$matchedRm->is_active) ? 'inactive' : 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($matchedRm) {
                    $migratedIds[] = $matchedRm->id;

                    // Update ledger entries & adjustments
                    DB::table('stock_ledgers')
                        ->where('raw_material_id', $matchedRm->id)
                        ->update(['packing_material_id' => $newPmId, 'raw_material_id' => null]);

                    DB::table('stock_adjustments')
                        ->where('raw_material_id', $matchedRm->id)
                        ->update(['packing_material_id' => $newPmId, 'raw_material_id' => null]);
                }
            }
        }

        // Handle Spacer Pouch & Clip Pouch legacy split if any raw_material exists
        $spacerRm = $existingRawMaterials->first(fn($rm) => strtolower($rm->name) === 'spacer pouch' || strtolower($rm->name) === 'spacer');
        if ($spacerRm) {
            $migratedIds[] = $spacerRm->id;
            $spacerPms = DB::table('packing_materials')->where('name', 'like', 'Spacer Pouch%')->get();
            foreach ($spacerPms as $sp) {
                DB::table('packing_materials')->where('id', $sp->id)->update([
                    'opening_stock' => (float) $spacerRm->opening_stock,
                    'current_stock' => (float) $spacerRm->current_stock,
                    'minimum_stock' => (float) $spacerRm->minimum_stock,
                ]);
            }
        }

        $clipRm = $existingRawMaterials->first(fn($rm) => strtolower($rm->name) === 'clip pouch' || strtolower($rm->name) === 'clip');
        if ($clipRm) {
            $migratedIds[] = $clipRm->id;
            $clipPms = DB::table('packing_materials')->where('name', 'like', 'Clip Pouch%')->get();
            foreach ($clipPms as $cp) {
                DB::table('packing_materials')->where('id', $cp->id)->update([
                    'opening_stock' => (float) $clipRm->opening_stock,
                    'current_stock' => (float) $clipRm->current_stock,
                    'minimum_stock' => (float) $clipRm->minimum_stock,
                ]);
            }
        }

        // Delete migrated packing materials from raw_materials
        if (!empty($migratedIds)) {
            DB::table('raw_materials')->whereIn('id', array_unique($migratedIds))->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('packing_materials')->truncate();
    }
};
