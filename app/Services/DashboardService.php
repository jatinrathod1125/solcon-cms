<?php

namespace App\Services;

use App\Models\ProductionBatch;
use App\Models\Machine;
use App\Models\RawMaterial;
use App\Models\Grade;
use App\Models\ActivityLog;
use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Get KPI Summary Card Stats.
     */
    public static function getKPIStats(): array
    {
        $today = Carbon::today()->toDateString();

        // Adhesive KPI Stats
        $todayBatches = ProductionBatch::whereDate('start_time', $today)
            ->where('status', 'completed')
            ->count();

        $todayBags = ProductionBatch::whereDate('start_time', $today)
            ->where('status', 'completed')
            ->sum('output_bags');

        $todayKg = ProductionBatch::whereDate('start_time', $today)
            ->where('status', 'completed')
            ->sum('output_kg');

        // Grout KPI Stats
        $todayGroutBatches = \App\Models\GroutProductionBatch::whereDate('created_at', $today)
            ->where('status', 'Completed')
            ->count();

        $todayGroutBags = \App\Models\GroutProductionBatch::whereDate('created_at', $today)
            ->where('status', 'Completed')
            ->sum('finished_bags');

        $todayGroutKg = (float) \App\Models\GroutProductionBatch::whereDate('created_at', $today)
            ->where('status', 'Completed')
            ->sum('total_weight_kg');

        $groutRunningCount = \App\Models\GroutProductionBatch::where('status', '!=', 'Completed')
            ->count();

        $groutPackingReadyCount = \App\Models\GroutProductionBatch::whereDate('created_at', $today)
            ->whereIn('status', ['Ready For Packing', 'Packing', 'Completed'])
            ->count();

        // Combined running machines count
        $runningAdhMachineIds = ProductionBatch::where('status', 'running')->pluck('machine_id')->toArray();
        $runningGroutMachineIds = \App\Models\GroutProductionBatch::where('status', '!=', 'Completed')->pluck('machine_id')->toArray();
        $runningMachineIds = array_unique(array_merge($runningAdhMachineIds, $runningGroutMachineIds));
        $runningMachines = count($runningMachineIds);

        $totalActiveMachines = Machine::where('is_active', true)->count();
        $idleMachines = max(0, $totalActiveMachines - $runningMachines);

        $lowStockItems = RawMaterial::where('is_active', true)
            ->whereColumn('current_stock', '<', 'minimum_stock')
            ->count();

        // Epoxy KPI Stats
        $todayEpoxyAssemblies = \App\Models\EpoxyAssembly::whereDate('created_at', $today)
            ->count();

        $todayEpoxyKits = \App\Models\EpoxyAssembly::whereDate('created_at', $today)
            ->sum('quantity');

        // Latest Epoxy Assembly
        $latestEpoxyAssembly = \App\Models\EpoxyAssembly::with(['product', 'color', 'operator'])
            ->latest('created_at')
            ->first();

        $completedBatchesToday = ProductionBatch::whereDate('end_time', $today)
            ->where('status', 'completed')
            ->count() + $todayGroutBatches + $todayEpoxyAssemblies;

        // Latest Packing for Grout
        $latestGroutPacking = \App\Models\GroutProductionBatch::where('status', 'Completed')
            ->with(['machine', 'color', 'operator'])
            ->latest('packing_end_time')
            ->first();

        return [
            'today_batches' => $todayBatches,
            'today_bags' => $todayBags,
            'today_kg' => $todayKg,
            'today_grout_batches' => $todayGroutBatches,
            'today_grout_bags' => $todayGroutBags,
            'today_grout_kg' => $todayGroutKg,
            'grout_running' => $groutRunningCount,
            'grout_packing_ready' => $groutPackingReadyCount,
            'today_epoxy_assemblies' => $todayEpoxyAssemblies,
            'today_epoxy_kits' => $todayEpoxyKits,
            'latest_epoxy_assembly' => $latestEpoxyAssembly,
            'running_machines' => $runningMachines,
            'idle_machines' => $idleMachines,
            'low_stock_items' => $lowStockItems,
            'completed_batches_today' => $completedBatchesToday,
            'latest_grout_packing' => $latestGroutPacking,
        ];
    }

    /**
     * Get live machines status and active run statistics.
     */
    public static function getLiveMachineStatus(?int $departmentId = null): array
    {
        $query = Machine::where('is_active', true)->with('department');
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }
        $machines = $query->orderBy('code')->get();
        $today = Carbon::today()->toDateString();
        $statuses = [];

        // Pre-fetch latest batch IDs for each machine
        $latestAdhBatchIds = ProductionBatch::selectRaw('max(id) as id')
            ->groupBy('machine_id')
            ->pluck('id')
            ->toArray();

        $latestGroutBatchIds = \App\Models\GroutProductionBatch::selectRaw('max(id) as id')
            ->groupBy('machine_id')
            ->pluck('id')
            ->toArray();

        $latestAdhBatches = ProductionBatch::whereIn('id', $latestAdhBatchIds)
            ->with(['grade', 'supervisor'])
            ->get()
            ->keyBy('machine_id');

        $latestGroutBatches = \App\Models\GroutProductionBatch::whereIn('id', $latestGroutBatchIds)
            ->with(['color', 'operator'])
            ->get()
            ->keyBy('machine_id');

        foreach ($machines as $machine) {
            $status = 'Idle';
            $grade = '-';
            $batchNo = '-';
            $supervisor = '-';
            $startTime = null;
            $elapsedSeconds = 0;

            $isGrout = $machine->department && strtoupper($machine->department->code) === 'GRT';

            if ($isGrout) {
                $latestBatch = $latestGroutBatches->get($machine->id);

                if ($latestBatch) {
                    if ($latestBatch->status !== 'Completed') {
                        $status = $latestBatch->status;
                        $grade = $latestBatch->color->name;
                        $batchNo = $latestBatch->batch_no;
                        $supervisor = $latestBatch->operator->name;
                        $startTime = $latestBatch->created_at;
                        $elapsedSeconds = Carbon::now()->diffInSeconds($latestBatch->created_at);
                    } elseif ($latestBatch->packing_end_time && $latestBatch->packing_end_time->toDateString() === $today) {
                        $status = 'Completed';
                        $grade = $latestBatch->color->name;
                        $batchNo = $latestBatch->batch_no;
                        $supervisor = $latestBatch->operator->name;
                    }
                }
            } else {
                $latestBatch = $latestAdhBatches->get($machine->id);

                if ($latestBatch) {
                    if ($latestBatch->status === 'running') {
                        $status = 'Running';
                        $grade = $latestBatch->grade->name;
                        $batchNo = $latestBatch->batch_no;
                        $supervisor = $latestBatch->supervisor->name;
                        $startTime = $latestBatch->start_time;
                        $elapsedSeconds = Carbon::now()->diffInSeconds($latestBatch->start_time);
                    } elseif ($latestBatch->status === 'completed' && $latestBatch->end_time && $latestBatch->end_time->toDateString() === $today) {
                        $status = 'Completed';
                        $grade = $latestBatch->grade->name;
                        $batchNo = $latestBatch->batch_no;
                        $supervisor = $latestBatch->supervisor->name;
                    }
                }
            }

            $statuses[] = [
                'machine_id' => $machine->id,
                'machine_name' => $machine->name,
                'machine_code' => $machine->code,
                'department_code' => $machine->department->code ?? '',
                'status' => $status,
                'batch_id' => $latestBatch ? $latestBatch->id : null,
                'grade' => $grade,
                'batch_no' => $batchNo,
                'supervisor' => $supervisor,
                'start_time' => $startTime ? $startTime->toIso8601String() : null,
                'elapsed_seconds' => $elapsedSeconds,
            ];
        }

        return $statuses;
    }

    /**
     * Fetch monthly calendar dates summaries.
     */
    public static function getCalendarData(string $yearMonth): array
    {
        $start = Carbon::parse($yearMonth . '-01')->startOfMonth();
        $end = Carbon::parse($yearMonth . '-01')->endOfMonth();

        // Adhesive completed batches
        $adhData = ProductionBatch::where('status', 'completed')
            ->whereBetween('start_time', [$start, $end])
            ->selectRaw('DATE(start_time) as date, count(*) as batches, sum(output_bags) as bags, sum(output_kg) as kg')
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->toArray();

        // Grout completed batches
        $groutData = \App\Models\GroutProductionBatch::where('status', 'Completed')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, count(*) as batches, sum(finished_bags) as bags, sum(total_weight_kg) as kg')
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->toArray();

        // Merge them
        $merged = [];
        $allDates = array_unique(array_merge(array_keys($adhData), array_keys($groutData)));
        foreach ($allDates as $date) {
            $merged[$date] = [
                'date' => $date,
                'batches' => ($adhData[$date]['batches'] ?? 0) + ($groutData[$date]['batches'] ?? 0),
                'bags' => ($adhData[$date]['bags'] ?? 0) + ($groutData[$date]['bags'] ?? 0),
                'kg' => (float)($adhData[$date]['kg'] ?? 0) + (float)($groutData[$date]['kg'] ?? 0),
            ];
        }

        return $merged;
    }

    /**
     * Fetch calendar date summaries and breakdown.
     */
    public static function getCalendarDateDetails(string $date): array
    {
        // Adhesive
        $totalAdh = ProductionBatch::whereDate('start_time', $date)
            ->where('status', 'completed')
            ->selectRaw('count(*) as batches, sum(output_bags) as bags, sum(output_kg) as kg')
            ->first();

        // Grout
        $totalGrout = \App\Models\GroutProductionBatch::whereDate('created_at', $date)
            ->where('status', 'Completed')
            ->selectRaw('count(*) as batches, sum(finished_bags) as bags, sum(total_weight_kg) as kg')
            ->first();

        $machineWiseAdh = ProductionBatch::whereDate('start_time', $date)
            ->where('status', 'completed')
            ->selectRaw('machine_id, count(*) as batches, sum(output_bags) as bags, sum(output_kg) as kg')
            ->groupBy('machine_id')
            ->with('machine')
            ->get();

        $machineWiseGrout = \App\Models\GroutProductionBatch::whereDate('created_at', $date)
            ->where('status', 'Completed')
            ->selectRaw('machine_id, count(*) as batches, sum(finished_bags) as bags, sum(total_weight_kg) as kg')
            ->groupBy('machine_id')
            ->with('machine')
            ->get();

        // Merge machine wise
        $machineWise = [];
        foreach ($machineWiseAdh as $m) {
            $machineWise[$m->machine->name] = [
                'machine' => $m->machine,
                'batches' => $m->batches,
                'bags' => $m->bags,
                'kg' => $m->kg
            ];
        }
        foreach ($machineWiseGrout as $m) {
            if (isset($machineWise[$m->machine->name])) {
                $machineWise[$m->machine->name]['batches'] += $m->batches;
                $machineWise[$m->machine->name]['bags'] += $m->bags;
                $machineWise[$m->machine->name]['kg'] += $m->kg;
            } else {
                $machineWise[$m->machine->name] = [
                    'machine' => $m->machine,
                    'batches' => $m->batches,
                    'bags' => $m->bags,
                    'kg' => $m->kg
                ];
            }
        }
        $machineWise = array_values($machineWise);

        $supervisorWiseAdh = ProductionBatch::whereDate('start_time', $date)
            ->where('status', 'completed')
            ->selectRaw('supervisor_id, count(*) as batches, sum(output_bags) as bags, sum(output_kg) as kg')
            ->groupBy('supervisor_id')
            ->with('supervisor')
            ->get();

        $supervisorWiseGrout = \App\Models\GroutProductionBatch::whereDate('created_at', $date)
            ->where('status', 'Completed')
            ->selectRaw('operator_id as supervisor_id, count(*) as batches, sum(finished_bags) as bags, sum(total_weight_kg) as kg')
            ->groupBy('operator_id')
            ->with('operator')
            ->get();

        // Merge supervisor wise
        $supervisorWise = [];
        foreach ($supervisorWiseAdh as $s) {
            $supervisorWise[$s->supervisor->name] = [
                'supervisor' => $s->supervisor,
                'batches' => $s->batches,
                'bags' => $s->bags,
                'kg' => $s->kg
            ];
        }
        foreach ($supervisorWiseGrout as $s) {
            $name = $s->operator->name ?? 'Unknown';
            if (isset($supervisorWise[$name])) {
                $supervisorWise[$name]['batches'] += $s->batches;
                $supervisorWise[$name]['bags'] += $s->bags;
                $supervisorWise[$name]['kg'] += $s->kg;
            } else {
                $supervisorWise[$name] = [
                    'supervisor' => $s->operator,
                    'batches' => $s->batches,
                    'bags' => $s->bags,
                    'kg' => $s->kg
                ];
            }
        }
        $supervisorWise = array_values($supervisorWise);

        $batchesAdh = ProductionBatch::whereDate('start_time', $date)
            ->where('status', 'completed')
            ->with(['machine', 'grade', 'supervisor'])
            ->get();

        $batchesGrout = \App\Models\GroutProductionBatch::whereDate('created_at', $date)
            ->where('status', 'Completed')
            ->with(['machine', 'color', 'operator'])
            ->get();

        $batches = [];
        foreach ($batchesAdh as $b) {
            $batches[] = [
                'id' => $b->id,
                'batch_no' => $b->batch_no,
                'machine_name' => $b->machine->name ?? 'N/A',
                'product_name' => $b->grade->name ?? 'N/A',
                'supervisor_name' => $b->supervisor->name ?? 'N/A',
                'output_bags' => $b->output_bags,
                'output_kg' => $b->output_kg,
                'type' => 'Adhesive',
                'url' => route('production.show', $b->id)
            ];
        }
        foreach ($batchesGrout as $b) {
            $batches[] = [
                'id' => $b->id,
                'batch_no' => $b->batch_no,
                'machine_name' => $b->machine->name ?? 'N/A',
                'product_name' => $b->color->name ?? 'N/A',
                'supervisor_name' => $b->operator->name ?? 'N/A',
                'output_bags' => $b->finished_bags,
                'output_kg' => $b->total_weight_kg,
                'type' => 'Grout',
                'url' => route('grout-production.show', $b->id)
            ];
        }

        return [
            'date' => $date,
            'total_batches' => ($totalAdh->batches ?? 0) + ($totalGrout->batches ?? 0),
            'total_bags' => ($totalAdh->bags ?? 0) + ($totalGrout->bags ?? 0),
            'total_kg' => (float)($totalAdh->kg ?? 0) + (float)($totalGrout->kg ?? 0),
            'machineWise' => $machineWise,
            'supervisorWise' => $supervisorWise,
            'batches' => $batches,
        ];
    }

    /**
     * Format Chart.js aggregations.
     */
    public static function getChartData(): array
    {
        $thirtyDaysAgo = Carbon::today()->subDays(29)->toDateString();

        $adhProduction = ProductionBatch::where('status', 'completed')
            ->whereDate('start_time', '>=', $thirtyDaysAgo)
            ->selectRaw('DATE(start_time) as date, SUM(output_kg) as total_kg')
            ->groupByRaw('DATE(start_time)')
            ->pluck('total_kg', 'date')
            ->toArray();

        $groutProduction = \App\Models\GroutProductionBatch::where('status', 'Completed')
            ->whereDate('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('DATE(created_at) as date, SUM(total_weight_kg) as total_kg')
            ->groupByRaw('DATE(created_at)')
            ->pluck('total_kg', 'date')
            ->toArray();

        // 1. Last 7 Days
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $label = Carbon::today()->subDays($i)->format('d M');
            $kgAdh = $adhProduction[$date] ?? 0;
            $kgGrout = $groutProduction[$date] ?? 0;
            $last7Days[] = ['label' => $label, 'value' => (float)$kgAdh + (float)$kgGrout];
        }

        // 2. Last 30 Days
        $last30Days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $label = Carbon::today()->subDays($i)->format('d M');
            $kgAdh = $adhProduction[$date] ?? 0;
            $kgGrout = $groutProduction[$date] ?? 0;
            $last30Days[] = ['label' => $label, 'value' => (float)$kgAdh + (float)$kgGrout];
        }

        // 3. Machine Utilization (Last 30 Days completed runs count)
        $thirtyDaysAgo = Carbon::today()->subDays(30);
        $machineUtilizationAdh = ProductionBatch::where('status', 'completed')
            ->where('start_time', '>=', $thirtyDaysAgo)
            ->selectRaw('machine_id, count(*) as count')
            ->groupBy('machine_id')
            ->with('machine')
            ->get();

        $machineUtilizationGrout = \App\Models\GroutProductionBatch::where('status', 'Completed')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('machine_id, count(*) as count')
            ->groupBy('machine_id')
            ->with('machine')
            ->get();

        $mergedUtilization = [];
        foreach ($machineUtilizationAdh as $item) {
            if ($item->machine) {
                $mergedUtilization[$item->machine->name] = ($mergedUtilization[$item->machine->name] ?? 0) + $item->count;
            }
        }
        foreach ($machineUtilizationGrout as $item) {
            if ($item->machine) {
                $mergedUtilization[$item->machine->name] = ($mergedUtilization[$item->machine->name] ?? 0) + $item->count;
            }
        }
        $machineUtilization = [];
        foreach ($mergedUtilization as $name => $count) {
            $machineUtilization[] = ['label' => $name, 'value' => $count];
        }

        // 4. Top Produced Grades/Colors (Last 30 Days)
        $topGradesAdh = ProductionBatch::where('status', 'completed')
            ->where('start_time', '>=', $thirtyDaysAgo)
            ->selectRaw('grade_id, sum(output_kg) as total_kg')
            ->groupBy('grade_id')
            ->with('grade')
            ->get()
            ->map(fn($item) => [
                'label' => $item->grade->name ?? 'Unknown',
                'value' => (float)$item->total_kg
            ])->toArray();

        $topColorsGrout = \App\Models\GroutProductionBatch::where('status', 'Completed')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('color_id, sum(total_weight_kg) as total_kg')
            ->groupBy('color_id')
            ->with('color')
            ->get()
            ->map(fn($item) => [
                'label' => $item->color->name ?? 'Unknown',
                'value' => (float)$item->total_kg
            ])->toArray();

        $topGrades = array_merge($topGradesAdh, $topColorsGrout);
        usort($topGrades, fn($a, $b) => $b['value'] <=> $a['value']);
        $topGrades = array_slice($topGrades, 0, 5);

        // 5. Raw Material Consumption (Last 30 Days OUT ledgers)
        $materialConsumption = StockLedger::where('transaction_type', 'OUT')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('raw_material_id, abs(sum(quantity)) as total_qty')
            ->groupBy('raw_material_id')
            ->with('rawMaterial')
            ->get()
            ->map(fn($item) => [
                'label' => $item->rawMaterial->name,
                'value' => (float)$item->total_qty
            ])->toArray();

        // 6. Low Stock items (Current Stock vs Minimum Stock)
        $lowStockCompare = RawMaterial::where('is_active', true)
            ->whereColumn('current_stock', '<', 'minimum_stock')
            ->get()
            ->map(fn($item) => [
                'label' => $item->name,
                'current' => (float)$item->current_stock,
                'minimum' => (float)$item->minimum_stock,
            ])->toArray();

        return [
            'last7Days' => $last7Days,
            'last30Days' => $last30Days,
            'machineUtilization' => $machineUtilization,
            'topGrades' => $topGrades,
            'materialConsumption' => $materialConsumption,
            'lowStockCompare' => $lowStockCompare,
        ];
    }

    /**
     * Get Low Stock items list for Widget.
     */
    public static function getLowStockWidgetData(): array
    {
        return RawMaterial::where('is_active', true)
            ->whereColumn('current_stock', '<', 'minimum_stock')
            ->orderBy('current_stock')
            ->with('stockUnit')
            ->get()
            ->map(function ($item) {
                $diff = (float)$item->current_stock - (float)$item->minimum_stock;
                $priority = 'Warning';

                // Critical: stock is less than 50% of minimum stock
                if ((float)$item->current_stock <= ((float)$item->minimum_stock * 0.5)) {
                    $priority = 'Critical';
                }

                return [
                    'material_name' => $item->name,
                    'material_code' => $item->code,
                    'current_stock' => $item->current_stock,
                    'minimum_stock' => $item->minimum_stock,
                    'difference' => $diff,
                    'unit' => $item->stockUnit->code,
                    'priority' => $priority,
                ];
            })->toArray();
    }

    /**
     * Compile Dashboard alert warnings.
     */
    public static function getDashboardAlerts(): array
    {
        $alerts = [];
        $today = Carbon::today()->toDateString();

        // 1. Running batch over 45 minutes
        $longRunning = ProductionBatch::where('status', 'running')
            ->where('start_time', '<', Carbon::now()->subMinutes(45))
            ->with(['machine', 'supervisor'])
            ->get();

        foreach ($longRunning as $batch) {
            $diff = Carbon::now()->diffInMinutes($batch->start_time);
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Long Running Batch Alert',
                'message' => "Batch #{$batch->batch_no} on Mixer {$batch->machine->name} has been running for {$diff} minutes (Supervisor: {$batch->supervisor->name})."
            ];
        }

        // 2. Low Stock Warning
        $lowStockCount = RawMaterial::where('is_active', true)
            ->whereColumn('current_stock', '<', 'minimum_stock')
            ->count();

        if ($lowStockCount > 0) {
            $alerts[] = [
                'type' => 'danger',
                'title' => 'Low Stock Warning',
                'message' => "There are {$lowStockCount} raw materials below their configured minimum stock level."
            ];
        }

        // 3. Formula Missing Alert
        $missingFormulas = Grade::where('is_active', true)
            ->whereDoesntHave('activeFormula')
            ->get();

        foreach ($missingFormulas as $grade) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Formula Missing',
                'message' => "Active Grade '{$grade->name}' (Code: {$grade->code}) does not have an active formula."
            ];
        }

        // 4. Machine Idle All Day Alert
        $machinesWithBatchesToday = ProductionBatch::whereDate('start_time', $today)
            ->distinct()
            ->pluck('machine_id')
            ->toArray();

        $idleAllDay = Machine::where('is_active', true)
            ->whereNotIn('id', $machinesWithBatchesToday)
            ->get();

        foreach ($idleAllDay as $machine) {
            $alerts[] = [
                'type' => 'info',
                'title' => 'Mixer Idle All Day',
                'message' => "Mixer machine {$machine->name} has not processed any production batches today."
            ];
        }

        // 5. Today's Production Completed Alert
        $todayStartedCount = ProductionBatch::whereDate('start_time', $today)->count();
        $todayRunningCount = ProductionBatch::whereDate('start_time', $today)->where('status', 'running')->count();

        if ($todayStartedCount > 0 && $todayRunningCount === 0) {
            $alerts[] = [
                'type' => 'success',
                'title' => 'Daily Runs Concluded',
                'message' => "All production batches started today have been finalized and completed."
            ];
        }

        return $alerts;
    }

    /**
     * Fetch latest activity logs timeline.
     */
    public static function getActivityTimeline(): array
    {
        return ActivityLog::with('user')
            ->latest('id')
            ->take(15)
            ->get()
            ->toArray();
    }
}
