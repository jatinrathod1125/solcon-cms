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
        width: 40px;
    }

    .order-action-button:hover {
        background: #f8fafc;
        color: #0f172a;
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
            border-radius: 16px !important;
            padding: 10px !important;
        }

        .marketing-orders-page .marketing-orders-table td {
            align-items: flex-start !important;
            font-size: 13px !important;
            padding: 10px 8px !important;
            text-align: right !important;
        }

        .marketing-orders-page .marketing-orders-table td::before {
            min-width: 92px;
        }

        .marketing-orders-page .order-action-button {
            height: 42px;
            width: 42px;
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
                                    <div class="flex items-center justify-end gap-2">
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
                                        <button type="button" class="expand-btn order-action-button hover:border-slate-300 hover:bg-white hover:text-slate-900 shadow-sm" data-order-id="{{ $order->id }}" title="View items">
                                            <i data-lucide="chevron-down" class="h-4 w-4 chevron-icon"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr id="sub-row-{{ $order->id }}" class="sub-row hidden bg-slate-50 border-b border-slate-100" data-status="{{ $order->status }}" data-search="{{ $searchText }}">
                                <td colspan="8" class="!p-4 sm:!p-6">
                                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                        <h4 class="mb-3 text-xs font-black uppercase tracking-wider text-slate-500">Ordered Items</h4>
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-left text-sm">
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
                                                                @if($item->coupon_quantity)
                                                                <span class="text-slate-400 text-xs">(x{{ $item->coupon_quantity }})</span>
                                                                @endif
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
                                        <p class="mt-3 text-base font-black text-slate-800">No approved orders found</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-500">When marketing orders are approved, they will appear here.</p>
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
        var $subRows = $('.sub-row');
        var $search = $('#marketingOrderSearch');
        var $visibleCount = $('#ordersVisibleCount');
        var $emptyFilteredRow = $('#emptyFilteredRow');

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
                
                // Hide sub-rows when filtering to prevent visual mess, 
                // but if we wanted to preserve them, we could check if they were already visible
                if (!showRow) {
                    $subRow.hide();
                    $row.find('.chevron-icon').attr('data-lucide', 'chevron-down');
                }
                
                if (showRow) {
                    visible += 1;
                }
            });

            // Re-render icons after changing attr
            if (window.renderHeroicons) {
                window.renderHeroicons();
            }

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

        $('.expand-btn').on('click', function() {
            var orderId = $(this).data('order-id');
            var $subRow = $('#sub-row-' + orderId);
            var $icon = $(this).find('.chevron-icon');
            
            $subRow.toggleClass('hidden');
            
            if ($subRow.hasClass('hidden')) {
                $icon.attr('data-lucide', 'chevron-down');
            } else {
                $icon.attr('data-lucide', 'chevron-up');
            }
            
            if (window.renderHeroicons) {
                window.renderHeroicons(this);
            }
        });

        applyOrderFilters();
    });
</script>
@endsection
