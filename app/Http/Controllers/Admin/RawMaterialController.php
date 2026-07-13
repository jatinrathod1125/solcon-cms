<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use App\Models\Department;
use App\Models\Unit;
use App\Http\Requests\Admin\StoreRawMaterialRequest;
use App\Http\Requests\Admin\UpdateRawMaterialRequest;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of raw materials.
     */
    public function index(Request $request)
    {
        $query = RawMaterial::with(['department', 'stockUnit', 'purchaseUnit']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        $rawMaterials = $query->latest()->paginate(10)->withQueryString();
        $departments = Department::getActive();

        return view('admin.raw_materials.index', compact('rawMaterials', 'departments'));
    }

    /**
     * Show the form for creating a new raw material.
     */
    public function create()
    {
        $departments = Department::getActive();
        $units = Unit::getActive();

        return view('admin.raw_materials.create', compact('departments', 'units'));
    }

    /**
     * Store a newly created raw material in storage.
     */
    public function store(StoreRawMaterialRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['purchase_unit_id'] = $data['purchase_unit_id'] ?? $data['stock_unit_id'];
        $data['purchase_conversion'] = $data['purchase_conversion'] ?? 1.0000;

        // Initialize current stock to match opening stock
        $data['current_stock'] = $data['opening_stock'];

        RawMaterial::create($data);

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Raw material created successfully.');
    }

    /**
     * Show the form for editing the raw material.
     */
    public function edit(RawMaterial $rawMaterial)
    {
        $departments = Department::getActive();
        $units = Unit::getActive();

        return view('admin.raw_materials.edit', compact('rawMaterial', 'departments', 'units'));
    }

    /**
     * Update the raw material in storage.
     */
    public function update(UpdateRawMaterialRequest $request, RawMaterial $rawMaterial)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['purchase_unit_id'] = $data['purchase_unit_id'] ?? $data['stock_unit_id'];
        $data['purchase_conversion'] = $data['purchase_conversion'] ?? 1.0000;

        $rawMaterial->update($data);

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Raw material updated successfully.');
    }

    /**
     * Remove the raw material from storage.
     */
    public function destroy(RawMaterial $rawMaterial)
    {
        $rawMaterial->delete();

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Raw material deleted successfully.');
    }

    /**
     * Export all raw materials to a CSV file.
     */
    public function exportCsv()
    {
        $filename = "raw_materials_export_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Code', 'Name', 'Department Code', 'Stock Unit Code', 'Purchase Unit Code',
            'Purchase Conversion', 'Opening Stock', 'Current Stock', 'Minimum Stock', 'Maximum Stock',
            'Active', 'Is Coupon', 'Description'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $materials = RawMaterial::with(['department', 'stockUnit', 'purchaseUnit'])->get();

            foreach ($materials as $material) {
                fputcsv($file, [
                    $material->code,
                    $material->name,
                    $material->department->code ?? '',
                    $material->stockUnit->code ?? '',
                    $material->purchaseUnit->code ?? '',
                    $material->purchase_conversion,
                    $material->opening_stock,
                    $material->current_stock,
                    $material->minimum_stock,
                    $material->maximum_stock,
                    $material->is_active ? '1' : '0',
                    $material->is_coupon ? '1' : '0',
                    $material->description ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import raw materials from a CSV file.
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:4096'
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        if (($handle = fopen($path, 'r')) === false) {
            return redirect()->back()->with('error', 'Could not open the uploaded CSV file.');
        }

        $header = fgetcsv($handle, 1000, ',');
        if (!$header) {
            fclose($handle);
            return redirect()->back()->with('error', 'CSV file is empty.');
        }

        // Normalize header values to lowercase and trim spaces
        $header = array_map(function($h) {
            return strtolower(trim($h));
        }, $header);

        // Map column indices
        $codeIdx = array_search('code', $header);
        $nameIdx = array_search('name', $header);
        
        $deptIdx = array_search('department code', $header);
        if ($deptIdx === false) $deptIdx = array_search('department_code', $header);
        
        $stockUnitIdx = array_search('stock unit code', $header);
        if ($stockUnitIdx === false) $stockUnitIdx = array_search('stock_unit_code', $header);
        
        $purchaseUnitIdx = array_search('purchase unit code', $header);
        if ($purchaseUnitIdx === false) $purchaseUnitIdx = array_search('purchase_unit_code', $header);
        
        $conversionIdx = array_search('purchase conversion', $header);
        if ($conversionIdx === false) $conversionIdx = array_search('purchase_conversion', $header);
        
        $openingStockIdx = array_search('opening stock', $header);
        if ($openingStockIdx === false) $openingStockIdx = array_search('opening_stock', $header);
        
        $minStockIdx = array_search('minimum stock', $header);
        if ($minStockIdx === false) $minStockIdx = array_search('minimum_stock', $header);
        
        $maxStockIdx = array_search('maximum stock', $header);
        if ($maxStockIdx === false) $maxStockIdx = array_search('maximum_stock', $header);
        
        $activeIdx = array_search('active', $header);
        if ($activeIdx === false) $activeIdx = array_search('is_active', $header);
        
        $couponIdx = array_search('is coupon', $header);
        if ($couponIdx === false) $couponIdx = array_search('is_coupon', $header);
        
        $descIdx = array_search('description', $header);

        // Required headers validation
        if ($codeIdx === false || $nameIdx === false || $deptIdx === false || $stockUnitIdx === false) {
            fclose($handle);
            return redirect()->back()->with('error', 'CSV is missing required headers. Expected at least: Code, Name, Department Code, Stock Unit Code.');
        }

        // Cache departments and units lookups in-memory to prevent N+1 queries
        $departments = \App\Models\Department::pluck('id', 'code')->all();
        $departments = array_change_key_case($departments, CASE_UPPER);

        $units = \App\Models\Unit::pluck('id', 'code')->all();
        $units = array_change_key_case($units, CASE_UPPER);

        $errors = [];
        $importedCount = 0;
        $rowNum = 1;

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $rowNum++;

                // Skip empty rows
                if (count(array_filter($row)) === 0) {
                    continue;
                }

                $code = trim($row[$codeIdx] ?? '');
                $name = trim($row[$nameIdx] ?? '');
                $deptCode = strtoupper(trim($row[$deptIdx] ?? ''));
                $stockUnitCode = strtoupper(trim($row[$stockUnitIdx] ?? ''));

                // Validate required values
                if (empty($code)) {
                    $errors[] = "Row {$rowNum}: Code is required.";
                    continue;
                }
                if (empty($name)) {
                    $errors[] = "Row {$rowNum}: Name is required.";
                    continue;
                }
                if (empty($deptCode)) {
                    $errors[] = "Row {$rowNum}: Department Code is required.";
                    continue;
                }
                if (!isset($departments[$deptCode])) {
                    $errors[] = "Row {$rowNum}: Department '{$deptCode}' does not exist.";
                    continue;
                }
                if (empty($stockUnitCode)) {
                    $errors[] = "Row {$rowNum}: Stock Unit Code is required.";
                    continue;
                }
                if (!isset($units[$stockUnitCode])) {
                    $errors[] = "Row {$rowNum}: Stock Unit '{$stockUnitCode}' does not exist.";
                    continue;
                }

                // Resolve purchase unit
                $purchaseUnitCode = $purchaseUnitIdx !== false ? strtoupper(trim($row[$purchaseUnitIdx] ?? '')) : '';
                $purchaseUnitId = null;
                if (!empty($purchaseUnitCode)) {
                    if (!isset($units[$purchaseUnitCode])) {
                        $errors[] = "Row {$rowNum}: Purchase Unit '{$purchaseUnitCode}' does not exist.";
                        continue;
                    }
                    $purchaseUnitId = $units[$purchaseUnitCode];
                } else {
                    $purchaseUnitId = $units[$stockUnitCode];
                }

                // Parse conversion and stocks
                $conversion = $conversionIdx !== false ? (float) ($row[$conversionIdx] ?? 1.0) : 1.0;
                if ($conversion <= 0) {
                    $errors[] = "Row {$rowNum}: Purchase Conversion must be greater than zero.";
                    continue;
                }

                $openingStock = $openingStockIdx !== false ? (float) ($row[$openingStockIdx] ?? 0.0) : 0.0;
                $minStock = $minStockIdx !== false ? (float) ($row[$minStockIdx] ?? 0.0) : 0.0;
                $maxStock = $maxStockIdx !== false ? (float) ($row[$maxStockIdx] ?? 999999.0) : 999999.0;

                if ($openingStock < 0) {
                    $errors[] = "Row {$rowNum}: Opening Stock must be non-negative.";
                    continue;
                }
                if ($minStock < 0) {
                    $errors[] = "Row {$rowNum}: Minimum Stock must be non-negative.";
                    continue;
                }
                if ($maxStock < $minStock) {
                    $errors[] = "Row {$rowNum}: Maximum Stock must be greater than or equal to Minimum Stock.";
                    continue;
                }

                // Parse active and coupon booleans
                $isActive = true;
                if ($activeIdx !== false) {
                    $actVal = strtolower(trim($row[$activeIdx] ?? ''));
                    if ($actVal === '0' || $actVal === 'no' || $actVal === 'false' || $actVal === 'inactive') {
                        $isActive = false;
                    }
                }

                $isCoupon = false;
                if ($couponIdx !== false) {
                    $coupVal = strtolower(trim($row[$couponIdx] ?? ''));
                    if ($coupVal === '1' || $coupVal === 'yes' || $coupVal === 'true') {
                        $isCoupon = true;
                    }
                }

                $description = $descIdx !== false ? trim($row[$descIdx] ?? '') : null;

                // Pessimistic lock lookup to protect against duplicate inserts under concurrency
                $material = RawMaterial::where('code', $code)->lockForUpdate()->first();

                $data = [
                    'name' => $name,
                    'department_id' => $departments[$deptCode],
                    'stock_unit_id' => $units[$stockUnitCode],
                    'purchase_unit_id' => $purchaseUnitId,
                    'purchase_conversion' => $conversion,
                    'opening_stock' => $openingStock,
                    'minimum_stock' => $minStock,
                    'maximum_stock' => $maxStock,
                    'is_active' => $isActive,
                    'is_coupon' => $isCoupon,
                    'description' => $description,
                ];

                if ($material) {
                    // Update metadata (preserve current stock to maintain transaction ledger integrity)
                    $material->update($data);
                } else {
                    // Create new material (initialize current stock to match opening stock)
                    $data['code'] = $code;
                    $data['current_stock'] = $openingStock;
                    RawMaterial::create($data);
                }

                $importedCount++;
            }

            fclose($handle);

            if (!empty($errors)) {
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->back()->with('error', 'Import aborted due to validation errors:<br>' . implode('<br>', array_slice($errors, 0, 8)));
            }

            \Illuminate\Support\Facades\DB::commit();

            \App\Services\ActivityLogService::log(
                'RAW_MATERIALS_IMPORTED',
                "Imported raw materials inventory successfully from CSV containing {$importedCount} records.",
                auth()->id()
            );

            return redirect()->route('admin.raw-materials.index')
                ->with('success', "Imported {$importedCount} raw material records successfully.");

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            if (is_resource($handle)) {
                fclose($handle);
            }
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
