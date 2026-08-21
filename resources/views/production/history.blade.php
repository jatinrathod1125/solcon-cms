@extends('layouts.app')

@section('title', 'Production History')
@section('header-title', 'Production History Log')

@section('content')
<div class="space-y-6 print-container">
    <!-- Top Header Panel (hidden during print) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white border border-slate-200 p-6 rounded-2xl shadow-sm print:hidden">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 flex items-center gap-2">
                <i data-lucide="history" class="w-5 h-5 text-indigo-650"></i>
                <span>Production Batches History Log</span>
            </h1>
            <p class="text-sm text-slate-500 mt-1">Search, filter, export, or print recorded production runs, batch outputs, and locked formulas.</p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Export CSV -->
            <a href="{{ route('production.history', array_merge(request()->all(), ['export' => 'csv'])) }}" 
               class="inline-flex items-center px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 font-semibold border border-slate-300 rounded-xl text-sm transition-colors gap-1.5 shadow-sm">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span>Export CSV</span>
            </a>
            <!-- Print Layout -->
            <button onclick="window.print()" 
                    class="inline-flex items-center px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition-all gap-1.5 shadow-md shadow-indigo-600/10">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Print Log</span>
            </button>
        </div>
    </div>

    <!-- Filters Card (hidden during print) -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden print:hidden">
        <div class="p-5 border-b border-slate-200 bg-slate-50/40">
            <form method="GET" action="{{ route('production.history') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-4 items-end">
                <!-- Search Batch Number -->
                <div>
                    <label for="search" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Search Batch No</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        </span>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="block w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            placeholder="e.g. ADH-2026...">
                    </div>
                </div>

                <!-- Brand Filter -->
                <div>
                    <label for="brand_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Brand</label>
                    <select id="brand_id" name="brand_id"
                        class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Machine Filter -->
                <div>
                    <label for="machine_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Mixer / Machine</label>
                    <select id="machine_id" name="machine_id"
                        class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">All Machines</option>
                        @foreach($machines as $machine)
                            <option value="{{ $machine->id }}" {{ request('machine_id') == $machine->id ? 'selected' : '' }}>
                                {{ $machine->name }} ({{ $machine->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Grade Filter -->
                <div>
                    <label for="grade_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Grade</label>
                    <select id="grade_id" name="grade_id"
                        class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">All Grades</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                {{ $grade->name }}@if($grade->brand) [{{ $grade->brand->name }}]@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Supervisor Filter -->
                <div>
                    <label for="supervisor_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Supervisor</label>
                    <select id="supervisor_id" name="supervisor_id"
                        class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">All Supervisors</option>
                        @foreach($supervisors as $supervisor)
                            <option value="{{ $supervisor->id }}" {{ request('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                {{ $supervisor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Status</label>
                    <select id="status" name="status"
                        class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">All Statuses</option>
                        <option value="running" {{ request('status') === 'running' ? 'selected' : '' }}>Running</option>
                        <option value="paused" {{ request('status') === 'paused' ? 'selected' : '' }}>Paused</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div>
                    <label for="date" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Date</label>
                    <input type="date" id="date" name="date" value="{{ request('date') }}"
                        class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>

                <!-- Action Buttons -->
                <div class="lg:col-span-7 flex justify-end gap-2 mt-2">
                    <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 font-semibold rounded-xl text-sm transition-colors flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        <span>Apply Filters</span>
                    </button>
                    @if(request()->anyFilled(['search', 'brand_id', 'machine_id', 'grade_id', 'supervisor_id', 'status', 'date']))
                        <a href="{{ route('production.history') }}" class="px-4 py-2 bg-slate-150 hover:bg-slate-200 text-slate-500 hover:text-slate-800 font-semibold border border-slate-300 rounded-xl text-sm transition-colors flex items-center justify-center gap-1">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            <span>Clear</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- History Batches Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-500 font-semibold text-xs uppercase tracking-wider">
                        <th class="p-4 w-40">Batch Number</th>
                        <th class="p-4">Machine</th>
                        <th class="p-4">Grade</th>
                        <th class="p-4">Supervisor</th>
                        <th class="p-4">Start Time</th>
                        <th class="p-4">End Time</th>
                        <th class="p-4 w-28 text-center">Status</th>
                        <th class="p-4 text-right">Output Bags</th>
                        <th class="p-4 text-right">Output KG</th>
                        <th class="p-4 w-20 text-center print:hidden">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($batches as $batch)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <!-- Batch Number -->
                            <td class="p-4 font-mono font-bold text-indigo-650">
                                <a href="{{ route('production.show', $batch->id) }}" class="hover:underline" target="_blank">
                                    {{ $batch->batch_no }}
                                </a>
                            </td>

                            <!-- Machine -->
                            <td class="p-4 font-semibold text-slate-800">
                                {{ $batch->machine->name }}
                                <span class="block text-xs font-mono text-slate-450">{{ $batch->machine->code }}</span>
                            </td>

                            <!-- Grade -->
                            <td class="p-4">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="font-semibold text-slate-800">{{ $batch->grade->name }}</span>
                                    @if($batch->grade?->brand)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            {{ $batch->grade->brand->name }}
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-450 font-mono">Formula v{{ $batch->formula->version ?? 'N/A' }}</div>
                            </td>

                            <!-- Supervisor -->
                            <td class="p-4 text-slate-600 font-medium">
                                {{ $batch->supervisor->name }}
                            </td>

                            <!-- Start Time -->
                            <td class="p-4 font-mono text-xs text-slate-500">
                                {{ $batch->start_time->format('d M Y, h:i A') }}
                            </td>

                            <!-- End Time -->
                            <td class="p-4 font-mono text-xs text-slate-500">
                                {{ $batch->end_time ? $batch->end_time->format('d M Y, h:i A') : '-' }}
                            </td>

                            <!-- Status Badge -->
                            <td class="p-4 text-center">
                                @if($batch->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-wide">
                                        Completed
                                    </span>
                                @elseif($batch->status === 'paused')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100 uppercase tracking-wide">
                                        Paused
                                    </span>
                                @elseif($batch->status === 'cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100 uppercase tracking-wide">
                                        Cancelled
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-wide animate-pulse">
                                        Running
                                    </span>
                                @endif
                            </td>

                            <!-- Output Bags -->
                            <td class="p-4 text-right font-mono text-slate-800 font-bold">
                                {{ $batch->output_bags ? number_format($batch->output_bags, 0) . ' Bags' : '-' }}
                            </td>

                            <!-- Output KG -->
                            <td class="p-4 text-right font-mono text-slate-800 font-bold">
                                {{ $batch->output_kg ? number_format($batch->output_kg, 2) . ' KG' : '-' }}
                            </td>

                            <!-- Action -->
                            <td class="p-4 text-center print:hidden">
                                <a href="{{ route('production.show', $batch->id) }}" 
                                   class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-all inline-block border border-slate-350" 
                                   title="View Details"
                                   target="_blank">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i data-lucide="package-x" class="w-8 h-8 text-slate-300"></i>
                                    <span>No batches found matching filter parameters.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($batches->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50 print:hidden">
                {{ $batches->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    @media print {
        header, .print\:hidden, sidebar, x-sidebar, #refreshTimer, .bg-slate-950 {
            display: none !important;
        }
        body, .print-container, .bg-white {
            background-color: white !important;
            color: black !important;
            box-shadow: none !important;
            border: none !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        table {
            border: 1px solid #cbd5e1 !important;
            width: 100% !important;
        }
        th, td {
            border: 1px solid #e2e8f0 !important;
            padding: 6px 8px !important;
            font-size: 11px !important;
        }
        th {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
        }
    }
</style>
@endsection
