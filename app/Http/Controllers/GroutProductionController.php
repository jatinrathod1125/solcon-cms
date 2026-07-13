<?php

namespace App\Http\Controllers;

use App\Models\GroutProductionBatch;
use App\Models\Machine;
use App\Models\Color;
use App\Models\Department;
use App\Models\User;
use App\Http\Requests\StoreGroutProductionRequest;
use App\Http\Requests\CompleteGroutProductionRequest;
use App\Services\GroutProductionService;
use App\Services\MixTimerService;
use App\Services\NotificationService;
use App\Services\PackingService;
use App\Services\BatchNumberService;
use App\Services\DepartmentAccessService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GroutProductionController extends Controller
{
    protected $productionService;
    protected $timerService;
    protected $notificationService;
    protected $packingService;
    protected $accessService;

    public function __construct(
        GroutProductionService $productionService,
        MixTimerService $timerService,
        NotificationService $notificationService,
        PackingService $packingService,
        DepartmentAccessService $accessService
    ) {
        $this->productionService = $productionService;
        $this->timerService = $timerService;
        $this->notificationService = $notificationService;
        $this->packingService = $packingService;
        $this->accessService = $accessService;
    }

    /**
     * Display the Grout Production dashboard & history list.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $dept = currentDepartment();
        if ($dept && strtoupper($dept->code) !== 'GRT') {
            $code = strtoupper($dept->code);
            if ($code === 'TAD') {
                return redirect()->route('production.index');
            } elseif ($code === 'EPX' || $code === 'EP') {
                return redirect()->route('epoxy.index');
            }
        }

        $groutDept = Department::where('code', 'GRT')->firstOrFail();

        // 1. Enforce department boundary access
        if (!$this->accessService->checkAccess($user, $groutDept->id)) {
            abort(403, 'Unauthorized access to Grout Department.');
        }

        // --- DASHBOARD MACHINE STATUS PANELS ---
        // Fetch M-01, M-04, M-05 specifically
        $machineCodes = ['M-01', 'M-04', 'M-05'];
        $machines = Machine::whereIn('code', $machineCodes)
            ->where('department_id', $groutDept->id)
            ->get();

        $liveMachineStatuses = [];
        foreach ($machines as $machine) {
            // Find active running batch on this machine
            $activeBatch = GroutProductionBatch::where('machine_id', $machine->id)
                ->where('status', '!=', 'Completed')
                ->with(['color', 'operator'])
                ->first();

            $remainingSeconds = 3600;
            if ($activeBatch) {
                $remainingSeconds = $this->timerService->getRemainingSeconds($activeBatch);
            }

            $liveMachineStatuses[$machine->code] = [
                'machine' => $machine,
                'batch' => $activeBatch,
                'remaining_seconds' => $remainingSeconds,
            ];
        }

        // --- BATCH HISTORY LIST ---
        $query = GroutProductionBatch::with(['machine', 'color', 'operator']);

        if ($request->filled('search')) {
            $query->where('batch_no', 'like', '%' . trim($request->input('search')) . '%');
        }

        if ($request->filled('machine_id')) {
            $query->where('machine_id', $request->input('machine_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $batches = $query->latest('id')->paginate(10)->withQueryString();
        $filterMachines = Machine::where('department_id', $groutDept->id)->get();

        return view('grout_production.index', compact('batches', 'liveMachineStatuses', 'filterMachines'));
    }

    /**
     * Show form to start Grout batch.
     */
    public function create()
    {
        $user = auth()->user();
        
        $dept = currentDepartment();
        if ($dept && strtoupper($dept->code) !== 'GRT') {
            $code = strtoupper($dept->code);
            if ($code === 'TAD') {
                return redirect()->route('production.create');
            } elseif ($code === 'EPX' || $code === 'EP') {
                return redirect()->route('epoxy.bucket-assembly');
            }
        }

        $groutDept = Department::where('code', 'GRT')->firstOrFail();

        if (!$this->accessService->checkAccess($user, $groutDept->id)) {
            abort(403, 'Unauthorized access to Grout Department.');
        }

        $machines = Machine::where('department_id', $groutDept->id)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        // Enforce status dynamic markers for creation page selection
        $runningMachineIds = GroutProductionBatch::where('status', '!=', 'Completed')
            ->pluck('machine_id')
            ->toArray();
        foreach ($machines as $machine) {
            $machine->status = in_array($machine->id, $runningMachineIds) ? 'running' : 'idle';
        }

        $colors = Color::where('department_id', $groutDept->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $batchNo = BatchNumberService::generate('GRT', \App\Models\GroutProductionBatch::class);
        $startTime = now();

        return view('grout_production.create', compact('machines', 'colors', 'batchNo', 'startTime'));
    }

    /**
     * Start a Grout production batch.
     */
    public function store(StoreGroutProductionRequest $request)
    {
        try {
            $batch = $this->productionService->startBatch(
                (int) $request->input('machine_id'),
                (int) $request->input('color_id'),
                $request->input('remarks'),
                $request->input('batch_no')
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Grout Production batch started successfully.',
                    'redirect_url' => route('grout-production.running', $batch->id),
                ]);
            }

            return redirect()->route('grout-production.running', $batch->id)
                ->with('success', 'Grout Production batch started successfully.');
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
     * Display running Grout production batch screen (Timeline floor panel).
     */
    public function running(GroutProductionBatch $batch)
    {
        $user = auth()->user();
        if (!$this->accessService->checkAccess($user, $batch->machine->department_id)) {
            abort(403, 'Unauthorized department batch operations.');
        }

        if ($batch->status === 'Completed') {
            return redirect()->route('grout-production.show', $batch->id);
        }

        $batch->load(['machine', 'color', 'operator']);
        
        $remainingSeconds = $this->timerService->getRemainingSeconds($batch);
        $isTimerCompleted = $this->timerService->isTimerCompleted($batch);

        return view('grout_production.running', compact('batch', 'remainingSeconds', 'isTimerCompleted'));
    }

    /**
     * Show Grout batch details (History review).
     */
    public function show(GroutProductionBatch $batch)
    {
        $user = auth()->user();
        if (!$this->accessService->checkAccess($user, $batch->machine->department_id)) {
            abort(403, 'Unauthorized.');
        }

        $batch->load(['machine', 'color', 'operator', 'ledgers.rawMaterial.stockUnit']);

        return view('grout_production.show', compact('batch'));
    }

    /**
     * Transition: Start mixing dry ingredients timer.
     */
    public function startTimer(GroutProductionBatch $batch)
    {
        try {
            $this->productionService->startTimer($batch->id);
            return back()->with('success', 'Dry mix mixing timer started successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Transition: Proceed to Stage 2 Mixing.
     */
    public function proceedToStage2(GroutProductionBatch $batch)
    {
        try {
            $this->productionService->proceedToStage2($batch->id);
            return back()->with('success', 'Cement loaded. Stage 2 Mixing initiated.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Transition: Mixing finished (Ready for packing).
     */
    public function finishMixing(GroutProductionBatch $batch)
    {
        try {
            $this->productionService->finishMixing($batch->id);
            return back()->with('success', 'Mixing process completed. Batch is ready for packaging.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Transition: Start packing.
     */
    public function startPacking(GroutProductionBatch $batch)
    {
        try {
            $this->productionService->startPacking($batch->id);
            return back()->with('success', 'Packaging process started.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Transition: Complete Grout production batch.
     */
    public function complete(CompleteGroutProductionRequest $request, GroutProductionBatch $batch)
    {
        try {
            $this->productionService->completeBatch(
                $batch->id,
                (int) $request->input('finished_bags'),
                $request->input('remarks')
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Grout Production batch completed successfully.',
                    'redirect_url' => route('grout-production.index'),
                ]);
            }

            return redirect()->route('grout-production.index')
                ->with('success', 'Grout Production batch completed successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()->withErrors(['finished_bags' => $e->getMessage()])->withInput();
        }
    }

    /**
     * API Polling Endpoint for live timer status updates on UI.
     */
    public function getTimerStatus(GroutProductionBatch $batch)
    {
        $remainingSeconds = $this->timerService->getRemainingSeconds($batch);
        $isTimerCompleted = $this->timerService->isTimerCompleted($batch);

        if ($isTimerCompleted && $batch->status === 'Timer Running') {
            // Check and trigger Grout Mixing Complete notification if not already sent
            $alreadyNotified = \App\Models\Notification::where('type', 'grout_mixing_complete')
                ->where('payload->batch_id', $batch->id)
                ->exists();

            if (!$alreadyNotified) {
                $machineCode = $batch->machine->code;
                $supervisorName = $batch->operator->name ?? 'Supervisor';
                $currentTime = \Carbon\Carbon::now()->timezone('Asia/Kolkata')->format('h:i A');

                $title = "Mixing Complete";
                $body = "Machine {$machineCode} mixing completed.\n" . 
                        "Batch: {$batch->batch_no}\n" .
                        "Department: Grout\n" .
                        "Supervisor: {$supervisorName}\n" .
                        "Current Time: {$currentTime}";

                $clickAction = route('grout-production.running', $batch->id);

                $this->notificationService->sendToDepartment(
                    'GRT',
                    $title,
                    $body,
                    'grout_mixing_complete',
                    [
                        'batch_id' => $batch->id,
                        'batch_no' => $batch->batch_no,
                        'machine_code' => $machineCode,
                        'click_action' => $clickAction
                    ]
                );
            }
        }

        return response()->json([
            'status' => $batch->status,
            'remaining_seconds' => $remainingSeconds,
            'is_completed' => $isTimerCompleted,
        ]);
    }

    /**
     * Skip the 1-hour mixing timer (Admin/Manager/Override only).
     */
    public function skipTimer(Request $request, GroutProductionBatch $batch)
    {
        $request->validate([
            'reason' => 'required|string|min:3|max:1000'
        ]);

        try {
            $this->productionService->skipTimer($batch->id, $request->input('reason'));
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Timer skipped successfully.'
                ]);
            }

            return back()->with('success', 'Timer skipped successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }
}
