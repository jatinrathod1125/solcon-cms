@extends('layouts.app')

@section('title', 'Marketing Order Queue')
@section('header-title', 'Marketing Order Queue')

@section('styles')
<style>
    .marketing-order-page {
        --erp-blue: #2563eb;
        --erp-ink: #0f172a;
    }
    .marketing-kpi-shell {
        background: linear-gradient(135deg, #0f172a 0%, #172554 62%, #2563eb 100%);
    }
    .page-content .marketing-kpi-shell,
    .page-content .marketing-kpi-shell .text-white {
        color: #fff !important;
    }
    .page-content .marketing-kpi-shell .text-blue-100,
    .page-content .marketing-kpi-shell .text-slate-300 {
        color: #dbeafe !important;
    }
    .marketing-filter-bar {
        backdrop-filter: blur(18px);
    }
    .marketing-grid-wrap .ag-theme-quartz {
        --ag-font-family: 'Manrope', ui-sans-serif, system-ui, sans-serif;
        --ag-border-color: #e2e8f0;
        --ag-header-background-color: #f8fafc;
        --ag-odd-row-background-color: #ffffff;
        --ag-row-hover-color: #f8fafc;
        --ag-selected-row-background-color: #eff6ff;
        --ag-wrapper-border-radius: 24px;
        --ag-header-height: 48px;
        --ag-row-height: 70px;
    }
    .marketing-status-badge,
    .marketing-priority-badge,
    .marketing-stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 5px 9px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }
    .marketing-status-pending { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .marketing-status-in_progress { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    .marketing-status-completed { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .marketing-status-cancelled { background: #fef2f2; color: #be123c; border: 1px solid #fecdd3; }
    .priority-low { background: #f8fafc; color: #475569; border: 1px solid #cbd5e1; }
    .priority-medium { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .priority-high { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    .priority-urgent { background: #fef2f2; color: #be123c; border: 1px solid #fecdd3; }
    .stock-available { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .stock-partial { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .stock-unavailable { background: #fef2f2; color: #be123c; border: 1px solid #fecdd3; }
    .stock-unknown { background: #f8fafc; color: #475569; border: 1px solid #cbd5e1; }
    .marketing-grid-action {
        display: inline-flex;
        min-height: 34px;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #fff;
        padding: 7px 10px;
        font-size: 11px;
        font-weight: 900;
        color: #475569;
        transition: .16s ease;
    }
    .marketing-grid-action:hover { border-color: #bfdbfe; color: #2563eb; background: #eff6ff; }
    .marketing-drawer-panel {
        width: min(560px, calc(100vw - 28px));
        transform: translateX(105%);
    }
    .marketing-drawer.is-open .marketing-drawer-panel {
        transform: translateX(0);
    }
    .marketing-drawer.is-open .marketing-drawer-backdrop {
        opacity: 1;
        pointer-events: auto;
    }
    .marketing-drawer-backdrop {
        opacity: 0;
        pointer-events: none;
        transition: opacity .24s ease;
    }
    .drawer-product-card {
        border-left: 4px solid #2563eb;
    }
    .mobile-order-card[data-availability="available"] { border-left: 4px solid #10b981; }
    .mobile-order-card[data-availability="partial"] { border-left: 4px solid #f59e0b; }
    .mobile-order-card[data-availability="unavailable"] { border-left: 4px solid #ef4444; }

    @media (max-width: 767px) {
        .marketing-filter-bar {
            position: static;
        }
        .marketing-drawer-panel {
            inset: auto 0 0 0;
            width: 100%;
            max-height: 88vh;
            border-radius: 28px 28px 0 0;
            transform: translateY(105%);
        }
        .marketing-drawer.is-open .marketing-drawer-panel {
            transform: translateY(0);
        }
    }
</style>
@endsection

@section('content')
@php
    $allOrders = collect($lanes)->flatMap(fn ($orders) => $orders)->values();
    $today = today();
    $tomorrow = today()->addDay();
    $totalOrders = $allOrders->count();
    $todayOrders = $allOrders->filter(fn ($order) => $order->order_date && $order->order_date->isToday())->count();
    $pendingOrders = $lanes['pending']->count();
    $productionOrders = $lanes['in_progress']->count();
    $completedOrders = $lanes['completed']->count();
    $cancelledOrders = $lanes['cancelled']->count();
    $readyStockOrders = $allOrders->where('availability', 'available')->count();
    $waitingProduction = $allOrders->filter(fn ($order) => in_array($order->availability, ['partial', 'unavailable'], true) && in_array($order->status, ['pending', 'in_progress'], true))->count();
    $urgentOrders = $allOrders->filter(fn ($order) => in_array($order->priority, ['high', 'urgent'], true))->count();
    $totalBags = $allOrders->sum(fn ($order) => $order->items->sum('quantity_bags'));

    $statusLabels = [
        'pending' => 'Pending',
        'in_progress' => 'Production',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];
    $availabilityLabels = [
        'available' => 'Stock Available',
        'partial' => 'Partial Stock',
        'unavailable' => 'Waiting Production',
        'unknown' => 'Insufficient Stock',
    ];

    $ordersPayload = $allOrders->map(function ($order) use ($statusLabels, $availabilityLabels) {
        $items = $order->items;
        $departments = $items->pluck('department_code')->filter()->unique()->values();
        $couponsUsed = $items->filter(fn ($item) => $item->coupon_raw_material_id !== null)
            ->map(fn ($item) => $item->couponMaterial->name ?? 'Coupon')
            ->unique()
            ->values();
        $availabilityClass = $order->availability_badge['class'] ?? $order->availability ?? 'unknown';
        $priority = $order->priority_info;
        $statusInfo = $order->status_info;

        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'party_name' => $order->party_name,
            'vehicle_number' => $order->vehicle_number ?: '',
            'delivery_date' => $order->order_date ? $order->order_date->toDateString() : '',
            'delivery_date_display' => $order->order_date ? $order->order_date->format('d M Y') : '-',
            'departments' => $departments->all(),
            'departments_text' => $departments->implode(', '),
            'items' => $items->map(fn ($item) => [
                'department_code' => $item->department_code,
                'department_label' => $item->department_label,
                'product_name' => $item->product_name,
                'packing' => $item->packing,
                'quantity_bags' => $item->quantity_bags,
                'coupon_name' => $item->coupon_name,
            ])->values()->all(),
            'items_text' => $order->items_summary,
            'item_count' => $items->count(),
            'total_bags' => (int) $items->sum('quantity_bags'),
            'coupon_text' => $couponsUsed->isNotEmpty() ? $couponsUsed->implode(', ') : 'No Coupon',
            'priority' => $order->priority,
            'priority_label' => $priority['label'] ?? ucfirst($order->priority),
            'stock_status' => $availabilityClass,
            'stock_label' => $availabilityLabels[$availabilityClass] ?? $order->availability_badge['label'] ?? ucfirst($availabilityClass),
            'production_status' => $order->status,
            'status_label' => $statusLabels[$order->status] ?? $statusInfo['label'] ?? ucfirst(str_replace('_', ' ', $order->status)),
            'created_by' => $order->creator->name ?? 'N/A',
            'created_at' => $order->created_at ? $order->created_at->format('d M Y, h:i A') : '-',
            'created_at_sort' => $order->created_at ? $order->created_at->timestamp : 0,
            'updated_at' => $order->updated_at ? $order->updated_at->format('d M Y, h:i A') : '-',
            'updated_at_sort' => $order->updated_at ? $order->updated_at->timestamp : 0,
            'remarks' => $order->remarks ?: '',
            'search_text' => strtolower(trim(implode(' ', [
                $order->order_number,
                $order->party_name,
                $order->vehicle_number,
                $order->remarks,
                $order->priority,
                $order->status,
                $availabilityClass,
                $order->items_summary,
                $couponsUsed->implode(' '),
            ]))),
        ];
    })->values();

    $partyOptions = $allOrders->pluck('party_name')->filter()->unique()->sort()->values();
    $vehicleOptions = $allOrders->pluck('vehicle_number')->filter()->unique()->sort()->values();
    $couponOptions = $ordersPayload->pluck('coupon_text')->filter(fn ($coupon) => $coupon !== 'No Coupon')->flatMap(fn ($coupon) => explode(', ', $coupon))->unique()->sort()->values();
@endphp

<div class="marketing-order-page mx-auto max-w-[1700px] space-y-5">
    <section class="marketing-kpi-shell overflow-hidden rounded-[28px] border border-slate-900/10 p-5 shadow-xl shadow-slate-900/10 sm:p-7">
        <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-3xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-[10px] font-extrabold uppercase tracking-[.18em] text-blue-100">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        Live ERP Queue
                    </span>
                    <span class="text-[11px] font-bold text-slate-300">{{ now()->format('l, d F Y') }}</span>
                </div>
                <h2 class="mt-4 text-2xl font-black tracking-tight text-white sm:text-3xl">Marketing Order Queue</h2>
                <p class="mt-2 max-w-2xl text-sm font-semibold leading-6 text-blue-100">Manufacturing-ready order control with live stock visibility, production status, and coupons in one queue.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @if(Auth::user()->isAdmin() || Auth::user()->isMarketing())
                <button type="button" onclick="openCreateModal()" class="inline-flex min-h-11 items-center gap-2 rounded-2xl bg-white px-4 py-2.5 text-xs font-extrabold text-blue-700 shadow-lg shadow-slate-950/10 transition hover:bg-blue-50">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    New Order
                </button>
                @endif
                <button type="button" onclick="refreshAvailability()" class="inline-flex min-h-11 items-center gap-2 rounded-2xl border border-white/15 bg-white/10 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-white/15">
                    <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                    Refresh
                </button>
                <button type="button" onclick="exportExcel()" class="inline-flex min-h-11 items-center gap-2 rounded-2xl border border-white/15 bg-white/10 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-white/15">
                    <i data-lucide="download" class="h-4 w-4"></i>
                    Excel
                </button>
                <button type="button" onclick="exportPdf()" class="inline-flex min-h-11 items-center gap-2 rounded-2xl border border-white/15 bg-white/10 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-white/15">
                    <i data-lucide="printer" class="h-4 w-4"></i>
                    PDF
                </button>
                <button type="button" onclick="printOrders()" class="inline-flex min-h-11 items-center gap-2 rounded-2xl border border-white/15 bg-white/10 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-white/15">
                    <i data-lucide="printer" class="h-4 w-4"></i>
                    Print
                </button>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-5 xl:grid-cols-9">
            @foreach([
                ['label' => "Today's Orders", 'value' => $todayOrders, 'tone' => 'text-blue-200'],
                ['label' => 'Pending', 'value' => $pendingOrders, 'tone' => 'text-blue-200'],
                ['label' => 'In Production', 'value' => $productionOrders, 'tone' => 'text-orange-200'],
                ['label' => 'Completed', 'value' => $completedOrders, 'tone' => 'text-emerald-200'],
                ['label' => 'Cancelled', 'value' => $cancelledOrders, 'tone' => 'text-rose-200'],
                ['label' => 'Ready Stock', 'value' => $readyStockOrders, 'tone' => 'text-emerald-200'],
                ['label' => 'Waiting Production', 'value' => $waitingProduction, 'tone' => 'text-amber-200'],
                ['label' => 'Urgent Orders', 'value' => $urgentOrders, 'tone' => 'text-rose-200'],
                ['label' => 'Total Bags', 'value' => number_format($totalBags), 'tone' => 'text-white'],
            ] as $kpi)
                <article class="rounded-2xl border border-white/10 bg-white/95 p-4">
                    <p class="truncate text-[9px] font-extrabold uppercase tracking-[.13em] text-slate-400">{{ $kpi['label'] }}</p>
                    <p class="mt-2 text-2xl font-black text-slate-950">{{ $kpi['value'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="marketing-filter-bar sticky top-[86px] z-30 rounded-[24px] border border-slate-200 bg-white/92 p-4 shadow-lg shadow-slate-900/5">
        <div class="grid gap-3 xl:grid-cols-[1.2fr_.8fr]">
            <label class="relative block">
                <span class="sr-only">Search orders</span>
                <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <input id="filterSearch" type="search" autocomplete="off" placeholder="Search party, order number, vehicle, product, coupon..." class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-bold text-slate-800 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100">
            </label>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                <select id="filterParty" class="h-12 rounded-2xl border border-slate-200 bg-white px-3 text-xs font-extrabold text-slate-700">
                    <option value="all">All Parties</option>
                    @foreach($partyOptions as $party)
                        <option value="{{ $party }}">{{ $party }}</option>
                    @endforeach
                </select>
                <input id="filterOrderNumber" type="search" placeholder="Order No." class="h-12 rounded-2xl border border-slate-200 bg-white px-3 text-xs font-extrabold text-slate-700">
                <select id="filterVehicle" class="h-12 rounded-2xl border border-slate-200 bg-white px-3 text-xs font-extrabold text-slate-700">
                    <option value="all">All Vehicles</option>
                    @foreach($vehicleOptions as $vehicle)
                        <option value="{{ $vehicle }}">{{ $vehicle }}</option>
                    @endforeach
                </select>
                <select id="filterDepartment" class="h-12 rounded-2xl border border-slate-200 bg-white px-3 text-xs font-extrabold text-slate-700">
                    <option value="all">All Departments</option>
                    <option value="TAD">Adhesive</option>
                    <option value="GRT">Grout</option>
                    <option value="EPX">Epoxy</option>
                </select>
            </div>
        </div>

        <div class="mt-3 grid gap-2 sm:grid-cols-2 xl:grid-cols-6">
            <select id="filterStatus" class="h-11 rounded-2xl border border-slate-200 bg-white px-3 text-xs font-extrabold text-slate-700">
                <option value="all">All Status</option>
                <option value="pending">Pending</option>
                <option value="in_progress">Production</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <select id="filterCoupon" class="h-11 rounded-2xl border border-slate-200 bg-white px-3 text-xs font-extrabold text-slate-700">
                <option value="all">All Coupons</option>
                <option value="none">No Coupon</option>
                @foreach($couponOptions as $coupon)
                    <option value="{{ $coupon }}">{{ $coupon }}</option>
                @endforeach
            </select>
            <input id="filterDeliveryDate" type="date" class="h-11 rounded-2xl border border-slate-200 bg-white px-3 text-xs font-extrabold text-slate-700">
            <select id="filterPriority" class="h-11 rounded-2xl border border-slate-200 bg-white px-3 text-xs font-extrabold text-slate-700">
                <option value="all">All Priority</option>
                <option value="urgent">Urgent</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>
            <select id="filterStock" class="h-11 rounded-2xl border border-slate-200 bg-white px-3 text-xs font-extrabold text-slate-700">
                <option value="all">All Stock</option>
                <option value="available">Stock Available</option>
                <option value="partial">Partial Stock</option>
                <option value="unavailable">Waiting Production</option>
            </select>
            <button type="button" id="resetFilters" class="h-11 rounded-2xl border border-slate-200 bg-white px-3 text-xs font-extrabold text-slate-600 transition hover:bg-slate-50">Reset Filters</button>
        </div>

        <div class="mt-3 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3">
            <div class="flex gap-2 overflow-x-auto pb-1">
                @foreach([
                    ['today', 'Today'],
                    ['tomorrow', 'Tomorrow'],
                    ['week', 'This Week'],
                    ['pending', 'Pending'],
                    ['production', 'Production'],
                    ['completed', 'Completed'],
                    ['cancelled', 'Cancelled'],
                ] as [$quick, $label])
                    <button type="button" data-quick-filter="{{ $quick }}" class="quick-filter-btn inline-flex min-h-9 shrink-0 items-center rounded-2xl border border-slate-200 bg-white px-3 py-2 text-[11px] font-extrabold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">{{ $label }}</button>
                @endforeach
            </div>
            <p id="filterSummary" class="text-[11px] font-bold text-slate-500">Showing {{ $totalOrders }} of {{ $totalOrders }} orders</p>
        </div>
    </section>

    <section class="marketing-grid-wrap hidden rounded-[24px] border border-slate-200 bg-white shadow-xl shadow-slate-900/5 md:block overflow-hidden">
        <div id="ordersSkeleton" class="grid gap-3 p-4">
            @for($i = 0; $i < 8; $i++)
                <div class="h-16 animate-pulse rounded-2xl bg-slate-100"></div>
            @endfor
        </div>
        <div id="marketingTableContainer" class="hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-[11px]" id="marketingOrdersTable">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-500 font-bold uppercase tracking-wider select-none text-[10px]">
                            <th class="p-3 cursor-pointer hover:bg-slate-100 transition-colors" onclick="sortTable('order_number')">
                                Order Number <span id="sort-icon-order_number"></span>
                            </th>
                            <th class="p-3 cursor-pointer hover:bg-slate-100 transition-colors" onclick="sortTable('party_name')">
                                Party Name <span id="sort-icon-party_name"></span>
                            </th>
                            <th class="p-3">Department</th>
                            <th class="p-3 cursor-pointer hover:bg-slate-100 transition-colors" onclick="sortTable('vehicle_number')">
                                Vehicle <span id="sort-icon-vehicle_number"></span>
                            </th>
                            <th class="p-3 cursor-pointer hover:bg-slate-100 transition-colors" onclick="sortTable('delivery_date')">
                                Delivery Date <span id="sort-icon-delivery_date"></span>
                            </th>
                            <th class="p-3">Items Summary</th>
                            <th class="p-3 text-right cursor-pointer hover:bg-slate-100 transition-colors" onclick="sortTable('total_bags')">
                                Bags <span id="sort-icon-total_bags"></span>
                            </th>
                            <th class="p-3">Coupon</th>
                            <th class="p-3 cursor-pointer hover:bg-slate-100 transition-colors" onclick="sortTable('priority')">
                                Priority <span id="sort-icon-priority"></span>
                            </th>
                            <th class="p-3 cursor-pointer hover:bg-slate-100 transition-colors" onclick="sortTable('stock_status')">
                                Stock Status <span id="sort-icon-stock_status"></span>
                            </th>
                            <th class="p-3 cursor-pointer hover:bg-slate-100 transition-colors" onclick="sortTable('production_status')">
                                Status <span id="sort-icon-production_status"></span>
                            </th>
                            <th class="p-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-semibold" id="marketingOrdersTableBody">
                        <!-- Rows rendered dynamically via JS -->
                    </tbody>
                </table>
            </div>
            <!-- Pagination bar -->
            <div class="p-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 bg-slate-50/50">
                <div>
                    Showing <span id="tablePageStart" class="font-bold">0</span> to <span id="tablePageEnd" class="font-bold">0</span> of <span id="tableTotalCount" class="font-bold">0</span> entries
                </div>
                <div class="flex items-center gap-1.5" id="tablePaginationButtons">
                    <!-- Pagination buttons dynamically rendered -->
                </div>
            </div>
        </div>
    </section>

    <section class="md:hidden">
        <div id="mobileOrdersList" class="space-y-3">
            @foreach($allOrders as $order)
                @include('marketing.orders._card', ['order' => $order])
            @endforeach
        </div>
    </section>

    <section id="ordersEmptyState" class="hidden rounded-[28px] border border-dashed border-slate-200 bg-white p-10 text-center shadow-sm">
        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-blue-50 text-blue-500">
            <i data-lucide="search" class="h-8 w-8"></i>
        </span>
        <h3 class="mt-4 text-lg font-black text-slate-900">No Orders Found</h3>
        <p class="mt-2 text-sm font-semibold text-slate-500">Try changing filters or clearing the search query.</p>
    </section>
</div>

<div id="marketingOrderDrawer" class="marketing-drawer pointer-events-none fixed inset-0 z-[75]">
    <button type="button" class="marketing-drawer-backdrop absolute inset-0 bg-slate-950/45 backdrop-blur-sm" onclick="closeOrderDrawer()" aria-label="Close order details"></button>
    <aside class="marketing-drawer-panel pointer-events-auto absolute right-3 top-3 flex max-h-[calc(100vh-24px)] flex-col overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-2xl transition-transform duration-300">
        <header class="flex shrink-0 items-start justify-between gap-4 border-b border-slate-100 bg-slate-50 p-5">
            <div class="min-w-0">
                <p class="text-[10px] font-black uppercase tracking-[.18em] text-blue-600">Order Summary</p>
                <h3 id="drawerOrderNumber" class="mt-1 truncate text-xl font-black text-slate-950">Order</h3>
                <div class="mt-1 flex flex-wrap items-center gap-2">
                    <p id="drawerPartyName" class="truncate text-sm font-bold text-slate-500">Loading...</p>
                    <span id="drawerReadOnly" class="hidden items-center gap-1 rounded-full border border-slate-200 bg-white px-2 py-0.5 text-[10px] font-black uppercase tracking-wider text-slate-500">
                        <i data-lucide="lock" class="h-3 w-3"></i>
                        Read Only
                    </span>
                </div>
            </div>
            <button type="button" onclick="closeOrderDrawer()" class="flex h-10 w-10 items-center justify-center rounded-2xl text-slate-400 transition hover:bg-white hover:text-slate-700" aria-label="Close order details">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </header>

        <div id="drawerLoading" class="flex flex-1 items-center justify-center p-10">
            <div class="text-center">
                <div class="mx-auto h-10 w-10 animate-spin rounded-full border-2 border-blue-100 border-t-blue-600"></div>
                <p class="mt-3 text-xs font-bold text-slate-500">Loading order details...</p>
            </div>
        </div>

        <div id="drawerContent" class="hidden min-h-0 flex-1 overflow-y-auto p-5">
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3">
                    <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Party</p>
                    <p id="drawerParty" class="mt-1 text-sm font-black text-slate-900"></p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3">
                    <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Vehicle</p>
                    <p id="drawerVehicle" class="mt-1 text-sm font-black text-slate-900"></p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3">
                    <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Contact</p>
                    <p class="mt-1 text-sm font-black text-slate-900">Not captured</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3">
                    <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Delivery</p>
                    <p id="drawerDelivery" class="mt-1 text-sm font-black text-slate-900"></p>
                </div>
                <div class="col-span-2 rounded-2xl border border-slate-100 bg-slate-50 p-3">
                    <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Address</p>
                    <p class="mt-1 text-sm font-bold text-slate-600">Not captured in current order record</p>
                </div>
                <div class="col-span-2 rounded-2xl border border-slate-100 bg-slate-50 p-3">
                    <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Remarks</p>
                    <p id="drawerRemarks" class="mt-1 text-sm font-bold leading-6 text-slate-700"></p>
                </div>
            </div>

            <section class="mt-5">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-black text-slate-950">Products</h4>
                    <span id="drawerStockBadge"></span>
                </div>
                <div id="drawerProducts" class="mt-3 space-y-3"></div>
            </section>

            <section class="mt-5">
                <h4 class="text-sm font-black text-slate-950">Timeline</h4>
                <div id="drawerTimeline" class="mt-3 space-y-3"></div>
            </section>

            <section class="mt-5">
                <h4 class="text-sm font-black text-slate-950">Activity History</h4>
                <div id="drawerActivity" class="mt-3 space-y-2"></div>
            </section>
        </div>

        <footer class="shrink-0 border-t border-slate-100 bg-white p-4">
            <div id="drawerActions" class="grid grid-cols-2 gap-2"></div>
        </footer>
    </aside>
</div>

<div id="orderFormModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-md">
    <div class="relative flex max-h-[90vh] min-h-0 w-full max-w-4xl flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl">
        <button type="button" onclick="closeFormModal()" class="absolute right-4 top-4 z-10 rounded-full p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" aria-label="Close order form">
            <i data-lucide="x" class="h-5 w-5"></i>
        </button>

        <header class="mb-4 flex shrink-0 items-center gap-3">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                <i data-lucide="clipboard-list" class="h-6 w-6"></i>
            </span>
            <div>
                <p class="text-[10px] font-black uppercase tracking-[.18em] text-blue-600">Marketing Department</p>
                <h3 class="text-lg font-black text-slate-900" id="modal-title-main">Generate New Order</h3>
            </div>
        </header>

        <form id="orderForm" class="min-h-0 flex-1 space-y-5 overflow-y-auto pr-2 text-xs">
            @csrf
            <input type="hidden" id="order_id" name="order_id">

            <div class="grid grid-cols-1 gap-4 rounded-2xl border border-slate-100 bg-slate-50/70 p-4 md:grid-cols-4">
                <div class="md:col-span-2">
                    <label class="mb-1 block font-extrabold uppercase tracking-wider text-slate-500">Party Name <span class="text-rose-500">*</span></label>
                    <input type="text" id="party_name" name="party_name" required placeholder="Enter party name..." class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition focus:ring-4 focus:ring-blue-100">
                </div>
                <div>
                    <label class="mb-1 block font-extrabold uppercase tracking-wider text-slate-500">Vehicle Number</label>
                    <input type="text" id="vehicle_number" name="vehicle_number" placeholder="GJ-05-XX-1234" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition focus:ring-4 focus:ring-blue-100">
                </div>
                <div>
                    <label class="mb-1 block font-extrabold uppercase tracking-wider text-slate-500">Priority</label>
                    <select id="priority" name="priority" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-bold outline-none transition focus:ring-4 focus:ring-blue-100">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block font-extrabold uppercase tracking-wider text-slate-500">Delivery Date <span class="text-rose-500">*</span></label>
                    <input type="date" id="order_date" name="order_date" required value="{{ date('Y-m-d') }}" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition focus:ring-4 focus:ring-blue-100">
                </div>
                <div class="md:col-span-4">
                    <label class="mb-1 block font-extrabold uppercase tracking-wider text-slate-500">Remarks</label>
                    <textarea id="remarks" name="remarks" rows="2" placeholder="Delivery or production notes..." class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition focus:ring-4 focus:ring-blue-100"></textarea>
                </div>
            </div>

            <div>
                <div class="mb-3 flex items-center justify-between gap-3">
                    <h4 class="text-xs font-black uppercase tracking-widest text-slate-700">Order Products</h4>
                    <button type="button" onclick="addItemRow()" class="inline-flex min-h-9 items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-black text-blue-700 transition hover:bg-blue-100">
                        <i data-lucide="plus" class="h-3.5 w-3.5"></i>
                        Add Item
                    </button>
                </div>
                <div id="items-container" class="space-y-4"></div>
            </div>
        </form>

        <footer class="mt-4 flex shrink-0 flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
            <span class="text-xs font-bold text-rose-600" id="form-error-msg"></span>
            <div class="flex gap-2">
                <button type="button" onclick="closeFormModal()" class="erp-button border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="button" onclick="submitOrderForm()" id="submitFormBtn" class="erp-button erp-button-primary">Save Order</button>
            </div>
        </footer>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const isAdmin = {{ auth()->user()->isAdmin() ? 'true' : 'false' }};
    const isSupervisor = {{ auth()->user()->isSupervisor() ? 'true' : 'false' }};
    const isMarketing = {{ auth()->user()->isMarketing() ? 'true' : 'false' }};
    const orderRows = @json($ordersPayload);
    const couponsList = @json($coupons);
    const adhesivesList = @json($adhesives);
    const groutsList = @json($grouts);
    const epoxiesList = @json($epoxies);
    const todayStr = '{{ $today->toDateString() }}';
    const tomorrowStr = '{{ $tomorrow->toDateString() }}';
    const weekEndStr = '{{ today()->endOfWeek()->toDateString() }}';

    let gridApi = null;
    let filteredRows = [...orderRows];
    let activeQuickFilter = null;
    let itemIndex = 0;
    let currentDrawerOrderId = null;
    const pageSize = 15;
    let currentPage = 1;
    let sortColumn = 'created_at_sort';
    let sortDirection = 'desc';

    document.addEventListener('DOMContentLoaded', function () {
        initMarketingTable();
        initFilters();
        if (window.autoAnimate) {
            ['mobileOrdersList', 'drawerProducts', 'drawerActions'].forEach(id => {
                const element = document.getElementById(id);
                if (element) window.autoAnimate(element);
            });
        }
        applyFilters();

        $('#orderForm').on('submit', function(e) {
            e.preventDefault();
        });

        const openOrderId = new URLSearchParams(window.location.search).get('open');
        if (openOrderId) {
            openOrderDetails(openOrderId);
        }
    });

    function escapeHtml(value) {
        return $('<div>').text(value ?? '').html();
    }

    function badgeHtml(type, value, label) {
        return `<span class="marketing-${type}-badge ${type === 'status' ? 'marketing-status-' : type === 'priority' ? 'priority-' : 'stock-'}${value}">${escapeHtml(label)}</span>`;
    }

    function initMarketingTable() {
        document.getElementById('ordersSkeleton').classList.add('hidden');
        document.getElementById('marketingTableContainer').classList.remove('hidden');

        document.getElementById('marketingOrdersTable').addEventListener('click', function (event) {
            const btn = event.target.closest('[data-action]');
            if (!btn) return;
            event.stopPropagation();
            const id = btn.dataset.id;
            const action = btn.dataset.action;
            if (action === 'view') openOrderDetails(id);
            if (action === 'edit') openEditModal(id);
            if (action === 'start') setInProgress(id);
            if (action === 'complete') completeOrder(id);
            if (action === 'print') printSingleOrder(id);
            if (action === 'delete') deleteOrder(id);
        });
    }

    function renderMarketingTable() {
        const tbody = document.getElementById('marketingOrdersTableBody');
        if (!tbody) return;

        const start = (currentPage - 1) * pageSize;
        const end = Math.min(start + pageSize, filteredRows.length);
        const pageRows = filteredRows.slice(start, end);

        tbody.innerHTML = '';

        if (pageRows.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="12" class="p-12 text-center text-slate-400">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-500">
                            <i data-lucide="search" class="w-6 h-6"></i>
                        </div>
                        <p class="text-sm font-black text-slate-700">No Orders Found</p>
                    </td>
                </tr>
            `;
            $('#tablePageStart').text(0);
            $('#tablePageEnd').text(0);
            $('#tableTotalCount').text(0);
            renderPaginationButtons(0);
            if (typeof lucide !== 'undefined') lucide.createIcons();
            return;
        }

        $('#tablePageStart').text(start + 1);
        $('#tablePageEnd').text(end);
        $('#tableTotalCount').text(filteredRows.length);

        pageRows.forEach(row => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-50/70 transition-colors cursor-pointer align-middle border-b border-slate-100/60';
            tr.onclick = (e) => {
                if (e.target.closest('[data-action]')) return;
                openOrderDetails(row.id);
            };

            const actions = [];
            actions.push(`<button type="button" class="marketing-grid-action" data-action="view" data-id="${row.id}">View</button>`);
            actions.push(`<button type="button" class="marketing-grid-action" data-action="print" data-id="${row.id}">Print</button>`);
            if (canEdit(row)) actions.push(`<button type="button" class="marketing-grid-action border-blue-100 text-blue-700 hover:bg-blue-50" data-action="edit" data-id="${row.id}">Edit</button>`);
            if (canStart(row)) actions.push(`<button type="button" class="marketing-grid-action border-orange-100 text-orange-700 hover:bg-orange-50" data-action="start" data-id="${row.id}">Start</button>`);
            if (canComplete(row)) actions.push(`<button type="button" class="marketing-grid-action border-emerald-100 text-emerald-700 hover:bg-emerald-50" data-action="complete" data-id="${row.id}">Complete</button>`);
            if (canCancel(row)) actions.push(`<button type="button" class="marketing-grid-action border-rose-100 text-rose-700 hover:bg-rose-50" data-action="delete" data-id="${row.id}">Delete</button>`);

            const actionsHtml = `<div class="flex h-full items-center gap-1.5 justify-end">${actions.join('')}</div>`;

            tr.innerHTML = `
                <td class="p-3 font-mono font-bold text-blue-700">${escapeHtml(row.order_number)}</td>
                <td class="p-3">
                    <div class="text-sm font-black text-slate-900">${escapeHtml(row.party_name)}</div>
                    <div class="text-[10px] font-bold text-slate-400 max-w-[200px] truncate">${escapeHtml(row.remarks || 'No remarks')}</div>
                </td>
                <td class="p-3">
                    <div class="flex flex-wrap gap-1">
                        ${(row.departments || []).map(d => `<span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[9px] font-black text-slate-600">${escapeHtml(d)}</span>`).join('')}
                    </div>
                </td>
                <td class="p-3 font-mono text-slate-600">${escapeHtml(row.vehicle_number || '-')}</td>
                <td class="p-3 font-bold text-slate-800">${escapeHtml(row.delivery_date_display)}</td>
                <td class="p-3 text-slate-500 font-bold max-w-xs truncate" title="${escapeHtml(row.items_text)}">${escapeHtml(row.items_text || 'No items')}</td>
                <td class="p-3 text-right font-black tabular-nums text-slate-900">${Number(row.total_bags).toLocaleString()}</td>
                <td class="p-3">
                    <span class="text-xs font-bold ${row.coupon_text === 'No Coupon' ? 'text-slate-400' : 'text-blue-700'}">${escapeHtml(row.coupon_text)}</span>
                </td>
                <td class="p-3">${badgeHtml('priority', row.priority, row.priority_label)}</td>
                <td class="p-3">${badgeHtml('stock', row.stock_status || 'unknown', row.stock_label)}</td>
                <td class="p-3">${badgeHtml('status', row.production_status, row.status_label)}</td>
                <td class="p-3" data-action="container">${actionsHtml}</td>
            `;
            tbody.appendChild(tr);
        });

        renderPaginationButtons(filteredRows.length);

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function renderPaginationButtons(totalCount) {
        const container = document.getElementById('tablePaginationButtons');
        if (!container) return;

        const totalPages = Math.ceil(totalCount / pageSize);
        container.innerHTML = '';

        if (totalPages <= 1) return;

        const prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.disabled = currentPage === 1;
        prevBtn.className = 'px-2.5 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed';
        prevBtn.innerHTML = '<i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>';
        prevBtn.onclick = () => {
            if (currentPage > 1) {
                currentPage--;
                renderMarketingTable();
            }
        };
        container.appendChild(prevBtn);

        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.type = 'button';
            pageBtn.className = `px-3 py-1.5 rounded-lg border text-xs font-bold transition ${i === currentPage ? 'bg-blue-600 text-white border-blue-600' : 'border-slate-200 text-slate-600 hover:bg-slate-50'}`;
            pageBtn.innerText = i;
            pageBtn.onclick = () => {
                currentPage = i;
                renderMarketingTable();
            };
            container.appendChild(pageBtn);
        }

        const nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.className = 'px-2.5 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed';
        nextBtn.innerHTML = '<i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>';
        nextBtn.onclick = () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderMarketingTable();
            }
        };
        container.appendChild(nextBtn);
    }

    function sortTable(column) {
        if (sortColumn === column) {
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            sortColumn = column;
            sortDirection = 'asc';
        }

        applySort();
        currentPage = 1;
        renderMarketingTable();
        updateSortIcons();
    }

    function applySort() {
        filteredRows.sort((a, b) => {
            let valA = a[sortColumn];
            let valB = b[sortColumn];

            if (sortColumn === 'delivery_date') {
                valA = a.delivery_date || '';
                valB = b.delivery_date || '';
            }

            if (valA < valB) return sortDirection === 'asc' ? -1 : 1;
            if (valA > valB) return sortDirection === 'asc' ? 1 : -1;
            return 0;
        });
    }

    function updateSortIcons() {
        const columns = ['order_number', 'party_name', 'vehicle_number', 'delivery_date', 'total_bags', 'priority', 'stock_status', 'production_status'];
        columns.forEach(col => {
            const el = document.getElementById(`sort-icon-${col}`);
            if (!el) return;
            if (col === sortColumn) {
                el.innerHTML = sortDirection === 'asc' ? ' ↑' : ' ↓';
                el.className = 'text-blue-600 font-bold ml-1';
            } else {
                el.innerHTML = '';
            }
        });
    }

    function canEdit(row) {
        if (['completed', 'cancelled'].includes(row.production_status)) return false;
        if (isAdmin) return true;
        return isMarketing && row.production_status === 'pending';
    }
    function canStart(row) {
        if (row.stock_status === 'available') return false;
        return row.production_status === 'pending' && (isAdmin || isSupervisor);
    }
    function canComplete(row) {
        if (['completed', 'cancelled'].includes(row.production_status)) return false;
        if (row.production_status === 'pending' && row.stock_status === 'available') {
            return isAdmin;
        }
        return row.production_status === 'in_progress' && isAdmin;
    }
    function canCancel(row) {
        if (['completed', 'cancelled'].includes(row.production_status)) return false;
        if (isAdmin) return true;
        return isMarketing && ['pending', 'in_progress'].includes(row.production_status);
    }

    function initFilters() {
        $('#filterSearch, #filterParty, #filterOrderNumber, #filterVehicle, #filterDepartment, #filterStatus, #filterCoupon, #filterDeliveryDate, #filterPriority, #filterStock').on('input change', applyFilters);
        $('[data-quick-filter]').on('click', function () {
            const next = $(this).data('quick-filter');
            activeQuickFilter = activeQuickFilter === next ? null : next;
            $('[data-quick-filter]').removeClass('border-blue-200 bg-blue-50 text-blue-700').addClass('border-slate-200 bg-white text-slate-600');
            if (activeQuickFilter) {
                $(`[data-quick-filter="${activeQuickFilter}"]`).addClass('border-blue-200 bg-blue-50 text-blue-700').removeClass('border-slate-200 bg-white text-slate-600');
            }
            applyFilters();
        });
        $('#resetFilters').on('click', function () {
            $('#filterSearch, #filterOrderNumber, #filterDeliveryDate').val('');
            $('#filterParty, #filterVehicle, #filterDepartment, #filterStatus, #filterCoupon, #filterPriority, #filterStock').val('all');
            activeQuickFilter = null;
            $('[data-quick-filter]').removeClass('border-blue-200 bg-blue-50 text-blue-700').addClass('border-slate-200 bg-white text-slate-600');
            applyFilters();
        });
    }

    function applyFilters() {
        const search = ($('#filterSearch').val() || '').toLowerCase().trim();
        const party = $('#filterParty').val() || 'all';
        const orderNumber = ($('#filterOrderNumber').val() || '').toLowerCase().trim();
        const vehicle = $('#filterVehicle').val() || 'all';
        const department = $('#filterDepartment').val() || 'all';
        const status = $('#filterStatus').val() || 'all';
        const coupon = $('#filterCoupon').val() || 'all';
        const deliveryDate = $('#filterDeliveryDate').val() || '';
        const priority = $('#filterPriority').val() || 'all';
        const stock = $('#filterStock').val() || 'all';

        filteredRows = orderRows.filter(row => {
            const matchesQuick = quickFilterMatches(row);
            return matchesQuick
                && (!search || row.search_text.includes(search))
                && (party === 'all' || row.party_name === party)
                && (!orderNumber || row.order_number.toLowerCase().includes(orderNumber))
                && (vehicle === 'all' || row.vehicle_number === vehicle)
                && (department === 'all' || (row.departments || []).includes(department))
                && (status === 'all' || row.production_status === status)
                && (coupon === 'all' || (coupon === 'none' ? row.coupon_text === 'No Coupon' : row.coupon_text.includes(coupon)))
                && (!deliveryDate || row.delivery_date === deliveryDate)
                && (priority === 'all' || row.priority === priority)
                && (stock === 'all' || row.stock_status === stock);
        });

        applySort();
        currentPage = 1;
        renderMarketingTable();
        updateSortIcons();
        syncMobileCards();
        $('#filterSummary').text(`Showing ${filteredRows.length} of ${orderRows.length} orders`);
    }

    function quickFilterMatches(row) {
        if (!activeQuickFilter) return true;
        if (activeQuickFilter === 'today') return row.delivery_date === todayStr;
        if (activeQuickFilter === 'tomorrow') return row.delivery_date === tomorrowStr;
        if (activeQuickFilter === 'week') return row.delivery_date >= todayStr && row.delivery_date <= weekEndStr;
        if (activeQuickFilter === 'pending') return row.production_status === 'pending';
        if (activeQuickFilter === 'production') return row.production_status === 'in_progress';
        if (activeQuickFilter === 'completed') return row.production_status === 'completed';
        if (activeQuickFilter === 'cancelled') return row.production_status === 'cancelled';
        return true;
    }

    function syncMobileCards() {
        const visibleIds = new Set(filteredRows.map(row => String(row.id)));
        document.querySelectorAll('.mobile-order-card').forEach(card => {
            card.classList.toggle('hidden', !visibleIds.has(String(card.dataset.id)));
        });
    }

    function openOrderDetails(orderId) {
        currentDrawerOrderId = orderId;
        const local = orderRows.find(row => String(row.id) === String(orderId));
        $('#drawerOrderNumber').text(local ? local.order_number : 'Order');
        $('#drawerPartyName').text(local ? local.party_name : 'Loading...');
        $('#drawerLoading').removeClass('hidden');
        $('#drawerContent').addClass('hidden');
        $('#drawerActions').empty();
        $('#marketingOrderDrawer').addClass('is-open').removeClass('pointer-events-none');
        document.body.classList.add('overflow-hidden');

        $.ajax({
            url: `{{ route('marketing.orders.show', ['order' => ':id']) }}`.replace(':id', orderId),
            method: 'GET',
            success: function(res) {
                if (!res.success) return;
                populateDrawer(res.order, local);
            },
            error: function() {
                $('#drawerLoading').html('<p class="text-sm font-bold text-rose-600">Failed to load order details.</p>');
            }
        });
    }

    function closeOrderDrawer() {
        $('#marketingOrderDrawer').removeClass('is-open').addClass('pointer-events-none');
        document.body.classList.remove('overflow-hidden');
    }

    function populateDrawer(order, local) {
        $('#drawerLoading').addClass('hidden');
        $('#drawerContent').removeClass('hidden');
        $('#drawerOrderNumber').text(`Order ${order.order_number}`);
        $('#drawerPartyName').text(order.party_name);
        $('#drawerReadOnly').toggleClass('hidden', !['completed', 'cancelled'].includes(order.status)).toggleClass('inline-flex', ['completed', 'cancelled'].includes(order.status));
        $('#drawerParty').text(order.party_name);
        $('#drawerVehicle').text(order.vehicle_number || 'N/A');
        $('#drawerDelivery').text(order.order_date || 'N/A');
        $('#drawerRemarks').text(order.remarks || 'No remarks provided.');

        const stockClass = order.availability_badge?.class || order.availability || 'unknown';
        const stockLabel = order.availability_badge?.label || stockClass;
        $('#drawerStockBadge').html(badgeHtml('stock', stockClass, stockLabel));

        const products = order.items.map(item => `
            <article class="drawer-product-card rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-wider text-blue-600">${escapeHtml(item.department_code)}</p>
                        <h5 class="mt-1 text-sm font-black text-slate-900">${escapeHtml(item.product_name)}</h5>
                        <p class="mt-1 text-xs font-bold text-slate-500">${escapeHtml(item.packing)}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-black text-slate-950">${Number(item.quantity_bags || 0).toLocaleString()}</p>
                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Bags</p>
                    </div>
                </div>
                <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                    <div class="rounded-xl bg-slate-50 p-2">
                        <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Coupon</p>
                        <p class="mt-1 font-black text-slate-800">${escapeHtml(item.coupon_name || 'No Coupon')}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-2">
                        <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Stock</p>
                        <p class="mt-1 font-black ${item.is_product_available && item.is_coupon_available !== false ? 'text-emerald-700' : 'text-rose-700'}">${item.is_product_available && item.is_coupon_available !== false ? 'Available' : 'Waiting'}</p>
                    </div>
                </div>
            </article>
        `).join('');
        $('#drawerProducts').html(products || '<p class="rounded-2xl border border-dashed border-slate-200 p-5 text-center text-xs font-bold text-slate-400">No products found.</p>');

        const row = local || orderRows.find(item => String(item.id) === String(order.id));
        const isStockAvailable = (order.availability === 'available') || (row && row.stock_status === 'available');
        const step2Label = isStockAvailable ? 'Stock Available' : 'Production';
        const step2Meta = isStockAvailable 
            ? 'Ready in Inventory' 
            : (order.approved_at || (order.status === 'in_progress' ? 'In progress' : 'Waiting'));
        const step2Done = isStockAvailable || ['in_progress', 'completed'].includes(order.status);

        const timeline = [
            ['Created', order.created_by_name || 'Marketing', true],
            [step2Label, step2Meta, step2Done],
            ['Completed', order.completed_at || 'Waiting', order.status === 'completed'],
        ].map(([label, meta, done]) => `
            <div class="flex gap-3">
                <span class="mt-1 h-3 w-3 rounded-full ${done ? 'bg-emerald-500' : 'bg-slate-300'}"></span>
                <div>
                    <p class="text-xs font-black text-slate-900">${label}</p>
                    <p class="text-[11px] font-bold text-slate-500">${escapeHtml(meta)}</p>
                </div>
            </div>
        `).join('');
        $('#drawerTimeline').html(timeline);

        const activity = [
            `Created by ${order.created_by_name || 'N/A'}`,
            order.approved_by_name ? `Approved by ${order.approved_by_name}` : (isStockAvailable ? 'Order ready for delivery' : 'Production approval pending'),
            order.cancel_reason ? `Cancelled: ${order.cancel_reason}` : null,
            order.completed_at ? `Completed at ${order.completed_at}` : null,
        ].filter(Boolean).map(item => `<div class="rounded-2xl border border-slate-100 bg-slate-50 p-3 text-xs font-bold text-slate-600">${escapeHtml(item)}</div>`).join('');
        $('#drawerActivity').html(activity);

        renderDrawerActions(row || { id: order.id, production_status: order.status });
    }

    function renderDrawerActions(row) {
        const buttons = [
            `<button type="button" onclick="openEditModal(${row.id})" class="drawer-action marketing ${canEdit(row) ? '' : 'hidden'}">Edit</button>`,
            `<button type="button" onclick="duplicateOrder(${row.id})" class="drawer-action marketing ${isAdmin || isMarketing ? '' : 'hidden'}">Duplicate</button>`,
            `<button type="button" onclick="printSingleOrder(${row.id})" class="drawer-action marketing">Print</button>`,
            `<button type="button" onclick="setInProgress(${row.id})" class="drawer-action production ${canStart(row) ? '' : 'hidden'}">Start</button>`,
            `<button type="button" onclick="completeOrder(${row.id})" class="drawer-action production ${canComplete(row) ? '' : 'hidden'}">Complete</button>`,
            `<button type="button" onclick="deleteOrder(${row.id})" class="drawer-action delete ${canCancel(row) ? '' : 'hidden'}">Delete</button>`,
        ];
        $('#drawerActions').html(buttons.join(''));
        $('#drawerActions .drawer-action').addClass('min-h-11 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs font-black text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed');
        $('#drawerActions .production').addClass('border-orange-200 bg-orange-50 text-orange-700');
        $('#drawerActions .marketing').addClass('border-blue-200 bg-blue-50 text-blue-700');
        $('#drawerActions .delete').addClass('border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100');
    }

    function exportExcel() {
        const headers = ['Order Number', 'Party Name', 'Department', 'Vehicle Number', 'Delivery Date', 'Items', 'Total Bags', 'Coupon', 'Priority', 'Stock Status', 'Production Status', 'Created By', 'Created Date', 'Last Updated'];
        const rows = filteredRows.map(row => [row.order_number, row.party_name, row.departments_text, row.vehicle_number, row.delivery_date_display, row.items_text, row.total_bags, row.coupon_text, row.priority_label, row.stock_label, row.status_label, row.created_by, row.created_at, row.updated_at]);
        const html = `<table><thead><tr>${headers.map(h => `<th>${escapeHtml(h)}</th>`).join('')}</tr></thead><tbody>${rows.map(row => `<tr>${row.map(cell => `<td>${escapeHtml(cell)}</td>`).join('')}</tr>`).join('')}</tbody></table>`;
        const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `marketing-orders-${todayStr}.xls`;
        link.click();
        URL.revokeObjectURL(link.href);
    }

    function printableHtml(title, rows) {
        return `
            <html><head><title>${escapeHtml(title)}</title>
            <style>body{font-family:Arial,sans-serif;color:#0f172a;padding:24px}table{border-collapse:collapse;width:100%;font-size:11px}th,td{border:1px solid #e2e8f0;padding:8px;text-align:left}th{background:#f8fafc;text-transform:uppercase;font-size:10px}h1{font-size:20px}</style>
            </head><body><h1>${escapeHtml(title)}</h1><table><thead><tr><th>Order</th><th>Party</th><th>Department</th><th>Vehicle</th><th>Date</th><th>Items</th><th>Bags</th><th>Stock</th><th>Status</th></tr></thead><tbody>
            ${rows.map(row => `<tr><td>${escapeHtml(row.order_number)}</td><td>${escapeHtml(row.party_name)}</td><td>${escapeHtml(row.departments_text)}</td><td>${escapeHtml(row.vehicle_number || '-')}</td><td>${escapeHtml(row.delivery_date_display)}</td><td>${escapeHtml(row.items_text)}</td><td>${escapeHtml(row.total_bags)}</td><td>${escapeHtml(row.stock_label)}</td><td>${escapeHtml(row.status_label)}</td></tr>`).join('')}
            </tbody></table></body></html>`;
    }

    function printRows(title, rows) {
        const win = window.open('', '_blank');
        win.document.write(printableHtml(title, rows));
        win.document.close();
        win.focus();
        win.print();
    }
    function exportPdf() {
        printRows('Marketing Orders PDF Export', filteredRows);
    }
    function printOrders() {
        printRows('Marketing Orders Print', filteredRows);
    }
    function printSingleOrder(orderId) {
        const row = orderRows.find(item => String(item.id) === String(orderId));
        if (row) printRows(`Marketing Order ${row.order_number}`, [row]);
    }

    function refreshAvailability() {
        Swal.fire({ title: 'Refreshing stock...', text: 'Verifying finished goods and coupon availability.', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.ajax({
            url: '{{ route("marketing.orders.refresh") }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: res => Swal.fire({ title: 'Refreshed', text: res.message, icon: 'success', timer: 1000, showConfirmButton: false }).then(() => window.location.reload()),
            error: () => Swal.fire('Error', 'Failed to refresh stock status.', 'error')
        });
    }

    function setInProgress(orderId) {
        executeStatusUpdate(orderId, 'in_progress');
    }
    function completeOrder(orderId) {
        Swal.fire({
            title: 'Deduct Stock & Complete?',
            text: 'This will deduct Finished Goods stock using the existing backend.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Complete Order'
        }).then(result => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: `{{ route('marketing.orders.complete', ['order' => ':id']) }}`.replace(':id', orderId),
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: res => Swal.fire('Completed', res.message, 'success').then(() => window.location.reload()),
                error: xhr => Swal.fire('Error', xhr.responseJSON?.message || 'Check finished goods stock availability.', 'error')
            });
        });
    }
    function executeStatusUpdate(orderId, newStatus, cancelReason = null) {
        $.ajax({
            url: `{{ route('marketing.orders.status', ['order' => ':id']) }}`.replace(':id', orderId),
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', status: newStatus, cancel_reason: cancelReason },
            success: res => Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: res.message, showConfirmButton: false, timer: 1500 }).then(() => window.location.reload()),
            error: xhr => Swal.fire('Error', xhr.responseJSON?.message || 'Transaction failed.', 'error')
        });
    }
    function cancelOrder(orderId) {
        Swal.fire({
            title: 'Cancel Order?',
            input: 'text',
            inputPlaceholder: 'Reason...',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Cancel Order'
        }).then(result => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: `{{ route('marketing.orders.destroy', ['order' => ':id']) }}`.replace(':id', orderId),
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}', cancel_reason: result.value },
                success: res => Swal.fire('Cancelled', res.message, 'success').then(() => window.location.reload()),
                error: () => Swal.fire('Error', 'Failed to cancel order.', 'error')
            });
        });
    }

    function deleteOrder(orderId) {
        Swal.fire({
            title: 'Permanently Delete Order?',
            text: 'This will delete the order record.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Delete'
        }).then(result => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: `{{ route('marketing.orders.destroy', ['order' => ':id']) }}`.replace(':id', orderId),
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}', cancel_reason: 'Deleted by Admin' },
                success: res => Swal.fire('Deleted', res.message, 'success').then(() => window.location.reload()),
                error: () => Swal.fire('Error', 'Failed to delete order.', 'error')
            });
        });
    }

    function openCreateModal() {
        $('#orderForm')[0].reset();
        $('#order_id').val('');
        $('#items-container').empty();
        $('#form-error-msg').text('');
        $('#modal-title-main').text('Generate New Order');
        $('#submitFormBtn').text('Create Order');
        itemIndex = 0;
        addItemRow();
        $('#orderFormModal').removeClass('hidden').addClass('flex');
    }
    function closeFormModal() {
        $('#orderFormModal').addClass('hidden').removeClass('flex');
    }
    function openEditModal(orderId) {
        closeOrderDrawer();
        loadOrderIntoForm(orderId, false);
    }
    function duplicateOrder(orderId) {
        closeOrderDrawer();
        loadOrderIntoForm(orderId, true);
    }
    function loadOrderIntoForm(orderId, duplicate) {
        Swal.fire({ title: 'Loading order...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.ajax({
            url: `{{ route('marketing.orders.show', ['order' => ':id']) }}`.replace(':id', orderId),
            method: 'GET',
            success: function(res) {
                Swal.close();
                if (!res.success) return;
                const order = res.order;
                $('#order_id').val(duplicate ? '' : order.id);
                $('#party_name').val(duplicate ? `${order.party_name} Copy` : order.party_name);
                $('#vehicle_number').val(order.vehicle_number || '');
                $('#priority').val(order.priority || 'medium');
                $('#order_date').val(order.order_date || todayStr);
                $('#remarks').val(order.remarks || '');
                $('#items-container').empty();
                itemIndex = 0;
                $('#modal-title-main').text(duplicate ? `Duplicate ${order.order_number}` : `Edit ${order.order_number}`);
                $('#submitFormBtn').text(duplicate ? 'Create Duplicate' : 'Save Changes');
                order.items.forEach(item => {
                    addItemRow({
                        department_code: item.department_code,
                        grade_id: item.department_code === 'TAD' ? adhesivesList.find(a => a.name === item.product_name)?.id : null,
                        color_id: item.department_code === 'GRT' ? groutsList.find(g => g.name === item.product_name)?.id : null,
                        epoxy_product_id: item.department_code === 'EPX' ? epoxiesList.find(e => e.name === item.product_name)?.id : null,
                        quantity_bags: item.quantity_bags,
                        packing: item.packing,
                        coupon_raw_material_id: couponsList.find(c => c.name === item.coupon_name)?.id || null,
                        coupon_quantity: item.coupon_quantity
                    });
                });
                $('#orderFormModal').removeClass('hidden').addClass('flex');
            },
            error: () => Swal.fire('Error', 'Failed to load order data.', 'error')
        });
    }

    function submitOrderForm() {
        const orderId = $('#order_id').val();
        const url = orderId ? `{{ route('marketing.orders.update', ['order' => ':id']) }}`.replace(':id', orderId) : '{{ route("marketing.orders.store") }}';
        const method = orderId ? 'PUT' : 'POST';
        $('#form-error-msg').text('');
        $.ajax({
            url,
            method,
            data: $('#orderForm').serialize(),
            success: function(res) {
                if (res.success) {
                    closeFormModal();
                    Swal.fire({ title: 'Saved', text: res.message, icon: 'success', timer: 1000, showConfirmButton: false }).then(() => window.location.reload());
                } else {
                    $('#form-error-msg').text(res.message || 'Unable to save order.');
                }
            },
            error: xhr => $('#form-error-msg').text(xhr.responseJSON?.message || 'Please verify form fields and try again.')
        });
    }

    function addItemRow(data = null) {
        const index = itemIndex++;
        const rowId = `item-row-${index}`;
        let departmentOptions = `
            <option value="">Select Dept</option>
            <option value="TAD" ${data && data.department_code === 'TAD' ? 'selected' : ''}>Tile Adhesive (TAD)</option>
            <option value="GRT" ${data && data.department_code === 'GRT' ? 'selected' : ''}>Grout (GRT)</option>
            <option value="EPX" ${data && data.department_code === 'EPX' ? 'selected' : ''}>Epoxy (EPX)</option>
        `;
        let couponOptions = '<option value="">No Coupon</option>';
        couponsList.forEach(coupon => {
            const selected = data && data.coupon_raw_material_id == coupon.id ? 'selected' : '';
            couponOptions += `<option value="${coupon.id}" ${selected}>${escapeHtml(coupon.name)} (Stock: ${parseInt(coupon.current_stock)} PCS)</option>`;
        });
        const rowHtml = `
            <div id="${rowId}" class="item-row relative grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 pt-7 md:grid-cols-6 md:pt-4">
                <button type="button" onclick="removeItemRow('${rowId}')" class="absolute right-2 top-2 rounded-xl p-2 text-rose-500 transition hover:bg-rose-50 hover:text-rose-700 md:relative md:right-0 md:top-0 md:col-span-1 md:mt-6 md:flex md:h-10 md:items-center md:justify-center">
                    <i data-lucide="trash" class="h-4 w-4"></i>
                </button>
                <div>
                    <label class="mb-1 block font-extrabold uppercase tracking-wider text-slate-500">Department</label>
                    <select name="items[${index}][department_code]" onchange="onDepartmentChange(this, ${index})" class="item-dept block w-full rounded-xl border border-slate-200 bg-white px-2 py-1.5 text-xs outline-none focus:ring-2 focus:ring-blue-500/20">${departmentOptions}</select>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block font-extrabold uppercase tracking-wider text-slate-500">Product</label>
                    <select name="items[${index}][grade_id]" onchange="onProductChange(this, ${index})" class="item-product block w-full rounded-xl border border-slate-200 bg-white px-2 py-1.5 text-xs outline-none focus:ring-2 focus:ring-blue-500/20"><option value="">Select Department First</option></select>
                    <input type="hidden" name="items[${index}][color_id]" class="item-hidden-color">
                    <input type="hidden" name="items[${index}][epoxy_product_id]" class="item-hidden-epoxy">
                </div>
                <div class="grid grid-cols-2 gap-2 md:col-span-2">
                    <div>
                        <label class="mb-1 block font-extrabold uppercase tracking-wider text-slate-500">Bags</label>
                        <input type="number" name="items[${index}][quantity_bags]" value="${data ? data.quantity_bags : 1}" min="1" oninput="onQtyChange(this, ${index})" class="item-qty block w-full rounded-xl border border-slate-200 bg-white px-2 py-1.5 text-xs outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="mb-1 block font-extrabold uppercase tracking-wider text-slate-500">Packing</label>
                        <select name="items[${index}][packing]" onchange="onPackingChange(this, ${index})" class="item-packing block w-full rounded-xl border border-slate-200 bg-white px-2 py-1.5 text-xs outline-none focus:ring-2 focus:ring-blue-500/20"><option value="">Select Packing</option></select>
                    </div>
                </div>
                <div class="md:col-span-2 md:col-start-2">
                    <label class="mb-1 block font-extrabold uppercase tracking-wider text-slate-500">Coupon</label>
                    <select name="items[${index}][coupon_raw_material_id]" onchange="onCouponChange(this, ${index})" class="item-coupon block w-full rounded-xl border border-slate-200 bg-white px-2 py-1.5 text-xs outline-none focus:ring-2 focus:ring-blue-500/20">${couponOptions}</select>
                    <input type="hidden" name="items[${index}][coupon_quantity]" class="item-coupon-qty" value="${data ? data.coupon_quantity : 1}">
                </div>
                <div class="flex flex-col justify-center border-t border-slate-200 pt-2 md:col-span-3 md:border-l md:border-t-0 md:pl-4 md:pt-0">
                    <p class="text-[10px] font-extrabold uppercase text-slate-400">Stock Availability Check</p>
                    <div class="mt-1 flex flex-wrap items-center gap-3 text-xs">
                        <span class="product-stock-pill inline-flex items-center gap-1 font-bold text-slate-500">Product FG: <strong class="stock-fg-count">0</strong><i class="stock-fg-status h-2 w-2 rounded-full bg-slate-300"></i></span>
                        <span class="coupon-stock-pill hidden items-center gap-1 font-bold text-slate-500">Coupon: <strong class="stock-coupon-count">0</strong><i class="stock-coupon-status h-2 w-2 rounded-full bg-slate-300"></i></span>
                    </div>
                </div>
            </div>
        `;
        $('#items-container').append(rowHtml);
        window.renderHeroicons(document.getElementById(rowId));
        if (data) populateProducts($(`#${rowId}`).find('.item-dept')[0], index, data);
    }
    function removeItemRow(rowId) {
        if ($('.item-row').length <= 1) {
            Swal.fire('Warning', 'An order must contain at least 1 product item.', 'warning');
            return;
        }
        $(`#${rowId}`).remove();
    }
    function onDepartmentChange(selectElement, index) {
        populateProducts(selectElement, index);
    }
    function populateProducts(selectDept, index, data = null) {
        const dept = $(selectDept).val();
        const row = $(selectDept).closest('.item-row');
        const productSelect = row.find('.item-product');
        const packingSelect = row.find('.item-packing');
        productSelect.empty();
        packingSelect.empty();
        row.find('.item-hidden-color, .item-hidden-epoxy').val('');
        if (!dept) {
            productSelect.append('<option value="">Select Department First</option>');
            return;
        }
        const products = dept === 'TAD' ? adhesivesList : (dept === 'GRT' ? groutsList : epoxiesList);
        productSelect.append('<option value="">Select Product</option>');
        products.forEach(p => {
            const productId = p.id;
            const selected = data && ((dept === 'TAD' && data.grade_id == productId) || (dept === 'GRT' && data.color_id == productId) || (dept === 'EPX' && data.epoxy_product_id == productId)) ? 'selected' : '';
            productSelect.append(`<option value="${productId}" ${selected}>${escapeHtml(p.name)} (${escapeHtml(p.code || '')})</option>`);
        });
        if (data) onProductChange(productSelect[0], index, data);
    }
    function onProductChange(selectProduct, index, data = null) {
        const productId = $(selectProduct).val();
        const row = $(selectProduct).closest('.item-row');
        const dept = row.find('.item-dept').val();
        const packingSelect = row.find('.item-packing');
        row.find('.item-hidden-color, .item-hidden-epoxy').val('');
        packingSelect.empty();
        if (!productId) return;
        if (dept === 'GRT') row.find('.item-hidden-color').val(productId);
        if (dept === 'EPX') row.find('.item-hidden-epoxy').val(productId);
        let packingOptions = '';
        if (dept === 'TAD') {
            const product = adhesivesList.find(p => p.id == productId);
            packingOptions = `<option value="${product?.bag_size?.name || '20 KG Bag'}" selected>${product?.bag_size?.name || '20 KG Bag'}</option>`;
        } else if (dept === 'GRT') {
            const product = groutsList.find(p => p.id == productId);
            packingOptions = `<option value="${product?.packing_size || '20 KG'}" selected>${product?.packing_size || '20 KG'}</option>`;
        } else if (dept === 'EPX') {
            packingOptions = '<option value="1 KG" selected>1 KG</option><option value="5 KG">5 KG</option><option value="700 GM">700 GM</option>';
        }
        packingSelect.append(packingOptions);
        if (data?.packing) packingSelect.val(data.packing);
        checkRealtimeStock(row, index);
    }
    function onQtyChange(inputQty, index) {
        const row = $(inputQty).closest('.item-row');
        const qty = parseInt($(inputQty).val()) || 1;
        row.find('.item-coupon-qty').val(qty);
        checkRealtimeStock(row, index);
    }
    function onPackingChange(selectPacking, index) {
        checkRealtimeStock($(selectPacking).closest('.item-row'), index);
    }
    function onCouponChange(selectCoupon, index) {
        checkRealtimeStock($(selectCoupon).closest('.item-row'), index);
    }
    function checkRealtimeStock(row, index) {
        const dept = row.find('.item-dept').val();
        const productId = row.find('.item-product').val();
        const packing = row.find('.item-packing').val();
        const quantity = parseInt(row.find('.item-qty').val()) || 0;
        const couponId = row.find('.item-coupon').val();
        if (!dept || !productId || quantity <= 0) return;
        $.ajax({
            url: '{{ route("marketing.api.product_stock") }}',
            method: 'GET',
            data: { department_code: dept, product_id: productId, packing, coupon_raw_material_id: couponId || null },
            success: function(res) {
                if (!res.success) return;
                const fgStock = res.stock.available_bags;
                const fgStatusPill = row.find('.stock-fg-status');
                row.find('.stock-fg-count').text(fgStock);
                if (fgStock >= quantity) {
                    fgStatusPill.removeClass('bg-rose-500 bg-slate-300').addClass('bg-emerald-500');
                    row.find('.product-stock-pill').removeClass('text-rose-600').addClass('text-emerald-700');
                } else {
                    fgStatusPill.removeClass('bg-emerald-500 bg-slate-300').addClass('bg-rose-500');
                    row.find('.product-stock-pill').removeClass('text-emerald-700').addClass('text-rose-600');
                }
            }
        });
        const couponPill = row.find('.coupon-stock-pill');
        if (couponId) {
            const coupon = couponsList.find(c => c.id == couponId);
            const couponStock = coupon ? parseInt(coupon.current_stock) : 0;
            const couponStatusPill = row.find('.stock-coupon-status');
            row.find('.stock-coupon-count').text(couponStock);
            couponPill.removeClass('hidden').addClass('inline-flex');
            if (couponStock >= quantity) {
                couponStatusPill.removeClass('bg-rose-500 bg-slate-300').addClass('bg-emerald-500');
                couponPill.removeClass('text-rose-600').addClass('text-emerald-700');
            } else {
                couponStatusPill.removeClass('bg-emerald-500 bg-slate-300').addClass('bg-rose-500');
                couponPill.removeClass('text-emerald-700').addClass('text-rose-600');
            }
        } else {
            couponPill.addClass('hidden').removeClass('inline-flex');
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeOrderDrawer();
            closeFormModal();
        }
    });
</script>
@endsection
