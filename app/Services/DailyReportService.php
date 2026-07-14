<?php

namespace App\Services;

use App\Models\ProductionBatch;
use App\Models\GroutProductionBatch;
use App\Models\EpoxyAssembly;
use App\Models\EpoxyComponentPreparation;
use App\Models\StockLedger;
use App\Models\Machine;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DailyReportService
{
    /**
     * Resolve date range from HTTP Request.
     */
    public static function resolveDateRange(Request $request): array
    {
        $preset = $request->input('range_preset');

        // Backward compatibility for single date input
        if (!$preset && $request->filled('date')) {
            $startDate = $request->input('date');
            $endDate = $request->input('date');
            $preset = 'custom';
        } else {
            switch ($preset) {
                case 'yesterday':
                    $startDate = now()->subDay()->toDateString();
                    $endDate = $startDate;
                    break;
                case 'last_7_days':
                    $startDate = now()->subDays(6)->toDateString();
                    $endDate = now()->toDateString();
                    break;
                case 'last_30_days':
                    $startDate = now()->subDays(29)->toDateString();
                    $endDate = now()->toDateString();
                    break;
                case 'current_month':
                    $startDate = now()->startOfMonth()->toDateString();
                    $endDate = now()->toDateString();
                    break;
                case 'previous_month':
                    $startDate = now()->subMonth()->startOfMonth()->toDateString();
                    $endDate = now()->subMonth()->endOfMonth()->toDateString();
                    break;
                case 'custom':
                    $startDate = $request->input('start_date', now()->toDateString());
                    $endDate = $request->input('end_date', now()->toDateString());
                    break;
                case 'today':
                default:
                    $preset = 'today';
                    $startDate = now()->toDateString();
                    $endDate = now()->toDateString();
                    break;
            }
        }

        return [
            'preset' => $preset,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_multi_day' => $startDate !== $endDate,
        ];
    }

    /**
     * Backward-compatible single date report compiler.
     */
    public static function getDailyReportData(string $date): array
    {
        return static::getProductionReportData($date, $date, 'all');
    }

    /**
     * Compile production report data supporting date ranges, department filtering, and machine/grade aggregations.
     */
    public static function getProductionReportData(string $startDate, string $endDate, string $deptFilter = 'all'): array
    {
        $user = auth()->user();
        $isSupervisor = $user && $user->isSupervisor();

        // For supervisors, use the session-based department set by middleware, not the primary department_id
        $currentDept = null;
        $userDeptCode = null;
        $supervisorDeptId = null;

        if ($isSupervisor) {
            $currentDept = \currentDepartment();
            $userDeptCode = $currentDept ? strtoupper($currentDept->code) : null;
            $supervisorDeptId = $currentDept ? $currentDept->id : null;
        }

        // Department filter resolution
        if ($isSupervisor) {
            $showAdhesive = ($userDeptCode === 'TAD');
            $showGrout = ($userDeptCode === 'GRT');
            $showEpoxy = ($userDeptCode === 'EP');
        } else {
            if ($deptFilter === 'TAD') {
                $showAdhesive = true; $showGrout = false; $showEpoxy = false;
            } elseif ($deptFilter === 'GRT') {
                $showAdhesive = false; $showGrout = true; $showEpoxy = false;
            } elseif ($deptFilter === 'EPX') {
                $showAdhesive = false; $showGrout = false; $showEpoxy = true;
            } else { // 'all'
                $showAdhesive = true; $showGrout = true; $showEpoxy = true;
            }
        }

        $isMultiDay = $startDate !== $endDate;

        // --- 1. ADHESIVE PRODUCTION STATS & AGGREGATIONS ---
        $grandTotal = (object) ['total_batches' => 0, 'total_bags' => 0, 'total_kg' => 0];
        $runningMachines = 0;
        $completedMachines = 0;
        $completedBatches = collect();
        $machineSummary = collect();
        $supervisorSummary = collect();
        $adhMachineDetails = [];
        $adhOverallGradeTotals = [];
        $adhMachineGrandTotals = [];
        $dayWiseAdhesive = [];
        $totalMachinesUsed = 0;

        if ($showAdhesive) {
            $adhBatchesQuery = ProductionBatch::whereBetween(DB::raw('DATE(start_time)'), [$startDate, $endDate])
                ->where('status', 'completed')
                ->when($isSupervisor, function ($q) use ($supervisorDeptId) {
                    $q->whereHas('machine', function ($qm) use ($supervisorDeptId) {
                        $qm->where('department_id', $supervisorDeptId);
                    });
                });

            $completedBatches = (clone $adhBatchesQuery)
                ->with(['machine', 'grade', 'supervisor'])
                ->orderBy('start_time', 'asc')
                ->get();

            // Pre-load coupon raw materials to identify coupon variants in-memory
            $coupons = \App\Models\RawMaterial::where('is_coupon', true)->pluck('name', 'id')->toArray();
            foreach ($completedBatches as $b) {
                $couponId = null;
                if (!empty($b->formula_snapshot)) {
                    foreach ($b->formula_snapshot as $itemData) {
                        $rmId = $itemData['raw_material_id'] ?? null;
                        if ($rmId && isset($coupons[$rmId])) {
                            $couponId = $rmId;
                            break;
                        }
                    }
                }
                if ($b->grade) {
                    $clonedGrade = clone $b->grade;
                    $suffix = $couponId ? ' (' . $coupons[$couponId] . ')' : ' (No Coupon)';
                    $clonedGrade->name = $clonedGrade->name . $suffix;
                    $b->setRelation('grade', $clonedGrade);
                }
            }

            $grandTotal = (object) [
                'total_batches' => $completedBatches->count(),
                'total_bags' => (int) $completedBatches->sum('output_bags'),
                'total_kg' => (float) $completedBatches->sum('output_kg'),
            ];

            // Summaries for test compatibility
            $machineSummary = ProductionBatch::whereBetween(DB::raw('DATE(start_time)'), [$startDate, $endDate])
                ->where('status', 'completed')
                ->when($isSupervisor, function ($q) use ($supervisorDeptId) {
                    $q->whereHas('machine', function ($qm) use ($supervisorDeptId) {
                        $qm->where('department_id', $supervisorDeptId);
                    });
                })
                ->select('machine_id', DB::raw('count(*) as total_batches'), DB::raw('sum(output_bags) as total_bags'), DB::raw('sum(output_kg) as total_kg'))
                ->groupBy('machine_id')
                ->with('machine')
                ->get();

            $supervisorSummary = ProductionBatch::whereBetween(DB::raw('DATE(start_time)'), [$startDate, $endDate])
                ->where('status', 'completed')
                ->when($isSupervisor, function ($q) use ($supervisorDeptId) {
                    $q->whereHas('machine', function ($qm) use ($supervisorDeptId) {
                        $qm->where('department_id', $supervisorDeptId);
                    });
                })
                ->select('supervisor_id', DB::raw('count(*) as total_batches'), DB::raw('sum(output_bags) as total_bags'), DB::raw('sum(output_kg) as total_kg'))
                ->groupBy('supervisor_id')
                ->with('supervisor')
                ->get();

            // Running & Completed Machines count
            $runningMachines = ProductionBatch::where('status', 'running')
                ->when($isSupervisor, function ($q) use ($supervisorDeptId) {
                    $q->whereHas('machine', function ($qm) use ($supervisorDeptId) {
                        $qm->where('department_id', $supervisorDeptId);
                    });
                })->distinct('machine_id')->count('machine_id');

            $completedMachines = $completedBatches->pluck('machine_id')->unique()->count();
            $totalMachinesUsed = $completedMachines;

            // Grouping by Machine
            $groupedByMachine = $completedBatches->groupBy('machine_id');
            foreach ($groupedByMachine as $machineId => $batches) {
                $machine = $batches->first()->machine;
                $machineName = $machine ? $machine->name : "Machine #{$machineId}";

                $gradeTotals = [];
                foreach ($batches as $b) {
                    $gradeName = $b->grade ? $b->grade->name : 'N/A';
                    if (!isset($gradeTotals[$gradeName])) {
                        $gradeTotals[$gradeName] = 0;
                    }
                    $gradeTotals[$gradeName] += $b->output_bags;
                }

                $totBags = (int) $batches->sum('output_bags');
                $totKg = (float) $batches->sum('output_kg');
                $totBatches = $batches->count();

                $adhMachineDetails[] = [
                    'machine_id' => $machineId,
                    'machine_name' => $machineName,
                    'machine_code' => $machine ? $machine->code : '',
                    'batches' => $batches,
                    'grade_totals' => $gradeTotals,
                    'total_batches' => $totBatches,
                    'total_bags' => $totBags,
                    'total_kg' => $totKg,
                ];

                $adhMachineGrandTotals[$machineName] = [
                    'batches' => $totBatches,
                    'bags' => $totBags,
                    'kg' => $totKg,
                ];
            }

            // Overall Grade-Wise Totals across ALL machines
            foreach ($completedBatches as $b) {
                $gradeName = $b->grade ? $b->grade->name : 'N/A';
                if (!isset($adhOverallGradeTotals[$gradeName])) {
                    $adhOverallGradeTotals[$gradeName] = 0;
                }
                $adhOverallGradeTotals[$gradeName] += $b->output_bags;
            }
            ksort($adhOverallGradeTotals);

            // Day-wise Grouping for Multi-Day Range
            if ($isMultiDay) {
                $groupedByDate = $completedBatches->groupBy(function ($b) {
                    return \Carbon\Carbon::parse($b->start_time)->format('Y-m-d');
                });

                foreach ($groupedByDate as $dateKey => $dayBatches) {
                    $dayMachines = [];
                    $dayOverallGradeTotals = [];
                    $dayMachineGrandTotals = [];

                    $dayByMachine = $dayBatches->groupBy('machine_id');
                    foreach ($dayByMachine as $mId => $mBatches) {
                        $mObj = $mBatches->first()->machine;
                        $mName = $mObj ? $mObj->name : "Machine #{$mId}";

                        $mGradeTotals = [];
                        foreach ($mBatches as $b) {
                            $gName = $b->grade ? $b->grade->name : 'N/A';
                            $mGradeTotals[$gName] = ($mGradeTotals[$gName] ?? 0) + $b->output_bags;
                            $dayOverallGradeTotals[$gName] = ($dayOverallGradeTotals[$gName] ?? 0) + $b->output_bags;
                        }

                        $mBags = (int) $mBatches->sum('output_bags');
                        $mKg = (float) $mBatches->sum('output_kg');
                        $mBatchesCnt = $mBatches->count();

                        $dayMachines[] = [
                            'machine_id' => $mId,
                            'machine_name' => $mName,
                            'batches' => $mBatches,
                            'grade_totals' => $mGradeTotals,
                            'total_batches' => $mBatchesCnt,
                            'total_bags' => $mBags,
                            'total_kg' => $mKg,
                        ];

                        $dayMachineGrandTotals[$mName] = [
                            'batches' => $mBatchesCnt,
                            'bags' => $mBags,
                            'kg' => $mKg,
                        ];
                    }

                    ksort($dayOverallGradeTotals);

                    $dayWiseAdhesive[$dateKey] = [
                        'date' => $dateKey,
                        'machines' => $dayMachines,
                        'grade_totals' => $dayOverallGradeTotals,
                        'machine_totals' => $dayMachineGrandTotals,
                        'total_batches' => $dayBatches->count(),
                        'total_bags' => (int) $dayBatches->sum('output_bags'),
                        'total_kg' => (float) $dayBatches->sum('output_kg'),
                    ];
                }
                ksort($dayWiseAdhesive);
            }
        }

        // --- 2. GROUT PRODUCTION STATS ---
        $groutGrandTotal = (object) ['total_batches' => 0, 'total_bags' => 0, 'total_kg' => 0];
        $groutCompletedBatches = collect();
        $groutMachineSummary = collect();
        $groutColorSummary = collect();
        $groutSupervisorSummary = collect();
        $groutPackingSummary = [
            '500_GM' => ['bags' => 0, 'pouches' => 0, 'kg' => 0],
            '1_KG' => ['bags' => 0, 'pouches' => 0, 'kg' => 0],
        ];

        if ($showGrout) {
            $groutBatchesQuery = GroutProductionBatch::whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->where('status', 'Completed')
                ->when($isSupervisor, function ($q) use ($supervisorDeptId) {
                    $q->whereHas('machine', function ($qm) use ($supervisorDeptId) {
                        $qm->where('department_id', $supervisorDeptId);
                    });
                });

            $groutCompletedBatches = (clone $groutBatchesQuery)
                ->with(['machine', 'color', 'operator'])
                ->orderBy('created_at', 'asc')
                ->get();

            $groutGrandTotal = (object) [
                'total_batches' => $groutCompletedBatches->count(),
                'total_bags' => (int) $groutCompletedBatches->sum('finished_bags'),
                'total_kg' => (float) $groutCompletedBatches->sum('total_weight_kg'),
            ];

            $groutMachineSummary = GroutProductionBatch::whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->where('status', 'Completed')
                ->when($isSupervisor, function ($q) use ($supervisorDeptId) {
                    $q->whereHas('machine', function ($qm) use ($supervisorDeptId) {
                        $qm->where('department_id', $supervisorDeptId);
                    });
                })
                ->select('machine_id', DB::raw('count(*) as total_batches'), DB::raw('sum(finished_bags) as total_bags'), DB::raw('sum(total_weight_kg) as total_kg'))
                ->groupBy('machine_id')
                ->with('machine')
                ->get();

            foreach ($groutCompletedBatches as $b) {
                $size = $b->color->packing_size ?? '500 GM';
                $bags = $b->finished_bags ?? 0;
                $kg = (float) ($b->total_weight_kg ?? 0);
                $pouchFactor = ($size === '500 GM') ? 50 : 25;
                $pouches = $bags * $pouchFactor;

                $key = ($size === '500 GM') ? '500_GM' : '1_KG';
                $groutPackingSummary[$key]['bags'] += $bags;
                $groutPackingSummary[$key]['pouches'] += $pouches;
                $groutPackingSummary[$key]['kg'] += $kg;
            }
        }

        // --- 3. EPOXY PRODUCTION STATS ---
        $epoxyGrandTotal = (object) ['total_assemblies' => 0, 'total_kits' => 0];
        $epoxyCompletedAssemblies = collect();
        $epoxyProductSummary = collect();
        $epoxyPreparations = collect();
        $epoxyPrepGrouped = [
            'bottles' => [],      // Resin/Hardener bottles
            'fillers' => [],      // Filler pouches (color-specific)
            'sparkles' => [],     // Jari / Sparkle pouches
            'cleaners' => [],     // Cleaners / Acid Canisters
            'others' => [],       // Others (SBR, SK+, etc.)
        ];

        if ($showEpoxy) {
            $epoxyCompletedAssemblies = EpoxyAssembly::whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->when($isSupervisor, function ($q) use ($supervisorDeptId) {
                    $q->whereHas('product', function ($qm) use ($supervisorDeptId) {
                        $qm->where('department_id', $supervisorDeptId);
                    });
                })
                ->with(['product', 'color', 'operator'])
                ->orderBy('created_at', 'asc')
                ->get();

            $epoxyGrandTotal = (object) [
                'total_assemblies' => $epoxyCompletedAssemblies->count(),
                'total_kits' => (int) $epoxyCompletedAssemblies->sum('quantity'),
            ];

            $epoxyProductSummary = EpoxyAssembly::whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->select('epoxy_product_id', DB::raw('count(*) as total_assemblies'), DB::raw('sum(quantity) as total_kits'))
                ->groupBy('epoxy_product_id')
                ->with('product')
                ->get();

            $epoxyPreparations = EpoxyComponentPreparation::whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->with(['component', 'color', 'operator'])
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($epoxyPreparations as $prep) {
                $comp = $prep->component;
                if (!$comp) continue;

                $colorName = $prep->color ? $prep->color->name : ($comp->color ? $comp->color->name : null);
                $displayName = $comp->name;
                if ($colorName && stripos($displayName, $colorName) === false) {
                    $displayName .= " ({$colorName})";
                }

                if ($comp->category === 'Bottle' || stripos($comp->code, 'HRD') !== false || stripos($comp->code, 'RSN') !== false) {
                    $key = $comp->name;
                    $epoxyPrepGrouped['bottles'][$key] = ($epoxyPrepGrouped['bottles'][$key] ?? 0) + $prep->quantity;
                } elseif ($comp->category === 'Pouch' && (stripos($comp->code, 'FIL') !== false || $comp->epoxy_filler_color_id !== null)) {
                    $key = $colorName ?: 'Base/Generic';
                    $epoxyPrepGrouped['fillers'][$key] = ($epoxyPrepGrouped['fillers'][$key] ?? 0) + $prep->quantity;
                } elseif (stripos($comp->code, 'SPK') !== false || stripos($comp->name, 'Sparkle') !== false || stripos($comp->name, 'Jari') !== false) {
                    $key = $comp->name;
                    $epoxyPrepGrouped['sparkles'][$key] = ($epoxyPrepGrouped['sparkles'][$key] ?? 0) + $prep->quantity;
                } elseif (stripos($comp->code, 'ACID') !== false || stripos($comp->name, 'Cleaner') !== false) {
                    $key = $comp->name;
                    $epoxyPrepGrouped['cleaners'][$key] = ($epoxyPrepGrouped['cleaners'][$key] ?? 0) + $prep->quantity;
                } else {
                    $key = $comp->name;
                    $epoxyPrepGrouped['others'][$key] = ($epoxyPrepGrouped['others'][$key] ?? 0) + $prep->quantity;
                }
            }
        }

        // --- 4. UNIFIED RAW MATERIAL CONSUMPTION ---
        $materialSummaryQuery = StockLedger::where('transaction_type', 'OUT')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);

        if ($isSupervisor) {
            $materialSummaryQuery->whereHas('rawMaterial', function ($q) use ($supervisorDeptId) {
                $q->where('department_id', $supervisorDeptId);
            });
        }

        $materialSummary = $materialSummaryQuery->select('raw_material_id', DB::raw('abs(sum(quantity)) as total_consumed'))
            ->groupBy('raw_material_id')
            ->with(['rawMaterial.stockUnit'])
            ->get();

        $totalConsumptionWeight = $materialSummary->sum('total_consumed');

        return [
            // Filter Params
            'startDate' => $startDate,
            'endDate' => $endDate,
            'date' => $startDate, // Backward compatibility key
            'deptFilter' => $deptFilter,
            'isMultiDay' => $isMultiDay,

            // Adhesive Output Data
            'grandTotal' => $grandTotal,
            'runningMachines' => $runningMachines,
            'completedMachines' => $completedMachines,
            'totalMachinesUsed' => $totalMachinesUsed,
            'completedBatches' => $completedBatches,
            'machineSummary' => $machineSummary,
            'supervisorSummary' => $supervisorSummary,
            'adhMachineDetails' => $adhMachineDetails,
            'adhOverallGradeTotals' => $adhOverallGradeTotals,
            'adhMachineGrandTotals' => $adhMachineGrandTotals,
            'dayWiseAdhesive' => $dayWiseAdhesive,
            'showAdhesive' => $showAdhesive,

            // Grout Output Data
            'groutGrandTotal' => $groutGrandTotal,
            'groutCompletedBatches' => $groutCompletedBatches,
            'groutMachineSummary' => $groutMachineSummary,
            'groutPackingSummary' => $groutPackingSummary,
            'showGrout' => $showGrout,

            // Epoxy Output Data
            'epoxyGrandTotal' => $epoxyGrandTotal,
            'epoxyCompletedAssemblies' => $epoxyCompletedAssemblies,
            'epoxyProductSummary' => $epoxyProductSummary,
            'epoxyPreparations' => $epoxyPreparations,
            'epoxyPrepGrouped' => $epoxyPrepGrouped,
            'showEpoxy' => $showEpoxy,

            // Unified Material Consumption
            'materialSummary' => $materialSummary,
            'totalConsumptionWeight' => $totalConsumptionWeight,
        ];
    }
}
