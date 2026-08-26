<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\EpoxyComponent;
use App\Models\EpoxyFillerColor;
use App\Models\RawMaterial;
use App\Models\Department;
use App\Models\Unit;
use App\Http\Requests\Admin\StoreEpoxyComponentRequest;
use App\Http\Requests\Admin\UpdateEpoxyComponentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EpoxyComponentController extends Controller
{
    public function index(Request $request)
    {
        $query = EpoxyComponent::with(['brand', 'unit', 'color', 'parentComponent', 'rawMaterial']);

        if (function_exists('currentBrand') && currentBrand()) {
            $query->forBrand(currentBrand());
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $components = $query->orderBy('name')->paginate(15)->withQueryString();
        $brands = Brand::active()->orderBy('name')->get();

        return view('admin.epoxy_components.index', compact('components', 'brands'));
    }

    public function create()
    {
        $units = Unit::where('is_active', true)->get();
        $colors = EpoxyFillerColor::where('is_active', true)->forCurrentBrand()->get();
        $parentComponents = EpoxyComponent::whereNull('parent_component_id')->forCurrentBrand()->get();
        $brands = Brand::active()->orderBy('name')->get();
        
        return view('admin.epoxy_components.create', compact('units', 'colors', 'parentComponents', 'brands'));
    }

    public function store(StoreEpoxyComponentRequest $request)
    {
        $data = $request->validated();
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);

        DB::transaction(function () use ($data, $request) {
            $component = EpoxyComponent::create($data);

            if ($request->purpose === 'Assembly Component') {
                $deptEPX = Department::where('code', 'EPX')->firstOrFail();
                $rawMaterial = RawMaterial::updateOrCreate(
                    ['code' => $component->code],
                    [
                        'brand_id' => $component->brand_id,
                        'name' => $component->name,
                        'department_id' => $deptEPX->id,
                        'stock_unit_id' => $component->unit_id,
                        'purchase_unit_id' => $component->unit_id,
                        'purchase_conversion' => 1.0,
                        'is_active' => $component->is_active,
                    ]
                );
                $component->update(['raw_material_id' => $rawMaterial->id]);
            }
        });

        return redirect()->route('admin.epoxy-components.index')->with('success', 'Epoxy Component created successfully.');
    }

    public function edit(EpoxyComponent $epoxyComponent)
    {
        $units = Unit::where('is_active', true)->get();
        $colors = EpoxyFillerColor::where('is_active', true)->forCurrentBrand()->get();
        $parentComponents = EpoxyComponent::whereNull('parent_component_id')->where('id', '!=', $epoxyComponent->id)->forCurrentBrand()->get();
        $brands = Brand::active()->orderBy('name')->get();
        
        return view('admin.epoxy_components.edit', compact('epoxyComponent', 'units', 'colors', 'parentComponents', 'brands'));
    }

    public function update(UpdateEpoxyComponentRequest $request, EpoxyComponent $epoxyComponent)
    {
        $data = $request->validated();
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');

        DB::transaction(function () use ($data, $request, $epoxyComponent) {
            $epoxyComponent->update($data);

            if ($request->purpose === 'Assembly Component') {
                $deptEPX = Department::where('code', 'EPX')->firstOrFail();
                $rawMaterial = RawMaterial::updateOrCreate(
                    ['code' => $epoxyComponent->code],
                    [
                        'brand_id' => $epoxyComponent->brand_id,
                        'name' => $epoxyComponent->name,
                        'department_id' => $deptEPX->id,
                        'stock_unit_id' => $epoxyComponent->unit_id,
                        'purchase_unit_id' => $epoxyComponent->unit_id,
                        'purchase_conversion' => 1.0,
                        'is_active' => $epoxyComponent->is_active,
                    ]
                );
                $epoxyComponent->update(['raw_material_id' => $rawMaterial->id]);
            } else {
                $epoxyComponent->update(['raw_material_id' => null]);
            }
        });

        return redirect()->route('admin.epoxy-components.index')->with('success', 'Epoxy Component updated successfully.');
    }

    public function destroy(EpoxyComponent $epoxyComponent)
    {
        if ($epoxyComponent->formulas()->exists()) {
            return back()->with('error', 'Cannot delete component: formulas exist.');
        }

        DB::transaction(function () use ($epoxyComponent) {
            if ($epoxyComponent->raw_material_id) {
                RawMaterial::where('id', $epoxyComponent->raw_material_id)->update(['is_active' => false]);
            }
            $epoxyComponent->delete();
        });

        return redirect()->route('admin.epoxy-components.index')->with('success', 'Epoxy Component deleted successfully.');
    }
}
