<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RawMaterial;
use App\Models\Department;
use App\Models\Unit;
use App\Models\User;
use App\Services\MarketingOrderService;
use Illuminate\Support\Facades\View;

echo "=== Testing Raw Material is_coupon Functionality ===\n";

$dept = Department::first();
$unit = Unit::first();

if (!$dept || !$unit) {
    echo "Department or Unit missing, cannot run test.\n";
    exit(1);
}

// 1. Create a raw material with is_coupon = 1
$testCode = 'TEST-COUP-' . rand(100, 999);
$material = RawMaterial::create([
    'code' => $testCode,
    'name' => 'Test Coupon Token',
    'department_id' => $dept->id,
    'stock_unit_id' => $unit->id,
    'purchase_unit_id' => $unit->id,
    'purchase_conversion' => 1,
    'opening_stock' => 50,
    'current_stock' => 50,
    'minimum_stock' => 10,
    'maximum_stock' => 500,
    'is_active' => true,
    'is_coupon' => true,
    'description' => 'Test coupon token for promo verification'
]);

echo "Created material ID: {$material->id}, is_coupon: " . ($material->is_coupon ? 'true' : 'false') . "\n";
assert($material->is_coupon === true, "is_coupon should be true");

// 2. Test MarketingOrderService::getAvailableCoupons
$service = app(MarketingOrderService::class);
$coupons = $service->getAvailableCoupons();
$found = $coupons->contains('id', $material->id);
echo "MarketingOrderService contains new coupon: " . ($found ? "YES" : "NO") . "\n";
assert($found, "MarketingOrderService should include the new coupon");

// 3. Test Update to false
$material->update(['is_coupon' => false]);
$material->refresh();
echo "Updated material is_coupon to: " . ($material->is_coupon ? 'true' : 'false') . "\n";
assert($material->is_coupon === false, "is_coupon should be false now");

$couponsAfter = $service->getAvailableCoupons();
$foundAfter = $couponsAfter->contains('id', $material->id);
echo "MarketingOrderService contains material when is_coupon=false: " . ($foundAfter ? "YES" : "NO (Correct)") . "\n";
assert(!$foundAfter, "MarketingOrderService should NOT include the material when is_coupon is false");

// 4. Test View rendering
$admin = User::first();
if ($admin) {
    auth()->login($admin);
}

View::share('errors', new \Illuminate\Support\ViewErrorBag());
try {
    $createHtml = View::make('admin.raw_materials.create', [
        'departments' => Department::getActive(),
        'units' => Unit::getActive(),
        'brands' => \App\Models\Brand::active()->get(),
    ])->render();
    echo "admin.raw_materials.create rendered successfully. Contains 'is_coupon': " . (str_contains($createHtml, 'name="is_coupon"') ? "YES" : "NO") . "\n";

    $editHtml = View::make('admin.raw_materials.edit', [
        'rawMaterial' => $material,
        'departments' => Department::getActive(),
        'units' => Unit::getActive(),
        'brands' => \App\Models\Brand::active()->get(),
    ])->render();
    echo "admin.raw_materials.edit rendered successfully. Contains 'is_coupon': " . (str_contains($editHtml, 'name="is_coupon"') ? "YES" : "NO") . "\n";

    $indexHtml = View::make('admin.raw_materials.index', [
        'rawMaterials' => RawMaterial::latest()->paginate(10),
        'departments' => Department::getActive(),
    ])->render();
    echo "admin.raw_materials.index rendered successfully. Contains 'is_coupon': " . (str_contains($indexHtml, 'name="is_coupon"') ? "YES" : "NO") . "\n";
} catch (\Exception $e) {
    echo "View render error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

// Clean up test material
$material->delete();
echo "Cleaned up test material.\n";
echo "=== ALL CHECKS PASSED ===\n";
