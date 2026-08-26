<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\EpoxyFormula;
use App\Models\EpoxyFormulaItem;
use App\Models\EpoxyProduct;
use App\Models\RawMaterial;
use App\Models\PackingMaterial;
use App\Models\Department;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EpoxyFormulaController extends Controller
{
    public function index(Request $request)
    {
        $query = EpoxyFormula::with(['product.brand', 'creator']);

        if (function_exists('currentBrand') && currentBrand()) {
            $query->forBrand(currentBrand());
        }

        if ($request->filled('brand_id')) {
            $query->forBrand($request->input('brand_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $formulas = $query->latest()->paginate(10)->withQueryString();
        $brands = Brand::active()->orderBy('name')->get();

        return view('admin.epoxy_formulas.index', compact('formulas', 'brands'));
    }

    public function create()
    {
        $products = EpoxyProduct::where('is_active', true)->forCurrentBrand()->with('brand')->orderBy('name')->get();
        $rawMaterials = RawMaterial::where('is_active', true)->with('brand')->forCurrentBrand()->orderBy('name')->get();
        $packingMaterials = PackingMaterial::where('status', 'active')->with(['brand', 'category'])->forCurrentBrand()->orderBy('name')->get();
        $units = Unit::where('is_active', true)->get();
        
        return view('admin.epoxy_formulas.create', compact('products', 'rawMaterials', 'packingMaterials', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'epoxy_product_id' => 'required|exists:epoxy_products,id',
            'version' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|in:raw,packing',
            'items.*.raw_material_id' => 'required_if:items.*.item_type,raw|nullable|exists:raw_materials,id',
            'items.*.packing_material_id' => 'required_if:items.*.item_type,packing|nullable|exists:packing_materials,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.unit_id' => 'required|exists:units,id',
            'items.*.material_type' => 'required|string|in:Bottle,Pouch,Accessory,Bucket',
            'items.*.is_dynamic_color' => 'boolean',
        ]);

        DB::transaction(function () use ($request) {
            // If this new formula is set as active, deactivate previous ones for the same product
            $isActive = $request->has('is_active');
            if ($isActive) {
                EpoxyFormula::where('epoxy_product_id', $request->epoxy_product_id)
                    ->update(['is_active' => false]);
            }

            $formula = EpoxyFormula::create([
                'epoxy_product_id' => $request->epoxy_product_id,
                'version' => $request->version,
                'is_active' => $isActive,
                'description' => $request->description,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $isPacking = isset($item['item_type']) && $item['item_type'] === 'packing';
                EpoxyFormulaItem::create([
                    'epoxy_formula_id' => $formula->id,
                    'raw_material_id' => !$isPacking ? ($item['raw_material_id'] ?? null) : null,
                    'packing_material_id' => $isPacking ? ($item['packing_material_id'] ?? null) : null,
                    'quantity' => $item['quantity'],
                    'unit_id' => $item['unit_id'],
                    'material_type' => $item['material_type'],
                    'is_dynamic_color' => isset($item['is_dynamic_color']) && $item['is_dynamic_color'],
                ]);
            }
        });

        return redirect()->route('admin.epoxy-formulas.index')->with('success', 'Epoxy Formula defined successfully.');
    }

    public function show(EpoxyFormula $epoxyFormula)
    {
        $epoxyFormula->load(['product.brand', 'items.rawMaterial', 'items.packingMaterial', 'items.unit']);
        return view('admin.epoxy_formulas.show', compact('epoxyFormula'));
    }

    public function edit(EpoxyFormula $epoxyFormula)
    {
        $products = EpoxyProduct::forCurrentBrand()->with('brand')->orderBy('name')->get();
        $rawMaterials = RawMaterial::where('is_active', true)->with('brand')->forCurrentBrand()->orderBy('name')->get();
        $packingMaterials = PackingMaterial::where('status', 'active')->with(['brand', 'category'])->forCurrentBrand()->orderBy('name')->get();
        $units = Unit::all();
        $epoxyFormula->load('items');

        return view('admin.epoxy_formulas.edit', compact('epoxyFormula', 'products', 'rawMaterials', 'packingMaterials', 'units'));
    }

    public function update(Request $request, EpoxyFormula $epoxyFormula)
    {
        $request->validate([
            'epoxy_product_id' => 'required|exists:epoxy_products,id',
            'version' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|in:raw,packing',
            'items.*.raw_material_id' => 'required_if:items.*.item_type,raw|nullable|exists:raw_materials,id',
            'items.*.packing_material_id' => 'required_if:items.*.item_type,packing|nullable|exists:packing_materials,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.unit_id' => 'required|exists:units,id',
            'items.*.material_type' => 'required|string|in:Bottle,Pouch,Accessory,Bucket',
            'items.*.is_dynamic_color' => 'boolean',
        ]);

        DB::transaction(function () use ($request, $epoxyFormula) {
            $isActive = $request->has('is_active');
            if ($isActive) {
                EpoxyFormula::where('epoxy_product_id', $request->epoxy_product_id)
                    ->where('id', '!=', $epoxyFormula->id)
                    ->update(['is_active' => false]);
            }

            $epoxyFormula->update([
                'epoxy_product_id' => $request->epoxy_product_id,
                'version' => $request->version,
                'is_active' => $isActive,
                'description' => $request->description,
            ]);

            // Sync items by deleting and recreating
            $epoxyFormula->items()->delete();

            foreach ($request->items as $item) {
                $isPacking = isset($item['item_type']) && $item['item_type'] === 'packing';
                EpoxyFormulaItem::create([
                    'epoxy_formula_id' => $epoxyFormula->id,
                    'raw_material_id' => !$isPacking ? ($item['raw_material_id'] ?? null) : null,
                    'packing_material_id' => $isPacking ? ($item['packing_material_id'] ?? null) : null,
                    'quantity' => $item['quantity'],
                    'unit_id' => $item['unit_id'],
                    'material_type' => $item['material_type'],
                    'is_dynamic_color' => isset($item['is_dynamic_color']) && $item['is_dynamic_color'],
                ]);
            }
        });

        return redirect()->route('admin.epoxy-formulas.index')->with('success', 'Epoxy Formula updated successfully.');
    }

    public function destroy(EpoxyFormula $epoxyFormula)
    {
        $epoxyFormula->delete();
        return redirect()->route('admin.epoxy-formulas.index')->with('success', 'Epoxy Formula deleted successfully.');
    }
}
