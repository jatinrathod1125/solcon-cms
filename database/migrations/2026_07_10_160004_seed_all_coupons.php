<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\RawMaterial;
use App\Models\Department;
use App\Models\Unit;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $deptTAD = Department::where('code', 'TAD')->first();
        $unitPCS = Unit::where('code', 'PCS')->first();

        if (!$deptTAD || !$unitPCS) {
            return;
        }

        $denominations = [5, 10, 15, 20, 25, 30, 40, 50, 100, 200];

        foreach ($denominations as $val) {
            // Standard Solcon Coupon
            RawMaterial::updateOrCreate(
                ['code' => "COUPON-{$val}"],
                [
                    'name' => "₹{$val} Solcon Coupon",
                    'department_id' => $deptTAD->id,
                    'stock_unit_id' => $unitPCS->id,
                    'purchase_unit_id' => $unitPCS->id,
                    'purchase_conversion' => 1.0000,
                    'opening_stock' => 5000.0000,
                    'current_stock' => 5000.0000,
                    'minimum_stock' => 200.0000,
                    'maximum_stock' => 100000.0000,
                    'description' => "Standard ₹{$val} promotional coupon placed inside bag",
                    'is_active' => true,
                    'is_coupon' => true,
                ]
            );

            // Custom Party Coupon
            RawMaterial::updateOrCreate(
                ['code' => "CUSTOM-COUPON-{$val}"],
                [
                    'name' => "₹{$val} Custom Party Coupon",
                    'department_id' => $deptTAD->id,
                    'stock_unit_id' => $unitPCS->id,
                    'purchase_unit_id' => $unitPCS->id,
                    'purchase_conversion' => 1.0000,
                    'opening_stock' => 5000.0000,
                    'current_stock' => 5000.0000,
                    'minimum_stock' => 200.0000,
                    'maximum_stock' => 100000.0000,
                    'description' => "Custom party ₹{$val} promotional coupon placed inside bag",
                    'is_active' => true,
                    'is_coupon' => true,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $denominations = [5, 10, 15, 20, 25, 30, 40, 50, 100, 200];
        
        foreach ($denominations as $val) {
            RawMaterial::where('code', "COUPON-{$val}")->delete();
            RawMaterial::where('code', "CUSTOM-COUPON-{$val}")->delete();
        }
    }
};
