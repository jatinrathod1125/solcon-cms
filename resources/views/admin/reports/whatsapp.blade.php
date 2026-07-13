<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>WhatsApp Summary - {{ $startDate }} {{ $isMultiDay ? 'to ' . $endDate : '' }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20px 25px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 10px;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .logo-text {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
        }
        .report-header {
            font-size: 11px;
            color: #2563eb;
            font-weight: 700;
            text-transform: uppercase;
        }
        .meta-text {
            text-align: right;
            font-size: 9px;
            color: #475569;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        table.data-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 800;
            text-align: left;
            padding: 4px 6px;
            font-size: 9px;
            text-transform: uppercase;
            border: 1px solid #cbd5e1;
        }
        table.data-table td {
            padding: 4px 6px;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 9px;
        }
        .subtotal-row td {
            background-color: #eff6ff !important;
            font-weight: 700;
            color: #1e40af;
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

    <table class="header-table">
        <tr>
            <td>
                <div class="logo-text">SOLCON INDUSTRIES</div>
                <div class="report-header">PRODUCTION SUMMARY (WHATSAPP LANDSCAPE)</div>
            </td>
            <td class="meta-text">
                <div><strong>Period:</strong> {{ $startDate }} {{ $isMultiDay ? 'to ' . $endDate : '' }}</div>
                <div><strong>Filter:</strong> {{ strtoupper($deptFilter === 'all' ? 'All Departments' : $deptFilter) }}</div>
            </td>
        </tr>
    </table>

    @if($showAdhesive)
        <div style="font-weight: 800; font-size: 11px; color: #1e3a8a; margin-bottom: 4px;">TILE ADHESIVE MACHINE BREAKDOWN</div>

        @foreach($adhMachineDetails as $m)
            <div style="margin-bottom: 8px; page-break-inside: avoid;">
                <div style="font-weight: 800; background: #e2e8f0; padding: 3px 6px; border: 1px solid #cbd5e1; border-bottom: none;">
                    MACHINE: {{ strtoupper($m['machine_name']) }} ({{ $m['total_batches'] }} Batches | {{ number_format($m['total_bags']) }} Bags)
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Batch No</th>
                            <th>Grade</th>
                            <th class="text-right">Bags</th>
                            <th class="text-center">Start</th>
                            <th class="text-center">End</th>
                            <th>Supervisor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($m['batches'] as $b)
                            <tr>
                                <td class="font-mono font-bold">{{ $b->batch_no }}</td>
                                <td>{{ $b->grade ? $b->grade->name : '-' }}</td>
                                <td class="text-right font-mono font-bold">{{ number_format($b->output_bags) }}</td>
                                <td class="text-center font-mono">{{ $b->start_time ? $b->start_time->format('h:i A') : '-' }}</td>
                                <td class="text-center font-mono">{{ $b->end_time ? $b->end_time->format('h:i A') : '-' }}</td>
                                <td>{{ $b->supervisor ? $b->supervisor->name : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach

        <!-- Grade Totals & Final Summary -->
        <table class="data-table" style="margin-top: 6px;">
            <tr class="subtotal-row">
                <td>Total Machines: {{ $totalMachinesUsed }}</td>
                <td>Total Batches: {{ $grandTotal->total_batches }}</td>
                <td class="text-right">Total Bags: {{ number_format($grandTotal->total_bags) }}</td>
                <td class="text-right">Total Weight: {{ number_format($grandTotal->total_kg, 2) }} KG</td>
            </tr>
        </table>
    @endif
 
    @if($showGrout && count($groutCompletedBatches) > 0)
        <div style="font-weight: 800; font-size: 11px; color: #065f46; margin-top: 15px; margin-bottom: 4px; border-bottom: 1.5px solid #065f46; padding-bottom: 2px;">GROUT PRODUCTION SUMMARY</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Batch No</th>
                    <th>Color</th>
                    <th class="text-right">Bags Output</th>
                    <th class="text-right">Total Weight (KG)</th>
                    <th>Operator</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groutCompletedBatches as $gb)
                    <tr>
                        <td class="font-mono font-bold">{{ $gb->batch_no }}</td>
                        <td>{{ $gb->color ? $gb->color->name : '-' }}</td>
                        <td class="text-right font-mono font-bold">{{ number_format($gb->finished_bags) }} Bags</td>
                        <td class="text-right font-mono">{{ number_format($gb->total_weight_kg, 2) }} KG</td>
                        <td>{{ $gb->operator ? $gb->operator->name : '-' }}</td>
                    </tr>
                @endforeach
                <tr class="subtotal-row">
                    <td colspan="2" class="font-bold">Grout Total Output</td>
                    <td class="text-right font-mono font-bold">{{ number_format($groutGrandTotal->total_bags) }} Bags</td>
                    <td class="text-right font-mono font-bold">{{ number_format($groutGrandTotal->total_kg, 2) }} KG</td>
                    <td>{{ $groutGrandTotal->total_batches }} Batches</td>
                </tr>
            </tbody>
        </table>
    @endif

    @if($showEpoxy && (count($epoxyCompletedAssemblies) > 0 || count($epoxyPreparations) > 0))
        <div style="font-weight: 800; font-size: 11px; color: #581c87; margin-top: 15px; margin-bottom: 4px; border-bottom: 1.5px solid #8b5cf6; padding-bottom: 2px;">EPOXY PRODUCTION & COMPONENT SUMMARY</div>
        
        @if(count($epoxyCompletedAssemblies) > 0)
            <div style="font-weight: bold; margin-bottom: 3px; font-size: 9px; color: #581c87;">Finished Kit/Bucket Assemblies</div>
            <table class="data-table">
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
            <div style="font-weight: bold; margin-top: 8px; margin-bottom: 3px; font-size: 9px; color: #581c87;">Floor Component Preparations</div>
            <table class="data-table">
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
                            <td colspan="3" style="background-color: #f8fafc; font-weight: bold; color: #1e40af; font-size: 8px; text-transform: uppercase; padding: 2px 4px;">Resin & Hardener (NOS)</td>
                        </tr>
                        @foreach($epoxyPrepGrouped['bottles'] as $name => $qty)
                            <tr>
                                <td style="padding-left: 12px;">{{ $name }}</td>
                                <td>Bottle</td>
                                <td class="text-right font-mono font-bold">{{ number_format($qty) }} Nos</td>
                            </tr>
                        @endforeach
                    @endif

                    @if(count($epoxyPrepGrouped['fillers']) > 0)
                        <tr>
                            <td colspan="3" style="background-color: #f8fafc; font-weight: bold; color: #065f46; font-size: 8px; text-transform: uppercase; padding: 2px 4px;">Filler Color Pouches (PKT)</td>
                        </tr>
                        @foreach($epoxyPrepGrouped['fillers'] as $colorName => $qty)
                            <tr>
                                <td style="padding-left: 12px;">700gm {{ $colorName }} Filler Pouch</td>
                                <td>Pouch</td>
                                <td class="text-right font-mono font-bold">{{ number_format($qty) }} Pkt</td>
                            </tr>
                        @endforeach
                    @endif

                    @if(count($epoxyPrepGrouped['sparkles']) > 0)
                        <tr>
                            <td colspan="3" style="background-color: #f8fafc; font-weight: bold; color: #92400e; font-size: 8px; text-transform: uppercase; padding: 2px 4px;">Jari Powder / Sparkles (PKT)</td>
                        </tr>
                        @foreach($epoxyPrepGrouped['sparkles'] as $name => $qty)
                            <tr>
                                <td style="padding-left: 12px;">{{ $name }}</td>
                                <td>Pouch</td>
                                <td class="text-right font-mono font-bold">{{ number_format($qty) }} Pkt</td>
                            </tr>
                        @endforeach
                    @endif

                    @if(count($epoxyPrepGrouped['cleaners']) > 0)
                        <tr>
                            <td colspan="3" style="background-color: #f8fafc; font-weight: bold; color: #155e75; font-size: 8px; text-transform: uppercase; padding: 2px 4px;">Tiles Cleaner (BOX / NOS)</td>
                        </tr>
                        @foreach($epoxyPrepGrouped['cleaners'] as $name => $qty)
                            <tr>
                                <td style="padding-left: 12px;">{{ $name }}</td>
                                <td>Cleaner</td>
                                <td class="text-right font-mono font-bold">{{ number_format($qty) }} Nos</td>
                            </tr>
                        @endforeach
                    @endif

                    @if(count($epoxyPrepGrouped['others']) > 0)
                        <tr>
                            <td colspan="3" style="background-color: #f8fafc; font-weight: bold; color: #374151; font-size: 8px; text-transform: uppercase; padding: 2px 4px;">SBR, SK+ & Others (NOS)</td>
                        </tr>
                        @foreach($epoxyPrepGrouped['others'] as $name => $qty)
                            <tr>
                                <td style="padding-left: 12px;">{{ $name }}</td>
                                <td>Other</td>
                                <td class="text-right font-mono font-bold">{{ number_format($qty) }} Nos</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        @endif
    @endif


    <div class="footer">
        Solcon Industries | Generated: {{ now()->format('d M Y, h:i:s A') }}
    </div>

</body>
</html>
