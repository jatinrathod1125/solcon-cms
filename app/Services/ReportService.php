<?php

namespace App\Services;

use App\Models\ProductionBatch;
use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Compile daily production aggregates and logs (Business Flow step 8).
     */
    public static function getDailyReportData(string $date): array
    {
        // 1. Machine-wise summary of completed batches
        $machineSummary = ProductionBatch::whereDate('start_time', $date)
            ->where('status', 'completed')
            ->select(
                'machine_id',
                DB::raw('count(*) as total_batches'),
                DB::raw('sum(output_bags) as total_bags'),
                DB::raw('sum(output_kg) as total_kg')
            )
            ->groupBy('machine_id')
            ->with('machine')
            ->get();

        // 2. Grand Totals
        $grandTotal = ProductionBatch::whereDate('start_time', $date)
            ->where('status', 'completed')
            ->select(
                DB::raw('count(*) as total_batches'),
                DB::raw('sum(output_bags) as total_bags'),
                DB::raw('sum(output_kg) as total_kg')
            )
            ->first();

        // 3. Raw Material consumption summary
        $materialSummary = StockLedger::where('transaction_type', 'OUT')
            ->whereHas('batch', function ($q) use ($date) {
                $q->whereDate('start_time', $date)->where('status', 'completed');
            })
            ->select(
                'raw_material_id',
                DB::raw('abs(sum(quantity)) as total_consumed')
            )
            ->groupBy('raw_material_id')
            ->with(['rawMaterial.stockUnit'])
            ->get();

        // 4. Supervisor summary
        $supervisorSummary = ProductionBatch::whereDate('start_time', $date)
            ->where('status', 'completed')
            ->select(
                'supervisor_id',
                DB::raw('count(*) as total_batches'),
                DB::raw('sum(output_bags) as total_bags'),
                DB::raw('sum(output_kg) as total_kg')
            )
            ->groupBy('supervisor_id')
            ->with('supervisor')
            ->get();

        // 5. Individual completed batches for detailed view
        $completedBatches = ProductionBatch::whereDate('start_time', $date)
            ->where('status', 'completed')
            ->with(['machine', 'grade', 'supervisor'])
            ->get();

        return [
            'machineSummary' => $machineSummary,
            'grandTotal' => $grandTotal,
            'materialSummary' => $materialSummary,
            'supervisorSummary' => $supervisorSummary,
            'completedBatches' => $completedBatches,
        ];
    }
}
