<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\DailyReportService;
use App\Services\ActivityLogService;

class ReportController extends Controller
{
    /**
     * Show the production report aggregates and filter interface.
     */
    public function dailySummary(Request $request)
    {
        $range = DailyReportService::resolveDateRange($request);
        $deptFilter = $request->input('department_code', 'all');

        $data = DailyReportService::getProductionReportData(
            $range['start_date'],
            $range['end_date'],
            $deptFilter
        );

        return view('admin.reports.daily', array_merge($data, [
            'rangePreset' => $range['preset'],
            'startDate' => $range['start_date'],
            'endDate' => $range['end_date'],
            'isMultiDay' => $range['is_multi_day'],
            'departmentCode' => $deptFilter,
        ]));
    }

    /**
     * Export the production report as a PDF.
     */
    public function exportPdf(Request $request)
    {
        $range = DailyReportService::resolveDateRange($request);
        $deptFilter = $request->input('department_code', 'all');

        $data = DailyReportService::getProductionReportData(
            $range['start_date'],
            $range['end_date'],
            $deptFilter
        );

        ActivityLogService::log(
            'PDF_DOWNLOADED',
            "Production report PDF downloaded for date range {$range['start_date']} to {$range['end_date']} (Dept: {$deptFilter}).",
            auth()->id()
        );

        $pdf = Pdf::loadView('admin.reports.pdf', array_merge($data, [
            'rangePreset' => $range['preset'],
            'startDate' => $range['start_date'],
            'endDate' => $range['end_date'],
            'isMultiDay' => $range['is_multi_day'],
            'departmentCode' => $deptFilter,
        ]));

        $filename = ($range['start_date'] === $range['end_date'])
            ? "Production-Report-{$range['start_date']}.pdf"
            : "Production-Report-{$range['start_date']}-to-{$range['end_date']}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Export the production report in WhatsApp optimized Landscape format.
     */
    public function exportWhatsappPdf(Request $request)
    {
        $range = DailyReportService::resolveDateRange($request);
        $deptFilter = $request->input('department_code', 'all');

        $data = DailyReportService::getProductionReportData(
            $range['start_date'],
            $range['end_date'],
            $deptFilter
        );

        ActivityLogService::log(
            'PDF_DOWNLOADED',
            "WhatsApp landscape production report PDF downloaded for range {$range['start_date']} to {$range['end_date']}.",
            auth()->id()
        );

        $pdf = Pdf::loadView('admin.reports.whatsapp', array_merge($data, [
            'rangePreset' => $range['preset'],
            'startDate' => $range['start_date'],
            'endDate' => $range['end_date'],
            'isMultiDay' => $range['is_multi_day'],
            'departmentCode' => $deptFilter,
        ]))->setPaper('a4', 'landscape');

        $filename = ($range['start_date'] === $range['end_date'])
            ? "WhatsApp-Production-Report-{$range['start_date']}.pdf"
            : "WhatsApp-Production-Report-{$range['start_date']}-to-{$range['end_date']}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Export the production report to Excel (CSV format).
     */
    public function exportExcel(Request $request)
    {
        $range = DailyReportService::resolveDateRange($request);
        $deptFilter = $request->input('department_code', 'all');

        $data = DailyReportService::getProductionReportData(
            $range['start_date'],
            $range['end_date'],
            $deptFilter
        );

        ActivityLogService::log(
            'EXCEL_DOWNLOADED',
            "Production report Excel downloaded for range {$range['start_date']} to {$range['end_date']}.",
            auth()->id()
        );

        $filename = "Production-Report-{$range['start_date']}-to-{$range['end_date']}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['SOLCON INDUSTRIES - PRODUCTION SUMMARY REPORT']);
            fputcsv($file, ['Period:', $data['startDate'] . ' to ' . $data['endDate']]);
            fputcsv($file, ['Department Filter:', strtoupper($data['deptFilter'])]);
            fputcsv($file, []);

            // Adhesive Production Data
            if ($data['showAdhesive']) {
                fputcsv($file, ['--- TILE ADHESIVE PRODUCTION REPORT ---']);
                
                foreach ($data['adhMachineDetails'] as $mDetails) {
                    fputcsv($file, ['MACHINE:', $mDetails['machine_name']]);
                    fputcsv($file, ['Batch No', 'Grade', 'Quantity (Bags)', 'Start Time', 'End Time', 'Supervisor']);

                    foreach ($mDetails['batches'] as $b) {
                        fputcsv($file, [
                            $b->batch_no,
                            $b->grade ? $b->grade->name : '-',
                            $b->output_bags,
                            $b->start_time ? $b->start_time->format('Y-m-d H:i') : '-',
                            $b->end_time ? $b->end_time->format('Y-m-d H:i') : '-',
                            $b->supervisor ? $b->supervisor->name : '-',
                        ]);
                    }

                    // Machine Grade Totals
                    fputcsv($file, ['Grade Wise Total for ' . $mDetails['machine_name']]);
                    foreach ($mDetails['grade_totals'] as $gName => $bags) {
                        fputcsv($file, ['', $gName, $bags . ' Bags']);
                    }
                    fputcsv($file, ['Machine Total:', $mDetails['total_batches'] . ' Batches', $mDetails['total_bags'] . ' Bags', number_format($mDetails['total_kg'], 2) . ' KG']);
                    fputcsv($file, []);
                }

                // Overall Grade Wise Totals
                fputcsv($file, ['OVERALL GRADE WISE TOTALS']);
                fputcsv($file, ['Grade', 'Total Bags']);
                foreach ($data['adhOverallGradeTotals'] as $gName => $bags) {
                    fputcsv($file, [$gName, $bags . ' Bags']);
                }
                fputcsv($file, []);

                // Machine Wise Grand Totals
                fputcsv($file, ['MACHINE WISE GRAND TOTALS']);
                fputcsv($file, ['Machine', 'Total Bags', 'Total KG']);
                foreach ($data['adhMachineGrandTotals'] as $mName => $mTot) {
                    fputcsv($file, [$mName, $mTot['bags'] . ' Bags', number_format($mTot['kg'], 2) . ' KG']);
                }
                fputcsv($file, []);

                // Adhesive Final Summary
                fputcsv($file, ['ADHESIVE FINAL SUMMARY']);
                fputcsv($file, ['Total Machines', $data['totalMachinesUsed']]);
                fputcsv($file, ['Total Batches', $data['grandTotal']->total_batches]);
                fputcsv($file, ['Total Bags', $data['grandTotal']->total_bags]);
                fputcsv($file, ['Total Weight (KG)', number_format($data['grandTotal']->total_kg, 2)]);
                fputcsv($file, []);
            }

            // Grout Data
            if ($data['showGrout'] && count($data['groutCompletedBatches']) > 0) {
                fputcsv($file, ['--- GROUT PRODUCTION REPORT ---']);
                fputcsv($file, ['Batch No', 'Color', 'Bags Output', 'Weight (KG)', 'Operator']);
                foreach ($data['groutCompletedBatches'] as $gb) {
                    fputcsv($file, [
                        $gb->batch_no,
                        $gb->color ? $gb->color->name : '-',
                        $gb->finished_bags,
                        number_format($gb->total_weight_kg, 2),
                        $gb->operator ? $gb->operator->name : '-',
                    ]);
                }
                fputcsv($file, ['Grout Total Bags', $data['groutGrandTotal']->total_bags]);
                fputcsv($file, ['Grout Total KG', number_format($data['groutGrandTotal']->total_kg, 2)]);
                fputcsv($file, []);
            }

            // Epoxy Data
            if ($data['showEpoxy'] && (count($data['epoxyCompletedAssemblies']) > 0 || count($data['epoxyPreparations']) > 0)) {
                fputcsv($file, ['--- EPOXY PRODUCTION & COMPONENT SUMMARY ---']);
                fputcsv($file, []);

                if (count($data['epoxyCompletedAssemblies']) > 0) {
                    fputcsv($file, ['FINISHED KIT/BUCKET ASSEMBLIES']);
                    fputcsv($file, ['Product', 'Color', 'Kits Assembled', 'Operator']);
                    foreach ($data['epoxyCompletedAssemblies'] as $ea) {
                        fputcsv($file, [
                            $ea->product ? $ea->product->name : '-',
                            $ea->color ? $ea->color->name : '-',
                            $ea->quantity,
                            $ea->operator ? $ea->operator->name : '-',
                        ]);
                    }
                    fputcsv($file, ['Epoxy Total Kits', '', $data['epoxyGrandTotal']->total_kits]);
                    fputcsv($file, []);
                }

                if (count($data['epoxyPreparations']) > 0) {
                    fputcsv($file, ['FLOOR COMPONENT PREPARATIONS']);
                    fputcsv($file, ['Category / Component', 'Type', 'Prepared Qty']);

                    if (count($data['epoxyPrepGrouped']['bottles']) > 0) {
                        fputcsv($file, ['RESIN & HARDENER (NOS)']);
                        foreach ($data['epoxyPrepGrouped']['bottles'] as $name => $qty) {
                            fputcsv($file, [$name, 'Bottle', $qty]);
                        }
                    }

                    if (count($data['epoxyPrepGrouped']['fillers']) > 0) {
                        fputcsv($file, ['FILLER COLOR POUCHES (PKT)']);
                        foreach ($data['epoxyPrepGrouped']['fillers'] as $colorName => $qty) {
                            fputcsv($file, ["700gm {$colorName} Filler Pouch", 'Pouch', $qty]);
                        }
                    }

                    if (count($data['epoxyPrepGrouped']['sparkles']) > 0) {
                        fputcsv($file, ['JARI POWDER / SPARKLES (PKT)']);
                        foreach ($data['epoxyPrepGrouped']['sparkles'] as $name => $qty) {
                            fputcsv($file, [$name, 'Pouch', $qty]);
                        }
                    }

                    if (count($data['epoxyPrepGrouped']['cleaners']) > 0) {
                        fputcsv($file, ['TILES CLEANERS (BOX/NOS)']);
                        foreach ($data['epoxyPrepGrouped']['cleaners'] as $name => $qty) {
                            fputcsv($file, [$name, 'Cleaner', $qty]);
                        }
                    }

                    if (count($data['epoxyPrepGrouped']['others']) > 0) {
                        fputcsv($file, ['SBR, SK+ & OTHERS (NOS)']);
                        foreach ($data['epoxyPrepGrouped']['others'] as $name => $qty) {
                            fputcsv($file, [$name, 'Other', $qty]);
                        }
                    }
                    fputcsv($file, []);
                }
            }

            // Material Consumption Summary (OUT)
            if ($data['showConsumption'] && (count($data['materialSummary']) > 0 || count($data['packingMaterialConsumptionSummary']) > 0)) {
                fputcsv($file, ['--- MATERIAL CONSUMPTION SUMMARY (OUT) ---']);
                
                if (count($data['materialSummary']) > 0) {
                    fputcsv($file, ['RAW MATERIAL CONSUMPTION']);
                    fputcsv($file, ['Material Name', 'Code', 'Department', 'Consumed Quantity', 'Unit']);
                    foreach ($data['materialSummary'] as $mat) {
                        if ($mat->rawMaterial) {
                            fputcsv($file, [
                                $mat->rawMaterial->name,
                                $mat->rawMaterial->code,
                                $mat->rawMaterial->department?->name ?? '-',
                                number_format($mat->total_consumed, 2),
                                $mat->rawMaterial->stockUnit?->code ?? 'KG',
                            ]);
                        }
                    }
                    fputcsv($file, ['Total Raw Consumed Weight:', number_format($data['totalConsumptionWeight'], 2) . ' KG']);
                    fputcsv($file, []);
                }

                if (count($data['packingMaterialConsumptionSummary']) > 0) {
                    fputcsv($file, ['PACKING MATERIAL CONSUMPTION']);
                    fputcsv($file, ['Packing Material Name', 'Code', 'Category', 'Consumed Quantity (PCS)']);
                    foreach ($data['packingMaterialConsumptionSummary'] as $pmOut) {
                        if ($pmOut->packingMaterial) {
                            fputcsv($file, [
                                $pmOut->packingMaterial->name,
                                $pmOut->packingMaterial->code,
                                $pmOut->packingMaterial->category?->name ?? '-',
                                $pmOut->total_consumed,
                            ]);
                        }
                    }
                    fputcsv($file, ['Total Packing Consumed Quantity:', $data['totalConsumptionPackingQty'] . ' PCS']);
                    fputcsv($file, []);
                }
            }

            // Material Inward Summary (IN)
            if ($data['showInward'] && (count($data['rawMaterialInwardSummary']) > 0 || count($data['packingMaterialInwardSummary']) > 0)) {
                fputcsv($file, ['--- MATERIAL INWARD & STOCK RECEIPTS (IN) ---']);
                
                if (count($data['rawMaterialInwardSummary']) > 0) {
                    fputcsv($file, ['RAW MATERIAL INWARD']);
                    fputcsv($file, ['Material Name', 'Code', 'Department', 'Inward Quantity', 'Unit', 'Entries Count']);
                    foreach ($data['rawMaterialInwardSummary'] as $rmIn) {
                        if ($rmIn->rawMaterial) {
                            fputcsv($file, [
                                $rmIn->rawMaterial->name,
                                $rmIn->rawMaterial->code,
                                $rmIn->rawMaterial->department?->name ?? '-',
                                $rmIn->total_inward,
                                $rmIn->rawMaterial->stockUnit?->code ?? 'KG',
                                $rmIn->entry_count,
                            ]);
                        }
                    }
                    fputcsv($file, ['Total Raw Inward Weight:', number_format($data['totalInwardRawWeight'], 2) . ' KG']);
                    fputcsv($file, []);
                }

                if (count($data['packingMaterialInwardSummary']) > 0) {
                    fputcsv($file, ['PACKING MATERIAL INWARD']);
                    fputcsv($file, ['Material Name', 'Code', 'Category', 'Inward Quantity (PCS)', 'Entries Count']);
                    foreach ($data['packingMaterialInwardSummary'] as $pmIn) {
                        if ($pmIn->packingMaterial) {
                            fputcsv($file, [
                                $pmIn->packingMaterial->name,
                                $pmIn->packingMaterial->code,
                                $pmIn->packingMaterial->category?->name ?? '-',
                                $pmIn->total_inward,
                                $pmIn->entry_count,
                            ]);
                        }
                    }
                    fputcsv($file, ['Total Packing Inward Quantity:', $data['totalInwardPackingQty'] . ' PCS']);
                    fputcsv($file, []);
                }
            }

            // Completed Dispatches Report
            if ($data['showDispatch'] && count($data['dispatches']) > 0) {
                fputcsv($file, ['--- COMPLETED DISPATCHES & LOGISTICS REPORT ---']);
                fputcsv($file, ['Total Dispatches:', $data['totalDispatchesCount']]);
                fputcsv($file, ['Total Dispatched Weight:', number_format($data['totalDispatchedWeightKg'], 2) . ' KG (' . number_format($data['totalDispatchedWeightKg'] / 1000, 3) . ' Tons)']);
                fputcsv($file, []);

                fputcsv($file, ['DISPATCHED PRODUCTS BREAKDOWN']);
                fputcsv($file, ['Product Name', 'Department', 'Quantity', 'Unit', 'Weight (KG)', 'Weight (Tons)']);
                foreach ($data['dispatchedProductsSummary'] as $prod) {
                    fputcsv($file, [
                        $prod['product_name'],
                        $prod['department'],
                        $prod['total_quantity'],
                        $prod['unit'],
                        number_format($prod['total_weight_kg'], 2),
                        number_format($prod['total_weight_kg'] / 1000, 3),
                    ]);
                }
                fputcsv($file, []);

                fputcsv($file, ['COMPLETED DISPATCHES LIST']);
                fputcsv($file, ['Dispatch No', 'Date', 'Party Name', 'City', 'Vehicle Number', 'Total Bags/Units', 'Total Weight (KG)', 'Products Summary']);
                foreach ($data['dispatches'] as $d) {
                    $dBags = $d->items->sum('quantity_bags');
                    $dKg = $d->items->sum('calculated_weight_kg');
                    $dDate = $d->loaded_at ?? $d->created_at;
                    $itemSummary = $d->items->map(fn($it) => $it->quantity_bags . ' ' . $it->unit_label . ' ' . $it->product_name)->implode(', ');
                    fputcsv($file, [
                        $d->dispatch_number,
                        $dDate ? $dDate->format('Y-m-d H:i') : '-',
                        $d->party_name,
                        $d->city ?: '-',
                        $d->vehicle_number ?: '-',
                        $dBags,
                        number_format($dKg, 2),
                        $itemSummary,
                    ]);
                }
                fputcsv($file, []);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
