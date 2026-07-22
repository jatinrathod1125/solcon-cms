@extends('layouts.app')

@section('title', 'Loading Screen - ' . $dispatch->dispatch_number)
@section('header-title', 'Warehouse Loading Screen')

@section('styles')
<style>
    .loading-screen-page .loading-card {
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        padding: 24px;
    }

    .status-large-badge {
        align-items: center;
        border-radius: 999px;
        display: inline-flex;
        font-size: 14px;
        font-weight: 900;
        padding: 8px 18px;
    }
</style>
@endsection

@section('content')
<div class="loading-screen-page space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-black tracking-tight text-slate-900">Loading Screen: {{ $dispatch->dispatch_number }}</h1>
                @php $statusInfo = $dispatch->status_info; @endphp
                <span class="status-large-badge border" style="background-color: {{ $statusInfo['bg'] }}; color: {{ $statusInfo['color'] }}; border-color: {{ $statusInfo['color'] }}40;">
                    <i data-lucide="{{ $statusInfo['icon'] }}" class="h-4 w-4 mr-1.5"></i>
                    {{ $statusInfo['label'] }}
                </span>
            </div>
            <p class="text-xs font-bold text-slate-500 mt-1">Dispatch Loading & Finished Goods Stock Deduction Control</p>
        </div>

        <a href="{{ route('dispatch.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-xs font-extrabold text-slate-700 hover:bg-slate-200 transition">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back to Dispatches
        </a>
    </div>

    <!-- Release Warning Banner if Release = No -->
    @if(!$dispatch->is_released)
        <div class="p-5 rounded-2xl bg-rose-50 border-2 border-rose-200 text-rose-800 flex items-start gap-4 shadow-sm">
            <div class="h-10 w-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                <i data-lucide="lock" class="h-5 w-5"></i>
            </div>
            <div>
                <h3 class="text-sm font-black uppercase tracking-wider text-rose-900">Goods are not released by Marketing</h3>
                <p class="text-xs font-bold text-rose-700 mt-1">Loading cannot proceed until Marketing releases this dispatch. Contact Marketing department for release approval.</p>
            </div>
        </div>
    @endif

    <!-- 1. Customer & Truck Details Card -->
    <div class="loading-card shadow-sm space-y-6">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3">Logistics & Driver Information</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 block mb-1">Customer / Party</span>
                <strong class="text-base font-black text-slate-900 block truncate">{{ $dispatch->party_name }}</strong>
                <span class="text-xs font-bold text-slate-500 block mt-1">📍 {{ $dispatch->city ?: 'N/A' }}</span>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 block mb-1">Truck / Vehicle</span>
                <strong class="text-base font-black text-blue-700 block">🚛 {{ $dispatch->vehicle_number ?: 'N/A' }}</strong>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 block mb-1">Driver Details</span>
                <strong class="text-sm font-black text-slate-900 block">👤 {{ $dispatch->driver_name ?: 'N/A' }}</strong>
                <span class="text-xs font-bold text-slate-600 block mt-0.5">📞 {{ $dispatch->driver_mobile ?: '-' }}</span>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 block mb-1">Expected Arrival</span>
                <strong class="text-xs font-black text-slate-800 block">📅 {{ $dispatch->expected_arrival_at ? $dispatch->expected_arrival_at->format('d M Y, h:i A') : 'TBD' }}</strong>
            </div>
        </div>
    </div>

    <!-- 2. Products to Load Table Card -->
    <div class="loading-card shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Products to Load into Truck</h3>
            <div class="flex items-center gap-4 text-xs font-extrabold text-slate-700">
                <span>Total Units: <strong class="text-blue-600 text-sm font-black">{{ $dispatch->total_bags }}</strong></span>
                <span>Total Weight: <strong class="text-blue-600 text-sm font-black">{{ number_format($dispatch->total_weight, 1) }} KG ({{ number_format($dispatch->total_tons, 2) }} Ton)</strong></span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-extrabold border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Dept</th>
                        <th class="px-4 py-3">Product Name</th>
                        <th class="px-4 py-3 text-center">Quantity</th>
                        <th class="px-4 py-3">Packing</th>
                        <th class="px-4 py-3 text-right">Weight (KG)</th>
                        <th class="px-4 py-3 text-right">Weight (Ton)</th>
                        <th class="px-4 py-3">Coupon</th>
                        <th class="px-4 py-3">FG Stock Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($dispatch->items as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3.5 font-extrabold text-slate-600 whitespace-nowrap">{{ $item->department_label }}</td>
                            <td class="px-4 py-3.5 font-black text-slate-900 text-sm">{{ $item->product_name }}</td>
                            <td class="px-4 py-3.5 text-center font-black text-blue-700 text-base">{{ $item->quantity_bags }} {{ $item->unit_label }}</td>
                            <td class="px-4 py-3.5 font-bold text-slate-600">{{ $item->packing ?: '-' }}</td>
                            <td class="px-4 py-3.5 text-right font-bold text-slate-800">{{ number_format($item->calculated_weight_kg, 1) }}</td>
                            <td class="px-4 py-3.5 text-right font-black text-emerald-600">{{ number_format($item->calculated_weight_kg / 1000, 2) }}</td>
                            <td class="px-4 py-3.5 font-bold text-slate-600">
                                @if($item->coupon_raw_material_id)
                                    <span class="inline-flex items-center gap-1 rounded bg-amber-50 px-2 py-0.5 text-xs font-bold text-amber-700 border border-amber-200">
                                        🏷️ {{ $item->coupon_name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 font-normal">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 font-bold text-slate-600">
                                @if($item->stock_info['is_available'])
                                    <span class="inline-flex items-center gap-1 rounded bg-emerald-50 px-2.5 py-1 text-xs font-black text-emerald-700 border border-emerald-200 shadow-sm">
                                        ✅ Available ({{ $item->stock_info['available_bags'] }} {{ $item->unit_label }} Stock)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded bg-rose-50 px-2.5 py-1 text-xs font-black text-rose-700 border border-rose-200 shadow-sm">
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

    <!-- 3. Loading Action Control Card -->
    <div class="loading-card shadow-lg space-y-4">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3">Warehouse Loading Execution</h3>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                @if($dispatch->status === 'completed')
                    <div class="text-emerald-700 font-extrabold text-xs flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="h-5 w-5"></i>
                        Loading Completed by {{ $dispatch->loader->name ?? 'Dispatch Staff' }} on {{ $dispatch->loaded_at ? $dispatch->loaded_at->format('d M Y, h:i A') : 'N/A' }}
                    </div>
                @elseif(!$dispatch->is_released)
                    <div class="text-rose-600 font-bold text-xs">
                        ⚠️ Disabled: Marketing must release goods before starting loading.
                    </div>
                @else
                    <div class="text-slate-600 font-bold text-xs">
                        Click "Start Loading" when truck arrives, then click "Finish Loading" once truck is loaded.
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                @if($dispatch->status !== 'completed')
                    @if($dispatch->status !== 'loading')
                        <button type="button" onclick="startLoading({{ $dispatch->id }})" {{ !$dispatch->is_released ? 'disabled' : '' }} class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-blue-600 text-white font-extrabold text-xs shadow-lg shadow-blue-600/25 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                            <i data-lucide="play" class="h-4 w-4 inline mr-1.5"></i>
                            Start Loading
                        </button>
                    @endif

                    <button type="button" onclick="finishLoading({{ $dispatch->id }})" {{ !$dispatch->is_released ? 'disabled' : '' }} class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-emerald-600 text-white font-black text-xs shadow-xl shadow-emerald-600/25 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                        <i data-lucide="check-circle" class="h-4 w-4 inline mr-1.5"></i>
                        Finish Loading & Deduct Stock
                    </button>
                @else
                    <button type="button" disabled class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-slate-200 text-slate-500 font-black text-xs cursor-not-allowed">
                        Dispatch Completed
                    </button>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function startLoading(dispatchId) {
        $.ajax({
            url: '/dispatch/' + dispatchId + '/start-loading',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.success) {
                    Swal.fire('Loading Started!', res.message, 'success').then(() => window.location.reload());
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

    function finishLoading(dispatchId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will complete loading and automatically deduct Finished Goods stock from inventory!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'YES, Finish Loading & Deduct Stock'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/dispatch/' + dispatchId + '/finish-loading',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                title: 'Completed!',
                                text: res.message,
                                icon: 'success',
                                confirmButtonColor: '#10b981'
                            }).then(() => window.location.reload());
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
