@extends('layouts.app')

@section('title', 'Dispatch Register Reports')
@section('header-title', 'Dispatch Register Reports')

@section('styles')
<style>
    .dispatch-reports-page .page-card {
        border-radius: 22px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        padding: 24px;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        #printableReport, #printableReport * {
            visibility: visible;
        }
        #printableReport {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="dispatch-reports-page space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 no-print">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900">Dispatch Register Reports</h1>
            <p class="text-xs font-bold text-slate-500 mt-1">Vehicle-wise, Driver-wise, Date-wise, and Customer-wise dispatch analysis.</p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-extrabold text-white hover:bg-slate-800 transition shadow-sm">
                <i data-lucide="printer" class="h-4 w-4"></i>
                Print Register
            </button>
            <a href="{{ route('dispatch.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-xs font-extrabold text-slate-700 hover:bg-slate-200 transition">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back to Dispatches
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="page-card shadow-sm no-print">
        <form method="GET" action="{{ route('dispatch.reports') }}" class="space-y-4">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Report Filters</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-[11px] font-extrabold uppercase text-slate-500 mb-1">Vehicle Number</label>
                    <input type="text" name="vehicle" value="{{ request('vehicle') }}" placeholder="Filter by vehicle..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold uppercase text-slate-500 mb-1">Driver Name</label>
                    <input type="text" name="driver" value="{{ request('driver') }}" placeholder="Filter by driver..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold uppercase text-slate-500 mb-1">Customer / Party</label>
                    <input type="text" name="customer" value="{{ request('customer') }}" placeholder="Filter by customer..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold uppercase text-slate-500 mb-1">Dispatch Type</label>
                    <select name="dispatch_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
                        <option value="">All Types</option>
                        <option value="factory_pickup" {{ request('dispatch_type') === 'factory_pickup' ? 'selected' : '' }}>Factory Pickup</option>
                        <option value="crossing_delivery" {{ request('dispatch_type') === 'crossing_delivery' ? 'selected' : '' }}>Crossing Delivery</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold uppercase text-slate-500 mb-1">Status</label>
                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
                        <option value="all">All Statuses</option>
                        <option value="planned" {{ request('status') === 'planned' ? 'selected' : '' }}>Planned</option>
                        <option value="loading" {{ request('status') === 'loading' ? 'selected' : '' }}>Loading</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold uppercase text-slate-500 mb-1">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold uppercase text-slate-500 mb-1">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-2 px-4 text-xs font-extrabold transition">
                        Apply Filters
                    </button>
                    <a href="{{ route('dispatch.reports') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl py-2 px-3 text-xs font-extrabold transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Printable Report Card -->
    <div id="printableReport" class="page-card shadow-sm space-y-6">
        
        <div class="flex items-center justify-between border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-xl font-black tracking-tight text-slate-900">SOLCON DISPATCH REGISTER</h2>
                <p class="text-xs font-bold text-slate-500">Generated on {{ date('d M Y, h:i A') }}</p>
            </div>
            <div class="text-right">
                <span class="text-xs font-black text-slate-800 block">Total Dispatches: {{ $dispatches->count() }}</span>
                <span class="text-xs font-extrabold text-blue-600 block">Total Weight: {{ number_format($dispatches->sum(fn($d) => $d->total_weight), 1) }} KG</span>
                <span class="text-xs font-black text-emerald-600 block">Total Ton: {{ number_format($dispatches->sum(fn($d) => $d->total_weight) / 1000, 2) }} Ton</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border border-slate-200">
                <thead class="bg-slate-100 text-slate-600 uppercase font-extrabold border-b border-slate-200">
                    <tr>
                        <th class="p-3">#</th>
                        <th class="p-3">Dispatch No</th>
                        <th class="p-3">Date</th>
                        <th class="p-3">Type</th>
                        <th class="p-3">Customer</th>
                        <th class="p-3">Vehicle</th>
                        <th class="p-3">Driver</th>
                        <th class="p-3 text-center">Total Units</th>
                        <th class="p-3 text-right">Weight (KG)</th>
                        <th class="p-3 text-right">Weight (Ton)</th>
                        <th class="p-3">Payment Req.</th>
                        <th class="p-3">Release</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($dispatches as $index => $dispatch)
                        <tr>
                            <td class="p-3 font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="p-3 font-mono font-black text-blue-700">{{ $dispatch->dispatch_number }}</td>
                            <td class="p-3 font-bold text-slate-600 whitespace-nowrap">{{ $dispatch->created_at->format('d/m/Y') }}</td>
                            <td class="p-3 font-extrabold text-slate-800">{{ $dispatch->type_info['label'] }}</td>
                            <td class="p-3 font-black text-slate-900">{{ $dispatch->party_name }}</td>
                            <td class="p-3 font-bold text-slate-700">{{ $dispatch->vehicle_number ?: '-' }}</td>
                            <td class="p-3 font-bold text-slate-700">{{ $dispatch->driver_name ?: '-' }}</td>
                            <td class="p-3 text-center font-black text-slate-900">{{ $dispatch->total_bags }}</td>
                            <td class="p-3 text-right font-bold text-slate-700">{{ number_format($dispatch->total_weight, 1) }}</td>
                            <td class="p-3 text-right font-black text-emerald-600">{{ number_format($dispatch->total_tons, 2) }}</td>
                            <td class="p-3 font-bold text-slate-700">
                                @if($dispatch->payment_required)
                                    <span class="text-rose-600 font-extrabold">Yes</span>
                                @else
                                    <span class="text-slate-400 font-normal">No</span>
                                @endif
                            </td>
                            <td class="p-3 font-bold">
                                @if($dispatch->is_released)
                                    <span class="text-emerald-600 font-extrabold">Released</span>
                                @else
                                    <span class="text-rose-600 font-extrabold">Hold</span>
                                @endif
                            </td>
                            <td class="p-3 font-extrabold">
                                {{ $dispatch->status_info['label'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="p-8 text-center text-slate-400 font-bold">
                                No dispatch records found matching filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
