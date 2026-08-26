<?php

use App\Models\Brand;
use App\Models\Department;
use App\Models\EpoxyComponent;
use App\Models\RawMaterial;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $solconBrand = Brand::where('code', 'SOL')->first() ?? Brand::first();
        $fixoraBrand = Brand::where('code', 'FIX')->first();
        $deptEPX = Department::where('code', 'EPX')->first();

        if (!$deptEPX || !$solconBrand) {
            return;
        }

        // 1. Ensure Solcon base raw materials belong to Solcon brand
        $solconRmCodes = ['EPX-BLK', 'EPX-WHT', 'EPX-BLT-01', 'F-011', 'HRD-500', 'REN-1K'];
        RawMaterial::whereIn('code', $solconRmCodes)
            ->where('department_id', $deptEPX->id)
            ->update(['brand_id' => $solconBrand->id]);

        // 2. Ensure each assembly component has an isolated, brand-matching RawMaterial
        $components = EpoxyComponent::where('purpose', 'Assembly Component')->get();

        foreach ($components as $component) {
            $targetBrandId = $component->brand_id ?? $solconBrand->id;

            $rawMaterial = RawMaterial::where('code', $component->code)
                ->where('department_id', $deptEPX->id)
                ->where('brand_id', $targetBrandId)
                ->first();

            if (!$rawMaterial) {
                $unitId = $component->unit_id ?? 3;
                $rawMaterial = RawMaterial::create([
                    'brand_id' => $targetBrandId,
                    'name' => $component->name,
                    'code' => $component->code,
                    'department_id' => $deptEPX->id,
                    'stock_unit_id' => $unitId,
                    'purchase_unit_id' => $unitId,
                    'purchase_conversion' => 1.0,
                    'opening_stock' => 0,
                    'current_stock' => 0,
                    'is_active' => $component->is_active,
                ]);
            }

            if ($component->raw_material_id !== $rawMaterial->id) {
                $component->update(['raw_material_id' => $rawMaterial->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No destructive reverse needed
    }
};
