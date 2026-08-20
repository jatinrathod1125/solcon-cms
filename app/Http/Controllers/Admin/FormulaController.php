<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Formula;
use App\Models\FormulaItem;
use App\Models\Grade;
use App\Models\RawMaterial;
use App\Models\PackingMaterial;
use App\Models\Unit;
use App\Http\Requests\Admin\StoreFormulaRequest;
use App\Http\Requests\Admin\UpdateFormulaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormulaController extends Controller
{
    /**
     * Display a listing of formulas.
     */
    public function index(Request $request)
    {
        $query = Formula::with(['grade.brand', 'creator']);

        if (function_exists('currentBrand') && currentBrand()) {
            $query->forBrand(currentBrand());
        }

        $formulas = $query->latest()->paginate(10)->withQueryString();

        return view('admin.formulas.index', compact('formulas'));
    }

    /**
     * Display the specified formula version.
     */
    public function show(Formula $formula)
    {
        $formula->load(['grade.brand', 'creator', 'items.rawMaterial.brand', 'items.packingMaterial.brand', 'items.unit']);

        return view('admin.formulas.show', compact('formula'));
    }

    /**
     * Show the form for creating a new formula.
     */
    public function create()
    {
        $grades = Grade::where('is_active', true)->with('brand')->orderBy('name')->get();
        $rawMaterials = RawMaterial::where('is_active', true)->with('brand')->orderBy('name')->get();
        $packingMaterials = PackingMaterial::where('status', 'active')->with(['brand', 'category'])->orderBy('name')->get();
        $units = Unit::getActive();
        $brands = Brand::active()->orderBy('name')->get();

        return view('admin.formulas.create', compact('grades', 'rawMaterials', 'packingMaterials', 'units', 'brands'));
    }

    /**
     * Store a newly created formula in storage.
     */
    public function store(StoreFormulaRequest $request)
    {
        DB::transaction(function () use ($request) {
            $gradeId = $request->input('grade_id');
            
            // Calculate next version number
            $maxVersion = Formula::where('grade_id', $gradeId)->max('version');
            $nextVersion = $maxVersion ? ($maxVersion + 1) : 1;

            $formula = Formula::create([
                'grade_id' => $gradeId,
                'version' => $nextVersion,
                'remarks' => $request->input('remarks'),
                'is_active' => $request->boolean('is_active', true),
                'created_by' => auth()->id(),
            ]);

            foreach ($request->input('items') as $item) {
                $itemType = $item['item_type'] ?? 'raw';
                $isPacking = $itemType === 'packing';

                FormulaItem::create([
                    'formula_id' => $formula->id,
                    'item_type' => $itemType,
                    'raw_material_id' => $isPacking ? null : ($item['raw_material_id'] ?? null),
                    'packing_material_id' => $isPacking ? ($item['packing_material_id'] ?? null) : null,
                    'quantity' => $item['quantity'],
                    'unit_id' => $item['unit_id'],
                    'consumption_method' => $item['consumption_method'] ?? 'formula',
                    'consumption_per_unit' => $item['consumption_per_unit'] ?? 1,
                    'sequence' => $item['sequence'] ?? 1,
                ]);
            }

            // Enforce only one active formula per grade
            if ($formula->is_active) {
                Formula::where('grade_id', $gradeId)
                    ->where('id', '!=', $formula->id)
                    ->update(['is_active' => false]);
            }

            \App\Services\ActivityLogService::log(
                'FORMULA_UPDATED',
                "Created new formula version #{$formula->version} for grade ID {$gradeId}."
            );
        });

        return redirect()->route('admin.formulas.index')
            ->with('success', 'Formula version created successfully.');
    }

    /**
     * Show the form for editing the formula.
     */
    public function edit(Formula $formula)
    {
        $formula->load(['items.rawMaterial.brand', 'items.packingMaterial.brand', 'grade.brand']);
        $grades = Grade::where('is_active', true)->with('brand')->orderBy('name')->get();
        $rawMaterials = RawMaterial::where('is_active', true)->with('brand')->orderBy('name')->get();
        $packingMaterials = PackingMaterial::where('status', 'active')->with(['brand', 'category'])->orderBy('name')->get();
        $units = Unit::getActive();
        $brands = Brand::active()->orderBy('name')->get();

        return view('admin.formulas.edit', compact('formula', 'grades', 'rawMaterials', 'packingMaterials', 'units', 'brands'));
    }

    /**
     * Update the formula in storage.
     */
    public function update(UpdateFormulaRequest $request, Formula $formula)
    {
        DB::transaction(function () use ($request, $formula) {
            $gradeId = $request->input('grade_id');

            $formula->update([
                'grade_id' => $gradeId,
                'remarks' => $request->input('remarks'),
                'is_active' => $request->boolean('is_active'),
            ]);

            // Sync items by deleting and recreating
            $formula->items()->delete();

            foreach ($request->input('items') as $item) {
                $itemType = $item['item_type'] ?? 'raw';
                $isPacking = $itemType === 'packing';

                FormulaItem::create([
                    'formula_id' => $formula->id,
                    'item_type' => $itemType,
                    'raw_material_id' => $isPacking ? null : ($item['raw_material_id'] ?? null),
                    'packing_material_id' => $isPacking ? ($item['packing_material_id'] ?? null) : null,
                    'quantity' => $item['quantity'],
                    'unit_id' => $item['unit_id'],
                    'consumption_method' => $item['consumption_method'] ?? 'formula',
                    'consumption_per_unit' => $item['consumption_per_unit'] ?? 1,
                    'sequence' => $item['sequence'] ?? 1,
                ]);
            }

            // Enforce only one active formula per grade
            if ($formula->is_active) {
                Formula::where('grade_id', $gradeId)
                    ->where('id', '!=', $formula->id)
                    ->update(['is_active' => false]);
            }

            \App\Services\ActivityLogService::log(
                'FORMULA_UPDATED',
                "Updated formula version #{$formula->version} for grade ID {$gradeId}."
            );
        });

        return redirect()->route('admin.formulas.index')
            ->with('success', 'Formula updated successfully.');
    }

    /**
     * Remove the formula from storage.
     */
    public function destroy(Formula $formula)
    {
        $formula->delete();

        return redirect()->route('admin.formulas.index')
            ->with('success', 'Formula deleted successfully.');
    }
}
