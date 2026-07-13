<div class="space-y-4 sm:space-y-5 text-left w-full min-w-0 max-w-full">
    <!-- Header panel with date and PDF exports -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 border-b border-slate-200 pb-3">
        <div>
            <h4 class="text-sm sm:text-base font-bold text-slate-800 flex items-center gap-1.5">
                <i data-lucide="calendar" class="w-4 h-4 text-indigo-650 shrink-0"></i>
                <span class="truncate">Production Breakdown: {{ \Carbon\Carbon::parse($details['date'])->format('d M, Y') }}</span>
            </h4>
            <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5">Aggregates and batch runs recorded on this day.</p>
        </div>

        @if($details['total_batches'] > 0)
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.reports.daily.pdf', ['date' => $details['date']]) }}"
                class="inline-flex items-center px-2.5 py-1.5 bg-slate-100 hover:bg-slate-250 text-slate-700 font-semibold border border-slate-300 rounded-xl text-xs transition-colors gap-1 shadow-sm">
                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                <span>Export PDF</span>
            </a>
            <a href="{{ route('admin.reports.daily.pdf.whatsapp', ['date' => $details['date']]) }}"
                class="inline-flex items-center px-2.5 py-1.5 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-700 font-semibold border border-emerald-500/20 rounded-xl text-xs transition-colors gap-1 shadow-sm">
                <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                <span>WhatsApp PDF</span>
            </a>
        </div>
        @endif
    </div>

    @if($details['total_batches'] > 0)
    <!-- Summary metrics -->
    <div class="grid grid-cols-3 gap-2 sm:gap-4 w-full">
        <div class="p-2.5 sm:p-4 bg-slate-50 border border-slate-200 rounded-xl min-w-0">
            <span class="text-[8px] sm:text-[10px] font-semibold text-slate-500 uppercase tracking-wider block truncate">Batches</span>
            <span class="text-sm sm:text-xl font-bold text-slate-800 font-mono block truncate">{{ number_format($details['total_batches']) }}</span>
        </div>
        <div class="p-2.5 sm:p-4 bg-slate-50 border border-slate-200 rounded-xl min-w-0">
            <span class="text-[8px] sm:text-[10px] font-semibold text-slate-500 uppercase tracking-wider block truncate">Bags</span>
            <span class="text-sm sm:text-xl font-bold text-slate-800 font-mono block truncate">{{ number_format($details['total_bags']) }}</span>
        </div>
        <div class="p-2.5 sm:p-4 bg-slate-50 border border-slate-200 rounded-xl min-w-0">
            <span class="text-[8px] sm:text-[10px] font-semibold text-slate-500 uppercase tracking-wider block truncate">Output (KG)</span>
            <span class="text-sm sm:text-xl font-bold text-slate-800 font-mono block truncate">{{ number_format($details['total_kg'], 1) }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Machine-wise breakdown -->
        <div class="space-y-2">
            <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Mixer / Machine Summary</h5>
            <div class="border border-slate-200 rounded-xl overflow-x-auto text-xs sm:text-sm shadow-inner">
                <table class="w-full text-left border-collapse min-w-[340px]">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-500 font-semibold text-[11px] sm:text-xs">
                            <th class="p-2 sm:p-2.5">Mixer</th>
                            <th class="p-2 sm:p-2.5 text-center">Batches</th>
                            <th class="p-2 sm:p-2.5 text-right">Bags</th>
                            <th class="p-2 sm:p-2.5 text-right">KG Output</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($details['machineWise'] as $summary)
                        <tr>
                            <td class="p-2 sm:p-2.5 font-semibold text-slate-800">{{ $summary['machine']->name }}</td>
                            <td class="p-2 sm:p-2.5 text-center font-mono font-bold text-indigo-600">{{ $summary['batches'] }}</td>
                            <td class="p-2 sm:p-2.5 text-right font-mono">{{ number_format($summary['bags'], 0) }}</td>
                            <td class="p-2 sm:p-2.5 text-right font-mono font-bold text-emerald-600">{{ number_format($summary['kg'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Supervisor-wise breakdown -->
        <div class="space-y-2">
            <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Supervisor Summary</h5>
            <div class="border border-slate-200 rounded-xl overflow-x-auto text-xs sm:text-sm shadow-inner">
                <table class="w-full text-left border-collapse min-w-[340px]">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-500 font-semibold text-[11px] sm:text-xs">
                            <th class="p-2 sm:p-2.5">Supervisor</th>
                            <th class="p-2 sm:p-2.5 text-center">Batches</th>
                            <th class="p-2 sm:p-2.5 text-right">Bags</th>
                            <th class="p-2 sm:p-2.5 text-right">KG Output</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($details['supervisorWise'] as $summary)
                        <tr>
                            <td class="p-2 sm:p-2.5 font-semibold text-slate-800">{{ $summary['supervisor']->name ?? 'N/A' }}</td>
                            <td class="p-2 sm:p-2.5 text-center font-mono font-bold text-indigo-600">{{ $summary['batches'] }}</td>
                            <td class="p-2 sm:p-2.5 text-right font-mono">{{ number_format($summary['bags'], 0) }}</td>
                            <td class="p-2 sm:p-2.5 text-right font-mono font-bold text-emerald-600">{{ number_format($summary['kg'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Completed Batches detailed list -->
    <div class="space-y-2">
        <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Batches Detailed Log</h5>
        <div class="border border-slate-200 rounded-xl overflow-x-auto text-xs sm:text-sm shadow-inner">
            <table class="w-full text-left border-collapse min-w-[540px]">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-500 font-semibold text-[11px] sm:text-xs">
                        <th class="p-2 sm:p-2.5">Batch Number</th>
                        <th class="p-2 sm:p-2.5">Mixer</th>
                        <th class="p-2 sm:p-2.5">Product</th>
                        <th class="p-2 sm:p-2.5">Supervisor / Operator</th>
                        <th class="p-2 sm:p-2.5 text-right">Output</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach($details['batches'] as $batch)
                    <tr>
                        <td class="p-2 sm:p-2.5 font-mono font-bold text-indigo-600 whitespace-nowrap">
                            <a href="{{ $batch['url'] }}" class="hover:underline" target="_blank">
                                {{ $batch['batch_no'] }}
                            </a>
                            <span class="inline-flex items-center rounded-md px-1 py-0.2 text-[9px] font-bold ring-1 ring-inset {{ $batch['type'] === 'Adhesive' ? 'bg-blue-50 text-blue-700 ring-blue-700/10' : 'bg-cyan-50 text-cyan-700 ring-cyan-700/10' }} ml-1">
                                {{ $batch['type'] }}
                            </span>
                        </td>
                        <td class="p-2 sm:p-2.5 whitespace-nowrap">{{ $batch['machine_name'] }}</td>
                        <td class="p-2 sm:p-2.5 font-semibold whitespace-nowrap">{{ $batch['product_name'] }}</td>
                        <td class="p-2 sm:p-2.5 text-slate-500 font-semibold whitespace-nowrap">{{ $batch['supervisor_name'] }}</td>
                        <td class="p-2 sm:p-2.5 text-right font-mono font-bold text-emerald-600 whitespace-nowrap">
                            {{ number_format($batch['output_bags']) }} Bags ({{ number_format($batch['output_kg'], 1) }} KG)
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <!-- No production on this day -->
    <div class="py-10 text-center text-slate-500 border border-dashed border-slate-200 rounded-2xl bg-slate-50">
        <i data-lucide="package-x" class="w-8 h-8 text-slate-350 mx-auto mb-2"></i>
        <h5 class="text-sm font-bold text-slate-700">No Production Summary</h5>
        <p class="text-xs text-slate-450 mt-0.5">No completed production batches recorded on this date.</p>
    </div>
    @endif
</div>
