<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\FinishedGood;
use App\Models\MarketingOrderItem;
use App\Models\RawMaterial;
use Illuminate\Http\Request;

class ProductionPlanningController extends Controller
{
    /**
     * Display the Production Planning Dashboard.
     */
    public function index(Request $request)
    {
        $activeTab = $request->input('tab', 'without_coupon');

        // 1. Get 20 Rs Coupon Raw Material IDs
        $coupon20Ids = RawMaterial::where('is_coupon', true)
            ->where(function ($q) {
                $q->where('code', 'like', '%20%')
                  ->orWhere('name', 'like', '%20%');
            })
            ->pluck('id')
            ->toArray();

        if (empty($coupon20Ids)) {
            $coupon20Ids = RawMaterial::where('is_coupon', true)->pluck('id')->toArray();
        }

        // ==========================================
        // TAB 1: WITHOUT COUPON
        // ==========================================
        $gradesQuery = Grade::with('brand')->where(function ($q) {
            $q->whereHas('department', function ($dq) {
                $dq->where('code', 'TAD');
            })->orWhere('department_id', 1);
        });

        if (function_exists('currentBrand') && currentBrand()) {
            $gradesQuery->forBrand(currentBrand());
        }

        $allAdhesiveGrades = $gradesQuery->orderBy('name')->get();

        if ($allAdhesiveGrades->isEmpty()) {
            $fallback = Grade::with('brand')->where('is_active', true);
            if (function_exists('currentBrand') && currentBrand()) {
                $fallback->forBrand(currentBrand());
            }
            $allAdhesiveGrades = $fallback->orderBy('name')->get();
        }

        // Aggregate Stock Without Coupon
        $stockWithoutCoupon = FinishedGood::query()
            ->selectRaw('grade_id, SUM(available_bags) as total_available')
            ->whereNull('coupon_raw_material_id')
            ->where(function ($q) {
                $q->whereNull('packing')
                  ->orWhere(function ($pQ) {
                      $pQ->where('packing', 'not like', '%custom%')
                         ->where('packing', 'not like', '%print%');
                  });
            })
            ->whereNotNull('grade_id')
            ->groupBy('grade_id')
            ->pluck('total_available', 'grade_id');

        // Aggregate Pending Orders Without Coupon
        $pendingWithoutCoupon = MarketingOrderItem::query()
            ->selectRaw('grade_id, SUM(quantity_bags) as total_pending')
            ->whereHas('order', function ($q) {
                $q->whereNotNull('approved_by')
                  ->whereIn('status', ['pending', 'in_progress']);
            })
            ->where('department_code', 'TAD')
            ->where('item_status', '!=', 'completed')
            ->whereNull('coupon_raw_material_id')
            ->where(function ($q) {
                $q->whereNull('packing')
                  ->orWhere(function ($pQ) {
                      $pQ->where('packing', 'not like', '%custom%')
                         ->where('packing', 'not like', '%print%');
                  });
            })
            ->where(function ($q) {
                $q->whereNull('remarks')
                  ->orWhere(function ($rQ) {
                      $rQ->where('remarks', 'not like', '%custom%')
                         ->where('remarks', 'not like', '%print%');
                  });
            })
            ->whereNotNull('grade_id')
            ->groupBy('grade_id')
            ->pluck('total_pending', 'grade_id');

        $tab1Data = $allAdhesiveGrades->map(function ($grade) use ($stockWithoutCoupon, $pendingWithoutCoupon) {
            $available = (int) ($stockWithoutCoupon[$grade->id] ?? 0);
            $pending = (int) ($pendingWithoutCoupon[$grade->id] ?? 0);
            $needProduction = max(0, $pending - $available);

            return [
                'grade_id' => $grade->id,
                'grade_name' => $grade->name,
                'grade_code' => $grade->code,
                'brand_name' => $grade->brand?->name ?? null,
                'available_stock' => $available,
                'pending_orders' => $pending,
                'need_production' => $needProduction,
                'status' => $needProduction === 0 ? 'Enough Stock' : 'Production Required',
                'category' => 'without_coupon',
            ];
        });

        // ==========================================
        // TAB 2: 20 RS COUPON
        // ==========================================
        // Only display F-101 and F-107
        $tab2GradesQuery = Grade::with('brand')->where(function ($q) {
            $q->where('code', 'F-101')->orWhere('name', 'F-101')
              ->orWhere('code', 'F-107')->orWhere('name', 'F-107')
              ->orWhere('code', 'like', '%101%')->orWhere('code', 'like', '%107%');
        });

        if (function_exists('currentBrand') && currentBrand()) {
            $tab2GradesQuery->forBrand(currentBrand());
        }

        $tab2Grades = $tab2GradesQuery->get();

        // Ensure both F-101 and F-107 exist in collection even if absent in DB
        $neededCodes = ['F-101', 'F-107'];
        foreach ($neededCodes as $code) {
            $found = $tab2Grades->contains(fn($g) => stripos($g->code, $code) !== false || stripos($g->name, $code) !== false);
            if (!$found) {
                // Mock object if grade doesn't exist in DB yet
                $mockGrade = new Grade([
                    'id' => $code === 'F-101' ? 9901 : 9907,
                    'name' => $code,
                    'code' => $code,
                ]);
                $tab2Grades->push($mockGrade);
            }
        }

        // Aggregate Stock 20 Rs Coupon
        $stock20Coupon = FinishedGood::query()
            ->selectRaw('grade_id, SUM(available_bags) as total_available')
            ->whereIn('coupon_raw_material_id', $coupon20Ids)
            ->whereNotNull('grade_id')
            ->groupBy('grade_id')
            ->pluck('total_available', 'grade_id');

        // Aggregate Pending Orders 20 Rs Coupon
        $pending20Coupon = MarketingOrderItem::query()
            ->selectRaw('grade_id, SUM(quantity_bags) as total_pending')
            ->whereHas('order', function ($q) {
                $q->whereNotNull('approved_by')
                  ->whereIn('status', ['pending', 'in_progress']);
            })
            ->where('department_code', 'TAD')
            ->where('item_status', '!=', 'completed')
            ->whereIn('coupon_raw_material_id', $coupon20Ids)
            ->whereNotNull('grade_id')
            ->groupBy('grade_id')
            ->pluck('total_pending', 'grade_id');

        $tab2Data = $tab2Grades->map(function ($grade) use ($stock20Coupon, $pending20Coupon) {
            $available = (int) ($stock20Coupon[$grade->id] ?? 0);
            $pending = (int) ($pending20Coupon[$grade->id] ?? 0);
            $needProduction = max(0, $pending - $available);

            return [
                'grade_id' => $grade->id,
                'grade_name' => $grade->name,
                'grade_code' => $grade->code,
                'brand_name' => $grade->brand?->name ?? null,
                'available_stock' => $available,
                'pending_orders' => $pending,
                'need_production' => $needProduction,
                'status' => $needProduction === 0 ? 'Enough Stock' : 'Production Required',
                'category' => 'coupon_20',
            ];
        });

        // ==========================================
        // TAB 3: CUSTOM PRINTED
        // ==========================================
        $customPrintedItems = MarketingOrderItem::query()
            ->select('marketing_order_items.grade_id', 'marketing_orders.party_name')
            ->selectRaw('SUM(marketing_order_items.quantity_bags) as total_pending')
            ->join('marketing_orders', 'marketing_order_items.marketing_order_id', '=', 'marketing_orders.id')
            ->whereNotNull('marketing_orders.approved_by')
            ->whereIn('marketing_orders.status', ['pending', 'in_progress'])
            ->where('marketing_order_items.item_status', '!=', 'completed')
            ->where('marketing_order_items.department_code', 'TAD')
            ->where(function ($q) {
                $q->where('marketing_order_items.packing', 'like', '%custom%')
                  ->orWhere('marketing_order_items.packing', 'like', '%print%')
                  ->orWhere('marketing_order_items.remarks', 'like', '%custom%')
                  ->orWhere('marketing_order_items.remarks', 'like', '%print%')
                  ->orWhere('marketing_orders.remarks', 'like', '%custom%')
                  ->orWhere('marketing_orders.remarks', 'like', '%print%');
            })
            ->groupBy('marketing_order_items.grade_id', 'marketing_orders.party_name')
            ->with('grade')
            ->get();

        $tab3Data = $customPrintedItems->map(function ($item) {
            $grade = $item->grade;
            $gradeId = $item->grade_id;
            $partyName = $item->party_name;
            $pending = (int) $item->total_pending;

            $available = (int) FinishedGood::where('grade_id', $gradeId)
                ->where(function ($q) use ($partyName) {
                    $q->where('packing', 'like', "%{$partyName}%")
                      ->orWhere('remarks', 'like', "%{$partyName}%")
                      ->orWhere('packing', 'like', '%custom%');
                })->sum('available_bags');

            $needProduction = max(0, $pending - $available);

            return [
                'grade_id' => $gradeId,
                'grade_name' => $grade ? $grade->name : 'Custom Grade',
                'grade_code' => $grade ? $grade->code : 'CP',
                'customer_name' => $partyName,
                'available_stock' => $available,
                'pending_orders' => $pending,
                'need_production' => $needProduction,
                'status' => $needProduction === 0 ? 'Enough Stock' : 'Production Required',
                'category' => 'custom_printed',
            ];
        });

        return view('production.planning', compact(
            'activeTab',
            'tab1Data',
            'tab2Data',
            'tab3Data'
        ));
    }

    /**
     * Get pending orders list for the Drawer AJAX modal.
     */
    public function getOrders(Request $request)
    {
        $gradeId = $request->input('grade_id');
        $category = $request->input('category'); // without_coupon, coupon_20, custom_printed
        $customerName = $request->input('customer_name');

        $query = MarketingOrderItem::query()
            ->with(['order', 'grade', 'couponMaterial'])
            ->whereHas('order', function ($q) {
                $q->whereNotNull('approved_by')
                  ->whereIn('status', ['pending', 'in_progress']);
            })
            ->where('department_code', 'TAD')
            ->where('item_status', '!=', 'completed');

        if ($gradeId && $gradeId < 9900) {
            $query->where('grade_id', $gradeId);
        }

        if ($category === 'without_coupon') {
            $query->whereNull('coupon_raw_material_id')
                  ->where(function ($q) {
                      $q->whereNull('packing')
                        ->orWhere(function ($pQ) {
                            $pQ->where('packing', 'not like', '%custom%')
                               ->where('packing', 'not like', '%print%');
                        });
                  });
        } elseif ($category === 'coupon_20') {
            $coupon20Ids = RawMaterial::where('is_coupon', true)
                ->where(function ($q) {
                    $q->where('code', 'like', '%20%')
                      ->orWhere('name', 'like', '%20%');
                })
                ->pluck('id')
                ->toArray();
            if (empty($coupon20Ids)) {
                $coupon20Ids = RawMaterial::where('is_coupon', true)->pluck('id')->toArray();
            }
            $query->whereIn('coupon_raw_material_id', $coupon20Ids);
        } elseif ($category === 'custom_printed') {
            if ($customerName) {
                $query->whereHas('order', function ($q) use ($customerName) {
                    $q->where('party_name', $customerName);
                });
            }
            $query->where(function ($q) {
                $q->where('packing', 'like', '%custom%')
                  ->orWhere('packing', 'like', '%print%')
                  ->orWhere('remarks', 'like', '%custom%')
                  ->orWhere('remarks', 'like', '%print%')
                  ->orWhereHas('order', function ($oQ) {
                      $oQ->where('remarks', 'like', '%custom%')
                        ->orWhere('remarks', 'like', '%print%');
                  });
            });
        }

        $items = $query->orderBy('id', 'desc')->get();

        $formattedOrders = $items->map(function ($item) {
            return [
                'order_number' => $item->order->order_number,
                'customer' => $item->order->party_name,
                'grade' => $item->grade ? ($item->grade->name . ' (' . $item->grade->code . ')') : 'N/A',
                'quantity' => $item->quantity_bags . ' Bags',
                'dispatch_date' => $item->order->order_date ? $item->order->order_date->format('d M Y') : 'Immediate',
                'pending_bags' => $item->quantity_bags . ' Bags',
                'coupon' => $item->coupon_name,
                'priority' => ucfirst($item->order->priority),
            ];
        });

        $grade = Grade::find($gradeId);

        return response()->json([
            'success' => true,
            'grade' => $grade ? ($grade->name . ' (' . $grade->code . ')') : 'Target Grade',
            'category' => match($category) {
                'without_coupon' => 'Without Coupon',
                'coupon_20' => '20 Rs Coupon',
                'custom_printed' => 'Custom Printed',
                default => 'All Categories'
            },
            'customer_name' => $customerName,
            'orders' => $formattedOrders,
        ]);
    }
}
