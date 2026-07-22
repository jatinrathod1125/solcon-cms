@extends('layouts.app')

@section('title', 'Dispatch #' . $dispatch->dispatch_number)
@section('header-title', 'Dispatch Details')

@section('styles')
<style>
    .dispatch-show-page .dispatch-card {
        border-radius: 22px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        padding: 24px;
    }

    .summary-dark-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
        border-radius: 20px;
        padding: 20px;
    }

    .status-badge, .type-badge, .release-badge, .payment-badge {
        align-items: center;
        border-radius: 999px;
        display: inline-flex;
        font-size: 12px;
        font-weight: 850;
        line-height: 1;
        min-height: 32px;
        padding: 6px 14px;
        white-space: nowrap;
    }
</style>
@endsection

@section('content')
<div class="dispatch-show-page space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-black tracking-tight text-slate-900">{{ $dispatch->dispatch_number }}</h1>
                @php
                    $statusInfo = $dispatch->status_info;
                    $typeInfo = $dispatch->type_info;
                @endphp
                <span class="status-badge border" style="background-color: {{ $statusInfo['bg'] }}; color: {{ $statusInfo['color'] }}; border-color: {{ $statusInfo['color'] }}40;">
                    <i data-lucide="{{ $statusInfo['icon'] }}" class="h-3.5 w-3.5 mr-1"></i>
                    {{ $statusInfo['label'] }}
                </span>
            </div>
            <p class="text-xs font-bold text-slate-500 mt-1">Created by {{ $dispatch->creator->name ?? 'System' }} on {{ $dispatch->created_at->format('d M Y h:i A') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('dispatch.loading', $dispatch->id) }}" class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-4 py-2.5 text-xs font-extrabold text-white shadow-lg shadow-purple-600/20 hover:bg-purple-700 transition">
                <i data-lucide="package-check" class="h-4 w-4"></i>
                Loading Screen
            </a>

            @if(auth()->user()->isAdmin() || auth()->user()->isMarketing())
                <button type="button" onclick="toggleRelease({{ $dispatch->id }}, {{ $dispatch->is_released ? 0 : 1 }})" class="inline-flex items-center gap-2 rounded-xl {{ $dispatch->is_released ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-600 text-white' }} px-4 py-2.5 text-xs font-extrabold transition">
                    <i data-lucide="{{ $dispatch->is_released ? 'lock' : 'unlock' }}" class="h-4 w-4"></i>
                    {{ $dispatch->is_released ? 'Hold Dispatch' : 'Release Dispatch' }}
                </button>

                @if($dispatch->status !== 'completed')
                    <a href="{{ route('dispatch.edit', $dispatch->id) }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-xs font-extrabold text-slate-700 hover:bg-slate-200 transition">
                        <i data-lucide="edit" class="h-4 w-4"></i>
                        Edit
                    </a>
                @endif
            @endif

            <a href="{{ route('dispatch.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-3.5 py-2.5 text-xs font-extrabold text-slate-600 hover:bg-slate-200 transition">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
            </a>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Details & Items -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Logistics Info Card -->
            <div class="dispatch-card space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3">Logistics & Customer Info</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 block">Dispatch Type</span>
                        <span class="type-badge mt-1" style="background-color: {{ $typeInfo['badge_bg'] }}; color: {{ $typeInfo['badge_color'] }};">
                            {{ $typeInfo['label'] }}
                        </span>
                    </div>

                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 block">Customer / Party</span>
                        <strong class="text-sm font-black text-slate-900 block mt-1">{{ $dispatch->party_name }}</strong>
                    </div>

                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 block">Vehicle Number</span>
                        <strong class="text-sm font-black text-slate-800 block mt-1">🚛 {{ $dispatch->vehicle_number ?: 'N/A' }}</strong>
                    </div>

                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 block">Driver Details</span>
                        <strong class="text-xs font-bold text-slate-800 block mt-1">👤 {{ $dispatch->driver_name ?: 'N/A' }} ({{ $dispatch->driver_mobile ?: '-' }})</strong>
                    </div>

                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 block">Expected Arrival</span>
                        <strong class="text-xs font-bold text-slate-800 block mt-1">📅 {{ $dispatch->expected_arrival_at ? $dispatch->expected_arrival_at->format('d M Y, h:i A') : 'N/A' }}</strong>
                    </div>

                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 block">City</span>
                        <strong class="text-xs font-bold text-slate-800 block mt-1">📍 {{ $dispatch->city ?: 'N/A' }}</strong>
                    </div>
                </div>

                @if($dispatch->dispatch_type === 'crossing_delivery')
                    <div class="pt-3 border-t border-slate-100 space-y-3">
                        @if($dispatch->place)
                            <div>
                                <span class="text-[10px] font-extrabold uppercase text-slate-400 block">Crossing Place / Transport</span>
                                <strong class="text-xs font-bold text-slate-800 block mt-0.5">{{ $dispatch->place }}</strong>
                            </div>
                        @endif

                        @if($dispatch->full_address)
                            <div>
                                <span class="text-[10px] font-extrabold uppercase text-slate-400 block">Full Address</span>
                                <p class="text-xs font-bold text-slate-700 mt-0.5">{{ $dispatch->full_address }}</p>
                            </div>
                        @endif

                        @if($dispatch->embed_google_map_url)
                            <div>
                                <span class="text-[10px] font-extrabold uppercase text-slate-400 block mb-2">Google Map Location</span>
                                <div class="rounded-xl overflow-hidden border border-slate-200 h-[220px]">
                                    <iframe width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen src="{{ $dispatch->embed_google_map_url }}"></iframe>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Dispatched Items Table -->
            <div class="dispatch-card space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3">Dispatched Items</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-400 uppercase font-extrabold border-b border-slate-200">
                            <tr>
                                <th class="px-3 py-2.5">Dept</th>
                                <th class="px-3 py-2.5">Product Name</th>
                                <th class="px-3 py-2.5 text-center">Quantity</th>
                                <th class="px-3 py-2.5">Packing</th>
                                <th class="px-3 py-2.5 text-right">Weight (KG)</th>
                                <th class="px-3 py-2.5 text-right">Weight (Ton)</th>
                                <th class="px-3 py-2.5">Coupon</th>
                                <th class="px-3 py-2.5">FG Stock Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($dispatch->items as $item)
                                <tr>
                                    <td class="px-3 py-3 font-bold text-slate-600 whitespace-nowrap">{{ $item->department_label }}</td>
                                    <td class="px-3 py-3 font-black text-slate-900">{{ $item->product_name }}</td>
                                    <td class="px-3 py-3 text-center font-extrabold text-blue-700 text-sm">{{ $item->quantity_bags }} {{ $item->unit_label }}</td>
                                    <td class="px-3 py-3 font-bold text-slate-600">{{ $item->packing ?: '-' }}</td>
                                    <td class="px-3 py-3 text-right font-bold text-slate-700">{{ number_format($item->calculated_weight_kg, 1) }}</td>
                                    <td class="px-3 py-3 text-right font-black text-emerald-600">{{ number_format($item->calculated_weight_kg / 1000, 2) }}</td>
                                    <td class="px-3 py-3 font-bold text-slate-600">
                                        @if($item->coupon_raw_material_id)
                                            <span class="inline-flex items-center gap-1 rounded bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700 border border-amber-200">
                                                🏷️ {{ $item->coupon_name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 font-normal">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 font-bold text-slate-600">
                                        @if($item->stock_info['is_available'])
                                            <span class="inline-flex items-center gap-1 rounded bg-emerald-50 px-2 py-0.5 text-[11px] font-black text-emerald-700 border border-emerald-200">
                                                ✅ Available ({{ $item->stock_info['available_bags'] }} {{ $item->unit_label }} Stock)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded bg-rose-50 px-2 py-0.5 text-[11px] font-black text-rose-700 border border-rose-200">
                                                ❌ Not Available ({{ $item->stock_info['available_bags'] }} {{ $item->unit_label }} Stock)
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Right Column: Product Summary Card, Payment, Status Logs -->
        <div class="space-y-6">

            <!-- Product Summary Card -->
            <div class="summary-dark-card shadow-xl space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 border-b border-white/10 pb-3 flex items-center gap-2">
                    <i data-lucide="package" class="h-4 w-4 text-blue-400"></i>
                    Product Summary Card
                </h3>

                <div class="space-y-3">
                    @foreach($dispatch->product_summary as $deptLabel => $data)
                        <div class="p-3 bg-white/5 rounded-xl border border-white/10 space-y-1">
                            <div class="flex items-center justify-between text-xs font-black text-white">
                                <span>{{ $deptLabel }}</span>
                                <span class="text-blue-400 font-extrabold">{{ $data['total_bags'] }} Bags ({{ number_format($data['total_weight'], 1) }} KG)</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-white/10 space-y-1.5">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-extrabold text-slate-300">Total Bags:</span>
                        <strong class="text-xl font-black text-white">{{ $dispatch->total_bags }} Bags</strong>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-extrabold text-slate-300">Total Weight:</span>
                        <strong class="text-lg font-black text-blue-400">{{ number_format($dispatch->total_weight, 1) }} KG</strong>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-extrabold text-slate-300">Total Ton:</span>
                        <strong class="text-lg font-black text-emerald-400">{{ number_format($dispatch->total_tons, 2) }} Ton</strong>
                    </div>
                </div>
            </div>

            <!-- Payment & Release Status Card -->
            <div class="dispatch-card space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3">Payment & Control Status</h3>

                <div>
                    <span class="text-[10px] font-extrabold uppercase text-slate-400 block">Release Goods Status</span>
                    @if($dispatch->is_released)
                        <span class="release-badge bg-emerald-50 text-emerald-700 border border-emerald-200 mt-1">
                            <i data-lucide="check-circle" class="h-3.5 w-3.5 mr-1"></i> Released by {{ $dispatch->releaser->name ?? 'Marketing' }}
                        </span>
                    @else
                        <span class="release-badge bg-rose-50 text-rose-700 border border-rose-200 mt-1">
                            <i data-lucide="lock" class="h-3.5 w-3.5 mr-1"></i> Goods Hold (Unreleased)
                        </span>
                    @endif
                </div>

                <div class="pt-3 border-t border-slate-100 space-y-2">
                    <span class="text-[10px] font-extrabold uppercase text-slate-400 block">Payment Control</span>
                    @if($dispatch->payment_required)
                        <span class="payment-badge bg-rose-50 text-rose-700 border border-rose-200">
                            💳 Payment Required
                        </span>
                    @else
                        <span class="payment-badge bg-slate-100 text-slate-500 border border-slate-200">
                            Payment Not Required
                        </span>
                    @endif
                </div>
            </div>

            <!-- Loading Log Timeline -->
            <div class="dispatch-card space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3">Loading Timeline</h3>

                <div class="space-y-3">
                    @forelse($dispatch->loadingLogs as $log)
                        <div class="flex items-start gap-3 text-xs">
                            <div class="h-2 w-2 rounded-full bg-blue-600 mt-1.5 shrink-0"></div>
                            <div>
                                <strong class="font-extrabold text-slate-800 block">{{ ucfirst($log->status) }}</strong>
                                <p class="text-slate-500 font-semibold">{{ $log->remarks }}</p>
                                <span class="text-[10px] text-slate-400 font-bold block mt-0.5">{{ $log->user->name ?? 'User' }} | {{ $log->created_at->format('d/m/Y h:i A') }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">No loading activities logged yet.</p>
                    @endforelse
                </div>
            </div>

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
</script>
@endsection
