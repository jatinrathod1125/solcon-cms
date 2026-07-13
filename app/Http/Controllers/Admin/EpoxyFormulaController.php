<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EpoxyFormula;
use App\Models\EpoxyFormulaItem;
use App\Models\EpoxyProduct;
use App\Models\RawMaterial;
use App\Models\Department;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EpoxyFormulaController extends Controller
{
    public function index()
    {
        $formulas = EpoxyFormula::with(['product', 'creator'])->get();
        return view('admin.epoxy_formulas.index', compact('formulas'));
    }

    public function create()
    {
        $products = EpoxyProduct::where('is_active', true)->get();
        $deptEPX = Department::where('code', 'EPX')->first();
        $rawMaterials = $deptEPX ? RawMaterial::where('department_id', $deptEPX->id)->get() : collect();
        $units = Unit::where('is_active', true)->get();
        
        return view('admin.epoxy_formulas.create', compact('products', 'rawMaterials', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'epoxy_product_id' => 'required|exists:epoxy_products,id',
            'version' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:raw_materials,id',
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
                EpoxyFormulaItem::create([
                    'epoxy_formula_id' => $formula->id,
                    'raw_material_id' => $item['raw_material_id'],
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
        $epoxyFormula->load(['product', 'items.rawMaterial', 'items.unit']);
        return view('admin.epoxy_formulas.show', compact('epoxyFormula'));
    }

    public function edit(EpoxyFormula $epoxyFormula)
    {
        $products = EpoxyProduct::all();
        $deptEPX = Department::where('code', 'EPX')->first();
        $rawMaterials = $deptEPX ? RawMaterial::where('department_id', $deptEPX->id)->get() : collect();
        $units = Unit::all();
        $epoxyFormula->load('items');

        return view('admin.epoxy_formulas.edit', compact('epoxyFormula', 'products', 'rawMaterials', 'units'));
    }

    public function update(Request $request, EpoxyFormula $epoxyFormula)
    {
        $request->validate([
            'epoxy_product_id' => 'required|exists:epoxy_products,id',
            'version' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:raw_materials,id',
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
                EpoxyFormulaItem::create([
                    'epoxy_formula_id' => $epoxyFormula->id,
                    'raw_material_id' => $item['raw_material_id'],
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
