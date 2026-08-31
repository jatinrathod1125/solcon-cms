@extends('layouts.app')

@section('title', 'Production Reports')
@section('header-title', 'Production Reports')

@section('content')
<div class="space-y-6 mx-auto max-w-[1600px]">

    <!-- Sleek Filter & Export Toolbar -->
    <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm space-y-4">
        <form id="reportFilterForm" method="GET" action="{{ route('admin.reports.daily') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 text-xs">
            
            <!-- Range Preset -->
            <div>
                <label class="block text-slate-500 mb-1 uppercase font-bold tracking-wider text-[9px]">Date Range Filter</label>
                <select name="range_preset" id="rangePreset" onchange="toggleCustomDates(this.value)" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <option value="today" {{ $rangePreset === 'today' ? 'selected' : '' }}>Today</option>
                    <option value="yesterday" {{ $rangePreset === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                    <option value="last_7_days" {{ $rangePreset === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="last_30_days" {{ $rangePreset === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="current_month" {{ $rangePreset === 'current_month' ? 'selected' : '' }}>Current Month</option>
                    <option value="previous_month" {{ $rangePreset === 'previous_month' ? 'selected' : '' }}>Previous Month</option>
                    <option value="custom" {{ $rangePreset === 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                </select>
            </div>

            <!-- Custom Start Date -->
            <div id="startDateGroup" class="{{ $rangePreset === 'custom' ? '' : 'hidden' }}">
                <label class="block text-slate-500 mb-1 uppercase font-bold tracking-wider text-[9px]">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/50">
            </div>

            <!-- Custom End Date -->
            <div id="endDateGroup" class="{{ $rangePreset === 'custom' ? '' : 'hidden' }}">
                <label class="block text-slate-500 mb-1 uppercase font-bold tracking-wider text-[9px]">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/50">
            </div>

            <!-- Department Filter -->
            <div>
                <label class="block text-slate-500 mb-1 uppercase font-bold tracking-wider text-[9px]">Department Filter</label>
                <select name="department_code" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <option value="all" {{ $departmentCode === 'all' ? 'selected' : '' }}>All Departments</option>
                    <option value="TAD" {{ $departmentCode === 'TAD' ? 'selected' : '' }}>Tile Adhesive</option>
                    <option value="GRT" {{ $departmentCode === 'GRT' ? 'selected' : '' }}>Grout</option>
                    <option value="EPX" {{ $departmentCode === 'EPX' ? 'selected' : '' }}>Epoxy</option>
                    <option value="DSP" {{ in_array($departmentCode, ['DSP', 'DISPATCH']) ? 'selected' : '' }}>Dispatch</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-1">
                <button type="submit" class="erp-button bg-blue-600 text-white hover:bg-blue-500 flex-1 justify-center">
                    <i data-lucide="filter" class="w-4 h-4"></i>Apply Filter
                </button>
            </div>
        </form>

        <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs">
            <div class="text-slate-500 font-semibold">
                Report Period: <span class="font-bold text-slate-800">{{ $startDate }}</span> {{ $isMultiDay ? 'to ' . $endDate : '' }}
                @if($departmentCode !== 'all')
                    • <span class="badge bg-blue-50 text-blue-700 font-bold px-2 py-0.5 rounded-md border border-blue-200">Dept: {{ $departmentCode }}</span>
                @endif
            </div>
            
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.reports.daily.pdf', request()->query()) }}" class="erp-button border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">
                    <i data-lucide="file-text" class="w-4 h-4 text-rose-500"></i>Export PDF
                </a>
                <a href="{{ route('admin.reports.daily.pdf.whatsapp', request()->query()) }}" class="erp-button border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">
                    <i data-lucide="share-2" class="w-4 h-4 text-emerald-500"></i>WhatsApp PDF
                </a>
                <a href="{{ route('admin.reports.daily.excel', request()->query()) }}" class="erp-button bg-slate-900 text-white hover:bg-slate-800">
                    <i data-lucide="download-cloud" class="w-4 h-4 text-emerald-400"></i>Export Excel
                </a>
            </div>
        </div>
    </div>

    <!-- ==================== TILE ADHESIVE PRODUCTION REPORT ==================== -->
    @if($showAdhesive)
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="layers" class="w-5 h-5 text-blue-600"></i>
                        <span>Tile Adhesive Production Report</span>
                    </h3>
                </div>
                <div class="text-xs font-bold text-slate-500">
                    Total Machines: {{ $totalMachinesUsed }} | Batches: {{ number_format($grandTotal->total_batches) }} | Bags: {{ number_format($grandTotal->total_bags) }}
                </div>
            </div>

            <!-- MACHINE WISE BATCH DETAILS & GRADE TOTALS -->
            @forelse($adhMachineDetails as $m)
                <div class="border border-slate-200 rounded-xl overflow-hidden space-y-0">
                    <div class="bg-slate-100 px-4 py-2.5 font-bold text-xs text-slate-800 flex items-center justify-between border-b border-slate-200">
                        <span class="flex items-center gap-2">
                            <i data-lucide="cpu" class="w-4 h-4 text-blue-600"></i>
                            <span>MACHINE: {{ strtoupper($m['machine_name']) }}</span>
                        </span>
                        <span class="font-mono text-slate-600">
                            {{ $m['total_batches'] }} Batches • {{ number_format($m['total_bags']) }} Bags • {{ number_format($m['total_kg'], 2) }} KG
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-450 uppercase font-extrabold text-[9px] tracking-wider border-b border-slate-100">
                                    <th class="px-4 py-2.5">Batch No</th>
                                    <th class="px-4 py-2.5">Grade</th>
                                    <th class="px-4 py-2.5 text-right">Quantity (Bags)</th>
                                    <th class="px-4 py-2.5 text-center">Start Time</th>
                                    <th class="px-4 py-2.5 text-center">End Time</th>
                                    <th class="px-4 py-2.5">Supervisor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-750">
                                @foreach($m['batches'] as $b)
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="px-4 py-2 font-mono font-bold text-slate-900">{{ $b->batch_no }}</td>
                                        <td class="px-4 py-2 font-bold text-blue-700">{{ $b->grade ? $b->grade->name : '-' }}</td>
                                        <td class="px-4 py-2 text-right font-mono font-bold text-slate-900">{{ number_format($b->output_bags) }} Bags</td>
                                        <td class="px-4 py-2 text-center font-mono text-slate-500">{{ $b->start_time ? $b->start_time->format('h:i A') : '-' }}</td>
                                        <td class="px-4 py-2 text-center font-mono text-slate-500">{{ $b->end_time ? $b->end_time->format('h:i A') : '-' }}</td>
                                        <td class="px-4 py-2 text-slate-600">{{ $b->supervisor ? $b->supervisor->name : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Grade Wise Total for this Machine -->
                    <div class="bg-slate-50 px-4 py-2.5 border-t border-slate-200 text-xs flex flex-wrap items-center gap-2">
                        <span class="font-bold text-slate-600">Grade Wise Total ({{ $m['machine_name'] }}):</span>
                        @foreach($m['grade_totals'] as $gName => $gBags)
                            <span class="bg-white border border-slate-200 px-2 py-0.5 rounded-md font-bold text-slate-800">
                                {{ $gName }}: {{ number_format($gBags) }} Bags
                            </span>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-slate-400 font-semibold text-xs">
                    No tile adhesive batches found for the selected filter period.
                </div>
            @endforelse

            <!-- OVERALL GRADE WISE TOTALS ACROSS ALL MACHINES -->
            @if(count($adhOverallGradeTotals) > 0)
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <div class="bg-slate-900 text-white px-4 py-2.5 font-bold text-xs uppercase tracking-wider">
                        Overall Grade Wise Totals (All Machines)
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3 p-4 bg-slate-50">
                        @foreach($adhOverallGradeTotals as $gName => $gBags)
                            <div class="bg-white border border-slate-200 rounded-xl p-3 text-center">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase">{{ $gName }}</span>
                                <span class="block text-base font-extrabold text-slate-900 font-mono mt-0.5">{{ number_format($gBags) }} Bags</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- MACHINE WISE GRAND TOTALS SUMMARY -->
            @if(count($adhMachineGrandTotals) > 0)
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <div class="bg-slate-100 px-4 py-2.5 font-bold text-xs text-slate-800 border-b border-slate-200 uppercase tracking-wider">
                        Machine Wise Grand Totals
                    </div>
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-450 uppercase font-extrabold text-[9px] border-b border-slate-100">
                                <th class="px-4 py-2.5">Machine Name</th>
                                <th class="px-4 py-2.5 text-center">Total Batches</th>
                                <th class="px-4 py-2.5 text-right">Total Bags</th>
                                <th class="px-4 py-2.5 text-right">Total Weight (KG)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-750">
                            @foreach($adhMachineGrandTotals as $mName => $mTot)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-2 font-bold text-slate-900">{{ $mName }}</td>
                                    <td class="px-4 py-2 text-center font-mono font-bold text-slate-700">{{ $mTot['batches'] }}</td>
                                    <td class="px-4 py-2 text-right font-mono font-bold text-blue-700">{{ number_format($mTot['bags']) }} Bags</td>
                                    <td class="px-4 py-2 text-right font-mono font-bold text-slate-900">{{ number_format($mTot['kg'], 2) }} KG</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- FINAL SUMMARY BOX -->
            <div class="bg-slate-900 text-white rounded-xl p-5 shadow-md">
                <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2 mb-4">
                    Tile Adhesive Final Summary
                </h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                    <div>
                        <span class="block text-[10px] text-slate-400 font-semibold uppercase">Total Machines Used</span>
                        <span class="block text-xl font-black font-mono text-white mt-1">{{ $totalMachinesUsed }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-slate-400 font-semibold uppercase">Total Batches Run</span>
                        <span class="block text-xl font-black font-mono text-cyan-400 mt-1">{{ number_format($grandTotal->total_batches) }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-slate-400 font-semibold uppercase">Total Bags Produced</span>
                        <span class="block text-xl font-black font-mono text-emerald-400 mt-1">{{ number_format($grandTotal->total_bags) }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-slate-400 font-semibold uppercase">Total Weight Output</span>
                        <span class="block text-xl font-black font-mono text-indigo-400 mt-1">{{ number_format($grandTotal->total_kg, 2) }} KG</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ==================== GROUT DEPARTMENT REPORT ==================== -->
    @if($showGrout && count($groutCompletedBatches) > 0)
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="text-base font-extrabold text-emerald-700 uppercase tracking-wide flex items-center gap-2">
                <i data-lucide="archive" class="w-5 h-5 text-emerald-600"></i>
                <span>Grout Production Report</span>
            </h3>
            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-450 uppercase font-extrabold text-[9px] border-b border-slate-100">
                            <th class="px-4 py-2.5">Batch No</th>
                            <th class="px-4 py-2.5">Color</th>
                            <th class="px-4 py-2.5 text-right">Bags Output</th>
                            <th class="px-4 py-2.5 text-right">Total Weight (KG)</th>
                            <th class="px-4 py-2.5">Operator</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-750">
                        @foreach($groutCompletedBatches as $gb)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-4 py-2 font-mono font-bold text-slate-900">{{ $gb->batch_no }}</td>
                                <td class="px-4 py-2 font-bold text-emerald-700">{{ $gb->color ? $gb->color->name : '-' }}</td>
                                <td class="px-4 py-2 text-right font-mono font-bold text-slate-900">{{ number_format($gb->finished_bags) }} Bags</td>
                                <td class="px-4 py-2 text-right font-mono text-slate-700">{{ number_format($gb->total_weight_kg, 2) }} KG</td>
                                <td class="px-4 py-2 text-slate-600">{{ $gb->operator ? $gb->operator->name : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- ==================== EPOXY DEPARTMENT REPORT ==================== -->
    @if($showEpoxy && (count($epoxyCompletedAssemblies) > 0 || count($epoxyPreparations) > 0))
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
            <h3 class="text-base font-extrabold text-purple-700 uppercase tracking-wide flex items-center gap-2">
                <i data-lucide="package" class="w-5 h-5 text-purple-600"></i>
                <span>Epoxy Production & Component Summary</span>
            </h3>

            @if(count($epoxyCompletedAssemblies) > 0)
                <div>
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">1. Finished Kit/Bucket Assemblies</h4>
                    <div class="overflow-x-auto border border-slate-200 rounded-xl">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-450 uppercase font-extrabold text-[9px] border-b border-slate-100">
                                    <th class="px-4 py-2.5">Product</th>
                                    <th class="px-4 py-2.5">Color</th>
                                    <th class="px-4 py-2.5 text-right">Kits Assembled</th>
                                    <th class="px-4 py-2.5">Operator</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-750">
                                @foreach($epoxyCompletedAssemblies as $ea)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-2 font-bold text-slate-900">{{ $ea->product ? $ea->product->name : '-' }}</td>
                                        <td class="px-4 py-2 text-purple-700 font-semibold">{{ $ea->color ? $ea->color->name : '-' }}</td>
                                        <td class="px-4 py-2 text-right font-mono font-bold text-slate-900">{{ number_format($ea->quantity) }} Kits</td>
                                        <td class="px-4 py-2 text-slate-600">{{ $ea->operator ? $ea->operator->name : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(count($epoxyPreparations) > 0)
                <div>
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">2. Floor Component Preparations</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <!-- Resin & Hardener Bottles -->
                        <div class="border border-slate-100 rounded-xl p-4 bg-slate-50/50 space-y-2">
                            <h5 class="text-xs font-extrabold text-blue-800 uppercase tracking-wide border-b border-slate-200/60 pb-1.5 flex justify-between">
                                <span>Resin & Hardener</span>
                                <span class="text-[10px] text-slate-400 font-normal">NOS</span>
                            </h5>
                            @if(count($epoxyPrepGrouped['bottles']) > 0)
                                <div class="space-y-1 text-xs">
                                    @foreach($epoxyPrepGrouped['bottles'] as $name => $qty)
                                        <div class="flex justify-between font-semibold">
                                            <span class="text-slate-650">{{ $name }}</span>
                                            <span class="font-mono text-slate-900">{{ number_format($qty) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-xs text-slate-400 italic">No bottles prepared</div>
                            @endif
                        </div>

                        <!-- Filler Color Pouches -->
                        <div class="border border-slate-100 rounded-xl p-4 bg-slate-50/50 space-y-2">
                            <h5 class="text-xs font-extrabold text-emerald-800 uppercase tracking-wide border-b border-slate-200/60 pb-1.5 flex justify-between">
                                <span>Filler Color</span>
                                <span class="text-[10px] text-slate-400 font-normal">PKT</span>
                            </h5>
                            @if(count($epoxyPrepGrouped['fillers']) > 0)
                                <div class="space-y-1 text-xs">
                                    @foreach($epoxyPrepGrouped['fillers'] as $colorName => $qty)
                                        <div class="flex justify-between font-semibold">
                                            <span class="text-slate-650">{{ $colorName }}</span>
                                            <span class="font-mono text-slate-900">{{ number_format($qty) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-xs text-slate-400 italic">No filler pouches prepared</div>
                            @endif
                        </div>

                        <!-- Jari / Sparkle Powder -->
                        <div class="border border-slate-100 rounded-xl p-4 bg-slate-50/50 space-y-2">
                            <h5 class="text-xs font-extrabold text-amber-800 uppercase tracking-wide border-b border-slate-200/60 pb-1.5 flex justify-between">
                                <span>Jari Powder (Sparkle)</span>
                                <span class="text-[10px] text-slate-400 font-normal">PKT</span>
                            </h5>
                            @if(count($epoxyPrepGrouped['sparkles']) > 0)
                                <div class="space-y-1 text-xs">
                                    @foreach($epoxyPrepGrouped['sparkles'] as $name => $qty)
                                        <div class="flex justify-between font-semibold">
                                            <span class="text-slate-650">{{ $name }}</span>
                                            <span class="font-mono text-slate-900">{{ number_format($qty) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-xs text-slate-400 italic">No jari powder prepared</div>
                            @endif
                        </div>

                        <!-- Tiles Cleaner -->
                        <div class="border border-slate-100 rounded-xl p-4 bg-slate-50/50 space-y-2">
                            <h5 class="text-xs font-extrabold text-cyan-800 uppercase tracking-wide border-b border-slate-200/60 pb-1.5 flex justify-between">
                                <span>Tiles Cleaner</span>
                                <span class="text-[10px] text-slate-400 font-normal">BOX / NOS</span>
                            </h5>
                            @if(count($epoxyPrepGrouped['cleaners']) > 0)
                                <div class="space-y-1 text-xs">
                                    @foreach($epoxyPrepGrouped['cleaners'] as $name => $qty)
                                        <div class="flex justify-between font-semibold">
                                            <span class="text-slate-650">{{ $name }}</span>
                                            <span class="font-mono text-slate-900">{{ number_format($qty) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-xs text-slate-400 italic">No cleaners prepared</div>
                            @endif
                        </div>

                        <!-- Others (SBR, SK+, etc.) -->
                        <div class="border border-slate-100 rounded-xl p-4 bg-slate-50/50 space-y-2 col-span-1 md:col-span-2 lg:col-span-1">
                            <h5 class="text-xs font-extrabold text-slate-800 uppercase tracking-wide border-b border-slate-200/60 pb-1.5 flex justify-between">
                                <span>SBR, SK+ & Others</span>
                                <span class="text-[10px] text-slate-400 font-normal">NOS</span>
                            </h5>
                            @if(count($epoxyPrepGrouped['others']) > 0)
                                <div class="space-y-1 text-xs">
                                    @foreach($epoxyPrepGrouped['others'] as $name => $qty)
                                        <div class="flex justify-between font-semibold">
                                            <span class="text-slate-650">{{ $name }}</span>
                                            <span class="font-mono text-slate-900">{{ number_format($qty) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-xs text-slate-400 italic">No other components prepared</div>
                            @endif
                        </div>

                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- ==================== 4. MATERIAL INWARD & STOCK RECEIPTS ==================== -->
    @if($showInward)
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-2">
            <div>
                <h3 class="text-base font-extrabold text-emerald-700 uppercase tracking-wide flex items-center gap-2">
                    <i data-lucide="arrow-down-left" class="w-5 h-5 text-emerald-600"></i>
                    <span>Material Inward & Stock Receipts (Stock IN)</span>
                </h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Raw materials & packing materials inwarded or added via positive stock adjustments.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-black">
                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600"></i>
                    {{ $inwardEntriesLog->count() }} Inward Entries
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- 4.1 Raw Material Inward Table -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center justify-between">
                    <span>4.1 Raw Material Receipts</span>
                    <span class="text-[11px] text-slate-400 font-semibold">{{ $rawMaterialInwardSummary->count() }} Items</span>
                </h4>
                <div class="overflow-x-auto border border-slate-200 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 uppercase font-extrabold text-[9px] border-b border-slate-100">
                                <th class="px-3.5 py-2.5">Material</th>
                                <th class="px-3.5 py-2.5">Code</th>
                                <th class="px-3.5 py-2.5 text-center">Entries</th>
                                <th class="px-3.5 py-2.5 text-right">Inward Qty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($rawMaterialInwardSummary as $rmIn)
                                @php $rm = $rmIn->rawMaterial; @endphp
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-3.5 py-2 font-bold text-slate-900">
                                        <div class="flex items-center gap-1.5">
                                            <span>{{ $rm->name }}</span>
                                            @if($rm->department)
                                                <span class="text-[9px] font-bold px-1.5 py-0.5 bg-slate-100 text-slate-600 rounded">{{ $rm->department->code }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3.5 py-2 font-mono text-slate-500 text-[11px]">{{ $rm->code }}</td>
                                    <td class="px-3.5 py-2 text-center font-semibold text-slate-600">{{ $rmIn->entry_count }}</td>
                                    <td class="px-3.5 py-2 text-right font-mono font-black text-emerald-700">
                                        +{{ number_format($rmIn->total_inward, 2) }} {{ $rm->stockUnit?->code ?? 'KG' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-xs text-slate-400 font-semibold italic">No raw material inward entries in this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4.2 Packing Material Inward Table -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center justify-between">
                    <span>4.2 Packing Material Receipts</span>
                    <span class="text-[11px] text-slate-400 font-semibold">{{ $packingMaterialInwardSummary->count() }} Items</span>
                </h4>
                <div class="overflow-x-auto border border-slate-200 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 uppercase font-extrabold text-[9px] border-b border-slate-100">
                                <th class="px-3.5 py-2.5">Material</th>
                                <th class="px-3.5 py-2.5">Code</th>
                                <th class="px-3.5 py-2.5 text-center">Entries</th>
                                <th class="px-3.5 py-2.5 text-right">Inward Qty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($packingMaterialInwardSummary as $pmIn)
                                @php $pm = $pmIn->packingMaterial; @endphp
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-3.5 py-2 font-bold text-slate-900">
                                        <div class="flex items-center gap-1.5">
                                            <span>{{ $pm->name }}</span>
                                            @if($pm->category)
                                                <span class="text-[9px] font-bold px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded">{{ $pm->category->name }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3.5 py-2 font-mono text-slate-500 text-[11px]">{{ $pm->code }}</td>
                                    <td class="px-3.5 py-2 text-center font-semibold text-slate-600">{{ $pmIn->entry_count }}</td>
                                    <td class="px-3.5 py-2 text-right font-mono font-black text-blue-700">
                                        +{{ number_format($pmIn->total_inward) }} PCS
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-xs text-slate-400 font-semibold italic">No packing material inward entries in this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 4.3 Inward Detailed Transactions Log -->
        @if($inwardEntriesLog->isNotEmpty())
            <div class="pt-3 border-t border-slate-100 space-y-2">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center justify-between">
                    <span>4.3 Inward Transactions Detailed Log</span>
                    <span class="text-[11px] text-slate-500 font-medium">Latest entries first</span>
                </h4>
                <div class="overflow-x-auto border border-slate-200 rounded-xl max-h-80 overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="sticky top-0 bg-slate-50 text-slate-500 uppercase font-extrabold text-[9px] border-b border-slate-200 z-10">
                            <tr>
                                <th class="px-3.5 py-2.5">Date & Time</th>
                                <th class="px-3.5 py-2.5">Type</th>
                                <th class="px-3.5 py-2.5">Material Name</th>
                                <th class="px-3.5 py-2.5 text-right">Quantity In</th>
                                <th class="px-3.5 py-2.5">Remarks / Source</th>
                                <th class="px-3.5 py-2.5">Created By</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @foreach($inwardEntriesLog as $entry)
                                @php
                                    $isRM = !is_null($entry->raw_material_id) && $entry->rawMaterial;
                                    $matName = $isRM ? $entry->rawMaterial->name : ($entry->packingMaterial?->name ?? 'Unknown');
                                    $matCode = $isRM ? $entry->rawMaterial->code : ($entry->packingMaterial?->code ?? '-');
                                    $unit = $isRM ? ($entry->rawMaterial->stockUnit?->code ?? 'KG') : 'PCS';
                                @endphp
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-3.5 py-2 font-mono text-[11px] text-slate-600 whitespace-nowrap">{{ $entry->created_at->format('d M Y, h:i A') }}</td>
                                    <td class="px-3.5 py-2 whitespace-nowrap">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold {{ $isRM ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                            {{ $isRM ? 'Raw Material' : 'Packing Material' }}
                                        </span>
                                    </td>
                                    <td class="px-3.5 py-2 font-bold text-slate-900">
                                        {{ $matName }} <span class="font-mono text-slate-400 text-[10px]">({{ $matCode }})</span>
                                    </td>
                                    <td class="px-3.5 py-2 text-right font-mono font-black text-emerald-700 whitespace-nowrap">
                                        +{{ number_format($entry->quantity, 2) }} {{ $unit }}
                                    </td>
                                    <td class="px-3.5 py-2 text-slate-500 text-[11px]">{{ $entry->remarks ?: 'Stock Inward / Adjustment' }}</td>
                                    <td class="px-3.5 py-2 text-slate-600 font-semibold">{{ $entry->creator?->name ?? 'Admin / System' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
    @endif

    <!-- ==================== 5. MATERIAL CONSUMPTION (STOCK OUT) ==================== -->
    @if($showConsumption && (count($materialSummary) > 0 || count($packingMaterialConsumptionSummary) > 0))
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-2">
                <div>
                    <h3 class="text-base font-extrabold text-amber-800 uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="arrow-up-right" class="w-5 h-5 text-amber-600"></i>
                        <span>Material Consumption (Stock OUT)</span>
                    </h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Raw materials & packing materials consumed for completed production and packaging.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-xl text-xs font-black">
                        <i data-lucide="package-minus" class="w-3.5 h-3.5 text-amber-600"></i>
                        {{ count($materialSummary) + count($packingMaterialConsumptionSummary) }} Consumed Materials
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- 5.1 Raw Material Consumption Table -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center justify-between">
                        <span>5.1 Raw Material Consumption</span>
                        <span class="text-[11px] text-amber-700 font-bold">Total: {{ number_format($totalConsumptionWeight, 2) }} KG</span>
                    </h4>
                    <div class="overflow-x-auto border border-slate-200 rounded-xl">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 uppercase font-extrabold text-[9px] border-b border-slate-100">
                                    <th class="px-3.5 py-2.5">Material Name</th>
                                    <th class="px-3.5 py-2.5">Code</th>
                                    <th class="px-3.5 py-2.5">Dept</th>
                                    <th class="px-3.5 py-2.5 text-right">Consumed Qty</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @forelse($materialSummary as $mat)
                                    @if($mat->rawMaterial)
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-3.5 py-2 font-bold text-slate-900">{{ $mat->rawMaterial->name }}</td>
                                            <td class="px-3.5 py-2 font-mono text-slate-500 text-[11px]">{{ $mat->rawMaterial->code }}</td>
                                            <td class="px-3.5 py-2">
                                                @if($mat->rawMaterial->department)
                                                    <span class="text-[9px] font-bold px-1.5 py-0.5 bg-slate-100 text-slate-600 rounded">{{ $mat->rawMaterial->department->code }}</span>
                                                @else
                                                    <span class="text-slate-400 text-xs">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3.5 py-2 text-right font-mono font-black text-rose-700">
                                                {{ number_format($mat->total_consumed, 2) }} {{ $mat->rawMaterial->stockUnit?->code ?? $mat->rawMaterial->unit?->code ?? 'KG' }}
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-xs text-slate-400 font-semibold italic">No raw material consumed in this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 5.2 Packing Material Consumption Table -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center justify-between">
                        <span>5.2 Packing Material Consumption</span>
                        <span class="text-[11px] text-indigo-700 font-bold">Total: {{ number_format($totalConsumptionPackingQty) }} PCS</span>
                    </h4>
                    <div class="overflow-x-auto border border-slate-200 rounded-xl">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 uppercase font-extrabold text-[9px] border-b border-slate-100">
                                    <th class="px-3.5 py-2.5">Packing Material</th>
                                    <th class="px-3.5 py-2.5">Code</th>
                                    <th class="px-3.5 py-2.5">Category</th>
                                    <th class="px-3.5 py-2.5 text-right">Consumed Qty</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @forelse($packingMaterialConsumptionSummary as $pmOut)
                                    @if($pmOut->packingMaterial)
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-3.5 py-2 font-bold text-slate-900">{{ $pmOut->packingMaterial->name }}</td>
                                            <td class="px-3.5 py-2 font-mono text-slate-500 text-[11px]">{{ $pmOut->packingMaterial->code }}</td>
                                            <td class="px-3.5 py-2">
                                                @if($pmOut->packingMaterial->category)
                                                    <span class="text-[9px] font-bold px-1.5 py-0.5 bg-indigo-50 text-indigo-700 rounded">{{ $pmOut->packingMaterial->category->name }}</span>
                                                @else
                                                    <span class="text-slate-400 text-xs">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3.5 py-2 text-right font-mono font-black text-rose-700">
                                                {{ number_format($pmOut->total_consumed) }} PCS
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-xs text-slate-400 font-semibold italic">No packing material consumed in this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ==================== 6. DISPATCH & OUTWARD LOGISTICS SUMMARY ==================== -->
    @if($showDispatch)
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-2">
            <div>
                <h3 class="text-base font-extrabold text-blue-700 uppercase tracking-wide flex items-center gap-2">
                    <i data-lucide="truck" class="w-5 h-5 text-blue-600"></i>
                    <span>Dispatch & Outward Logistics Summary</span>
                </h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Finished goods loaded and dispatched during the selected period.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-800 border border-blue-200 rounded-xl text-xs font-black">
                    <i data-lucide="package-check" class="w-3.5 h-3.5 text-blue-600"></i>
                    {{ $totalDispatchesCount }} Completed Dispatches
                </span>
            </div>
        </div>

        <!-- Dispatch Metric Badges -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100/40 border border-blue-200/80 rounded-xl p-4">
                <span class="text-[10px] font-extrabold text-blue-700 uppercase tracking-wider block">Completed Dispatches</span>
                <strong class="text-2xl font-black text-slate-900 mt-1 block font-mono">{{ $totalDispatchesCount }} <span class="text-xs text-slate-500 font-bold">Vehicles/Orders</span></strong>
                <span class="text-[11px] font-bold text-blue-800 mt-0.5 block">{{ $dispatchedProductsSummary->count() }} Product Varieties • Factory Pickup & Crossing Deliveries</span>
            </div>
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/40 border border-emerald-200/80 rounded-xl p-4">
                <span class="text-[10px] font-extrabold text-emerald-700 uppercase tracking-wider block">Total Dispatched Weight</span>
                <strong class="text-2xl font-black text-slate-900 mt-1 block font-mono">{{ number_format($totalDispatchedWeightKg, 2) }} <span class="text-xs text-slate-500 font-bold">KG</span></strong>
                <span class="text-[11px] font-bold text-emerald-800 mt-0.5 block">≈ {{ number_format($totalDispatchedWeightKg / 1000, 3) }} Metric Tons</span>
            </div>
        </div>

        @if($dispatches->isNotEmpty())
            <div class="space-y-6">
                <!-- 6.1 Dispatched Products Breakdown -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center justify-between">
                        <span>6.1 Dispatched Products Breakdown</span>
                        <span class="text-[11px] text-slate-400 font-semibold">{{ $dispatchedProductsSummary->count() }} Products</span>
                    </h4>
                    <div class="overflow-x-auto border border-slate-200 rounded-xl">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 uppercase font-extrabold text-[9px] border-b border-slate-100">
                                    <th class="px-4 py-2.5">Product Name</th>
                                    <th class="px-4 py-2.5">Department</th>
                                    <th class="px-4 py-2.5 text-right">Dispatched Quantity</th>
                                    <th class="px-4 py-2.5 text-right">Total Weight (KG)</th>
                                    <th class="px-4 py-2.5 text-right">Total Weight (Tons)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @foreach($dispatchedProductsSummary as $prod)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-2 font-bold text-slate-900">{{ $prod['product_name'] }}</td>
                                        <td class="px-4 py-2 font-semibold text-slate-600">{{ $prod['department'] }}</td>
                                        <td class="px-4 py-2 text-right font-mono font-black text-blue-700">
                                            {{ number_format($prod['total_quantity']) }} {{ $prod['unit'] }}
                                        </td>
                                        <td class="px-4 py-2 text-right font-mono font-bold text-slate-900">{{ number_format($prod['total_weight_kg'], 2) }} KG</td>
                                        <td class="px-4 py-2 text-right font-mono font-black text-emerald-700">{{ number_format($prod['total_weight_kg'] / 1000, 3) }} T</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 6.2 Dispatches Detailed Listing -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center justify-between">
                        <span>6.2 Completed Dispatches List</span>
                        <span class="text-[11px] text-slate-400 font-semibold">{{ $dispatches->count() }} Dispatches</span>
                    </h4>
                    <div class="overflow-x-auto border border-slate-200 rounded-xl">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 uppercase font-extrabold text-[9px] border-b border-slate-100">
                                    <th class="px-4 py-2.5">Dispatch No</th>
                                    <th class="px-4 py-2.5">Date & Time</th>
                                    <th class="px-4 py-2.5">Party Name</th>
                                    <th class="px-4 py-2.5">City</th>
                                    <th class="px-4 py-2.5">Vehicle</th>
                                    <th class="px-4 py-2.5 text-right">Bags / Units</th>
                                    <th class="px-4 py-2.5 text-right">Weight (KG)</th>
                                    <th class="px-4 py-2.5">Products Loaded</th>
                                    <th class="px-4 py-2.5">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @foreach($dispatches as $d)
                                    @php
                                        $dBags = $d->items->sum('quantity_bags');
                                        $dKg = $d->items->sum('calculated_weight_kg');
                                        $dDate = $d->loaded_at ?? $d->created_at;
                                        $itemSummary = $d->items->map(fn($it) => $it->quantity_bags . ' ' . $it->unit_label . ' ' . $it->product_name)->implode(', ');
                                    @endphp
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-2.5 font-mono font-bold text-blue-700 whitespace-nowrap">{{ $d->dispatch_number }}</td>
                                        <td class="px-4 py-2.5 font-mono text-slate-600 whitespace-nowrap text-[11px]">{{ $dDate ? $dDate->format('d M Y, h:i A') : '-' }}</td>
                                        <td class="px-4 py-2.5 font-bold text-slate-900">{{ $d->party_name }}</td>
                                        <td class="px-4 py-2.5 text-slate-600 font-semibold">{{ $d->city ?: 'N/A' }}</td>
                                        <td class="px-4 py-2.5 font-mono text-slate-800">{{ $d->vehicle_number ?: '-' }}</td>
                                        <td class="px-4 py-2.5 text-right font-mono font-black text-indigo-700">{{ number_format($dBags) }}</td>
                                        <td class="px-4 py-2.5 text-right font-mono font-bold text-slate-900">{{ number_format($dKg, 2) }}</td>
                                        <td class="px-4 py-2.5 text-slate-600 text-[11px] max-w-xs truncate" title="{{ $itemSummary }}">{{ $itemSummary }}</td>
                                        <td class="px-4 py-2.5 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                ✅ Completed
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <p class="text-xs text-slate-400 font-semibold italic text-center py-6">No completed dispatches recorded during this date range.</p>
        @endif
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
function toggleCustomDates(val) {
    if (val === 'custom') {
        $('#startDateGroup, #endDateGroup').removeClass('hidden');
    } else {
        $('#startDateGroup, #endDateGroup').addClass('hidden');
    }
}
</script>
@endsection
