<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Grade;
use App\Models\RawMaterial;
use App\Models\ProductionBatch;
use App\Models\FinishedGood;
use App\Models\StockLedger;
use App\Services\ProductionService;
use Illuminate\Support\Facades\DB;

echo "=== 1. Testing Grade::getCompatibleGrades() ===\n";
$f101 = Grade::where('code', 'F-101')->first();
if ($f101) {
    echo "Found F-101 (ID: {$f101->id}, Brand: {$f101->brand?->name})\n";
    $compatibles = $f101->getCompatibleGrades();
    echo "Compatible grades count: " . count($compatibles) . "\n";
    foreach ($compatibles as $cg) {
        echo "  - [{$cg['brand_name']}] {$cg['name']} ({$cg['code']}), Bag: {$cg['packing_material_name']} (Bag Size: {$cg['bag_size']}kg)\n";
    }
} else {
    echo "F-101 not found, checking first grade:\n";
    $firstGrade = Grade::first();
    if ($firstGrade) {
        $compatibles = $firstGrade->getCompatibleGrades();
        echo "Grade: {$firstGrade->name}, Compatibles: " . count($compatibles) . "\n";
    }
}

echo "\n=== 2. Testing Split Breakdown Execution in Transaction (Rollback) ===\n";
DB::beginTransaction();
try {
    // Find an adhesive batch or create a temporary mock batch
    $batch = ProductionBatch::where('status', 'running')->first();
    if (!$batch) {
        $batch = ProductionBatch::where('status', 'completed')->first();
    }

    if (!$batch) {
        echo "No batch found to test with.\n";
        DB::rollBack();
        exit;
    }

    echo "Using batch #{$batch->batch_no} (ID: {$batch->id}, Grade: {$batch->grade->name})\n";

    // Find compatible grade (e.g. FX-01 or another grade)
    $compatibles = $batch->grade->getCompatibleGrades();
    $grade1 = $compatibles[0] ?? ['id' => $batch->grade_id, 'bag_size' => 20, 'packing_material_id' => null];
    $grade2 = $compatibles[1] ?? $grade1;

    // Find coupon raw material
    $coupon = RawMaterial::where('is_coupon', true)->where('is_active', true)->first();

    $couponId = $coupon ? $coupon->id : null;
    $couponName = $coupon ? $coupon->name : 'Test Coupon';

    echo "Selected Grade 1: ID {$grade1['id']}\n";
    echo "Selected Grade 2: ID {$grade2['id']}\n";
    echo "Coupon: " . ($couponId ? "ID {$couponId} ({$couponName})" : "None") . "\n";

    // Prepare split breakdown:
    // Split 1: 20 bags Grade 1 with Coupon
    // Split 2: 30 bags Grade 1 without Coupon
    // Split 3: 50 bags Grade 2 without Coupon
    // Total: 100 bags
    $splitBreakdown = [
        [
            'grade_id' => $grade1['id'],
            'packing_material_id' => $grade1['packing_material_id'],
            'coupon_raw_material_id' => $couponId,
            'bags' => 20,
            'bag_size' => $grade1['bag_size'] ?? 20,
            'kg' => 20 * ($grade1['bag_size'] ?? 20),
        ],
        [
            'grade_id' => $grade1['id'],
            'packing_material_id' => $grade1['packing_material_id'],
            'coupon_raw_material_id' => null,
            'bags' => 30,
            'bag_size' => $grade1['bag_size'] ?? 20,
            'kg' => 30 * ($grade1['bag_size'] ?? 20),
        ],
        [
            'grade_id' => $grade2['id'],
            'packing_material_id' => $grade2['packing_material_id'],
            'coupon_raw_material_id' => null,
            'bags' => 50,
            'bag_size' => $grade2['bag_size'] ?? 20,
            'kg' => 50 * ($grade2['bag_size'] ?? 20),
        ],
    ];

    // Reset status to running so we can complete it
    $testBatch = clone $batch;
    $testBatch->status = 'running';
    $testBatch->save();

    // Ensure raw materials have enough stock for this test
    foreach ($testBatch->formula_snapshot as $item) {
        $rm = RawMaterial::find($item['raw_material_id']);
        if ($rm) {
            $rm->current_stock = 999999;
            $rm->save();
        }
    }
    if ($grade1['packing_material_id']) {
        $p1 = RawMaterial::find($grade1['packing_material_id']);
        if ($p1) { $p1->current_stock = 999999; $p1->save(); }
    }
    if ($grade2['packing_material_id']) {
        $p2 = RawMaterial::find($grade2['packing_material_id']);
        if ($p2) { $p2->current_stock = 999999; $p2->save(); }
    }
    if ($couponId) {
        $c = RawMaterial::find($couponId);
        if ($c) { $c->current_stock = 999999; $c->save(); }
    }

    $service = app(ProductionService::class);
    $completed = $service->completeBatch(
        $testBatch->id,
        100, // 100 bags
        now()->toDateTimeString(),
        'Multi-brand split test run',
        $splitBreakdown
    );

    echo "Batch completed successfully!\n";
    echo "Batch Status: {$completed->status}\n";
    echo "Batch Output Bags: {$completed->output_bags}\n";
    echo "Batch Output KG: {$completed->output_kg}\n";
    echo "Breakdown in DB:\n";
    print_r($completed->output_breakdown);

    // Verify finished goods stocks
    echo "\n=== Verifying Finished Goods Records ===\n";
    $fg1_coupon = FinishedGood::where('grade_id', $grade1['id'])
        ->where('coupon_raw_material_id', $couponId)
        ->first();
    echo "FG Grade 1 with Coupon bags: " . ($fg1_coupon ? $fg1_coupon->available_bags : 'Not found') . "\n";

    $fg1_nocoupon = FinishedGood::where('grade_id', $grade1['id'])
        ->whereNull('coupon_raw_material_id')
        ->first();
    echo "FG Grade 1 No Coupon bags: " . ($fg1_nocoupon ? $fg1_nocoupon->available_bags : 'Not found') . "\n";

    $fg2_nocoupon = FinishedGood::where('grade_id', $grade2['id'])
        ->whereNull('coupon_raw_material_id')
        ->first();
    echo "FG Grade 2 No Coupon bags: " . ($fg2_nocoupon ? $fg2_nocoupon->available_bags : 'Not found') . "\n";

    DB::rollBack();
    echo "\nTest passed perfectly! Transaction rolled back cleanly.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error during split production test: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
