@extends('layouts.app')

@section('title', 'Edit Dispatch #' . $dispatch->dispatch_number)
@section('header-title', 'Edit Dispatch Planning')

@section('styles')
<style>
    .dispatch-edit-page .dispatch-card {
        border-radius: 22px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        padding: 24px;
    }
</style>
@endsection

@section('content')
<div class="dispatch-edit-page space-y-6">

    <!-- Top Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900">Edit Dispatch {{ $dispatch->dispatch_number }}</h1>
            <p class="text-xs font-bold text-slate-500 mt-1">Update dispatch planning details, logistics, or payment status.</p>
        </div>
        <a href="{{ route('dispatch.show', $dispatch->id) }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-xs font-extrabold text-slate-700 hover:bg-slate-200 transition">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back to Details
        </a>
    </div>

    <form id="dispatchEditForm" method="POST" action="{{ route('dispatch.update', $dispatch->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Dispatch Type & Customer Info -->
        <div class="dispatch-card space-y-6">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">1. Customer & Dispatch Information</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Dispatch Type</label>
                    <select name="dispatch_type" id="dispatch_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800" onchange="toggleCrossingFields(this.value)">
                        <option value="factory_pickup" {{ $dispatch->dispatch_type === 'factory_pickup' ? 'selected' : '' }}>Factory Pickup</option>
                        <option value="crossing_delivery" {{ $dispatch->dispatch_type === 'crossing_delivery' ? 'selected' : '' }}>Crossing Delivery</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Customer / Party Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="party_name" value="{{ $dispatch->party_name }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Vehicle Number <span class="text-rose-500">*</span></label>
                    <input type="text" name="vehicle_number" value="{{ $dispatch->vehicle_number }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Driver Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="driver_name" value="{{ $dispatch->driver_name }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Driver Mobile <span class="text-rose-500">*</span></label>
                    <input type="text" name="driver_mobile" value="{{ $dispatch->driver_mobile }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Expected Arrival Date & Time</label>
                    <input type="datetime-local" name="expected_arrival_at" value="{{ $dispatch->expected_arrival_at ? $dispatch->expected_arrival_at->format('Y-m-d\TH:i') : '' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">City</label>
                    <input type="text" name="city" value="{{ $dispatch->city }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800">
                </div>
            </div>

            <!-- Crossing Fields -->
            <div id="crossingFields" class="{{ $dispatch->dispatch_type === 'crossing_delivery' ? '' : 'hidden' }} space-y-4 pt-4 border-t border-slate-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Place / Transport Name</label>
                        <input type="text" name="place" value="{{ $dispatch->place }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800">
                    </div>
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Google Map URL</label>
                        <input type="text" name="google_map_url" value="{{ $dispatch->google_map_url }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Full Address</label>
                    <textarea name="full_address" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800">{{ $dispatch->full_address }}</textarea>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">General Remarks</label>
                <input type="text" name="remarks" value="{{ $dispatch->remarks }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800">
            </div>
        </div>

        <!-- 2. Payment & Release Controls -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Payment Section -->
            <div class="dispatch-card space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 flex items-center gap-2">
                    <i data-lucide="credit-card" class="h-4 w-4 text-slate-600"></i>
                    2. Payment Section
                </h3>

                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-xs font-extrabold text-slate-800">Payment Required?</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="payment_required" value="1" {{ $dispatch->payment_required ? 'checked' : '' }} class="sr-only peer" onchange="togglePaymentFields(this.checked)">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div id="paymentFields" class="{{ $dispatch->payment_required ? '' : 'hidden' }} space-y-4 pt-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Payment Status</label>
                            <select name="payment_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
                                <option value="pending" {{ $dispatch->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="partial" {{ $dispatch->payment_status === 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="paid" {{ $dispatch->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Payment Amount (₹)</label>
                            <input type="number" step="0.01" name="payment_amount" value="{{ $dispatch->payment_amount }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Payment Date</label>
                            <input type="date" name="payment_date" value="{{ $dispatch->payment_date ? $dispatch->payment_date->format('Y-m-d') : '' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Reference Number / Chq No.</label>
                            <input type="text" name="payment_reference" value="{{ $dispatch->payment_reference }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Release Control Section -->
            <div class="dispatch-card space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 flex items-center gap-2">
                    <i data-lucide="shield-check" class="h-4 w-4 text-emerald-600"></i>
                    3. Release Control (Marketing Approval)
                </h3>

                <div class="p-4 bg-emerald-50/50 rounded-2xl border border-emerald-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm font-black text-slate-900 block">Release Goods for Loading?</span>
                            <p class="text-xs font-bold text-slate-500">If set to NO, warehouse staff cannot load truck.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_released" value="1" {{ $dispatch->is_released ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>
            </div>

        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
            <a href="{{ route('dispatch.show', $dispatch->id) }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-700 font-extrabold text-xs hover:bg-slate-200 transition">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 text-white font-extrabold text-xs shadow-lg shadow-blue-600/25 hover:bg-blue-700 transition">
                Save Changes
            </button>
        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
    function toggleCrossingFields(type) {
        if (type === 'crossing_delivery') {
            $('#crossingFields').slideDown(200);
        } else {
            $('#crossingFields').slideUp(200);
        }
    }

    function togglePaymentFields(checked) {
        if (checked) {
            $('#paymentFields').slideDown(200);
        } else {
            $('#paymentFields').slideUp(200);
        }
    }

    $('#dispatchEditForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        title: 'Updated!',
                        text: res.message,
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        window.location.href = "{{ route('dispatch.show', $dispatch->id) }}";
                    });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function(xhr) {
                var msg = 'Failed to update dispatch planning.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire('Error', msg, 'error');
            }
        });
    });
</script>
@endsection
