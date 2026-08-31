<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Production Report - {{ $startDate }} {{ $isMultiDay ? 'to ' . $endDate : '' }}</title>
    <style>
        @page {
            margin: 25px 30px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 10px;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .logo {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .report-title {
            font-size: 12px;
            font-weight: 700;
            color: #2563eb;
            margin-top: 2px;
            text-transform: uppercase;
        }
        .report-meta {
            float: right;
            text-align: right;
            font-size: 10px;
            color: #475569;
            margin-top: -32px;
        }
        .badge {
            display: inline-block;
            background: #e2e8f0;
            color: #1e293b;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            margin-top: 2px;
        }
        .section-header {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            background-color: #f1f5f9;
            padding: 5px 8px;
            border-left: 4px solid #2563eb;
            margin-top: 14px;
            margin-bottom: 6px;
        }
        .machine-card {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        .machine-title {
            font-size: 10px;
            font-weight: 800;
            color: #1e293b;
            background: #e2e8f0;
            padding: 4px 8px;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            border: 1px solid #cbd5e1;
            border-bottom: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 800;
            text-align: left;
            padding: 4px 6px;
            font-size: 9px;
            text-transform: uppercase;
            border: 1px solid #cbd5e1;
        }
        td {
            padding: 4px 6px;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 9.5px;
        }
        tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .subtotal-row td {
            background-color: #eff6ff !important;
            font-weight: 700;
            color: #1e40af;
            border-top: 1.5px solid #93c5fd;
        }
        .grade-pills {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-top: none;
            padding: 4px 8px;
            font-size: 9px;
            color: #334155;
        }
        .grade-pill-item {
            display: inline-block;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 1px 6px;
            border-radius: 3px;
            margin-right: 4px;
            font-weight: 700;
        }
        .summary-box {
            background: #f8fafc;
            border: 1.5px solid #0f172a;
            border-radius: 4px;
            padding: 8px 12px;
            margin-top: 14px;
            page-break-inside: avoid;
        }
        .summary-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-grid td {
            border: none;
            padding: 4px 8px;
            background: transparent !important;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: Courier, monospace; }
        .font-bold { font-weight: bold; }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            border-top: 1px solid #cbd5e1;
            padding-top: 4px;
            font-size: 8px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">SOLCON INDUSTRIES</div>
        <div class="report-title">PRODUCTION SUMMARY REPORT</div>
        <div class="report-meta">
            <div><strong>Period:</strong> {{ $startDate }} {{ $isMultiDay ? 'to ' . $endDate : '' }}</div>
            <div class="badge">Department: {{ strtoupper($deptFilter === 'all' ? 'All Departments' : (in_array(strtoupper($deptFilter), ['DSP', 'DISPATCH']) ? 'Dispatch' : $deptFilter)) }}</div>
        </div>
    </div>

    <!-- 1. TILE ADHESIVE DEPARTMENT -->
    @if($showAdhesive)
        <div class="section-header">1. TILE ADHESIVE PRODUCTION</div>

        @if($isMultiDay && count($dayWiseAdhesive) > 0)
            <!-- Day-Wise Grouping for Multi-Day Range -->
            @foreach($dayWiseAdhesive as $dayDate => $dayData)
                <div style="margin-top: 10px; margin-bottom: 4px; font-weight: 800; font-size: 10.5px; color: #1e3a8a; border-bottom: 1px solid #93c5fd; padding-bottom: 2px;">
                    DATE: {{ $dayDate }} (Daily Total: {{ $dayData['total_batches'] }} Batches | {{ number_format($dayData['total_bags']) }} Bags)
                </div>

                @foreach($dayData['machines'] as $m)
                    <div class="machine-card">
                        <div class="machine-title">MACHINE: {{ strtoupper($m['machine_name']) }}</div>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 22%;">Batch No</th>
                                    <th style="width: 18%;">Grade</th>
                                    <th class="text-right" style="width: 15%;">Qty (Bags)</th>
                                    <th class="text-center" style="width: 18%;">Start Time</th>
                                    <th class="text-center" style="width: 18%;">End Time</th>
                                    <th style="width: 18%;">Supervisor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($m['batches'] as $b)
                                    <tr>
                                        <td class="font-mono font-bold">{{ $b->batch_no }}</td>
                                        <td class="font-bold">{{ $b->grade ? $b->grade->name : '-' }}</td>
                                        <td class="text-right font-mono font-bold">{{ number_format($b->output_bags) }} Bags</td>
                                        <td class="text-center font-mono">{{ $b->start_time ? $b->start_time->format('h:i A') : '-' }}</td>
                                        <td class="text-center font-mono">{{ $b->end_time ? $b->end_time->format('h:i A') : '-' }}</td>
                                        <td>{{ $b->supervisor ? $b->supervisor->name : '-' }}</td>
                                    </tr>
                                @endforeach
                                <tr class="subtotal-row">
                                    <td colspan="2" class="font-bold">Machine Grand Total ({{ $m['machine_name'] }})</td>
                                    <td class="text-right font-mono font-bold">{{ number_format($m['total_bags']) }} Bags</td>
                                    <td colspan="2" class="text-right font-mono">{{ number_format($m['total_kg'], 2) }} KG</td>
                                    <td>{{ $m['total_batches'] }} Batches</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Grade Wise Total for this Machine -->
                        <div class="grade-pills">
                            <strong>Grade Wise Total ({{ $m['machine_name'] }}):</strong>
                            @foreach($m['grade_totals'] as $gName => $gBags)
                                <span class="grade-pill-item">{{ $gName }}: {{ number_format($gBags) }} Bags</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endforeach

        @else
            <!-- Single Day Machine-Wise Breakdown -->
            @forelse($adhMachineDetails as $m)
                <div class="machine-card">
                    <div class="machine-title">MACHINE: {{ strtoupper($m['machine_name']) }}</div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 22%;">Batch No</th>
                                <th style="width: 18%;">Grade</th>
                                <th class="text-right" style="width: 15%;">Qty (Bags)</th>
                                <th class="text-center" style="width: 18%;">Start Time</th>
                                <th class="text-center" style="width: 18%;">End Time</th>
                                <th style="width: 18%;">Supervisor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($m['batches'] as $b)
                                <tr>
                                    <td class="font-mono font-bold">{{ $b->batch_no }}</td>
                                    <td class="font-bold">{{ $b->grade ? $b->grade->name : '-' }}</td>
                                    <td class="text-right font-mono font-bold">{{ number_format($b->output_bags) }} Bags</td>
                                    <td class="text-center font-mono">{{ $b->start_time ? $b->start_time->format('h:i A') : '-' }}</td>
                                    <td class="text-center font-mono">{{ $b->end_time ? $b->end_time->format('h:i A') : '-' }}</td>
                                    <td>{{ $b->supervisor ? $b->supervisor->name : '-' }}</td>
                                </tr>
                            @endforeach
                            <tr class="subtotal-row">
                                <td colspan="2" class="font-bold">Machine Grand Total ({{ $m['machine_name'] }})</td>
                                <td class="text-right font-mono font-bold">{{ number_format($m['total_bags']) }} Bags</td>
                                <td colspan="2" class="text-right font-mono">{{ number_format($m['total_kg'], 2) }} KG</td>
                                <td>{{ $m['total_batches'] }} Batches</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Grade Wise Total for this Machine -->
                    <div class="grade-pills">
                        <strong>Grade Wise Total ({{ $m['machine_name'] }}):</strong>
                        @foreach($m['grade_totals'] as $gName => $gBags)
                            <span class="grade-pill-item">{{ $gName }}: {{ number_format($gBags) }} Bags</span>
                        @endforeach
                    </div>
                </div>
            @empty
                <p style="font-style: italic; color: #64748b; padding: 4px 0;">No tile adhesive production recorded for this date range.</p>
            @endforelse
        @endif

        <!-- OVERALL GRADE WISE TOTALS ACROSS ALL MACHINES -->
        @if(count($adhOverallGradeTotals) > 0)
            <div style="margin-top: 10px; page-break-inside: avoid;">
                <div style="font-size: 10px; font-weight: 800; color: #0f172a; text-transform: uppercase; margin-bottom: 4px;">OVERALL GRADE WISE TOTALS</div>
                <table>
                    <thead>
                        <tr>
                            <th>Grade Name</th>
                            <th class="text-right">Total Bags Produced</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adhOverallGradeTotals as $gName => $gBags)
                            <tr>
                                <td class="font-bold">{{ $gName }}</td>
                                <td class="text-right font-mono font-bold">{{ number_format($gBags) }} Bags</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- MACHINE WISE GRAND TOTALS SUMMARY -->
        @if(count($adhMachineGrandTotals) > 0)
            <div style="margin-top: 10px; page-break-inside: avoid;">
                <div style="font-size: 10px; font-weight: 800; color: #0f172a; text-transform: uppercase; margin-bottom: 4px;">MACHINE WISE GRAND TOTALS</div>
                <table>
                    <thead>
                        <tr>
                            <th>Machine Name</th>
                            <th class="text-center">Total Batches</th>
                            <th class="text-right">Total Bags</th>
                            <th class="text-right">Total Weight (KG)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adhMachineGrandTotals as $mName => $mTot)
                            <tr>
                                <td class="font-bold">{{ $mName }}</td>
                                <td class="text-center font-mono">{{ $mTot['batches'] }}</td>
                                <td class="text-right font-mono font-bold">{{ number_format($mTot['bags']) }} Bags</td>
                                <td class="text-right font-mono font-bold">{{ number_format($mTot['kg'], 2) }} KG</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- ADHESIVE FINAL SUMMARY BOX -->
        <div class="summary-box">
            <div style="font-weight: 800; font-size: 11px; color: #0f172a; text-transform: uppercase; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 6px;">
                TILE ADHESIVE FINAL SUMMARY
            </div>
            <table class="summary-grid">
                <tr>
                    <td style="width: 25%;"><strong>Total Machines Used:</strong> <span class="font-mono">{{ $totalMachinesUsed }}</span></td>
                    <td style="width: 25%;"><strong>Total Batches:</strong> <span class="font-mono">{{ $grandTotal->total_batches }}</span></td>
                    <td style="width: 25%;"><strong>Total Bags:</strong> <span class="font-mono font-bold">{{ number_format($grandTotal->total_bags) }}</span></td>
                    <td style="width: 25%;"><strong>Total Weight:</strong> <span class="font-mono font-bold">{{ number_format($grandTotal->total_kg, 2) }} KG</span></td>
                </tr>
            </table>
        </div>
    @endif

    <!-- 2. GROUT DEPARTMENT SUMMARY -->
    @if($showGrout && count($groutCompletedBatches) > 0)
        <div class="section-header" style="border-left-color: #10b981; color: #065f46;">2. GROUT PRODUCTION SUMMARY</div>
        <table>
            <thead>
                <tr>
                    <th>Batch No</th>
                    <th>Color</th>
                    <th class="text-right">Bags Output</th>
                    <th class="text-right">Total KG</th>
                    <th>Operator</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groutCompletedBatches as $gb)
                    <tr>
                        <td class="font-mono font-bold">{{ $gb->batch_no }}</td>
                        <td class="font-bold">{{ $gb->color ? $gb->color->name : '-' }}</td>
                        <td class="text-right font-mono font-bold">{{ number_format($gb->finished_bags) }} Bags</td>
                        <td class="text-right font-mono">{{ number_format($gb->total_weight_kg, 2) }} KG</td>
                        <td>{{ $gb->operator ? $gb->operator->name : '-' }}</td>
                    </tr>
                @endforeach
                <tr class="subtotal-row">
                    <td colspan="2" class="font-bold">Grout Total</td>
                    <td class="text-right font-mono font-bold">{{ number_format($groutGrandTotal->total_bags) }} Bags</td>
                    <td class="text-right font-mono font-bold">{{ number_format($groutGrandTotal->total_kg, 2) }} KG</td>
                    <td>{{ $groutGrandTotal->total_batches }} Batches</td>
                </tr>
            </tbody>
        </table>
    @endif

    <!-- 3. EPOXY DEPARTMENT SUMMARY -->
    @if($showEpoxy && (count($epoxyCompletedAssemblies) > 0 || count($epoxyPreparations) > 0))
        <div class="section-header" style="border-left-color: #8b5cf6; color: #581c87;">3. EPOXY PRODUCTION & COMPONENT SUMMARY</div>
        
        @if(count($epoxyCompletedAssemblies) > 0)
            <div style="margin-bottom: 8px; font-weight: bold; font-size: 11px; color: #581c87;">3.1 Finished Kit/Bucket Assemblies</div>
            <table style="margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Color</th>
                        <th class="text-right">Kits Assembled</th>
                        <th>Operator</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($epoxyCompletedAssemblies as $ea)
                        <tr>
                            <td class="font-bold">{{ $ea->product ? $ea->product->name : '-' }}</td>
                            <td>{{ $ea->color ? $ea->color->name : '-' }}</td>
                            <td class="text-right font-mono font-bold">{{ number_format($ea->quantity) }} Kits</td>
                            <td>{{ $ea->operator ? $ea->operator->name : '-' }}</td>
                        </tr>
                    @endforeach
                    <tr class="subtotal-row">
                        <td colspan="2" class="font-bold">Epoxy Total Kits</td>
                        <td class="text-right font-mono font-bold">{{ number_format($epoxyGrandTotal->total_kits) }} Kits</td>
                        <td>{{ $epoxyGrandTotal->total_assemblies }} Assemblies</td>
                    </tr>
                </tbody>
            </table>
        @endif

        @if(count($epoxyPreparations) > 0)
            <div style="margin-bottom: 8px; font-weight: bold; font-size: 11px; color: #581c87;">3.2 Floor Component Preparations</div>
            <table style="margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th>Category / Component</th>
                        <th>Type</th>
                        <th class="text-right font-bold">Prepared Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($epoxyPrepGrouped['bottles']) > 0)
                        <tr>
                            <td colspan="3" style="background-color: #f3f4f6; font-weight: bold; color: #1e40af; font-size: 9px; text-transform: uppercase; padding: 4px 8px;">Resin & Hardener (NOS)</td>
                        </tr>
                        @foreach($epoxyPrepGrouped['bottles'] as $name => $qty)
                            <tr>
                                <td style="padding-left: 15px;">{{ $name }}</td>
                                <td>Bottle</td>
                                <td class="text-right font-mono font-bold">{{ number_format($qty) }} Nos</td>
                            </tr>
                        @endforeach
                    @endif

                    @if(count($epoxyPrepGrouped['fillers']) > 0)
                        <tr>
                            <td colspan="3" style="background-color: #f3f4f6; font-weight: bold; color: #065f46; font-size: 9px; text-transform: uppercase; padding: 4px 8px;">Filler Color Pouches (PKT)</td>
                        </tr>
                        @foreach($epoxyPrepGrouped['fillers'] as $colorName => $qty)
                            <tr>
                                <td style="padding-left: 15px;">700gm {{ $colorName }} Filler Pouch</td>
                                <td>Pouch</td>
                                <td class="text-right font-mono font-bold">{{ number_format($qty) }} Pkt</td>
                            </tr>
                        @endforeach
                    @endif

                    @if(count($epoxyPrepGrouped['sparkles']) > 0)
                        <tr>
                            <td colspan="3" style="background-color: #f3f4f6; font-weight: bold; color: #92400e; font-size: 9px; text-transform: uppercase; padding: 4px 8px;">Jari Powder / Sparkles (PKT)</td>
                        </tr>
                        @foreach($epoxyPrepGrouped['sparkles'] as $name => $qty)
                            <tr>
                                <td style="padding-left: 15px;">{{ $name }}</td>
                                <td>Pouch</td>
                                <td class="text-right font-mono font-bold">{{ number_format($qty) }} Pkt</td>
                            </tr>
                        @endforeach
                    @endif

                    @if(count($epoxyPrepGrouped['cleaners']) > 0)
                        <tr>
                            <td colspan="3" style="background-color: #f3f4f6; font-weight: bold; color: #155e75; font-size: 9px; text-transform: uppercase; padding: 4px 8px;">Tiles Cleaner (BOX / NOS)</td>
                        </tr>
                        @foreach($epoxyPrepGrouped['cleaners'] as $name => $qty)
                            <tr>
                                <td style="padding-left: 15px;">{{ $name }}</td>
                                <td>Cleaner</td>
                                <td class="text-right font-mono font-bold">{{ number_format($qty) }} Nos</td>
                            </tr>
                        @endforeach
                    @endif

                    @if(count($epoxyPrepGrouped['others']) > 0)
                        <tr>
                            <td colspan="3" style="background-color: #f3f4f6; font-weight: bold; color: #374151; font-size: 9px; text-transform: uppercase; padding: 4px 8px;">SBR, SK+ & Others (NOS)</td>
                        </tr>
                        @foreach($epoxyPrepGrouped['others'] as $name => $qty)
                            <tr>
                                <td style="padding-left: 15px;">{{ $name }}</td>
                                <td>Other</td>
                                <td class="text-right font-mono font-bold">{{ number_format($qty) }} Nos</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        @endif
    @endif

    <!-- 4. MATERIAL CONSUMPTION (OUT) -->
    @if($showConsumption && (count($materialSummary) > 0 || count($packingMaterialConsumptionSummary) > 0))
        <div class="section-header" style="border-left-color: #f59e0b; color: #b45309;">4. MATERIAL CONSUMPTION (OUT)</div>
        
        @if(count($materialSummary) > 0)
            <div style="margin-bottom: 4px; font-weight: bold; font-size: 10px; color: #b45309;">4.1 Raw Material Consumption (Total: {{ number_format($totalConsumptionWeight, 2) }} KG)</div>
            <table style="margin-bottom: 10px;">
                <thead>
                    <tr>
                        <th>Material Name</th>
                        <th>Code</th>
                        <th>Dept</th>
                        <th class="text-right">Consumed Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materialSummary as $mat)
                        @if($mat->rawMaterial)
                            <tr>
                                <td class="font-bold">{{ $mat->rawMaterial->name }}</td>
                                <td class="font-mono">{{ $mat->rawMaterial->code }}</td>
                                <td>{{ $mat->rawMaterial->department?->code ?? '-' }}</td>
                                <td class="text-right font-mono font-bold" style="color: #b45309;">{{ number_format($mat->total_consumed, 2) }} {{ $mat->rawMaterial->stockUnit?->code ?? $mat->rawMaterial->unit?->code ?? 'KG' }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif

        @if(count($packingMaterialConsumptionSummary) > 0)
            <div style="margin-bottom: 4px; font-weight: bold; font-size: 10px; color: #4338ca;">4.2 Packing Material Consumption (Total: {{ number_format($totalConsumptionPackingQty) }} Units)</div>
            <table style="margin-bottom: 10px;">
                <thead>
                    <tr>
                        <th>Packing Material Name</th>
                        <th>Code</th>
                        <th>Category</th>
                        <th class="text-right">Consumed Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packingMaterialConsumptionSummary as $pmOut)
                        @if($pmOut->packingMaterial)
                            <tr>
                                <td class="font-bold">{{ $pmOut->packingMaterial->name }}</td>
                                <td class="font-mono">{{ $pmOut->packingMaterial->code }}</td>
                                <td>{{ $pmOut->packingMaterial->category?->name ?? '-' }}</td>
                                <td class="text-right font-mono font-bold" style="color: #4338ca;">{{ number_format($pmOut->total_consumed) }} PCS</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif
    @endif

    <!-- 5. MATERIAL INWARD & STOCK RECEIPTS (IN) -->
    @if($showInward && (count($rawMaterialInwardSummary) > 0 || count($packingMaterialInwardSummary) > 0))
        <div class="section-header" style="border-left-color: #10b981; color: #047857;">5. MATERIAL INWARD & STOCK RECEIPTS (IN)</div>
        
        @if(count($rawMaterialInwardSummary) > 0)
            <div style="margin-bottom: 4px; font-weight: bold; font-size: 10px; color: #047857;">5.1 Raw Material Receipts (Total: {{ number_format($totalInwardRawWeight, 2) }} KG)</div>
            <table style="margin-bottom: 10px;">
                <thead>
                    <tr>
                        <th>Material Name</th>
                        <th>Code</th>
                        <th class="text-center">Entries</th>
                        <th class="text-right">Inward Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rawMaterialInwardSummary as $rmIn)
                        @if($rmIn->rawMaterial)
                            <tr>
                                <td class="font-bold">{{ $rmIn->rawMaterial->name }}</td>
                                <td class="font-mono">{{ $rmIn->rawMaterial->code }}</td>
                                <td class="text-center font-mono">{{ $rmIn->entry_count }}</td>
                                <td class="text-right font-mono font-bold" style="color: #047857;">+{{ number_format($rmIn->total_inward, 2) }} {{ $rmIn->rawMaterial->stockUnit?->code ?? 'KG' }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif

        @if(count($packingMaterialInwardSummary) > 0)
            <div style="margin-bottom: 4px; font-weight: bold; font-size: 10px; color: #0369a1;">5.2 Packing Material Receipts (Total: {{ number_format($totalInwardPackingQty) }} Units)</div>
            <table style="margin-bottom: 10px;">
                <thead>
                    <tr>
                        <th>Packing Material Name</th>
                        <th>Code</th>
                        <th class="text-center">Entries</th>
                        <th class="text-right">Inward Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packingMaterialInwardSummary as $pmIn)
                        @if($pmIn->packingMaterial)
                            <tr>
                                <td class="font-bold">{{ $pmIn->packingMaterial->name }}</td>
                                <td class="font-mono">{{ $pmIn->packingMaterial->code }}</td>
                                <td class="text-center font-mono">{{ $pmIn->entry_count }}</td>
                                <td class="text-right font-mono font-bold" style="color: #0369a1;">+{{ number_format($pmIn->total_inward) }} PCS</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif
    @endif

    <!-- 6. DISPATCH & OUTWARD LOGISTICS SUMMARY -->
    @if($showDispatch && count($dispatches) > 0)
        <div class="section-header" style="border-left-color: #3b82f6; color: #1d4ed8;">6. DISPATCH & OUTWARD LOGISTICS SUMMARY</div>
        
        <div style="margin-bottom: 4px; font-weight: bold; font-size: 10px; color: #1d4ed8;">
            Dispatched Totals: {{ $totalDispatchesCount }} Completed Dispatches | {{ number_format($totalDispatchedWeightKg, 2) }} KG ({{ number_format($totalDispatchedWeightKg / 1000, 3) }} Tons)
        </div>

        @if(count($dispatchedProductsSummary) > 0)
            <table style="margin-bottom: 10px;">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Department</th>
                        <th class="text-right">Quantity</th>
                        <th class="text-right">Weight (KG)</th>
                        <th class="text-right">Weight (Tons)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dispatchedProductsSummary as $prod)
                        <tr>
                            <td class="font-bold">{{ $prod['product_name'] }}</td>
                            <td>{{ $prod['department'] }}</td>
                            <td class="text-right font-mono font-bold">{{ number_format($prod['total_quantity']) }} {{ $prod['unit'] }}</td>
                            <td class="text-right font-mono">{{ number_format($prod['total_weight_kg'], 2) }} KG</td>
                            <td class="text-right font-mono font-bold" style="color: #047857;">{{ number_format($prod['total_weight_kg'] / 1000, 3) }} T</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div style="margin-bottom: 4px; font-weight: bold; font-size: 10px; color: #475569;">Completed Dispatches List:</div>
        <table>
            <thead>
                <tr>
                    <th>Dispatch No</th>
                    <th>Date</th>
                    <th>Party Name</th>
                    <th>City</th>
                    <th>Vehicle</th>
                    <th class="text-right">Units</th>
                    <th class="text-right">Weight (KG)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dispatches as $d)
                    @php
                        $dBags = $d->items->sum('quantity_bags');
                        $dKg = $d->items->sum('calculated_weight_kg');
                        $dDate = $d->loaded_at ?? $d->created_at;
                    @endphp
                    <tr>
                        <td class="font-mono font-bold">{{ $d->dispatch_number }}</td>
                        <td class="font-mono">{{ $dDate ? $dDate->format('d/m/Y') : '-' }}</td>
                        <td class="font-bold">{{ $d->party_name }}</td>
                        <td>{{ $d->city ?: '-' }}</td>
                        <td class="font-mono">{{ $d->vehicle_number ?: '-' }}</td>
                        <td class="text-right font-mono font-bold">{{ number_format($dBags) }}</td>
                        <td class="text-right font-mono font-bold">{{ number_format($dKg, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Solcon Industries Production Tracking System | Report Generated By: {{ auth()->user()->name ?? 'System' }} | Timestamp: {{ now()->format('d M Y, h:i:s A') }}
    </div>

</body>
</html>
