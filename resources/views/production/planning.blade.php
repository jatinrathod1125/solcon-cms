@extends('layouts.app')

@section('title', 'Production Planning')
@section('header-title', 'Production Planning')

@section('content')
<div class="mx-auto max-w-[1600px] space-y-4">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <p class="text-xs font-semibold text-slate-400">Real-time demand planning, stock coverage, and production requirement calculation.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('production.create') }}" class="erp-button bg-blue-600 text-white hover:bg-blue-500">
                <i data-lucide="play" class="w-4 h-4"></i>Start Production Batch
            </a>
        </div>
    </div>

    <!-- Permanent Sleek Tab Navigation Bar -->
    <div class="bg-white border border-slate-200 rounded-2xl p-2 shadow-sm flex flex-wrap sm:flex-nowrap gap-2 text-xs">
        <button onclick="switchTab('without_coupon')" id="btnTab-without_coupon" class="tab-btn flex-1 py-2.5 px-4 rounded-xl font-bold transition flex items-center justify-center gap-2 bg-blue-600 text-white shadow-sm">
            <i data-lucide="box" class="w-4 h-4"></i>
            1. Without Coupon
            <span class="px-2 py-0.5 rounded-full text-[10px] bg-white/20 font-mono">
                {{ $tab1Data->count() }} Grades
            </span>
        </button>

        <button onclick="switchTab('coupon_20')" id="btnTab-coupon_20" class="tab-btn flex-1 py-2.5 px-4 rounded-xl font-bold transition flex items-center justify-center gap-2 text-slate-600 hover:bg-slate-50">
            <i data-lucide="ticket" class="w-4 h-4 text-emerald-600"></i>
            2. 20 Rs Coupon
            <span class="px-2 py-0.5 rounded-full text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200/60 font-mono">
                F-101 & F-107 Only
            </span>
        </button>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: WITHOUT COUPON -->
    <!-- ========================================== -->
    <div id="tabContent-without_coupon" class="tab-content transition-all duration-300">
        <article class="erp-card overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Without Coupon Demand & Stock</span>
                <span class="text-[10px] font-mono text-slate-400">Formula: Need Production = Max(Pending Orders - Available Stock, 0)</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-450 uppercase font-extrabold tracking-wider text-[9px]">
                            <th class="px-5 py-3">Grade</th>
                            <th class="px-5 py-3 text-right">Available Stock</th>
                            <th class="px-5 py-3 text-right">Pending Orders</th>
                            <th class="px-5 py-3 text-right">Need Production</th>
                            <th class="px-5 py-3 text-center">Status</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-750">
                        @forelse($tab1Data as $row)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-3 text-slate-900 font-bold">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span>{{ $row['grade_name'] }}</span>
                                        @if(!empty($row['brand_name']))
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                {{ $row['brand_name'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-slate-400 font-semibold font-mono text-[10px]">({{ $row['grade_code'] }})</span>
                                </td>

                                <td class="px-5 py-3 text-right font-mono font-bold text-slate-800">
                                    {{ format_quantity($row['available_stock']) }} <span class="text-[10px] text-slate-400 font-normal">Bags</span>
                                </td>

                                <td class="px-5 py-3 text-right font-mono font-bold text-blue-600">
                                    {{ format_quantity($row['pending_orders']) }} <span class="text-[10px] text-blue-400 font-normal">Bags</span>
                                </td>

                                <td class="px-5 py-3 text-right font-mono font-bold {{ $row['need_production'] > 0 ? 'text-amber-600' : 'text-slate-400' }}">
                                    {{ format_quantity($row['need_production']) }} <span class="text-[10px] font-normal">Bags</span>
                                </td>

                                <td class="px-5 py-3 text-center">
                                    @if($row['need_production'] == 0)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                                            Enough Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-amber-600"></i>
                                            Production Required
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-3 text-right space-x-1.5">
                                    <button onclick="openOrdersDrawer({{ $row['grade_id'] }}, 'without_coupon')" class="erp-button border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs">
                                        <i data-lucide="eye" class="w-3.5 h-3.5 text-blue-600"></i>
                                        View Orders
                                    </button>
                                    @if($row['need_production'] > 0)
                                        <a href="{{ route('production.create', ['grade_id' => $row['grade_id']]) }}" class="erp-button bg-blue-600 text-white hover:bg-blue-500 text-xs">
                                            <i data-lucide="play" class="w-3.5 h-3.5"></i>
                                            Start Batch
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-slate-400 font-semibold">
                                    No adhesive grades found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </div>

    <!-- ========================================== -->
    <!-- TAB 2: 20 RS COUPON -->
    <!-- ========================================== -->
    <div id="tabContent-coupon_20" class="tab-content hidden transition-all duration-300">
        <article class="erp-card overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-emerald-50/20 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">20 Rs Coupon Demand & Stock (F-101 & F-107 Only)</span>
                <span class="text-[10px] font-mono text-emerald-700 font-semibold">Strictly restricted to F-101 and F-107 grades</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-450 uppercase font-extrabold tracking-wider text-[9px]">
                            <th class="px-5 py-3">Grade</th>
                            <th class="px-5 py-3 text-right">Available Stock</th>
                            <th class="px-5 py-3 text-right">Pending Orders</th>
                            <th class="px-5 py-3 text-right">Need Production</th>
                            <th class="px-5 py-3 text-center">Status</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-750">
                        @forelse($tab2Data as $row)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-3 text-slate-900 font-bold">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span>{{ $row['grade_name'] }}</span>
                                        @if(!empty($row['brand_name']))
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                {{ $row['brand_name'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-emerald-600 font-semibold font-mono text-[10px]">(₹20 Coupon)</span>
                                </td>

                                <td class="px-5 py-3 text-right font-mono font-bold text-slate-800">
                                    {{ format_quantity($row['available_stock']) }} <span class="text-[10px] text-slate-400 font-normal">Bags</span>
                                </td>

                                <td class="px-5 py-3 text-right font-mono font-bold text-blue-600">
                                    {{ format_quantity($row['pending_orders']) }} <span class="text-[10px] text-blue-400 font-normal">Bags</span>
                                </td>

                                <td class="px-5 py-3 text-right font-mono font-bold {{ $row['need_production'] > 0 ? 'text-amber-600' : 'text-slate-400' }}">
                                    {{ format_quantity($row['need_production']) }} <span class="text-[10px] font-normal">Bags</span>
                                </td>

                                <td class="px-5 py-3 text-center">
                                    @if($row['need_production'] == 0)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                                            Enough Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-amber-600"></i>
                                            Production Required
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-3 text-right space-x-1.5">
                                    <button onclick="openOrdersDrawer({{ $row['grade_id'] }}, 'coupon_20')" class="erp-button border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs">
                                        <i data-lucide="eye" class="w-3.5 h-3.5 text-blue-600"></i>
                                        View Orders
                                    </button>
                                    @if($row['need_production'] > 0)
                                        <a href="{{ route('production.create', ['grade_id' => $row['grade_id']]) }}" class="erp-button bg-blue-600 text-white hover:bg-blue-500 text-xs">
                                            <i data-lucide="play" class="w-3.5 h-3.5"></i>
                                            Start Batch
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-slate-400 font-semibold">
                                    No 20 Rs coupon grades configured.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </div>

</div>

<!-- ========================================== -->
<!-- RIGHT-SIDE SLIDE-OVER DRAWER (VIEW ORDERS) -->
<!-- ========================================== -->
<div id="drawerOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300" onclick="closeOrdersDrawer()"></div>

<div id="ordersDrawer" class="fixed inset-y-0 right-0 max-w-2xl w-full bg-white border-l border-slate-200 shadow-2xl z-50 transform translate-x-full transition-transform duration-300 flex flex-col">
    <!-- Drawer Header -->
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
        <div>
            <span id="drawerCategoryBadge" class="inline-block px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                Approved Pending Orders
            </span>
            <h3 id="drawerGradeTitle" class="text-sm font-extrabold text-slate-900 mt-1">Grade Pending Orders</h3>
        </div>
        <button onclick="closeOrdersDrawer()" class="p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-200 transition">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Drawer Content / Orders Table -->
    <div class="p-6 flex-1 overflow-y-auto space-y-4">
        <div id="drawerLoading" class="py-12 text-center text-slate-400 space-y-2">
            <i data-lucide="loader-2" class="w-7 h-7 animate-spin mx-auto text-blue-600"></i>
            <p class="text-xs font-semibold">Loading pending orders...</p>
        </div>

        <div id="drawerContent" class="hidden">
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-450 uppercase font-extrabold tracking-wider text-[9px]">
                            <th class="px-4 py-3">Order Number</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Grade</th>
                            <th class="px-4 py-3 text-right">Quantity</th>
                            <th class="px-4 py-3">Dispatch Date</th>
                            <th class="px-4 py-3 text-right">Pending Bags</th>
                        </tr>
                    </thead>
                    <tbody id="drawerOrdersTable" class="divide-y divide-slate-100 font-medium text-slate-750">
                        <!-- Dynamically populated -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Drawer Footer -->
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
        <button type="button" onclick="closeOrdersDrawer()" class="erp-button border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs">
            Close
        </button>
        <a id="drawerStartBatchBtn" href="{{ route('production.create') }}" class="erp-button bg-blue-600 text-white hover:bg-blue-500 text-xs">
            <i data-lucide="play" class="w-4 h-4"></i>
            Start Batch Production
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentTab = '{{ $activeTab }}';

    function switchTab(tabKey) {
        currentTab = tabKey;
        $('.tab-btn').removeClass('bg-blue-600 text-white shadow-sm').addClass('text-slate-600 hover:bg-slate-50');
        $(`#btnTab-${tabKey}`).addClass('bg-blue-600 text-white shadow-sm').removeClass('text-slate-600 hover:bg-slate-50');
        
        $('.tab-content').addClass('hidden');
        $(`#tabContent-${tabKey}`).removeClass('hidden');

        if (history.pushState) {
            const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + tabKey;
            window.history.pushState({path: newUrl}, '', newUrl);
        }
    }

    function openOrdersDrawer(gradeId, category, customerName = '') {
        $('#drawerOverlay').removeClass('hidden');
        setTimeout(() => {
            $('#drawerOverlay').removeClass('opacity-0');
            $('#ordersDrawer').removeClass('translate-x-full');
        }, 10);

        $('#drawerLoading').removeClass('hidden');
        $('#drawerContent').addClass('hidden');

        $.ajax({
            url: "{{ route('production.planning.orders') }}",
            type: 'GET',
            data: {
                grade_id: gradeId,
                category: category,
                customer_name: customerName
            },
            success: function(res) {
                $('#drawerLoading').addClass('hidden');
                $('#drawerContent').removeClass('hidden');

                $('#drawerGradeTitle').text(res.grade + (res.customer_name ? ' - ' + res.customer_name : ''));
                $('#drawerCategoryBadge').text(res.category);
                $('#drawerStartBatchBtn').attr('href', "{{ route('production.create') }}?grade_id=" + gradeId);

                let rowsHtml = '';
                if (res.orders && res.orders.length > 0) {
                    res.orders.forEach(function(o) {
                        rowsHtml += `
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-4 py-3 font-mono font-bold text-slate-900">${o.order_number}</td>
                                <td class="px-4 py-3 font-bold text-slate-900">${o.customer}</td>
                                <td class="px-4 py-3 text-slate-600 font-mono">${o.grade}</td>
                                <td class="px-4 py-3 text-right font-mono font-bold text-slate-900">${o.quantity}</td>
                                <td class="px-4 py-3 font-mono text-slate-400 text-[11px]">${o.dispatch_date}</td>
                                <td class="px-4 py-3 text-right font-mono font-bold text-amber-600">${o.pending_bags}</td>
                            </tr>
                        `;
                    });
                } else {
                    rowsHtml = `
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400 font-semibold">
                                No approved pending orders found for this selection.
                            </td>
                        </tr>
                    `;
                }
                $('#drawerOrdersTable').html(rowsHtml);
            },
            error: function() {
                $('#drawerLoading').addClass('hidden');
                alert('Could not fetch pending order details.');
            }
        });
    }

    function closeOrdersDrawer() {
        $('#ordersDrawer').addClass('translate-x-full');
        $('#drawerOverlay').addClass('opacity-0');
        setTimeout(() => {
            $('#drawerOverlay').addClass('hidden');
        }, 300);
    }

    $(function() {
        switchTab(currentTab);
    });
</script>
@endsection
