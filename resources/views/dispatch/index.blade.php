@extends('layouts.app')

@section('title', 'Dispatch Management')
@section('header-title', 'Dispatch Management')

@section('styles')
<style>
    .dispatch-page {
        --dispatch-blue: #2563eb;
        --dispatch-ink: #0f172a;
        --dispatch-border: #e2e8f0;
    }

    .dispatch-page .page-card {
        border-radius: 22px;
    }

    .dispatch-metric {
        border: 1px solid var(--dispatch-border);
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        padding: 16px;
    }

    .dispatch-metric span {
        color: #64748b;
        display: block;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.08em;
        line-height: 1.2;
        text-transform: uppercase;
    }

    .dispatch-metric strong {
        color: var(--dispatch-ink);
        display: block;
        font-size: 26px;
        font-weight: 900;
        line-height: 1.1;
        margin-top: 8px;
    }

    .dispatch-table th {
        font-size: 11px !important;
        padding: 14px 16px !important;
    }

    .dispatch-table td {
        font-size: 13px !important;
        padding: 14px 16px !important;
        vertical-align: middle;
    }

    .status-badge, .type-badge, .release-badge, .payment-badge {
        align-items: center;
        border-radius: 999px;
        display: inline-flex;
        font-size: 12px;
        font-weight: 850;
        line-height: 1;
        min-height: 30px;
        padding: 6px 12px;
        white-space: nowrap;
    }

    .dispatch-action-button {
        align-items: center;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        color: #64748b;
        display: inline-flex;
        height: 38px;
        justify-content: center;
        transition: all 0.18s ease;
        width: 38px;
    }

    .dispatch-action-button:hover {
        background: #f8fafc;
        color: #0f172a;
    }
</style>
@endsection

@section('content')
<div class="dispatch-page space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900">Dispatch Management</h1>
            <p class="text-xs font-bold text-slate-500 mt-1">Manage dispatch planning, truck loading, release statuses, and payments.</p>
        </div>
        @if(auth()->user()->isAdmin() || auth()->user()->isMarketing())
        <div class="flex items-center gap-3">
            <a href="{{ route('dispatch.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-extrabold text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition">
                <i data-lucide="plus-circle" class="h-4 w-4"></i>
                Create Dispatch
            </a>
        </div>
        @endif
    </div>

    <!-- Dashboard Metrics Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="dispatch-metric">
            <span>Today's Dispatches</span>
            <strong>{{ $metrics['todays_count'] ?? 0 }}</strong>
        </div>
        <div class="dispatch-metric">
            <span>Pending Loading</span>
            <strong class="text-amber-600">{{ $metrics['pending_loading'] ?? 0 }}</strong>
        </div>
        <div class="dispatch-metric">
            <span>Waiting Truck</span>
            <strong class="text-purple-600">{{ $metrics['waiting_truck'] ?? 0 }}</strong>
        </div>
        <div class="dispatch-metric">
            <span>Completed</span>
            <strong class="text-emerald-600">{{ $metrics['completed'] ?? 0 }}</strong>
        </div>
    </div>

    <!-- Main List Card -->
    <div class="page-card bg-white p-6 shadow-sm border border-slate-200">
        
        <!-- Filter Form Bar -->
        <form method="GET" action="{{ route('dispatch.index') }}" class="mb-6 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                
                <!-- Search Input -->
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Dispatch No, Customer, Vehicle..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2 text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                </div>

                <!-- Dispatch Type Filter -->
                <div>
                    <select name="dispatch_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <option value="">All Types</option>
                        <option value="factory_pickup" {{ request('dispatch_type') === 'factory_pickup' ? 'selected' : '' }}>Factory Pickup</option>
                        <option value="crossing_delivery" {{ request('dispatch_type') === 'crossing_delivery' ? 'selected' : '' }}>Crossing Delivery</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <option value="all">All Statuses</option>
                        <option value="planned" {{ request('status') === 'planned' ? 'selected' : '' }}>Planned</option>
                        <option value="waiting_for_truck" {{ request('status') === 'waiting_for_truck' ? 'selected' : '' }}>Waiting Truck</option>
                        <option value="truck_arrived" {{ request('status') === 'truck_arrived' ? 'selected' : '' }}>Truck Arrived</option>
                        <option value="loading" {{ request('status') === 'loading' ? 'selected' : '' }}>Loading</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Release Status Filter -->
                <div>
                    <select name="is_released" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <option value="">All Release Status</option>
                        <option value="1" {{ request('is_released') === '1' ? 'selected' : '' }}>Released</option>
                        <option value="0" {{ request('is_released') === '0' ? 'selected' : '' }}>Hold (Unreleased)</option>
                    </select>
                </div>

                <!-- Action Filter Buttons -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white rounded-xl py-2 px-4 text-xs font-extrabold transition">
                        Filter
                    </button>
                    <a href="{{ route('dispatch.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl py-2 px-3 text-xs font-extrabold transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="dispatch-table w-full text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase font-extrabold tracking-wider border-b border-slate-200">
                    <tr>
                        <th>Dispatch No</th>
                        <th>Type</th>
                        <th>Customer / City</th>
                        <th>Vehicle / Driver</th>
                        <th class="text-center">Total Units</th>
                        <th class="text-right">Total Weight</th>
                        <th class="text-right">Total Ton</th>
                        <th class="text-center">Payment Req.</th>
                        <th>Release</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dispatches as $dispatch)
                        @php
                            $statusInfo = $dispatch->status_info;
                            $typeInfo = $dispatch->type_info;
                        @endphp
                        <tr class="transition hover:bg-slate-50/70">
                            <!-- Dispatch No -->
                            <td class="font-mono text-sm font-black text-blue-700">
                                <a href="{{ route('dispatch.show', $dispatch->id) }}" class="hover:underline">
                                    {{ $dispatch->dispatch_number }}
                                </a>
                                <span class="block text-[10px] text-slate-400 font-normal">
                                    {{ $dispatch->created_at->format('d/m/Y h:i A') }}
                                </span>
                            </td>

                            <!-- Type -->
                            <td>
                                <span class="type-badge" style="background-color: {{ $typeInfo['badge_bg'] }}; color: {{ $typeInfo['badge_color'] }};">
                                    {{ $typeInfo['label'] }}
                                </span>
                            </td>

                            <!-- Customer / City -->
                            <td>
                                <span class="font-extrabold text-slate-900 block">{{ $dispatch->party_name }}</span>
                                @if($dispatch->city)
                                    <span class="text-[11px] font-bold text-slate-500 block mt-0.5">📍 {{ $dispatch->city }}</span>
                                @endif
                            </td>

                            <!-- Vehicle & Driver -->
                            <td>
                                <div class="font-bold text-slate-800 text-xs">🚛 {{ $dispatch->vehicle_number ?: 'N/A' }}</div>
                                <div class="text-[11px] font-bold text-slate-500">📞 {{ $dispatch->driver_mobile ?: '-' }}</div>
                            </td>

                            <!-- Total Units -->
                            <td class="text-center">
                                <span class="font-black text-slate-900 text-sm">{{ $dispatch->total_bags }}</span>
                            </td>

                            <!-- Total Weight (KG) -->
                            <td class="text-right">
                                <span class="font-bold text-slate-800">{{ number_format($dispatch->total_weight, 1) }} KG</span>
                            </td>

                            <!-- Total Ton -->
                            <td class="text-right">
                                <span class="font-black text-emerald-600 text-sm">{{ number_format($dispatch->total_tons, 2) }} Ton</span>
                            </td>

                            <!-- Payment Required -->
                            <td class="text-center">
                                @if($dispatch->payment_required)
                                    <span class="payment-badge bg-rose-50 text-rose-700 border border-rose-200">
                                        💳 Yes
                                    </span>
                                @else
                                    <span class="payment-badge bg-slate-100 text-slate-500 border border-slate-200">
                                        No
                                    </span>
                                @endif
                            </td>

                            <!-- Release -->
                            <td>
                                @if($dispatch->is_released)
                                    <span class="release-badge bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i data-lucide="check-circle" class="h-3 w-3 mr-1"></i> Released
                                    </span>
                                @else
                                    <span class="release-badge bg-rose-50 text-rose-700 border border-rose-200">
                                        <i data-lucide="lock" class="h-3 w-3 mr-1"></i> Hold
                                    </span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td>
                                <span class="status-badge border" style="background-color: {{ $statusInfo['bg'] }}; color: {{ $statusInfo['color'] }}; border-color: {{ $statusInfo['color'] }}40;">
                                    <i data-lucide="{{ $statusInfo['icon'] }}" class="h-3.5 w-3.5 mr-1"></i>
                                    {{ $statusInfo['label'] }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="text-right">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    
                                    <!-- View Details -->
                                    <a href="{{ route('dispatch.show', $dispatch->id) }}" class="dispatch-action-button hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700" title="View details">
                                        <i data-lucide="eye" class="h-4 w-4"></i>
                                    </a>

                                    <!-- Loading Screen -->
                                    <a href="{{ route('dispatch.loading', $dispatch->id) }}" class="dispatch-action-button hover:border-purple-200 hover:bg-purple-50 hover:text-purple-700" title="Loading Screen">
                                        <i data-lucide="package-check" class="h-4 w-4"></i>
                                    </a>

                                    <!-- Toggle Release (Marketing/Admin) -->
                                    @if(auth()->user()->isAdmin() || auth()->user()->isMarketing())
                                        <button type="button" onclick="toggleRelease({{ $dispatch->id }}, {{ $dispatch->is_released ? 0 : 1 }})" class="dispatch-action-button {{ $dispatch->is_released ? 'hover:bg-rose-50 hover:text-rose-700' : 'hover:bg-emerald-50 hover:text-emerald-700' }}" title="{{ $dispatch->is_released ? 'Hold Dispatch' : 'Release Dispatch' }}">
                                            <i data-lucide="{{ $dispatch->is_released ? 'lock' : 'unlock' }}" class="h-4 w-4"></i>
                                        </button>

                                        @if($dispatch->status !== 'completed')
                                            <a href="{{ route('dispatch.edit', $dispatch->id) }}" class="dispatch-action-button hover:border-amber-200 hover:bg-amber-50 hover:text-amber-700" title="Edit planning">
                                                <i data-lucide="edit" class="h-4 w-4"></i>
                                            </a>

                                            <button type="button" onclick="deleteDispatch({{ $dispatch->id }})" class="dispatch-action-button hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700" title="Delete dispatch">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        @endif
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400 font-bold text-sm">
                                No dispatches found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    function toggleRelease(dispatchId, newStatus) {
        var actionText = newStatus ? 'Release' : 'Hold/Lock';
        Swal.fire({
            title: actionText + ' this dispatch?',
            text: newStatus ? 'Warehouse loading staff will be allowed to start loading.' : 'Warehouse loading staff will be prevented from loading.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: newStatus ? '#10b981' : '#ef4444',
            confirmButtonText: 'Yes, ' + actionText
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/dispatch/' + dispatchId + '/release',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_released: newStatus
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Success', res.message, 'success').then(() => window.location.reload());
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Server error';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    }

    function deleteDispatch(dispatchId) {
        Swal.fire({
            title: 'Delete this dispatch?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/dispatch/' + dispatchId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Deleted!', res.message, 'success').then(() => window.location.reload());
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Server error';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    }
</script>
@endsection
