<?php

namespace App\Http\Controllers;

use App\Models\EpoxyAssembly;
use App\Models\EpoxyProduct;
use App\Models\Color;
use App\Models\EpoxyFillerColor;
use App\Models\EpoxyComponent;
use App\Models\EpoxyComponentMapping;
use App\Models\EpoxyComponentPreparation;
use App\Models\RawMaterial;
use App\Models\Department;
use App\Services\EpoxyAssemblyService;
use App\Services\StockService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class EpoxyAssemblyController extends Controller
{
    /**
     * Display Epoxy manual assembly log history, component entry history, and daily summary.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $dept = Department::where('code', 'EPX')->first();
        if ($dept) {
            session(['current_department_id_' . $user->id => $dept->id]);
        }

        // 1. Daily Component Summary Report
        $targetDate = $request->input('date', now()->format('Y-m-d'));
        $dailySummary = EpoxyComponentPreparation::with(['component', 'color'])
            ->whereDate('created_at', $targetDate)
            ->select('epoxy_component_id', 'epoxy_filler_color_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('epoxy_component_id', 'epoxy_filler_color_id')
            ->get();

        // 2. Bucket Assemblies List
        $assemblyQuery = EpoxyAssembly::with(['product', 'color', 'epoxyFillerColor', 'operator']);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $cleanSearch = ltrim(str_replace('#EPX-', '', strtoupper($search)), '0');
            $assemblyQuery->where(function ($q) use ($search, $cleanSearch) {
                $q->where('id', 'like', '%' . $cleanSearch . '%')
                  ->orWhere('remarks', 'like', '%' . $search . '%');
            });
        }
        if ($request->filled('epoxy_product_id')) {
            $assemblyQuery->where('epoxy_product_id', $request->input('epoxy_product_id'));
        }
        if ($request->filled('epoxy_filler_color_id')) {
            $assemblyQuery->where('epoxy_filler_color_id', $request->input('epoxy_filler_color_id'));
        }
        if ($request->filled('date')) {
            $assemblyQuery->whereDate('created_at', $request->input('date'));
        }

        $assemblies = $assemblyQuery->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'assemblies_page')
            ->withQueryString();

        // 3. Component Preparations List
        $prepQuery = EpoxyComponentPreparation::with(['component', 'color', 'operator']);
        if ($request->filled('date')) {
            $prepQuery->whereDate('created_at', $request->input('date'));
        }
        $preparations = $prepQuery->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'preps_page')
            ->withQueryString();

        $products = EpoxyProduct::orderBy('name')->get();
        $colors = EpoxyFillerColor::where('is_active', true)->orderBy('name')->get();

        return view('epoxy_assembly.index', compact('assemblies', 'preparations', 'dailySummary', 'dept', 'products', 'colors', 'targetDate'));
    }

    /**
     * Show Component Entry Form.
     */
    public function componentEntryForm()
    {
        $user = auth()->user();
        $dept = Department::where('code', 'EPX')->first();
        if ($dept) {
            session(['current_department_id_' . $user->id => $dept->id]);
        }

        $components = EpoxyComponent::where('is_active', true)
            ->with(['unit', 'color', 'parentComponent', 'rawMaterial', 'activeFormula'])
            ->orderBy('name')
            ->get();

        return view('epoxy_assembly.component_entry', compact('components', 'dept'));
    }

    /**
     * Store Component Entry (Single).
     */
    public function storeComponentEntry(Request $request)
    {
        $request->validate([
            'epoxy_component_id' => 'required|exists:epoxy_components,id',
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string|max:1000',
        ]);

        try {
            EpoxyAssemblyService::prepareComponent(
                (int) $request->epoxy_component_id,
                (int) $request->quantity,
                $request->remarks
            );

            return redirect()->route('epoxy.index')->with('success', 'Epoxy component preparation saved successfully.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Store Bulk Component Entry (AJAX / Form Batch).
     */
    public function storeBulkComponentEntry(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.epoxy_component_id' => 'required|exists:epoxy_components,id',
            'items.*.quantity' => 'nullable|integer|min:0',
            'items.*.remarks' => 'nullable|string|max:500',
            'global_remarks' => 'nullable|string|max:1000',
        ]);

        try {
            $processedCount = 0;
            $items = $request->input('items', []);

            DB::transaction(function () use ($items, $request, &$processedCount) {
                foreach ($items as $item) {
                    $qty = (int) ($item['quantity'] ?? 0);
                    if ($qty <= 0) {
                        continue;
                    }

                    $componentId = (int) $item['epoxy_component_id'];
                    $remarks = !empty($item['remarks']) 
                        ? trim($item['remarks']) 
                        : (trim($request->input('global_remarks')) ?: null);

                    EpoxyAssemblyService::prepareComponent(
                        $componentId,
                        $qty,
                        $remarks
                    );

                    $processedCount++;
                }
            });

            if ($processedCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid component quantities were entered (quantity must be > 0).',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully logged preparation for {$processedCount} ready component(s). Stock deducted.",
                'processed_count' => $processedCount,
            ]);
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first() ?? 'Validation error occurred.';
            return response()->json([
                'success' => false,
                'message' => $firstError,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show Bucket Assembly Form.
     */
    public function bucketAssemblyForm()
    {
        $user = auth()->user();
        $dept = Department::where('code', 'EPX')->first();
        if ($dept) {
            session(['current_department_id_' . $user->id => $dept->id]);
        }

        $products = EpoxyProduct::where('is_active', true)
            ->with('activeFormula')
            ->get();

        $colors = EpoxyFillerColor::where('is_active', true)->orderBy('name')->get();

        return view('epoxy_assembly.create', compact('products', 'colors', 'dept'));
    }

    /**
     * Store Bucket Assembly.
     */
    public function storeBucketAssembly(Request $request)
    {
        $request->validate([
            'epoxy_product_id' => 'required|exists:epoxy_products,id',
            'quantity' => 'required|integer|min:1',
            'color_id' => 'nullable|exists:colors,id',
            'epoxy_filler_color_id' => 'nullable|exists:epoxy_filler_colors,id',
            'remarks' => 'nullable|string|max:1000',
        ]);

        try {
            $assembly = EpoxyAssemblyService::assembleProduct(
                (int) $request->epoxy_product_id,
                (int) $request->quantity,
                $request->color_id ? (int) $request->color_id : null,
                $request->remarks,
                $request->epoxy_filler_color_id ? (int) $request->epoxy_filler_color_id : null
            );

            return redirect()->route('epoxy.index')->with('success', "Bucket assembly completed successfully. Ready component stock deducted.");
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * AJAX Endpoint: Preview formula ingredients scaled by target quantity.
     */
    public function previewFormula(Request $request, EpoxyProduct $product)
    {
        $quantity = (int) $request->query('quantity', 1);
        $colorId = $request->query('epoxy_filler_color_id');
        $legacyColorId = $request->query('color_id');

        $formula = $product->activeFormula;
        if (!$formula) {
            return response()->json(['error' => 'No active formula for this product.'], 422);
        }

        $color = null;
        $legacyColor = null;

        if ($product->requires_color) {
            if ($colorId) {
                $color = EpoxyFillerColor::find($colorId);
            } elseif ($legacyColorId) {
                $legacyColor = Color::find($legacyColorId);
            }
        }

        $deptEPX = Department::where('code', 'EPX')->first();
        $deptId = $deptEPX ? $deptEPX->id : null;

        $items = [];
        foreach ($formula->items as $item) {
            $rawMaterial = $item->rawMaterial;
            $resolvedRm = $rawMaterial;
            $status = 'Available';

            if ($item->is_dynamic_color) {
                if ($color) {
                    $component = EpoxyComponent::where('template_material_id', $rawMaterial->id)->first();
                    if ($component) {
                        $mapping = EpoxyComponentMapping::where('epoxy_component_id', $component->id)
                            ->where('epoxy_filler_color_id', $color->id)
                            ->first();
                        if ($mapping) {
                            $resolvedRm = $mapping->rawMaterial;
                        } else {
                            $resolvedRm = (object)[
                                'id' => null,
                                'name' => "{$rawMaterial->name} ({$color->name} - Not Configured)",
                                'code' => $rawMaterial->code,
                                'current_stock' => 0,
                            ];
                            $status = 'Missing Component';
                        }
                    }
                } elseif ($legacyColor) {
                    // Fallback to legacy resolution for tests/grout colors
                    $colorCodeSuffix = str_replace('GR-', '', $legacyColor->code);
                    $specificRmCode = $rawMaterial->code . '-' . $colorCodeSuffix;

                    $resolvedRm = RawMaterial::where('department_id', $deptId)
                        ->where('code', $specificRmCode)
                        ->first();

                    if (!$resolvedRm) {
                        $firstWord = explode(' ', trim($legacyColor->name))[0];
                        $resolvedRm = RawMaterial::where('department_id', $deptId)
                            ->where('name', 'like', '%' . $firstWord . '%')
                            ->where('name', 'like', '%Filler%')
                            ->first();
                    }

                    if (!$resolvedRm) {
                        $resolvedRm = (object)[
                            'id' => null,
                            'name' => "{$rawMaterial->name} ({$legacyColor->name} - Not Configured)",
                            'code' => $specificRmCode,
                            'current_stock' => 0,
                        ];
                        $status = 'Missing Component';
                    }
                } else {
                    $resolvedRm = (object)[
                        'id' => null,
                        'name' => "{$rawMaterial->name} (Color Required)",
                        'code' => $rawMaterial->code,
                        'current_stock' => 0,
                    ];
                    $status = 'Missing Component';
                }
            } else {
                $component = EpoxyComponent::where('template_material_id', $rawMaterial->id)->first();
                if ($component) {
                    $mapping = EpoxyComponentMapping::where('epoxy_component_id', $component->id)
                        ->whereNull('epoxy_filler_color_id')
                        ->first();
                    if ($mapping) {
                        $resolvedRm = $mapping->rawMaterial;
                    }
                }
            }

            $needed = (float) $item->quantity * $quantity;
            $stock = $resolvedRm ? (float) $resolvedRm->current_stock : 0;

            if ($resolvedRm && $resolvedRm->id && $stock < $needed) {
                $status = 'Insufficient Stock';
            }

            $items[] = [
                'name' => $resolvedRm ? $resolvedRm->name : 'Unknown Raw Material',
                'code' => $resolvedRm ? $resolvedRm->code : 'N/A',
                'quantity' => $needed,
                'unit' => $item->unit->code,
                'type' => $item->material_type,
                'stock' => $stock,
                'status' => $status,
            ];
        }

        return response()->json([
            'product_name' => $product->name,
            'quantity' => $quantity,
            'color_name' => $color ? $color->name : ($legacyColor ? $legacyColor->name : null),
            'items' => $items,
        ]);
    }
}
