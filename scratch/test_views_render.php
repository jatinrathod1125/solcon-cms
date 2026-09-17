<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProductionBatch;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$admin = User::first();
Auth::login($admin);

$batch = ProductionBatch::with(['grade.brand', 'grade.bagSize', 'machine.department', 'supervisor'])->first();

echo "Testing views rendering for batch #{$batch->batch_no}...\n";

// Test 1: running view
try {
    $coupons = \App\Models\RawMaterial::where('is_coupon', true)->where('is_active', true)->orderBy('name')->get();
    $htmlRunning = view('production.running', compact('batch', 'coupons'))->render();
    echo "✔ production.running rendered successfully (" . strlen($htmlRunning) . " bytes)\n";
} catch (\Exception $e) {
    echo "❌ production.running failed: " . $e->getMessage() . "\n";
}

// Test 2: complete view
try {
    $compatibleGrades = $batch->grade ? $batch->grade->getCompatibleGrades() : [];
    $availableCoupons = \App\Models\RawMaterial::where('is_coupon', true)->where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']);
    $htmlComplete = view('production.complete', compact('batch', 'compatibleGrades', 'availableCoupons'))->render();
    echo "✔ production.complete rendered successfully (" . strlen($htmlComplete) . " bytes)\n";
} catch (\Exception $e) {
    echo "❌ production.complete failed: " . $e->getMessage() . "\n";
}

// Test 3: show view with breakdown
try {
    $batch->output_breakdown = [
        [
            'grade_id' => 1,
            'grade_name' => 'F-101',
            'grade_code' => 'F-101',
            'brand_name' => 'Solcon',
            'packing_material_name' => 'F101 Bag',
            'coupon_name' => 'RS-20 Solcon',
            'bags' => 20,
            'kg' => 400.0,
        ],
        [
            'grade_id' => 9,
            'grade_name' => 'FX-01',
            'grade_code' => 'FX-01',
            'brand_name' => 'Fixora',
            'packing_material_name' => 'FX-01 BAG',
            'coupon_name' => null,
            'bags' => 80,
            'kg' => 1600.0,
        ]
    ];
    $htmlShow = view('production.show', compact('batch'))->render();
    echo "✔ production.show rendered successfully (" . strlen($htmlShow) . " bytes)\n";
} catch (\Exception $e) {
    echo "❌ production.show failed: " . $e->getMessage() . "\n";
}
