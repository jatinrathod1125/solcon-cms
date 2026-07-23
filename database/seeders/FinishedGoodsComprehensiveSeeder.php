<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Color;
use App\Models\EpoxyProduct;
use App\Models\EpoxyComponent;
use App\Models\EpoxyFillerColor;
use App\Models\RawMaterial;
use App\Models\FinishedGood;
use Illuminate\Support\Facades\DB;

class FinishedGoodsComprehensiveSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        FinishedGood::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $deptTAD = Department::where('code', 'TAD')->first();
        $deptGRT = Department::where('code', 'GRT')->first();
        $deptEPX = Department::where('code', 'EPX')->first();

        // 1. TAD (Tile Adhesive)
        if ($deptTAD) {
            $grades = Grade::where('is_active', true)->get();
            $coupons = RawMaterial::where('is_coupon', true)->get();
            $packings = ['20 KG', '40 KG', '50 KG'];

            foreach ($grades as $grade) {
                foreach ($packings as $pkg) {
                    // Without Coupon
                    FinishedGood::create([
                        'department_id' => $deptTAD->id,
                        'grade_id' => $grade->id,
                        'coupon_raw_material_id' => null,
                        'packing' => $pkg,
                        'available_bags' => 500,
                        'available_weight' => 500 * (float)(preg_match('/(\d+)/', $pkg, $m) ? $m[1] : 20),
                        'minimum_stock' => 50,
                        'status' => 'active',
                    ]);

                    // Top Coupon Denominations
                    foreach ($coupons->take(2) as $cpn) {
                        FinishedGood::create([
                            'department_id' => $deptTAD->id,
                            'grade_id' => $grade->id,
                            'coupon_raw_material_id' => $cpn->id,
                            'packing' => $pkg,
                            'available_bags' => 500,
                            'available_weight' => 500 * (float)(preg_match('/(\d+)/', $pkg, $m) ? $m[1] : 20),
                            'minimum_stock' => 50,
                            'status' => 'active',
                        ]);
                    }
                }
            }
        }

        // 2. GRT (Grout) - 500 GM is 0 stock (on-demand)
        if ($deptGRT) {
            $colors = Color::where('is_active', true)->get();
            $packings = ['1 KG', '5 KG', '20 KG', '25 KG', '500 GM'];

            foreach ($colors as $color) {
                foreach ($packings as $pkg) {
                    $is500gm = ($pkg === '500 GM');
                    FinishedGood::create([
                        'department_id' => $deptGRT->id,
                        'color_id' => $color->id,
                        'packing' => $pkg,
                        'available_bags' => $is500gm ? 0 : 500,
                        'available_weight' => $is500gm ? 0.0000 : (500 * (float)(preg_match('/(\d+)/', $pkg, $m) ? $m[1] : 1)),
                        'minimum_stock' => 50,
                        'status' => 'active',
                    ]);
                }
            }
        }

        // 3. EPX (Epoxy Products & Buckets)
        if ($deptEPX) {
            $epoxyProds = EpoxyProduct::all();
            $epoxyColors = EpoxyFillerColor::where('is_active', true)->get();

            foreach ($epoxyProds as $ep) {
                if (in_array($ep->code, ['1B', '5B', 'SOL'])) {
                    $pkgs = ($ep->code === 'SOL') ? ['1.8KG', '900 GM', '450 GM'] : [($ep->code === '1B' ? '1KG' : '5KG')];
                    foreach ($epoxyColors as $color) {
                        foreach ($pkgs as $pkg) {
                            FinishedGood::create([
                                'department_id' => $deptEPX->id,
                                'epoxy_product_id' => $ep->id,
                                'epoxy_filler_color_id' => $color->id,
                                'packing' => $pkg,
                                'available_bags' => 500,
                                'available_weight' => 500 * (float)(preg_match('/(\d+)/', $pkg, $m) ? $m[1] : 1),
                                'minimum_stock' => 50,
                                'status' => 'active',
                            ]);
                        }
                    }
                } else {
                    // Products without Filler Color
                    $isOnDemand = ($ep->code === 'RK');
                    $pkgs = match ($ep->code) {
                        'RK' => ['0.3KG', '1.5KG'],
                        'TC' => ['1-LTR', '5-LTR'],
                        'GA' => ['200GM'],
                        'SP' => ['2MM', '3MM', '4MM', '5MM', '6MM', '1BOX (50 PCS)'],
                        'TL' => ['CLIP 2MM', 'CLIP 3MM', 'CLIP 4MM', 'WEDGE', 'LEVELLING JACK SPACER', 'PLASTIC TROWEL', 'STEEL TROWEL', 'PLIER', 'VACUUM'],
                        default => ['1KG', '5KG'],
                    };

                    foreach ($pkgs as $pkg) {
                        FinishedGood::create([
                            'department_id' => $deptEPX->id,
                            'epoxy_product_id' => $ep->id,
                            'packing' => $pkg,
                            'available_bags' => $isOnDemand ? 0 : 500,
                            'available_weight' => $isOnDemand ? 0.0000 : 500.0000,
                            'minimum_stock' => 50,
                            'status' => 'active',
                        ]);
                    }
                }
            }

            // 4. Epoxy Components (Jari Powders stock = 500, SB+/SB++/SK+ stock = 0 on-demand)
            $allComponents = EpoxyComponent::where('is_active', true)->get();
            foreach ($allComponents as $comp) {
                $isCompOnDemand = str_contains($comp->code, 'SBP') || str_contains($comp->code, 'SBPP') || str_contains($comp->code, 'SKP');
                $pkg = str_contains($comp->code, 'JARI') ? 'Pckt' : 'Box';

                FinishedGood::create([
                    'department_id' => $deptEPX->id,
                    'epoxy_component_id' => $comp->id,
                    'packing' => $pkg,
                    'available_bags' => $isCompOnDemand ? 0 : 500,
                    'available_weight' => $isCompOnDemand ? 0.0000 : 500.0000,
                    'minimum_stock' => 50,
                    'status' => 'active',
                ]);
            }
        }
    }
}
