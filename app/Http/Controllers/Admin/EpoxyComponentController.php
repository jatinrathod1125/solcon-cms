<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EpoxyComponent;
use App\Models\EpoxyFillerColor;
use App\Models\RawMaterial;
use App\Models\Department;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EpoxyComponentController extends Controller
{
    public function index()
    {
        $components = EpoxyComponent::with(['unit', 'color', 'parentComponent', 'rawMaterial'])->get();
        return view('admin.epoxy_components.index', compact('components'));
    }

    public function create()
    {
        $units = Unit::where('is_active', true)->get();
        $colors = EpoxyFillerColor::where('is_active', true)->get();
        $parentComponents = EpoxyComponent::whereNull('parent_component_id')->get();
        
        return view('admin.epoxy_components.create', compact('units', 'colors', 'parentComponents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:epoxy_components,code',
            'category' => 'required|string|in:Bottle,Pouch,Packet,Liquid,Powder,Plastic,Accessory,Other',
            'purpose' => 'required|string|in:Assembly Component,Direct Finished Product',
            'unit_id' => 'required|exists:units,id',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'parent_component_id' => 'nullable|exists:epoxy_components,id',
            'epoxy_filler_color_id' => 'nullable|exists:epoxy_filler_colors,id',
        ]);

        DB::transaction(function () use ($request) {
            $component = EpoxyComponent::create([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'category' => $request->category,
                'purpose' => $request->purpose,
                'unit_id' => $request->unit_id,
                'is_active' => $request->has('is_active'),
                'description' => $request->description,
                'parent_component_id' => $request->parent_component_id,
                'epoxy_filler_color_id' => $request->epoxy_filler_color_id,
            ]);

            if ($request->purpose === 'Assembly Component') {
                $deptEPX = Department::where('code', 'EPX')->firstOrFail();
                $rawMaterial = RawMaterial::updateOrCreate(
                    ['code' => $component->code],
                    [
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
        $colors = EpoxyFillerColor::where('is_active', true)->get();
        $parentComponents = EpoxyComponent::whereNull('parent_component_id')->where('id', '!=', $epoxyComponent->id)->get();
        
        return view('admin.epoxy_components.edit', compact('epoxyComponent', 'units', 'colors', 'parentComponents'));
    }

    public function update(Request $request, EpoxyComponent $epoxyComponent)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:epoxy_components,code,' . $epoxyComponent->id,
            'category' => 'required|string|in:Bottle,Pouch,Packet,Liquid,Powder,Plastic,Accessory,Other',
            'purpose' => 'required|string|in:Assembly Component,Direct Finished Product',
            'unit_id' => 'required|exists:units,id',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'parent_component_id' => 'nullable|exists:epoxy_components,id',
            'epoxy_filler_color_id' => 'nullable|exists:epoxy_filler_colors,id',
        ]);

        DB::transaction(function () use ($request, $epoxyComponent) {
            $epoxyComponent->update([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'category' => $request->category,
                'purpose' => $request->purpose,
                'unit_id' => $request->unit_id,
                'is_active' => $request->has('is_active'),
                'description' => $request->description,
                'parent_component_id' => $request->parent_component_id,
                'epoxy_filler_color_id' => $request->epoxy_filler_color_id,
            ]);

            if ($request->purpose === 'Assembly Component') {
                $deptEPX = Department::where('code', 'EPX')->firstOrFail();
                $rawMaterial = RawMaterial::updateOrCreate(
                    ['code' => $epoxyComponent->code],
                    [
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
