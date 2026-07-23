<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackingMaterial;
use App\Models\PackingMaterialCategory;
use App\Models\Unit;
use App\Models\StockLedger;
use App\Http\Requests\Admin\StorePackingMaterialRequest;
use App\Http\Requests\Admin\UpdatePackingMaterialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PackingMaterialController extends Controller
{
    /**
     * Display a listing of packing materials.
     */
    public function index(Request $request)
    {
        $query = PackingMaterial::with(['category', 'unit']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('size', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'low_stock') {
                $query->whereColumn('current_stock', '<', 'minimum_stock');
            } else {
                $query->where('status', $status);
            }
        }

        $packingMaterials = $query->orderBy('name')->paginate(15)->withQueryString();
        $categories = PackingMaterialCategory::orderBy('name')->get();

        return view('admin.packing_materials.index', compact('packingMaterials', 'categories'));
    }

    /**
     * Show the form for creating a new packing material.
     */
    public function create()
    {
        $categories = PackingMaterialCategory::orderBy('name')->get();
        $units = Unit::where('is_active', true)->get();

        return view('admin.packing_materials.create', compact('categories', 'units'));
    }

    /**
     * Store a newly created packing material in storage.
     */
    public function store(StorePackingMaterialRequest $request)
    {
        $data = $request->validated();
        $data['current_stock'] = $data['opening_stock'];

        $packingMaterial = DB::transaction(function () use ($data) {
            $item = PackingMaterial::create($data);

            if ((float) $item->opening_stock > 0) {
                StockLedger::create([
                    'packing_material_id' => $item->id,
                    'transaction_type' => 'IN',
                    'quantity' => (float) $item->opening_stock,
                    'balance_after' => (float) $item->opening_stock,
                    'remarks' => 'Initial Opening Stock',
                    'created_by' => auth()->id() ?? 1,
                ]);
            }

            return $item;
        });

        return redirect()->route('admin.packing-materials.index')
            ->with('success', 'Packing material created successfully.');
    }

    /**
     * Show the form for editing the packing material.
     */
    public function edit(PackingMaterial $packingMaterial)
    {
        $categories = PackingMaterialCategory::orderBy('name')->get();
        $units = Unit::where('is_active', true)->get();

        return view('admin.packing_materials.edit', compact('packingMaterial', 'categories', 'units'));
    }

    /**
     * Update the specified packing material in storage.
     */
    public function update(UpdatePackingMaterialRequest $request, PackingMaterial $packingMaterial)
    {
        $data = $request->validated();
        $packingMaterial->update($data);

        return redirect()->route('admin.packing-materials.index')
            ->with('success', 'Packing material updated successfully.');
    }

    /**
     * Remove the specified packing material from storage.
     */
    public function destroy(PackingMaterial $packingMaterial)
    {
        if ($packingMaterial->stockLedgers()->exists()) {
            $packingMaterial->update(['status' => 'inactive']);
            return redirect()->route('admin.packing-materials.index')
                ->with('warning', 'Packing material has stock transactions, so it was marked inactive instead of being permanently deleted.');
        }

        $packingMaterial->delete();

        return redirect()->route('admin.packing-materials.index')
            ->with('success', 'Packing material deleted successfully.');
    }

    /**
     * Export all packing materials to CSV.
     */
    public function exportCsv()
    {
        $filename = "packing_materials_export_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Category', 'Code', 'Name', 'Size', 'Unit Code', 'Opening Stock', 'Current Stock', 'Minimum Stock', 'Status', 'Remarks'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $materials = PackingMaterial::with(['category', 'unit'])->orderBy('name')->get();

            foreach ($materials as $material) {
                fputcsv($file, [
                    $material->category->name ?? '',
                    $material->code ?? '',
                    $material->name,
                    $material->size ?? '',
                    $material->unit->code ?? 'PCS',
                    $material->opening_stock,
                    $material->current_stock,
                    $material->minimum_stock,
                    $material->status,
                    $material->remarks ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import packing materials from CSV file.
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle, 1000, ',');

        if (!$header) {
            return back()->with('error', 'The uploaded file is empty or invalid.');
        }

        $categories = PackingMaterialCategory::pluck('id', 'name')->toArray();
        $units = Unit::pluck('id', 'code')->toArray();
        $defaultUnitId = Unit::where('code', 'PCS')->first()?->id ?? Unit::first()?->id ?? 1;

        $imported = 0;
        $errors = [];
        $rowNum = 1;

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $rowNum++;
            if (count($row) < 3) continue;

            $catName = trim($row[0] ?? '');
            $code = trim($row[1] ?? '');
            $name = trim($row[2] ?? '');
            $size = trim($row[3] ?? '');
            $unitCode = trim($row[4] ?? '');
            $openingStock = (float) ($row[5] ?? 0);
            $currentStock = (float) ($row[6] ?? $openingStock);
            $minimumStock = (float) ($row[7] ?? 0);
            $status = strtolower(trim($row[8] ?? 'active')) === 'inactive' ? 'inactive' : 'active';
            $remarks = trim($row[9] ?? '');

            if (empty($name)) {
                $errors[] = "Row {$rowNum}: Material name is required.";
                continue;
            }

            // Find or create category
            $categoryId = null;
            if (!empty($catName)) {
                if (!isset($categories[$catName])) {
                    $catObj = PackingMaterialCategory::create(['name' => $catName]);
                    $categories[$catName] = $catObj->id;
                }
                $categoryId = $categories[$catName];
            } else {
                $categoryId = PackingMaterialCategory::first()?->id ?? 1;
            }

            $unitId = (!empty($unitCode) && isset($units[$unitCode])) ? $units[$unitCode] : $defaultUnitId;

            // Check for special expansion: Spacer Pouch or Clip Pouch
            if (str_contains(strtolower($name), 'spacer pouch') || str_contains(strtolower($catName), 'spacer pouch')) {
                $spacerSizes = ['2mm', '3mm', '4mm', '5mm'];
                foreach ($spacerSizes as $s) {
                    $variantName = "Spacer Pouch {$s}";
                    $variantCode = !empty($code) ? "{$code}-{$s}" : "PCH-SPC-{$s}";
                    PackingMaterial::updateOrCreate(
                        ['name' => $variantName, 'category_id' => $categoryId],
                        [
                            'code' => strtoupper($variantCode),
                            'size' => $s,
                            'unit_id' => $unitId,
                            'minimum_stock' => $minimumStock,
                            'opening_stock' => $openingStock,
                            'current_stock' => $currentStock,
                            'remarks' => $remarks,
                            'status' => $status,
                        ]
                    );
                    $imported++;
                }
            } elseif (str_contains(strtolower($name), 'clip pouch') || str_contains(strtolower($catName), 'clip pouch')) {
                $clipSizes = ['2mm', '3mm', '4mm'];
                foreach ($clipSizes as $s) {
                    $variantName = "Clip Pouch {$s}";
                    $variantCode = !empty($code) ? "{$code}-{$s}" : "PCH-CLP-{$s}";
                    PackingMaterial::updateOrCreate(
                        ['name' => $variantName, 'category_id' => $categoryId],
                        [
                            'code' => strtoupper($variantCode),
                            'size' => $s,
                            'unit_id' => $unitId,
                            'minimum_stock' => $minimumStock,
                            'opening_stock' => $openingStock,
                            'current_stock' => $currentStock,
                            'remarks' => $remarks,
                            'status' => $status,
                        ]
                    );
                    $imported++;
                }
            } else {
                PackingMaterial::updateOrCreate(
                    ['name' => $name, 'category_id' => $categoryId],
                    [
                        'code' => !empty($code) ? strtoupper($code) : null,
                        'size' => !empty($size) ? $size : null,
                        'unit_id' => $unitId,
                        'minimum_stock' => $minimumStock,
                        'opening_stock' => $openingStock,
                        'current_stock' => $currentStock,
                        'remarks' => $remarks,
                        'status' => $status,
                    ]
                );
                $imported++;
            }
        }

        fclose($handle);

        $msg = "Imported {$imported} packing material records successfully.";
        if (!empty($errors)) {
            $msg .= " Errors: " . implode(' ', $errors);
        }

        return redirect()->route('admin.packing-materials.index')->with('success', $msg);
    }
}
