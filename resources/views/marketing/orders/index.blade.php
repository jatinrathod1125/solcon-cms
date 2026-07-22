@extends('layouts.app')

@section('title', 'Marketing Orders')
@section('header-title', 'Marketing Orders')

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
    .availability-badge,
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

    .availability-available { background: #ecfdf5; color: #047857; }
    .availability-partial { background: #fffbeb; color: #a16207; }
    .availability-unavailable { background: #fef2f2; color: #dc2626; }
    .availability-unknown { background: #f1f5f9; color: #475569; }

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

        .marketing-orders-page .marketing-orders-table tr {
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
        'all' => ['label' => 'All Orders', 'count' => $orders->count()],
        'pending' => ['label' => 'Pending', 'count' => $orders->where('status', 'pending')->count()],
        'in_progress' => ['label' => 'In Progress', 'count' => $orders->where('status', 'in_progress')->count()],
        'completed' => ['label' => 'Completed', 'count' => $orders->where('status', 'completed')->count()],
        'cancelled' => ['label' => 'Cancelled', 'count' => $orders->where('status', 'cancelled')->count()],
    ];
    $availableCount = $orders->filter(fn ($order) => ($order->availability_badge['class'] ?? 'unknown') === 'available')->count();
    $totalItems = $orders->sum(fn ($order) => $order->items->count());
@endphp

<div class="marketing-orders-page mx-auto max-w-[1700px] space-y-5">
    <section class="page-card bg-white border border-slate-200 p-5 shadow-sm space-y-5">
        <header class="flex flex-col gap-4 border-b border-slate-100 pb-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <i data-lucide="clipboard-list" class="h-5 w-5"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black tracking-tight text-slate-950">Orders List</h2>
                    <p class="mt-1 text-sm font-semibold text-slate-500">Search, review, edit, and track marketing orders from one clean board.</p>
                </div>
            </div>

            <div class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">
                <label class="relative block min-w-0 flex-1 sm:w-80">
                    <span class="sr-only">Search orders</span>
                    <i data-lucide="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input id="marketingOrderSearch" type="search" placeholder="Search order, party, city..."
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm font-bold text-slate-900 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100">
                </label>

                <a href="{{ route('marketing.orders.create') }}"
                    class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-blue-600 px-5 text-sm font-extrabold text-white shadow-sm shadow-blue-600/20 transition hover:bg-blue-700">
                    <i data-lucide="plus-circle" class="h-5 w-5"></i>
                    <span>Create Order</span>
                </a>
            </div>
        </header>

        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
            <div class="order-metric">
                <span>Total Orders</span>
                <strong>{{ $orders->count() }}</strong>
            </div>
            <div class="order-metric">
                <span>Pending</span>
                <strong>{{ $statusTabs['pending']['count'] }}</strong>
            </div>
            <div class="order-metric">
                <span>Available</span>
                <strong>{{ $availableCount }}</strong>
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
                            <th>City / Vehicle</th>
                            <th>Coupon</th>
                            <th class="text-center">Items</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Stock</th>
                            <th>Created By</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($orders as $order)
                            @php
                                $statusInfo = $order->status_info;
                                $priority = $order->priority ?: 'medium';
                                $availability = $order->availability_badge;
                                $cList = $order->items->map(fn($item) => $item->coupon_name)->filter(fn($c) => $c && $c !== 'No Coupon' && $c !== 'N/A')->unique()->implode(', ');
                                $searchText = strtolower($order->order_number . ' ' . $order->party_name . ' ' . ($order->city ?: '') . ' ' . ($order->vehicle_number ?: '') . ' ' . $cList . ' ' . ($order->creator->name ?? '') . ' ' . $order->status . ' ' . $priority);
                            @endphp
                            <tr class="order-row transition hover:bg-slate-50/70" data-status="{{ $order->status }}" data-search="{{ $searchText }}">
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
                                <td data-label="City / Vehicle" class="font-bold text-slate-600">{{ $order->city ?: ($order->vehicle_number ?: 'N/A') }}</td>
                                <td data-label="Coupon" class="font-bold text-slate-700">
                                    @if($cList)
                                        <span class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-0.5 text-xs font-bold text-amber-700 border border-amber-200">
                                            <i data-lucide="tag" class="h-3 w-3"></i> {{ $cList }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 font-normal">None</span>
                                    @endif
                                </td>
                                <td data-label="Items" class="text-center font-black text-slate-900">{{ $order->items->count() }}</td>
                                <td data-label="Priority">
                                    <span class="priority-badge priority-{{ $priority }}">{{ ucfirst($priority) }}</span>
                                </td>
                                <td data-label="Status">
                                    <span class="status-badge border"
                                        style="background-color: {{ $statusInfo['bg'] }}; color: {{ $statusInfo['color'] }}; border-color: {{ $statusInfo['color'] }}40;">
                                        {{ $statusInfo['label'] }}
                                    </span>
                                </td>
                                <td data-label="Stock">
                                    <span class="availability-badge availability-{{ $availability['class'] ?? 'unknown' }}">{{ $availability['label'] ?? 'Unknown' }}</span>
                                </td>
                                <td data-label="Created By" class="font-bold text-slate-600">{{ $order->creator->name ?? 'System' }}</td>
                                <td data-label="Actions" class="text-right">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <a href="{{ route('marketing.orders.show', $order->id) }}" class="order-action-button hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700" title="View details">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                        @if(auth()->user()->isAdmin() && $order->status === 'pending')
                                            <button type="button" onclick="approveOrder({{ $order->id }})" class="order-action-button hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700" title="Approve order" style="border-color:#bbf7d0;background:#f0fdf4;color:#16a34a;">
                                                <i data-lucide="check-circle" class="h-4 w-4"></i>
                                            </button>
                                        @endif
                                        @if($order->status === 'pending' || auth()->user()->isAdmin())
                                            <a href="{{ route('marketing.orders.edit', $order->id) }}" class="order-action-button hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700" title="Edit order">
                                                <i data-lucide="edit" class="h-4 w-4"></i>
                                            </a>
                                            <button type="button" onclick="confirmDeleteOrder({{ $order->id }})" class="order-action-button hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700" title="Delete/cancel order">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="p-10 text-center">
                                    <div class="mx-auto max-w-sm">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                            <i data-lucide="clipboard-list" class="h-6 w-6"></i>
                                        </div>
                                        <p class="mt-3 text-base font-black text-slate-800">No marketing orders found</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-500">Create the first order to start tracking dispatch work.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        @if($orders->isNotEmpty())
                            <tr id="emptyFilteredRow" class="hidden">
                                <td colspan="10" class="p-10 text-center text-sm font-bold text-slate-500">
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
        var $rows = $('.order-row');
        var $search = $('#marketingOrderSearch');
        var $visibleCount = $('#ordersVisibleCount');
        var $emptyFilteredRow = $('#emptyFilteredRow');

        function applyOrderFilters() {
            var query = ($search.val() || '').toLowerCase().trim();
            var status = $('.status-tab.active').data('status') || 'all';
            var visible = 0;

            $rows.each(function() {
                var $row = $(this);
                var matchesStatus = status === 'all' || $row.data('status') === status;
                var matchesSearch = !query || String($row.data('search')).indexOf(query) !== -1;
                var showRow = matchesStatus && matchesSearch;

                $row.toggle(showRow);
                if (showRow) {
                    visible += 1;
                }
            });

            $visibleCount.text(visible);
            if ($emptyFilteredRow.length) {
                $emptyFilteredRow.toggleClass('hidden', visible !== 0);
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

        applyOrderFilters();
    });

    function confirmDeleteOrder(orderId) {
        Swal.fire({
            title: 'Cancel this order?',
            text: 'This will move the order out of active work.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Yes, cancel it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/marketing/orders/' + orderId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Cancelled!',
                                'The order has been cancelled successfully.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Failed to cancel order.', 'error');
                        }
                    },
                    error: function(xhr) {
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Server error occurred.';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    }
    function approveOrder(orderId) {
        Swal.fire({
            title: 'Approve this order?',
            text: 'This will notify supervisors and make the order visible to them.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Yes, approve it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/marketing/orders/' + orderId + '/approve',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Approved!',
                                response.message || 'The order has been approved.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Failed to approve order.', 'error');
                        }
                    },
                    error: function(xhr) {
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Server error occurred.';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    }
</script>
@endsection
