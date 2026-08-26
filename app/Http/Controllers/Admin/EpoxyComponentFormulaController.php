<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\EpoxyComponent;
use App\Models\EpoxyComponentFormula;
use App\Models\EpoxyComponentFormulaItem;
use App\Models\RawMaterial;
use App\Models\PackingMaterial;
use App\Models\Department;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EpoxyComponentFormulaController extends Controller
{
    public function index(Request $request)
    {
        $query = EpoxyComponentFormula::with(['component.brand', 'creator']);

        if (function_exists('currentBrand') && currentBrand()) {
            $query->forBrand(currentBrand());
        }

        if ($request->filled('brand_id')) {
            $query->forBrand($request->input('brand_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('component', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $formulas = $query->latest()->paginate(10)->withQueryString();
        $brands = Brand::active()->orderBy('name')->get();

        return view('admin.epoxy_component_formulas.index', compact('formulas', 'brands'));
    }

    public function create(Request $request)
    {
        $components = EpoxyComponent::where('is_active', true)->forCurrentBrand()->with('brand')->orderBy('name')->get();

        $deptEPX = Department::where('code', 'EPX')->first();
        $rawMaterials = collect();
        if ($deptEPX) {
            $compRmIds = EpoxyComponent::whereNotNull('raw_material_id')->pluck('raw_material_id')->toArray();
            $rmQuery = RawMaterial::where('department_id', $deptEPX->id)
                ->where('is_active', true)
                ->whereNotIn('id', $compRmIds);
            if (function_exists('currentBrand') && currentBrand()) {
                $rmQuery->forBrand(currentBrand());
            }
            $rawMaterials = $rmQuery->orderBy('name')->get();
        }

        $pmQuery = PackingMaterial::where('status', 'active')->with(['brand', 'category']);
        if (function_exists('currentBrand') && currentBrand()) {
            $pmQuery->forBrand(currentBrand());
        }
        $packingMaterials = $pmQuery->orderBy('name')->get();

        $units = Unit::where('is_active', true)->orderBy('name')->get();
        $preselectedComponentId = $request->query('component_id');

        return view('admin.epoxy_component_formulas.create', compact('components', 'rawMaterials', 'packingMaterials', 'units', 'preselectedComponentId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'epoxy_component_id' => 'required|exists:epoxy_components,id',
            'version' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|in:raw,packing',
            'items.*.raw_material_id' => 'required_if:items.*.item_type,raw|nullable|exists:raw_materials,id',
            'items.*.packing_material_id' => 'required_if:items.*.item_type,packing|nullable|exists:packing_materials,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.unit_id' => 'required|exists:units,id',
        ]);

        DB::transaction(function () use ($request) {
            $isActive = $request->has('is_active');
            if ($isActive) {
                EpoxyComponentFormula::where('epoxy_component_id', $request->epoxy_component_id)
                    ->update(['is_active' => false]);
            }

            $formula = EpoxyComponentFormula::create([
                'epoxy_component_id' => $request->epoxy_component_id,
                'version' => $request->version,
                'is_active' => $isActive,
                'description' => $request->description,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $isPacking = isset($item['item_type']) && $item['item_type'] === 'packing';
                EpoxyComponentFormulaItem::create([
                    'epoxy_component_formula_id' => $formula->id,
                    'raw_material_id' => !$isPacking ? ($item['raw_material_id'] ?? null) : null,
                    'packing_material_id' => $isPacking ? ($item['packing_material_id'] ?? null) : null,
                    'quantity' => $item['quantity'],
                    'unit_id' => $item['unit_id'],
                ]);
            }
        });

        return redirect()->route('admin.epoxy-component-formulas.index')->with('success', 'Component Formula defined successfully.');
    }

    public function show(EpoxyComponentFormula $epoxyComponentFormula)
    {
        $epoxyComponentFormula->load(['component.brand', 'items.rawMaterial.brand', 'items.packingMaterial.brand', 'items.unit']);
        return view('admin.epoxy_component_formulas.show', compact('epoxyComponentFormula'));
    }

    public function edit(EpoxyComponentFormula $epoxyComponentFormula)
    {
        $components = EpoxyComponent::forCurrentBrand()->with('brand')->orderBy('name')->get();
        $deptEPX = Department::where('code', 'EPX')->first();

        $rawMaterials = collect();
        if ($deptEPX) {
            $compRmIds = EpoxyComponent::whereNotNull('raw_material_id')->pluck('raw_material_id')->toArray();
            $rmQuery = RawMaterial::where('department_id', $deptEPX->id)
                ->where('is_active', true)
                ->whereNotIn('id', $compRmIds);
            if (function_exists('currentBrand') && currentBrand()) {
                $rmQuery->forBrand(currentBrand());
            }
            $rawMaterials = $rmQuery->orderBy('name')->get();
        }

        $pmQuery = PackingMaterial::where('status', 'active')->with(['brand', 'category']);
        if (function_exists('currentBrand') && currentBrand()) {
            $pmQuery->forBrand(currentBrand());
        }
        $packingMaterials = $pmQuery->orderBy('name')->get();

        $units = Unit::orderBy('name')->get();
        $epoxyComponentFormula->load(['items.rawMaterial.brand', 'items.packingMaterial.brand']);

        return view('admin.epoxy_component_formulas.edit', compact('epoxyComponentFormula', 'components', 'rawMaterials', 'packingMaterials', 'units'));
    }

    public function update(Request $request, EpoxyComponentFormula $epoxyComponentFormula)
    {
        $request->validate([
            'epoxy_component_id' => 'required|exists:epoxy_components,id',
            'version' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|in:raw,packing',
            'items.*.raw_material_id' => 'required_if:items.*.item_type,raw|nullable|exists:raw_materials,id',
            'items.*.packing_material_id' => 'required_if:items.*.item_type,packing|nullable|exists:packing_materials,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.unit_id' => 'required|exists:units,id',
        ]);

        DB::transaction(function () use ($request, $epoxyComponentFormula) {
            $isActive = $request->has('is_active');
            if ($isActive) {
                EpoxyComponentFormula::where('epoxy_component_id', $request->epoxy_component_id)
                    ->where('id', '!=', $epoxyComponentFormula->id)
                    ->update(['is_active' => false]);
            }

            $epoxyComponentFormula->update([
                'epoxy_component_id' => $request->epoxy_component_id,
                'version' => $request->version,
                'is_active' => $isActive,
                'description' => $request->description,
                'updated_by' => auth()->id(),
            ]);

            $epoxyComponentFormula->items()->delete();

            foreach ($request->items as $item) {
                $isPacking = isset($item['item_type']) && $item['item_type'] === 'packing';
                EpoxyComponentFormulaItem::create([
                    'epoxy_component_formula_id' => $epoxyComponentFormula->id,
                    'raw_material_id' => !$isPacking ? ($item['raw_material_id'] ?? null) : null,
                    'packing_material_id' => $isPacking ? ($item['packing_material_id'] ?? null) : null,
                    'quantity' => $item['quantity'],
                    'unit_id' => $item['unit_id'],
                ]);
            }
        });

        return redirect()->route('admin.epoxy-component-formulas.index')->with('success', 'Component Formula updated successfully.');
    }

    public function destroy(EpoxyComponentFormula $epoxyComponentFormula)
    {
        $epoxyComponentFormula->delete();
        return redirect()->route('admin.epoxy-component-formulas.index')->with('success', 'Component Formula deleted successfully.');
    }
}

