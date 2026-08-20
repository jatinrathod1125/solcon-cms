<?php

namespace App\Http\Controllers;

use App\Models\ProductionBatch;
use App\Models\Grade;
use App\Models\Machine;
use App\Models\RawMaterial;
use App\Models\User;
use App\Http\Requests\StoreProductionRequest;
use App\Http\Requests\CompleteProductionRequest;
use App\Services\ProductionService;
use App\Services\FormulaService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductionController extends Controller
{
    /**
     * Display the production dashboard and list of batches.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $dept = currentDepartment();
        if ($dept) {
            $code = strtoupper($dept->code);
            if ($code === 'GRT') {
                return redirect()->route('grout-production.index');
            } elseif ($code === 'EPX' || $code === 'EP') {
                return redirect()->route('epoxy.index');
            }
        }

        $isSupervisor = $user && $user->isSupervisor();

        // --- DASHBOARD STATS ---
        $statsQuery = ProductionBatch::query();
        if ($isSupervisor) {
            $statsQuery->whereHas('machine', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            });
        }

        // 1. Running Batches
        $runningBatchesCount = (clone $statsQuery)->where('status', 'running')->count();

        // 2. Completed Today
        $todayStart = Carbon::today();
        $todayEnd = Carbon::today()->endOfDay();
        $completedTodayQuery = (clone $statsQuery)
            ->where('status', 'completed')
            ->whereBetween('end_time', [$todayStart, $todayEnd]);
        
        $completedTodayCount = (clone $completedTodayQuery)->count();

        // 3. Today's Production Bags
        $todayBagsSum = (clone $completedTodayQuery)->sum('output_bags');

        // 4. Today's Production KG
        $todayKgSum = (clone $completedTodayQuery)->sum('output_kg');

        // 5. Running Machines
        $runningMachinesCount = (clone $statsQuery)
            ->where('status', 'running')
            ->distinct('machine_id')
            ->count('machine_id');

        // 6. Idle Machines
        $activeMachinesQuery = Machine::where('is_active', true);
        if ($isSupervisor) {
            $activeMachinesQuery->where('department_id', $user->department_id);
        }
        $totalActiveMachines = $activeMachinesQuery->count();
        $idleMachinesCount = max(0, $totalActiveMachines - $runningMachinesCount);

        // 7. Low Stock Counter
        $lowStockQuery = RawMaterial::where('is_active', true)->whereColumn('current_stock', '<', 'minimum_stock');
        if ($isSupervisor) {
            $lowStockQuery->where('department_id', $user->department_id);
        }
        $lowStockCount = $lowStockQuery->count();

        // --- FILTERED PRODUCTION TABLE ---
        $query = ProductionBatch::with(['machine', 'grade.bagSize', 'supervisor']);
        if ($isSupervisor) {
            $query->whereHas('machine', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            });
        }

        // Search by batch number
        if ($request->filled('search')) {
            $query->where('batch_no', 'like', '%' . trim($request->input('search')) . '%');
        }

        // Filter by Machine
        if ($request->filled('machine_id')) {
            $query->where('machine_id', $request->input('machine_id'));
        }

        // Filter by Grade
        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->input('grade_id'));
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by Date
        if ($request->filled('date')) {
            $query->whereDate('start_time', $request->input('date'));
        }

        $batches = $query->latest('id')->paginate(10)->withQueryString();

        // Filter options for dropdowns
        $filterMachinesQuery = Machine::where('is_active', true);
        $filterGradesQuery = Grade::where('is_active', true)->whereHas('activeFormula');
        if ($isSupervisor) {
            $filterMachinesQuery->where('department_id', $user->department_id);
            $filterGradesQuery->where('department_id', $user->department_id);
        }
        if (function_exists('currentBrand') && currentBrand()) {
            $filterGradesQuery->forBrand(currentBrand());
        }
        $machines = $filterMachinesQuery->orderBy('name')->get();
        $grades = $filterGradesQuery->orderBy('name')->get();

        return view('production.index', compact(
            'batches',
            'machines',
            'grades',
            'runningBatchesCount',
            'completedTodayCount',
            'todayBagsSum',
            'todayKgSum',
            'runningMachinesCount',
            'idleMachinesCount',
            'lowStockCount'
        ));
    }

    /**
     * Show the start batch creation form.
     */
    public function create()
    {
        $user = auth()->user();
        
        $dept = currentDepartment();
        if ($dept) {
            $code = strtoupper($dept->code);
            if ($code === 'GRT') {
                return redirect()->route('grout-production.create');
            } elseif ($code === 'EPX' || $code === 'EP') {
                return redirect()->route('epoxy.bucket-assembly');
            }
        }

        $isSupervisor = $user && $user->isSupervisor();

        $machinesQuery = Machine::where('is_active', true);
        $gradesQuery = Grade::where('is_active', true)->whereHas('activeFormula');

        if ($isSupervisor) {
            $machinesQuery->where('department_id', $user->department_id);
            $gradesQuery->where('department_id', $user->department_id);
        }

        if (function_exists('currentBrand') && currentBrand()) {
            $gradesQuery->forBrand(currentBrand());
        }

        $machines = $machinesQuery->orderBy('name')->get();
        $grades = $gradesQuery->orderBy('name')->get();

        // Dynamically set running/idle status for each machine
        $runningMachineIds = ProductionBatch::whereIn('status', ['running', 'paused'])->pluck('machine_id')->toArray();
        foreach ($machines as $machine) {
            $machine->status = in_array($machine->id, $runningMachineIds) ? 'running' : 'idle';
        }

        $batchNo = \App\Services\BatchNumberService::generate();
        $startTime = now();

        $supervisors = User::whereHas('roles', function ($q) {
            $q->where('slug', 'supervisor');
        })->where('is_active', true)->orderBy('name')->get();

        $coupons = RawMaterial::where('is_coupon', true)->where('is_active', true)->orderBy('name')->get();

        return view('production.create', compact('machines', 'grades', 'batchNo', 'startTime', 'supervisors', 'coupons'));
    }

    public function store(StoreProductionRequest $request)
    {
        try {
            $couponId = $request->filled('coupon_raw_material_id') ? (int) $request->input('coupon_raw_material_id') : null;
            $batch = ProductionService::startBatch(
                (int) $request->input('machine_id'),
                (int) $request->input('grade_id'),
                $request->input('remarks'),
                $request->input('batch_no'),
                $couponId
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Production batch started successfully.',
                    'redirect_url' => route('production.show', $batch->id),
                ]);
            }

            return redirect()->route('production.index')
                ->with('success', 'Production batch started successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()->withErrors(['machine_id' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Display production batch details or running screen depending on status.
     */
    public function show(ProductionBatch $batch)
    {
        $user = auth()->user();
        // Check Supervisor department boundary
        if ($user && $user->isSupervisor() && $batch->machine->department_id !== $user->department_id) {
            abort(403, 'Unauthorized access to department batch data.');
        }

        $batch->load(['machine', 'grade.bagSize', 'supervisor', 'ledgers.rawMaterial.stockUnit', 'ledgers.packingMaterial.unit']);

        if (in_array($batch->status, ['running', 'paused'])) {
            $coupons = RawMaterial::where('is_coupon', true)->where('is_active', true)->orderBy('name')->get();
            return view('production.running', compact('batch', 'coupons'));
        }

        return view('production.show', compact('batch'));
    }

    /**
     * Show the complete batch screen.
     */
    public function completeForm(ProductionBatch $batch)
    {
        $user = auth()->user();
        if ($user && $user->isSupervisor() && $batch->machine->department_id !== $user->department_id) {
            abort(403, 'Unauthorized access.');
        }

        if ($batch->status !== 'running') {
            return redirect()->route('production.show', $batch->id)
                ->with('error', 'Only running batches can be completed.');
        }

        $batch->load(['machine', 'grade.bagSize', 'supervisor']);

        return view('production.complete', compact('batch'));
    }

    /**
     * Complete a running production batch.
     */
    public function complete(CompleteProductionRequest $request, ProductionBatch $batch)
    {
        try {
            ProductionService::completeBatch(
                $batch->id,
                (float) $request->input('output_bags'),
                $request->input('end_time'),
                $request->input('remarks')
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Production batch completed successfully.',
                    'redirect_url' => route('production.index'),
                ]);
            }

            return redirect()->route('production.index')
                ->with('success', 'Production batch completed successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Fetch active formula details for dynamic preview (AJAX).
     */
    public function getFormula(Grade $grade)
    {
        $user = auth()->user();
        if ($user && $user->isSupervisor() && $grade->department_id !== $user->department_id) {
            return response()->json(['error' => 'Unauthorized grade access.'], 403);
        }

        $formula = FormulaService::getActiveFormula($grade->id);

        if (!$formula) {
            return response()->json(['error' => 'No active formula configured for this grade.'], 404);
        }

        $items = [];
        foreach ($formula->items as $item) {
            $isPacking = ($item->item_type ?? 'raw') === 'packing' || (bool)$item->packing_material_id;
            $items[] = [
                'item_type' => $isPacking ? 'packing' : 'raw',
                'raw_material_id' => $item->raw_material_id,
                'raw_material_code' => $isPacking ? ($item->packingMaterial->code ?? 'PM') : ($item->rawMaterial->code ?? ''),
                'raw_material_name' => $isPacking ? ($item->packingMaterial->name ?? '') : ($item->rawMaterial->name ?? ''),
                'packing_material_id' => $item->packing_material_id,
                'quantity' => (float) $item->quantity,
                'unit_code' => $item->unit->code,
                'consumption_method' => $item->consumption_method ?? 'formula',
                'consumption_per_unit' => (float) ($item->consumption_per_unit ?? 1.0),
            ];
        }

        return response()->json([
            'formula_id' => $formula->id,
            'version' => $formula->version,
            'bag_size_name' => $grade->bagSize->name,
            'bag_size_value' => (float) $grade->bagSize->value,
            'items' => $items,
        ]);
    }

    /**
     * Sidebar link redirect: go to active running batch, or list them if multiple, or redirect to dashboard.
     */
    public function runningActive()
    {
        $user = auth()->user();
        $query = ProductionBatch::whereIn('status', ['running', 'paused']);

        if ($user && $user->isSupervisor()) {
            $query->whereHas('machine', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            });
        }

        $running = $query->get();

        if ($running->count() === 1) {
            return redirect()->route('production.show', $running->first()->id);
        }

        // If multiple or none, redirect to dashboard table
        return redirect()->route('production.index');
    }

    /**
     * Display a paginated history list of completed production batches.
     */
    public function history(Request $request)
    {
        $dept = currentDepartment();
        if ($dept) {
            $code = strtoupper($dept->code);
            if ($code === 'GRT') {
                return redirect()->route('grout-production.index');
            } elseif ($code === 'EPX' || $code === 'EP') {
                return redirect()->route('epoxy.index');
            }
        }

        $user = auth()->user();
        $isSupervisor = $user && $user->isSupervisor();

        $query = ProductionBatch::with(['machine', 'grade.bagSize', 'supervisor']);
        
        if ($isSupervisor) {
            $query->whereHas('machine', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            });
        }

        // Search by batch number
        if ($request->filled('search')) {
            $query->where('batch_no', 'like', '%' . trim($request->input('search')) . '%');
        }

        // Filter by Machine
        if ($request->filled('machine_id')) {
            $query->where('machine_id', $request->input('machine_id'));
        }

        // Filter by Grade
        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->input('grade_id'));
        }

        // Filter by Date
        if ($request->filled('date')) {
            $query->whereDate('start_time', $request->input('date'));
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by Supervisor
        if ($request->filled('supervisor_id')) {
            $query->where('supervisor_id', $request->input('supervisor_id'));
        }

        // Export CSV logic
        if ($request->input('export') === 'csv') {
            $batchesToExport = $query->latest('id')->get();
            $csvFileName = 'production_history_' . now()->format('Ymd_His') . '.csv';
            
            // Log PDF/CSV Downloaded activity
            \App\Services\ActivityLogService::log(
                'PDF_DOWNLOADED',
                "Production history CSV downloaded containing " . $batchesToExport->count() . " records.",
                auth()->id()
            );

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$csvFileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
            
            $columns = ['Batch Number', 'Machine Code', 'Machine Name', 'Grade Code', 'Grade Name', 'Supervisor', 'Start Time', 'End Time', 'Output Bags', 'Output KG', 'Status'];
            
            $callback = function() use($batchesToExport, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                
                foreach ($batchesToExport as $b) {
                    fputcsv($file, [
                        $b->batch_no,
                        $b->machine->code,
                        $b->machine->name,
                        $b->grade->code,
                        $b->grade->name,
                        $b->supervisor->name,
                        $b->start_time ? $b->start_time->format('Y-m-d H:i:s') : '',
                        $b->end_time ? $b->end_time->format('Y-m-d H:i:s') : '',
                        $b->output_bags,
                        $b->output_kg,
                        $b->status
                    ]);
                }
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }

        $batches = $query->latest('id')->paginate(10)->withQueryString();

        // Dropdowns data
        $filterMachinesQuery = Machine::where('is_active', true);
        $filterGradesQuery = Grade::where('is_active', true);
        if ($isSupervisor) {
            $filterMachinesQuery->where('department_id', $user->department_id);
            $filterGradesQuery->where('department_id', $user->department_id);
        }
        $machines = $filterMachinesQuery->orderBy('name')->get();
        $grades = $filterGradesQuery->orderBy('name')->get();

        // Supervisors list
        $supervisors = \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'supervisor');
        })->orderBy('name')->get();

        return view('production.history', compact('batches', 'machines', 'grades', 'supervisors'));
    }

    /**
     * Display the stock ledger movements log.
     */
    public function ledger(Request $request)
    {
        $user = auth()->user();
        $isSupervisor = $user && $user->isSupervisor();

        $query = \App\Models\StockLedger::with([
            'rawMaterial.stockUnit',
            'packingMaterial.unit',
            'packingMaterial.category',
            'batch.machine',
            'batch.grade',
            'batch.supervisor',
            'groutBatch.machine',
            'groutBatch.color',
            'groutBatch.operator',
            'creator'
        ]);

        if ($isSupervisor) {
            $query->where(function ($q) use ($user) {
                $q->whereHas('batch.machine', function ($qm) use ($user) {
                    $qm->where('department_id', $user->department_id);
                })->orWhereHas('groutBatch.machine', function ($qm) use ($user) {
                    $qm->where('department_id', $user->department_id);
                })->orWhere(function ($qo) use ($user) {
                    $qo->whereNull('batch_id')
                       ->whereNull('grout_batch_id')
                       ->where(function ($qm) use ($user) {
                           $qm->whereHas('rawMaterial', function ($qr) use ($user) {
                               $qr->where('department_id', $user->department_id);
                           })->orWhereNotNull('packing_material_id');
                       });
                });
            });
        }

        // Search by batch number, material name, code, or remarks
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereHas('batch', function ($qb) use ($search) {
                    $qb->where('batch_no', 'like', '%' . $search . '%');
                })->orWhereHas('groutBatch', function ($qg) use ($search) {
                    $qg->where('batch_no', 'like', '%' . $search . '%');
                })->orWhereHas('rawMaterial', function ($qr) use ($search) {
                    $qr->where('name', 'like', '%' . $search . '%')
                       ->orWhere('code', 'like', '%' . $search . '%');
                })->orWhereHas('packingMaterial', function ($qp) use ($search) {
                    $qp->where('name', 'like', '%' . $search . '%')
                       ->orWhere('code', 'like', '%' . $search . '%');
                })->orWhere('remarks', 'like', '%' . $search . '%');
            });
        }

        // Filter by Material Type
        if ($request->filled('material_type')) {
            $mType = $request->input('material_type');
            if ($mType === 'raw') {
                $query->whereNotNull('raw_material_id');
            } elseif ($mType === 'packing') {
                $query->whereNotNull('packing_material_id');
            }
        }

        // Filter by Raw Material
        if ($request->filled('raw_material_id')) {
            $query->where('raw_material_id', $request->input('raw_material_id'));
        }

        // Filter by Packing Material
        if ($request->filled('packing_material_id')) {
            $query->where('packing_material_id', $request->input('packing_material_id'));
        }

        // Filter by Transaction Type
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->input('transaction_type'));
        }

        // Filter by Date Range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        $ledgers = $query->latest('id')->paginate(15)->withQueryString();

        // Raw Materials for filter select option
        $rawMaterialsQuery = \App\Models\RawMaterial::where('is_active', true);
        if ($isSupervisor) {
            $rawMaterialsQuery->where('department_id', $user->department_id);
        }
        $rawMaterials = $rawMaterialsQuery->orderBy('name')->get();

        // Packing Materials for filter select option
        $packingMaterials = \App\Models\PackingMaterial::where('status', 'active')
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('production.ledger', compact('ledgers', 'rawMaterials', 'packingMaterials'));
    }

    /**
     * Cancel / Discard a running/paused batch.
     */
    public function cancel(Request $request, ProductionBatch $batch)
    {
        try {
            ProductionService::cancelBatch($batch->id, $request->input('remarks'));

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Production batch cancelled successfully.',
                    'redirect_url' => route('production.index'),
                ]);
            }

            return redirect()->route('production.index')
                ->with('success', 'Production batch cancelled successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Pause a running batch.
     */
    public function pause(ProductionBatch $batch)
    {
        try {
            ProductionService::pauseBatch($batch->id);

            return redirect()->route('production.show', $batch->id)
                ->with('success', 'Production batch paused successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Resume a paused batch.
     */
    public function resume(ProductionBatch $batch)
    {
        try {
            ProductionService::resumeBatch($batch->id);

            return redirect()->route('production.show', $batch->id)
                ->with('success', 'Production batch resumed successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the coupon of a running/paused production batch.
     */
    public function updateCoupon(Request $request, ProductionBatch $batch)
    {
        $user = auth()->user();
        if ($user && $user->isSupervisor() && $batch->machine->department_id !== $user->department_id) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'coupon_raw_material_id' => ['nullable', 'exists:raw_materials,id']
        ]);

        try {
            $couponId = $request->filled('coupon_raw_material_id') ? (int) $request->input('coupon_raw_material_id') : null;
            ProductionService::updateBatchCoupon($batch, $couponId);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Coupon updated successfully.'
                ]);
            }

            return redirect()->route('production.show', $batch->id)
                ->with('success', 'Coupon updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
