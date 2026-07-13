<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GroutFormula;
use App\Models\GroutFormulaItem;
use App\Models\Color;
use App\Models\RawMaterial;
use App\Models\Unit;
use App\Services\GroutFormulaService;
use App\Http\Requests\Admin\StoreGroutFormulaRequest;
use App\Http\Requests\Admin\UpdateGroutFormulaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GroutFormulaController extends Controller
{
    protected $formulaService;

    public function __construct(GroutFormulaService $formulaService)
    {
        $this->formulaService = $formulaService;
    }

    /**
     * Display a listing of Grout formulas.
     */
    public function index(Request $request)
    {
        $query = GroutFormula::with(['color', 'creator']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('color', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $formulas = $query->latest()->paginate(10)->withQueryString();

        return view('admin.grout_formulas.index', compact('formulas'));
    }

    /**
     * Display the specified formula version.
     */
    public function show(GroutFormula $grout_formula)
    {
        $formula = $grout_formula->load(['color', 'creator', 'items.rawMaterial', 'items.unit']);

        // Group items by mix stage
        $stage1Items = $formula->items->where('mix_stage', 'Stage 1');
        $stage2Items = $formula->items->where('mix_stage', 'Stage 2');

        return view('admin.grout_formulas.show', compact('formula', 'stage1Items', 'stage2Items'));
    }

    /**
     * Show the form for creating a new formula.
     */
    public function create()
    {
        $colors = Color::where('is_active', true)->orderBy('name')->get();
        $rawMaterials = RawMaterial::where('is_active', true)->orderBy('name')->get();
        $units = Unit::getActive();

        return view('admin.grout_formulas.create', compact('colors', 'rawMaterials', 'units'));
    }

    /**
     * Store a newly created formula in storage.
     */
    public function store(StoreGroutFormulaRequest $request)
    {
        try {
            $itemsData = $this->formulaService->validateFormula($request->input('items', []));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        DB::transaction(function () use ($request, $itemsData) {
            $colorId = $request->input('color_id');
            $version = $this->formulaService->incrementVersion($colorId);

            $formula = GroutFormula::create([
                'color_id' => $colorId,
                'version' => $version,
                'remarks' => $request->input('remarks'),
                'is_active' => $request->boolean('is_active', true),
                'created_by' => auth()->id(),
            ]);

            foreach ($itemsData as $item) {
                GroutFormulaItem::create([
                    'grout_formula_id' => $formula->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'quantity' => $item['quantity'],
                    'unit_id' => $item['unit_id'],
                    'mix_stage' => $item['mix_stage'],
                    'display_order' => $item['display_order'] ?? 0,
                ]);
            }

            // Enforce only one active formula version per Color
            if ($formula->is_active) {
                GroutFormula::where('color_id', $colorId)
                    ->where('id', '!=', $formula->id)
                    ->update(['is_active' => false]);
            }

            \App\Services\ActivityLogService::log(
                'FORMULA_UPDATED',
                "Created new Grout formula version #{$formula->version} for color ID {$colorId}."
            );
        });

        return redirect()->route('admin.grout-formulas.index')
            ->with('success', 'Grout Formula version created successfully.');
    }

    /**
     * Show the form for editing the formula.
     */
    public function edit(GroutFormula $grout_formula)
    {
        $formula = $grout_formula->load('items');
        $colors = Color::where('is_active', true)->orderBy('name')->get();
        $rawMaterials = RawMaterial::where('is_active', true)->orderBy('name')->get();
        $units = Unit::getActive();

        return view('admin.grout_formulas.edit', compact('formula', 'colors', 'rawMaterials', 'units'));
    }

    /**
     * Update the formula in storage.
     */
    public function update(UpdateGroutFormulaRequest $request, GroutFormula $grout_formula)
    {
        try {
            $itemsData = $this->formulaService->validateFormula($request->input('items', []));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        DB::transaction(function () use ($request, $grout_formula, $itemsData) {
            $colorId = $request->input('color_id');

            $grout_formula->update([
                'color_id' => $colorId,
                'remarks' => $request->input('remarks'),
                'is_active' => $request->boolean('is_active'),
                'updated_by' => auth()->id(),
            ]);

            // Sync items by deleting and recreating
            $grout_formula->items()->delete();

            foreach ($itemsData as $item) {
                GroutFormulaItem::create([
                    'grout_formula_id' => $grout_formula->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'quantity' => $item['quantity'],
                    'unit_id' => $item['unit_id'],
                    'mix_stage' => $item['mix_stage'],
                    'display_order' => $item['display_order'] ?? 0,
                ]);
            }

            // Enforce only one active formula version per Color
            if ($grout_formula->is_active) {
                GroutFormula::where('color_id', $colorId)
                    ->where('id', '!=', $grout_formula->id)
                    ->update(['is_active' => false]);
            }

            \App\Services\ActivityLogService::log(
                'FORMULA_UPDATED',
                "Updated Grout formula version #{$grout_formula->version} for color ID {$colorId}."
            );
        });

        return redirect()->route('admin.grout-formulas.index')
            ->with('success', 'Grout Formula updated successfully.');
    }

    /**
     * Remove the formula from storage.
     */
    public function destroy(GroutFormula $grout_formula)
    {
        $grout_formula->delete();

        return redirect()->route('admin.grout-formulas.index')
            ->with('success', 'Grout Formula version deleted successfully.');
    }
}
