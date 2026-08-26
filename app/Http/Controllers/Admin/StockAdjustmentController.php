<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockAdjustment;
use App\Models\RawMaterial;
use App\Models\PackingMaterial;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    /**
     * Display stock adjustments log.
     */
    public function index(Request $request)
    {
        $query = StockAdjustment::with(['rawMaterial.department', 'rawMaterial.brand', 'packingMaterial.category', 'packingMaterial.brand', 'creator']);

        if (function_exists('currentBrand') && currentBrand()) {
            $query->forBrand(currentBrand());
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereHas('rawMaterial', function($rmQ) use ($search) {
                    $rmQ->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                })->orWhereHas('packingMaterial', function($pmQ) use ($search) {
                    $pmQ->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                })->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        if ($request->filled('raw_material_id')) {
            $query->where('raw_material_id', $request->input('raw_material_id'));
        }

        if ($request->filled('packing_material_id')) {
            $query->where('packing_material_id', $request->input('packing_material_id'));
        }

        $adjustments = $query->latest()->paginate(20)->withQueryString();
        
        if ($request->ajax()) {
            return view('admin.stock_adjustments._table', compact('adjustments'))->render();
        }

        // Active raw materials and packing materials lists for creation dropdown (filtered by active brand)
        $rawMaterials = RawMaterial::where('is_active', true)
            ->forCurrentBrand()
            ->with(['department', 'brand'])
            ->orderBy('name')
            ->get();

        $packingMaterials = PackingMaterial::where('status', 'active')
            ->forCurrentBrand()
            ->with(['category', 'brand', 'unit'])
            ->orderBy('name')
            ->get();

        return view('admin.stock_adjustments.index', compact('adjustments', 'rawMaterials', 'packingMaterials'));
    }

    /**
     * Store new stock adjustment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_type' => 'required|in:raw,packing',
            'raw_material_id' => 'required_if:material_type,raw|nullable|exists:raw_materials,id',
            'packing_material_id' => 'required_if:material_type,packing|nullable|exists:packing_materials,id',
            'quantity' => 'required|numeric',
            'remarks' => 'nullable|string|max:1000',
        ]);

        try {
            $rawMaterialId = $validated['material_type'] === 'raw' ? (int)$validated['raw_material_id'] : null;
            $packingMaterialId = $validated['material_type'] === 'packing' ? (int)$validated['packing_material_id'] : null;

            if (function_exists('currentBrand') && $currentBrand = currentBrand()) {
                if ($rawMaterialId) {
                    $rm = RawMaterial::where('id', $rawMaterialId)->forBrand($currentBrand)->first();
                    if (!$rm) {
                        return redirect()->back()->withInput()->with('error', 'Selected raw material is not accessible for the active brand.');
                    }
                }
                if ($packingMaterialId) {
                    $pm = PackingMaterial::where('id', $packingMaterialId)->forBrand($currentBrand)->first();
                    if (!$pm) {
                        return redirect()->back()->withInput()->with('error', 'Selected packing material is not accessible for the active brand.');
                    }
                }
            }

            $adjustment = StockService::adjustStock(
                $rawMaterialId,
                (float)$validated['quantity'],
                $validated['remarks'] ?? null,
                $packingMaterialId
            );

            return redirect()->route('admin.stock-adjustments.index')
                ->with('success', 'Stock adjustment logged successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}

