@extends('layouts.app')

@section('title', 'Approved Orders')
@section('header-title', 'Approved Orders')

@section('styles')
<style>
    .marketing-orders-page {
        --marketing-blue: #2563eb;
        --marketing-ink: #0f172a;
        --marketing-border: #e2e8f0;
    }

    .marketing-orders-page .page-card {
        border-radius: 22px;
    }

    .order-metric {
        border: 1px solid var(--marketing-border);
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        padding: 16px;
    }

    .order-metric span {
        color: #64748b;
        display: block;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.08em;
        line-height: 1.2;
        text-transform: uppercase;
    }

    .order-metric strong {
        color: var(--marketing-ink);
        display: block;
        font-size: 26px;
        font-weight: 900;
        line-height: 1.1;
        margin-top: 8px;
    }

    .status-tab {
        align-items: center;
        border: 1px solid transparent;
        border-radius: 12px;
        color: #64748b;
        display: inline-flex;
        flex: 0 0 auto;
        font-size: 13px;
        font-weight: 850;
        gap: 8px;
        min-height: 40px;
        padding: 9px 14px;
        transition: all 0.18s ease;
    }

    .status-tab:hover {
        color: #0f172a;
        background: #ffffff;
    }

    .status-tab.active {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: var(--marketing-blue);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.08);
    }

    .status-tab span {
        align-items: center;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 999px;
        display: inline-flex;
        font-size: 11px;
        justify-content: center;
        min-width: 26px;
        padding: 2px 7px;
    }

    .marketing-orders-table th {
        font-size: 11px !important;
        padding: 14px 16px !important;
    }

    .marketing-orders-table td {
        font-size: 13px !important;
        padding: 14px 16px !important;
        vertical-align: middle;
    }

    .priority-badge,
    .status-badge {
        align-items: center;
        border-radius: 999px;
        display: inline-flex;
        font-size: 12px;
        font-weight: 850;
        line-height: 1;
        min-height: 30px;
        padding: 8px 11px;
        white-space: nowrap;
    }

    .priority-low { background: #ecfdf5; color: #047857; }
    .priority-medium { background: #fffbeb; color: #b45309; }
    .priority-high { background: #fef2f2; color: #dc2626; }
    .priority-urgent { background: #fff7ed; color: #c2410c; }

    .order-action-button {
        align-items: center;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        color: #64748b;
        display: inline-flex;
        height: 40px;
        justify-content: center;
        transition: all 0.18s ease;
        padding: 0 12px;
    }

    .order-action-button:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .chevron-icon {
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .chevron-icon.rotate-180 {
        transform: rotate(180deg);
    }

    /* Reset / properly format nested items table on desktop */
    .marketing-orders-page .sub-items-table {
        display: table !important;
        width: 100% !important;
        border-collapse: collapse !important;
    }

    .marketing-orders-page .sub-items-table thead {
        display: table-header-group !important;
    }

    .marketing-orders-page .sub-items-table tbody {
        display: table-row-group !important;
    }

    .marketing-orders-page .sub-items-table tr {
        display: table-row !important;
        border: 0 !important;
        border-radius: 0 !important;
        background: transparent !important;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
    }

    .marketing-orders-page .sub-items-table td,
    .marketing-orders-page .sub-items-table th {
        display: table-cell !important;
        text-align: left !important;
    }

    .marketing-orders-page .sub-items-table td::before,
    .marketing-orders-page .sub-items-table th::before {
        display: none !important;
        content: none !important;
    }

    @media (max-width: 767px) {
        .marketing-orders-page .page-card {
            border-radius: 18px;
            padding: 16px !important;
        }

        .marketing-orders-page .order-metric {
            padding: 14px;
        }

        .marketing-orders-page .order-metric strong {
            font-size: 22px;
        }

        .marketing-orders-page .overflow-x-auto {
            margin-inline: 0 !important;
            padding-inline: 0 !important;
        }

        .marketing-orders-page .marketing-orders-table tr.main-row {
            border-radius: 18px !important;
            padding: 14px !important;
            margin-bottom: 12px !important;
            transition: all 0.2s ease !important;
            border: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
        }

        .marketing-orders-page .marketing-orders-table tr.main-row.is-expanded {
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            border-bottom: 1px dashed #cbd5e1 !important;
            margin-bottom: 0 !important;
        }

        .marketing-orders-page .marketing-orders-table tr.sub-row {
            border: 1px solid #e2e8f0 !important;
            border-top: none !important;
            border-radius: 0 0 18px 18px !important;
            margin-top: 0 !important;
            margin-bottom: 16px !important;
            padding: 12px !important;
            background: #f8fafc !important;
            box-shadow: 0 6px 12px -2px rgb(0 0 0 / 0.05) !important;
        }

        .marketing-orders-page .marketing-orders-table tr.sub-row.hidden {
            display: none !important;
        }

        .marketing-orders-page .marketing-orders-table tr.sub-row:not(.hidden) {
            display: block !important;
        }

        .marketing-orders-page .marketing-orders-table tr.sub-row > td {
            display: block !important;
            padding: 0 !important;
            width: 100% !important;
            text-align: left !important;
            border: 0 !important;
        }

        .marketing-orders-page .marketing-orders-table tr.sub-row > td::before {
            display: none !important;
            content: none !important;
        }

        .marketing-orders-page .marketing-orders-table tr.main-row td {
            align-items: center !important;
            font-size: 13px !important;
            padding: 9px 8px !important;
            text-align: right !important;
            display: flex !important;
            justify-content: space-between !important;
        }

        .marketing-orders-page .marketing-orders-table tr.main-row td::before {
            min-width: 92px;
            font-size: 10px !important;
            font-weight: 800 !important;
            color: #64748b !important;
            text-transform: uppercase !important;
            letter-spacing: 0.06em !important;
        }

        .marketing-orders-page .order-action-button {
            height: 38px;
        }
    }
</style>
@endsection

@section('content')
@php
    $statusTabs = [
        'all' => ['label' => 'All Approved', 'count' => $orders->count()],
        'in_progress' => ['label' => 'In Progress', 'count' => $orders->where('status', 'in_progress')->count()],
        'completed' => ['label' => 'Completed', 'count' => $orders->where('status', 'completed')->count()],
    ];
    $totalItems = $orders->sum(fn ($order) => $order->items->count());
@endphp

<div class="marketing-orders-page mx-auto max-w-[1700px] space-y-5">
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-bold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <a href="{{ route('dispatch.index') }}" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black transition-all">
                View Dispatch Board &rarr;
            </a>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-sm font-bold flex items-center gap-2 shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session('warning'))
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-amber-800 text-sm font-bold flex items-center gap-2 shadow-sm">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600"></i>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    <section class="page-card bg-white border border-slate-200 p-5 shadow-sm space-y-5">
        <header class="flex flex-col gap-4 border-b border-slate-100 pb-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                    <i data-lucide="check-circle" class="h-5 w-5"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black tracking-tight text-slate-950">Approved Orders</h2>
                    <p class="mt-1 text-sm font-semibold text-slate-500">Read-only view of approved marketing orders for supervisors.</p>
                </div>
            </div>

            <div class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">
                <label class="relative block min-w-0 flex-1 sm:w-80">
                    <span class="sr-only">Search orders</span>
                    <i data-lucide="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input id="marketingOrderSearch" type="search" placeholder="Search order, party, city..."
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm font-bold text-slate-900 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100">
                </label>
            </div>
        </header>

        {{-- DATE & DEPARTMENT FILTER BAR --}}
        <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 space-y-3">
            <form method="GET" action="{{ route('supervisor.orders') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                <!-- Preset Dropdown -->
                <div class="lg:col-span-3">
                    <label class="block text-slate-500 mb-1 uppercase font-extrabold tracking-wider text-[10px]">Date Range Filter</label>
                    <select id="supervisorRangePreset" name="range_preset" class="block w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        <option value="today" {{ ($rangePreset ?? 'today') === 'today' ? 'selected' : '' }}>Today (Default)</option>
                        <option value="yesterday" {{ ($rangePreset ?? '') === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="this_week" {{ ($rangePreset ?? '') === 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="this_month" {{ ($rangePreset ?? '') === 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="custom" {{ ($rangePreset ?? '') === 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                        <option value="all" {{ ($rangePreset ?? '') === 'all' ? 'selected' : '' }}>All Time (All Orders)</option>
                    </select>
                </div>

                <!-- Custom Start Date -->
                <div id="supStartDateGroup" class="lg:col-span-3 {{ ($rangePreset ?? '') === 'custom' ? '' : 'hidden' }}">
                    <label class="block text-slate-500 mb-1 uppercase font-extrabold tracking-wider text-[10px]">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="block w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                </div>

                <!-- Custom End Date -->
                <div id="supEndDateGroup" class="lg:col-span-3 {{ ($rangePreset ?? '') === 'custom' ? '' : 'hidden' }}">
                    <label class="block text-slate-500 mb-1 uppercase font-extrabold tracking-wider text-[10px]">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="block w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                </div>

                <!-- Department Filter -->
                <div class="lg:col-span-3">
                    <label class="block text-slate-500 mb-1 uppercase font-extrabold tracking-wider text-[10px]">Department</label>
                    <select name="department_code" class="block w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        <option value="all" {{ ($departmentCode ?? 'all') === 'all' ? 'selected' : '' }}>All Departments</option>
                        <option value="TAD" {{ ($departmentCode ?? '') === 'TAD' ? 'selected' : '' }}>Tile Adhesive</option>
                        <option value="GRT" {{ ($departmentCode ?? '') === 'GRT' ? 'selected' : '' }}>Grout</option>
                        <option value="EPX" {{ ($departmentCode ?? '') === 'EPX' ? 'selected' : '' }}>Epoxy</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="lg:col-span-3 flex items-center gap-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black transition-all shadow-sm">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                        <span>Apply Filter</span>
                    </button>
                    <a href="{{ route('supervisor.orders', ['range_preset' => 'today']) }}" class="inline-flex items-center justify-center gap-1 px-3 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition-all" title="Reset to Today">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Today</span>
                    </a>
                </div>
            </form>

            <div class="pt-2 border-t border-slate-200/60 flex flex-wrap items-center justify-between gap-2 text-[11px] font-semibold text-slate-500">
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span>Active Filter:</span>
                    @if(($rangePreset ?? 'today') === 'all')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-extrabold border border-blue-200">All Time</span>
                    @elseif($startDate && $endDate)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-extrabold border border-blue-200">
                            {{ $startDate === $endDate ? 'Date: ' . $startDate : $startDate . ' to ' . $endDate }}
                        </span>
                    @endif

                    @if($departmentCode && $departmentCode !== 'all')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-extrabold border border-indigo-200">
                            Dept: {{ $departmentCode }}
                        </span>
                    @endif
                </div>

                @if(($rangePreset ?? 'today') !== 'all' && $orders->isEmpty())
                    <a href="{{ route('supervisor.orders', ['range_preset' => 'all']) }}" class="text-blue-600 hover:underline font-bold">
                        Click here to view All Past Orders &rarr;
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
            <div class="order-metric">
                <span>Total Approved</span>
                <strong>{{ $orders->count() }}</strong>
            </div>
            <div class="order-metric">
                <span>In Progress</span>
                <strong>{{ $statusTabs['in_progress']['count'] }}</strong>
            </div>
            <div class="order-metric">
                <span>Completed</span>
                <strong>{{ $statusTabs['completed']['count'] }}</strong>
            </div>
            <div class="order-metric">
                <span>Total Items</span>
                <strong>{{ $totalItems }}</strong>
            </div>
        </div>

        <div class="flex gap-2 overflow-x-auto rounded-2xl border border-slate-200 bg-slate-100 p-1.5" role="tablist" aria-label="Order status filters">
            @foreach($statusTabs as $status => $tab)
                <button type="button" class="status-tab {{ $status === 'all' ? 'active' : '' }}" data-status="{{ $status }}">
                    {{ $tab['label'] }}
                    <span>{{ $tab['count'] }}</span>
                </button>
            @endforeach
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="marketing-orders-table responsive-table w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-extrabold tracking-wider border-b border-slate-200">
                        <tr>
                            <th>Order No.</th>
                            <th>Party Name</th>
                            <th>City</th>
                            <th>Coupon</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Approved By</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($orders as $order)
                            @php
                                $statusInfo = $order->status_info;
                                $priority = $order->priority ?: 'medium';
                                $cList = $order->items->map(fn($item) => $item->coupon_name)->filter(fn($c) => $c && $c !== 'No Coupon' && $c !== 'N/A')->unique()->implode(', ');
                                $searchText = strtolower($order->order_number . ' ' . $order->party_name . ' ' . $order->city . ' ' . $cList . ' ' . $order->status . ' ' . $priority);
                            @endphp
                            <tr class="main-row transition hover:bg-slate-50/70" data-status="{{ $order->status }}" data-search="{{ $searchText }}" data-order-id="{{ $order->id }}">
                                <td data-label="Order No." class="font-mono text-sm font-black text-blue-700">
                                    <div class="flex items-center gap-1.5">
                                        <span>{{ $order->order_number }}</span>
                                        @if($order->is_edited)
                                            <span class="inline-flex items-center gap-0.5 rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-black text-amber-800 border border-amber-300 shadow-sm" title="Order was updated/edited">
                                                ✏️ Edited
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td data-label="Party Name" class="font-extrabold text-slate-900">{{ $order->party_name }}</td>
                                <td data-label="City" class="font-bold text-slate-600">{{ $order->city ?: 'N/A' }}</td>
                                <td data-label="Coupon" class="font-bold text-slate-700">
                                    @if($cList)
                                        <span class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-0.5 text-xs font-bold text-amber-700 border border-amber-200">
                                            <i data-lucide="tag" class="h-3 w-3"></i> {{ $cList }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 font-normal">None</span>
                                    @endif
                                </td>
                                <td data-label="Priority">
                                    <span class="priority-badge priority-{{ $priority }}">{{ ucfirst($priority) }}</span>
                                </td>
                                <td data-label="Status">
                                    <span class="status-badge border"
                                        style="background-color: {{ $statusInfo['bg'] ?? '#f1f5f9' }}; color: {{ $statusInfo['color'] ?? '#475569' }}; border-color: {{ $statusInfo['color'] ?? '#475569' }}40;">
                                        {{ $statusInfo['label'] ?? ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td data-label="Approved By" class="font-bold text-slate-600">{{ $order->approver->name ?? 'System' }}</td>
                                <td data-label="Actions" class="text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2 flex-wrap">
                                        @if(auth()->user()?->isAdmin() && $order->status !== 'completed' && $order->status !== 'cancelled')
                                            @if($order->isProductionReady())
                                                <form method="POST" action="{{ route('supervisor.orders.ready-to-dispatch', $order->id) }}" class="inline" onsubmit="return confirm('Order quantity and production quantity match! Mark this order as Ready to Dispatch and set status to Completed on Dispatch board?')">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-black shadow-sm transition-all whitespace-nowrap" title="Order quantity matches completed production stock. Click to dispatch!">
                                                        <i data-lucide="truck" class="h-3.5 w-3.5"></i>
                                                        <span>Ready to Dispatch</span>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="inline-flex items-center gap-1 rounded-xl bg-amber-50 px-2.5 py-1.5 text-[11px] font-black text-amber-700 border border-amber-200 whitespace-nowrap" title="Waiting for production quantity to match order quantity">
                                                    <i data-lucide="clock" class="h-3 w-3"></i> Awaiting Production
                                                </span>
                                            @endif
                                        @endif
                                        <button type="button" class="expand-btn order-action-button bg-slate-50 hover:border-slate-300 hover:bg-blue-50 hover:text-blue-700 shadow-sm gap-1.5 font-bold text-xs" data-order-id="{{ $order->id }}" title="View items">
                                            <span class="md:hidden text-[11px] font-extrabold text-blue-700">Items ({{ $order->items->count() }})</span>
                                            <i data-lucide="chevron-down" class="h-4 w-4 chevron-icon"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr id="sub-row-{{ $order->id }}" class="sub-row hidden bg-slate-50 border-b border-slate-100" data-status="{{ $order->status }}" data-search="{{ $searchText }}">
                                <td colspan="8" class="!p-3 sm:!p-5">
                                    <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm space-y-3">
                                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                                                <i data-lucide="package" class="w-4 h-4 text-blue-600"></i>
                                                <span>Ordered Items ({{ $order->items->count() }})</span>
                                            </h4>
                                            <span class="text-[11px] font-bold text-slate-500 font-mono">{{ $order->order_number }}</span>
                                        </div>

                                        {{-- MOBILE ITEMS LIST (Screen < 768px) --}}
                                        <div class="space-y-3 md:hidden">
                                            @forelse($order->items as $item)
                                                @php $st = $item->stock_info; @endphp
                                                <div class="rounded-xl border border-slate-200/80 bg-slate-50/50 p-3.5 space-y-2.5 {{ $item->is_edited ? 'border-l-4 border-l-amber-500 bg-amber-50/30' : '' }}">
                                                    <div class="flex items-start justify-between gap-2 border-b border-slate-200/60 pb-2">
                                                        <div>
                                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                                <span class="font-black text-slate-900 text-xs">{{ $item->product_name }}</span>
                                                                @if($item->brand)
                                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                                        {{ $item->brand->name }}
                                                                    </span>
                                                                @endif
                                                                @if($item->is_edited)
                                                                    <span class="inline-flex items-center gap-0.5 rounded bg-amber-100 px-1.5 py-0.5 text-[9px] font-extrabold text-amber-800 border border-amber-300">
                                                                        Updated
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <span class="inline-block mt-0.5 text-[10px] font-extrabold text-slate-500 uppercase tracking-wide bg-slate-200/60 px-1.5 py-0.5 rounded">{{ $item->department_label }}</span>
                                                        </div>
                                                        <div class="text-right shrink-0">
                                                            <span class="font-mono text-xs font-black text-blue-700 bg-blue-50 px-2 py-1 rounded-lg border border-blue-200">{{ $item->quantity_bags }} {{ $item->unit_label }}</span>
                                                            <span class="block text-[10px] font-semibold text-slate-500 mt-1">{{ $item->packing ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                                        <div>
                                                            <span class="text-slate-400 font-bold uppercase text-[9px] block">Weight:</span>
                                                            <span class="font-extrabold text-slate-800">{{ number_format($item->calculated_weight_kg, 1) }} KG</span>
                                                            <span class="text-slate-500 text-[10px] font-semibold">({{ number_format($item->calculated_weight_kg / 1000, 2) }} Ton)</span>
                                                        </div>
                                                        <div>
                                                            <span class="text-slate-400 font-bold uppercase text-[9px] block">Coupon:</span>
                                                            <span class="font-extrabold text-slate-800">{{ $item->coupon_name ?: 'None' }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="pt-2 border-t border-slate-200/60 flex flex-col gap-1 text-xs">
                                                        <div>
                                                            @if($st['is_available'])
                                                                <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-2 py-1 text-[11px] font-bold text-emerald-700 border border-emerald-200">
                                                                    ✅ Available ({{ $st['available_bags'] }} {{ $item->unit_label }} Stock)
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center gap-1 rounded-lg bg-rose-50 px-2 py-1 text-[11px] font-bold text-rose-700 border border-rose-200">
                                                                    ❌ Not Available ({{ $st['available_bags'] }} {{ $item->unit_label }} Stock)
                                                                </span>
                                                            @endif
                                                        </div>
                                                        @if($item->remarks)
                                                            <div class="text-[11px] text-slate-600 font-medium italic mt-0.5">
                                                                <span class="font-bold text-slate-400 not-italic">Note:</span> {{ $item->remarks }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="py-4 text-center text-xs font-bold text-slate-400">No items found for this order.</div>
                                            @endforelse
                                        </div>

                                        {{-- DESKTOP ITEMS TABLE (Screen >= 768px) --}}
                                        <div class="hidden md:block overflow-x-auto">
                                            <table class="sub-items-table w-full text-left text-sm">
                                                <thead class="border-b border-slate-100 text-xs font-extrabold text-slate-400">
                                                    <tr>
                                                        <th class="px-3 py-2">Dept</th>
                                                        <th class="px-3 py-2">Product</th>
                                                        <th class="px-3 py-2 text-center">Quantity</th>
                                                        <th class="px-3 py-2">Packing</th>
                                                        <th class="px-3 py-2 text-right">Weight (KG)</th>
                                                        <th class="px-3 py-2 text-right">Weight (Ton)</th>
                                                        <th class="px-3 py-2">Coupon</th>
                                                        <th class="px-3 py-2">Stock Availability</th>
                                                        <th class="px-3 py-2">Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-50">
                                                    @forelse($order->items as $item)
                                                    @php $st = $item->stock_info; @endphp
                                                    <tr class="{{ $item->is_edited ? 'bg-amber-50/80 border-l-4 border-l-amber-500 font-bold' : '' }}">
                                                        <td class="px-3 py-2 font-bold text-slate-600 whitespace-nowrap">{{ $item->department_label }}</td>
                                                        <td class="px-3 py-2 font-black text-slate-900">
                                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                                <span>{{ $item->product_name }}</span>
                                                                @if($item->brand)
                                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                                        {{ $item->brand->name }}
                                                                    </span>
                                                                @endif
                                                                @if($item->is_edited)
                                                                    <span class="inline-flex items-center gap-0.5 rounded bg-amber-100 px-1.5 py-0.5 text-[9px] font-extrabold text-amber-800 border border-amber-300 ml-1" title="Product updated">
                                                                        Updated
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="px-3 py-2 text-center font-extrabold text-blue-700">{{ $item->quantity_bags }} {{ $item->unit_label }}</td>
                                                        <td class="px-3 py-2 font-bold text-slate-600">{{ $item->packing ?? '-' }}</td>
                                                        <td class="px-3 py-2 text-right font-bold text-slate-700">{{ number_format($item->calculated_weight_kg, 1) }}</td>
                                                        <td class="px-3 py-2 text-right font-black text-emerald-600">{{ number_format($item->calculated_weight_kg / 1000, 2) }}</td>
                                                        <td class="px-3 py-2 font-bold text-slate-600">
                                                            @if($item->coupon_name)
                                                                {{ $item->coupon_name }} 
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-2 whitespace-nowrap font-bold">
                                                            @if($st['is_available'])
                                                                <span class="inline-flex items-center gap-1 rounded bg-emerald-50 px-2 py-0.5 text-xs font-bold text-emerald-700 border border-emerald-200">
                                                                    ✅ Available ({{ $st['available_bags'] }} {{ $item->unit_label }} Stock)
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center gap-1 rounded bg-rose-50 px-2 py-0.5 text-xs font-bold text-rose-700 border border-rose-200">
                                                                    ❌ Not Available ({{ $st['available_bags'] }} {{ $item->unit_label }} Stock)
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-2 text-slate-500 text-xs">{{ $item->remarks ?? '-' }}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="9" class="px-3 py-4 text-center text-xs font-bold text-slate-400">No items found for this order.</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-10 text-center">
                                    <div class="mx-auto max-w-sm">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                            <i data-lucide="check-circle" class="h-6 w-6"></i>
                                        </div>
                                        <p class="mt-3 text-base font-black text-slate-800">
                                            @if(($rangePreset ?? 'today') === 'today')
                                                No approved orders for today ({{ $startDate }})
                                            @else
                                                No approved orders found for this period
                                            @endif
                                        </p>
                                        <p class="mt-1 text-xs font-semibold text-slate-500">
                                            Use the filter above to choose past dates or view all orders.
                                        </p>
                                        @if(($rangePreset ?? 'today') !== 'all')
                                            <div class="mt-4">
                                                <a href="{{ route('supervisor.orders', ['range_preset' => 'all']) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black shadow-sm transition-all">
                                                    <i data-lucide="list-filter" class="w-3.5 h-3.5"></i>
                                                    <span>View All Orders (All Time)</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        @if($orders->isNotEmpty())
                            <tr id="emptyFilteredRow" class="hidden">
                                <td colspan="8" class="p-10 text-center text-sm font-bold text-slate-500">
                                    No orders match your current search or status filter.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <footer class="flex flex-col gap-3 pt-1 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm font-bold text-slate-500">
                Showing <span id="ordersVisibleCount">{{ $orders->count() }}</span> of {{ $orders->count() }} orders
            </p>

            <button type="button" id="clearOrderFilters" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-extrabold text-slate-700 transition hover:bg-slate-50">
                <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                <span>Clear Filters</span>
            </button>
        </footer>
    </section>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var $mainRows = $('.main-row');
        var $search = $('#marketingOrderSearch');
        var $visibleCount = $('#ordersVisibleCount');
        var $emptyFilteredRow = $('#emptyFilteredRow');

        // Dynamic toggle for custom start/end date
        $('#supervisorRangePreset').on('change', function() {
            var val = $(this).val();
            if (val === 'custom') {
                $('#supStartDateGroup, #supEndDateGroup').removeClass('hidden');
            } else {
                $('#supStartDateGroup, #supEndDateGroup').addClass('hidden');
            }
        });

        function applyOrderFilters() {
            var query = ($search.val() || '').toLowerCase().trim();
            var status = $('.status-tab.active').data('status') || 'all';
            var visible = 0;

            $mainRows.each(function() {
                var $row = $(this);
                var orderId = $row.data('order-id');
                var $subRow = $('#sub-row-' + orderId);
                
                var matchesStatus = status === 'all' || $row.data('status') === status;
                var matchesSearch = !query || String($row.data('search')).indexOf(query) !== -1;
                var showRow = matchesStatus && matchesSearch;

                $row.toggle(showRow);
                
                if (!showRow) {
                    $subRow.addClass('hidden');
                    $row.removeClass('is-expanded');
                    $row.find('.chevron-icon').removeClass('rotate-180');
                }
                
                if (showRow) {
                    visible += 1;
                }
            });

            $visibleCount.text(visible);
            if ($emptyFilteredRow.length) {
                $emptyFilteredRow.toggleClass('hidden', visible === 0);
            }
        }

        $('.status-tab').on('click', function() {
            $('.status-tab').removeClass('active');
            $(this).addClass('active');
            applyOrderFilters();
        });

        $search.on('input', applyOrderFilters);

        $('#clearOrderFilters').on('click', function() {
            $search.val('');
            $('.status-tab').removeClass('active');
            $('.status-tab[data-status="all"]').addClass('active');
            applyOrderFilters();
        });

        $(document).on('click', '.expand-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var orderId = $(this).data('order-id');
            var $subRow = $('#sub-row-' + orderId);
            var $mainRow = $('tr.main-row[data-order-id="' + orderId + '"]');
            var $chevron = $(this).find('.chevron-icon');

            $subRow.toggleClass('hidden');
            var isOpen = !$subRow.hasClass('hidden');
            
            $mainRow.toggleClass('is-expanded', isOpen);
            $chevron.toggleClass('rotate-180', isOpen);
        });

        applyOrderFilters();
    });
</script>
@endsection
