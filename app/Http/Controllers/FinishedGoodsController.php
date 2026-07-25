<?php

namespace App\Http\Controllers;

use App\Models\FinishedGood;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Color;
use App\Models\EpoxyProduct;
use App\Models\RawMaterial;
use App\Services\FinishedGoodsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FinishedGoodsController extends Controller
{
    protected $finishedGoodsService;

    public function __construct(FinishedGoodsService $finishedGoodsService)
    {
        $this->finishedGoodsService = $finishedGoodsService;
    }

    /**
     * List Finished Goods Inventory.
     */
    public function index(Request $request)
    {
        $query = FinishedGood::with(['department', 'grade', 'color', 'epoxyProduct', 'couponMaterial']);

        // Search by Product Name
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                // Search Grade name
                $q->whereHas('grade', function ($g) use ($search) {
                    $g->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
                })
                    // Search Color name
                    ->orWhereHas('color', function ($c) use ($search) {
                        $c->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
                    })
                    // Search Epoxy Product name
                    ->orWhereHas('epoxyProduct', function ($e) use ($search) {
                        $e->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
                    });
            });
        }

        // Filters
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        if ($request->filled('packing')) {
            $query->where('packing', 'like', '%' . $request->input('packing') . '%');
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'low_stock') {
                $query->whereRaw('available_bags <= minimum_stock')->where('available_bags', '>', 0);
            } elseif ($status === 'out_of_stock') {
                $query->where('available_bags', '<=', 0);
            } elseif ($status === 'active') {
                $query->whereRaw('available_bags > minimum_stock');
            }
        }

        // Sorting
        $sortField = $request->input('sort', 'updated_at');
        $sortOrder = $request->input('order', 'desc');

        $allowedSorts = ['available_bags', 'available_weight', 'minimum_stock', 'status', 'updated_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('updated_at', 'desc');
        }

        $items = $query->paginate(20)->withQueryString();

        if ($request->ajax()) {
            return view('finished_goods._table', compact('items'))->render();
        }

        $departments = Department::orderBy('name')->get();
        $grades = Grade::with('bagSize')->where('is_active', true)->orderBy('name')->get();
        $colors = Color::where('is_active', true)->orderBy('name')->get();
        $epoxyProducts = EpoxyProduct::where('is_active', true)->orderBy('name')->get();
        $couponMaterials = RawMaterial::where('is_coupon', true)->where('is_active', true)->orderBy('name')->get();

        // Get unique packing sizes for filter dropdown
        $packingSizes = FinishedGood::distinct()->pluck('packing')->filter()->values();

        return view('finished_goods.index', compact('items', 'departments', 'grades', 'colors', 'epoxyProducts', 'couponMaterials', 'packingSizes'));
    }

    /**
     * Store a newly created Finished Good (Admin only).
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action. Only administrators can manually create finished goods.');
        }

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'grade_id' => 'nullable|exists:grades,id',
            'color_id' => 'nullable|exists:colors,id',
            'epoxy_product_id' => 'nullable|exists:epoxy_products,id',
            'coupon_raw_material_id' => 'nullable|exists:raw_materials,id',
            'packing' => 'required|string|max:100',
            'available_bags' => 'required|integer|min:0',
            'available_weight' => 'nullable|numeric|min:0',
            'minimum_stock' => 'nullable|integer|min:0',
            'remarks' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $dept = Department::findOrFail($validated['department_id']);
            $deptCode = strtoupper($dept->code);

            $gradeId = null;
            $colorId = null;
            $epoxyProductId = null;

            if ($deptCode === 'GRT') {
                $colorId = $validated['color_id'] ?? null;
            } elseif ($deptCode === 'EPX' || $deptCode === 'EP') {
                $epoxyProductId = $validated['epoxy_product_id'] ?? null;
            } else {
                $gradeId = $validated['grade_id'] ?? null;
            }

            $bags = (int) $validated['available_bags'];
            $weight = $validated['available_weight'] !== null ? (float) $validated['available_weight'] : null;

            if ($weight === null) {
                $unitWeight = 1.0;
                if (preg_match('/(\d+(?:\.\d+)?)\s*kg/i', $validated['packing'], $matches)) {
                    $unitWeight = (float) $matches[1];
                } elseif ($gradeId) {
                    $g = Grade::find($gradeId);
                    if ($g && $g->bagSize) {
                        $unitWeight = (float) $g->bagSize->value;
                    }
                }
                $weight = $bags * $unitWeight;
            }

            $couponId = $validated['coupon_raw_material_id'] ?? null;
            $packing = trim($validated['packing']);
            $minStock = isset($validated['minimum_stock']) ? (int) $validated['minimum_stock'] : 20;

            // Check if FinishedGood with exact combination already exists
            $existingFG = FinishedGood::where('department_id', $dept->id)
                ->where('grade_id', $gradeId)
                ->where('color_id', $colorId)
                ->where('epoxy_product_id', $epoxyProductId)
                ->where('coupon_raw_material_id', $couponId)
                ->where('packing', $packing)
                ->lockForUpdate()
                ->first();

            if ($existingFG) {
                $newBags = $existingFG->available_bags + $bags;
                $newWeight = $existingFG->available_weight + $weight;
                $status = ($newBags <= 0) ? 'out_of_stock' : (($newBags <= $minStock) ? 'low_stock' : 'active');

                $existingFG->update([
                    'available_bags' => $newBags,
                    'available_weight' => $newWeight,
                    'minimum_stock' => $minStock,
                    'status' => $status,
                    'remarks' => $validated['remarks'] ?: $existingFG->remarks,
                ]);
                $finishedGood = $existingFG;
            } else {
                $status = ($bags <= 0) ? 'out_of_stock' : (($bags <= $minStock) ? 'low_stock' : 'active');

                $finishedGood = FinishedGood::create([
                    'department_id' => $dept->id,
                    'grade_id' => $gradeId,
                    'color_id' => $colorId,
                    'epoxy_product_id' => $epoxyProductId,
                    'coupon_raw_material_id' => $couponId,
                    'packing' => $packing,
                    'available_bags' => $bags,
                    'available_weight' => $weight,
                    'minimum_stock' => $minStock,
                    'last_production_date' => now(),
                    'status' => $status,
                    'remarks' => $validated['remarks'] ?? null,
                ]);
            }

            DB::commit();

            \App\Services\ActivityLogService::log(
                'FINISHED_GOOD_CREATED',
                "Finished Good '{$finishedGood->product_name}' ({$finishedGood->packing}) created manually by admin with {$bags} units.",
                auth()->id()
            );

            return redirect()->route('finished-goods.index')
                ->with('success', "Finished Good '{$finishedGood->product_name}' created successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create Finished Good: ' . $e->getMessage());
        }
    }

    /**
     * Perform a manual stock adjustment.
     */
    public function adjust(Request $request, FinishedGood $finishedGood)
    {
        $validated = $request->validate([
            'type' => 'required|in:increase,decrease',
            'quantity' => 'required|integer|min:1',
            'weight' => 'nullable|numeric|min:0.0001',
            'reason' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        try {
            $this->finishedGoodsService->adjustStock(
                $finishedGood->id,
                $validated['type'],
                $validated['quantity'],
                $validated['weight'] ?? null,
                $validated['reason'],
                $validated['remarks'] ?? null
            );

            return redirect()->route('finished-goods.index')
                ->with('success', 'Manual stock adjustment logged successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Adjustment failed: ' . $e->getMessage());
        }
    }

    /**
     * Export Finished Goods to CSV (Excel compatible).
     */
    public function export()
    {
        $items = FinishedGood::with(['department', 'grade', 'color', 'epoxyProduct', 'couponMaterial'])->get();
        $csvFileName = 'finished_goods_inventory_' . now()->format('Ymd_His') . '.csv';

        // Log export activity
        \App\Services\ActivityLogService::log(
            'FINISHED_GOODS_EXPORTED',
            "Finished goods inventory CSV exported containing " . $items->count() . " records.",
            auth()->id()
        );

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Department', 'Product', 'Packing', 'Quantity', 'Status'];

        $callback = function () use ($items, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->department->name,
                    $item->product_name,
                    $item->packing,
                    $item->available_bags,
                    $item->formatted_status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import Finished Goods from CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:4096',
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle);

        if (!$header || count($header) < 4) {
            fclose($handle);
            return redirect()->back()->with('error', 'Invalid CSV format. Must contain at least Department, Product, Packing, Quantity.');
        }

        // Clean headers
        $header = array_map(fn($h) => strtolower(trim(str_replace("\u{FEFF}", '', $h))), $header);

        $colMap = [
            'department' => array_search('department', $header),
            'product' => array_search('product', $header),
            'packing' => array_search('packing', $header),
            'quantity' => array_search('quantity', $header),
            'status' => array_search('status', $header),
        ];

        if ($colMap['department'] === false || $colMap['product'] === false || $colMap['packing'] === false || $colMap['quantity'] === false) {
            fclose($handle);
            return redirect()->back()->with('error', 'Missing required CSV headers. Expected: Department, Product, Packing, Quantity.');
        }

        $importedCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                if (empty(array_filter($row)))
                    continue; // Skip empty rows

                $deptVal = trim($row[$colMap['department']]);
                $prodVal = trim($row[$colMap['product']]);
                $packingVal = trim($row[$colMap['packing']]);
                $qtyVal = (int) trim($row[$colMap['quantity']]);

                // Find Department
                $dept = Department::where('code', $deptVal)->orWhere('name', $deptVal)->first();
                if (!$dept) {
                    $errors[] = "Row " . ($importedCount + 2) . ": Department '{$deptVal}' not found.";
                    continue;
                }

                $gradeId = null;
                $colorId = null;
                $epoxyProductId = null;
                $couponRawMaterialId = null;
                $defaultBagWeight = 1.0;

                $deptCode = strtoupper($dept->code);
                // Match product depending on department
                if ($deptCode === 'TAD') {
                    $gradeSearchName = $prodVal;
                    $couponName = null;
                    if (preg_match('/([^(]+)\s*\(([^)]+)\)/', $prodVal, $matches)) {
                        $gradeSearchName = trim($matches[1]);
                        $couponName = trim($matches[2]);
                    }

                    $grade = Grade::where('code', $gradeSearchName)->orWhere('name', $gradeSearchName)->first();
                    if (!$grade) {
                        $errors[] = "Row " . ($importedCount + 2) . ": Product Grade '{$prodVal}' not found.";
                        continue;
                    }
                    $gradeId = $grade->id;
                    if ($grade->bagSize) {
                        $defaultBagWeight = (float) $grade->bagSize->value;
                    }

                    if ($couponName && strtolower($couponName) !== 'no coupon') {
                        $coupon = RawMaterial::where('is_coupon', true)
                            ->where(function($q) use ($couponName) {
                                $q->where('code', $couponName)->orWhere('name', $couponName);
                            })
                            ->first();
                        if ($coupon) {
                            $couponRawMaterialId = $coupon->id;
                        }
                    }
                } elseif ($deptCode === 'GRT') {
                    $color = Color::where('code', $prodVal)->orWhere('name', $prodVal)->first();
                    if (!$color) {
                        $errors[] = "Row " . ($importedCount + 2) . ": Color '{$prodVal}' not found.";
                        continue;
                    }
                    $colorId = $color->id;
                    $defaultBagWeight = 20.0;
                    $packingLower = strtolower($packingVal);
                    if (preg_match('/(\d+)\s*kg/', $packingLower, $matches)) {
                        $defaultBagWeight = (float) $matches[1];
                    }
                } elseif ($deptCode === 'EPX' || $deptCode === 'EP') {
                    // Epoxy could be Product Name, sometimes with Color specified. Let's parse.
                    $colorName = null;
                    $prodSearchName = $prodVal;
                    if (preg_match('/([^(]+)\s*\(([^)]+)\)/', $prodVal, $matches)) {
                        $prodSearchName = trim($matches[1]);
                        $colorName = trim($matches[2]);
                    }

                    $epoxyProduct = EpoxyProduct::where('code', $prodSearchName)->orWhere('name', $prodSearchName)->first();
                    if (!$epoxyProduct) {
                        $errors[] = "Row " . ($importedCount + 2) . ": Epoxy Product '{$prodSearchName}' not found.";
                        continue;
                    }
                    $epoxyProductId = $epoxyProduct->id;

                    if ($colorName) {
                        $color = Color::where('code', $colorName)->orWhere('name', $colorName)->first();
                        if ($color) {
                            $colorId = $color->id;
                        }
                    }

                    // Estimate Epoxy Weight
                    $codeUpper = strtoupper($epoxyProduct->code);
                    if (str_contains($codeUpper, '5K') || str_contains($codeUpper, '5KG')) {
                        $defaultBagWeight = 5.0;
                    } elseif (str_contains($codeUpper, '1K') || str_contains($codeUpper, '1KG')) {
                        $defaultBagWeight = 1.0;
                    } elseif (str_contains($codeUpper, 'FIL') || str_contains($codeUpper, 'FILLER')) {
                        $defaultBagWeight = 0.7;
                    }
                }

                // Check or create finished good
                $finishedGood = FinishedGood::where('department_id', $dept->id)
                    ->where('grade_id', $gradeId)
                    ->where('color_id', $colorId)
                    ->where('epoxy_product_id', $epoxyProductId)
                    ->where('packing', $packingVal)
                    ->where('coupon_raw_material_id', $couponRawMaterialId)
                    ->lockForUpdate()
                    ->first();

                $calculatedWeight = $qtyVal * $defaultBagWeight;

                // Helper to determine status
                $minimum = $finishedGood ? $finishedGood->minimum_stock : 20;
                $status = 'active';
                if ($qtyVal <= 0) {
                    $status = 'out_of_stock';
                } elseif ($qtyVal <= $minimum) {
                    $status = 'low_stock';
                }

                if ($finishedGood) {
                    $finishedGood->update([
                        'available_bags' => $qtyVal,
                        'available_weight' => $calculatedWeight,
                        'status' => $status,
                        'remarks' => 'Imported via CSV',
                    ]);
                } else {
                    FinishedGood::create([
                        'department_id' => $dept->id,
                        'grade_id' => $gradeId,
                        'color_id' => $colorId,
                        'epoxy_product_id' => $epoxyProductId,
                        'coupon_raw_material_id' => $couponRawMaterialId,
                        'packing' => $packingVal,
                        'available_bags' => $qtyVal,
                        'available_weight' => $calculatedWeight,
                        'minimum_stock' => 20,
                        'status' => $status,
                        'remarks' => 'Imported via CSV',
                    ]);
                }

                $importedCount++;
            }

            fclose($handle);

            if (!empty($errors)) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Import aborted due to validation errors:<br>' . implode('<br>', array_slice($errors, 0, 5)));
            }

            DB::commit();

            \App\Services\ActivityLogService::log(
                'FINISHED_GOODS_IMPORTED',
                "Imported finished goods inventory successfully from CSV containing {$importedCount} records.",
                auth()->id()
            );

            return redirect()->route('finished-goods.index')
                ->with('success', "Imported {$importedCount} finished goods records successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
