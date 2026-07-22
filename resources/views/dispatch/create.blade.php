@extends('layouts.app')

@section('title', 'Create Dispatch Planning')
@section('header-title', 'Create Dispatch Planning')

@section('styles')
<style>
    .dispatch-create-page {
        --dispatch-blue: #2563eb;
    }

    .dispatch-card {
        border-radius: 22px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        padding: 24px;
    }

    .summary-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
        border-radius: 20px;
        padding: 20px;
    }

    .map-preview-container {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        height: 220px;
        background: #f8fafc;
    }
</style>
@endsection

@section('content')
<div class="dispatch-create-page space-y-6">

    <!-- Top Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900">Create Dispatch Planning</h1>
            <p class="text-xs font-bold text-slate-500 mt-1">Plan factory pickup or crossing delivery and select customer orders.</p>
        </div>
        <a href="{{ route('dispatch.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-xs font-extrabold text-slate-700 hover:bg-slate-200 transition">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back to Dispatches
        </a>
    </div>

    <form id="dispatchCreateForm" method="POST" action="{{ route('dispatch.store') }}" class="space-y-6">
        @csrf

        <!-- 1. Dispatch Type Selection -->
        <div class="dispatch-card space-y-4">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">1. Select Dispatch Type</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="relative flex flex-col p-4 border-2 rounded-2xl cursor-pointer transition focus:outline-none type-selector-label" id="label_factory_pickup" onclick="setDispatchType('factory_pickup')">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                            <i data-lucide="building-2" class="h-4 w-4 text-blue-600"></i>
                            Factory Pickup
                        </span>
                        <input type="radio" name="dispatch_type" value="factory_pickup" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                    </div>
                    <p class="text-xs text-slate-500 font-bold">Truck comes to factory. Customer vehicle picks up goods directly from factory premises.</p>
                </label>

                <label class="relative flex flex-col p-4 border-2 rounded-2xl cursor-pointer transition focus:outline-none type-selector-label" id="label_crossing_delivery" onclick="setDispatchType('crossing_delivery')">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                            <i data-lucide="map-pin" class="h-4 w-4 text-blue-600"></i>
                            Crossing Delivery
                        </span>
                        <input type="radio" name="dispatch_type" value="crossing_delivery" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                    </div>
                    <p class="text-xs text-slate-500 font-bold">Factory vehicle delivers goods to external transport crossing location or destination.</p>
                </label>
            </div>
        </div>

        <!-- 2. Logistics & Delivery Information -->
        <div class="dispatch-card space-y-6">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">2. Customer & Logistics Details</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Vehicle Number -->
                <div>
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Vehicle Number <span class="text-rose-500">*</span></label>
                    <input type="text" id="vehicle_number" name="vehicle_number" required placeholder="e.g. GJ-01-AB-1234" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                </div>

                <!-- Expected Arrival Date & Time -->
                <div>
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Expected Arrival Date & Time</label>
                    <input type="datetime-local" id="expected_arrival_at" name="expected_arrival_at" value="{{ date('Y-m-d\TH:i') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                </div>

                <!-- Driver Name -->
                <div>
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Driver Name <span class="text-rose-500">*</span></label>
                    <input type="text" id="driver_name" name="driver_name" required placeholder="Driver Full Name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                </div>

                <!-- Driver Mobile -->
                <div>
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Driver Mobile <span class="text-rose-500">*</span></label>
                    <input type="text" id="driver_mobile" name="driver_mobile" required placeholder="Mobile Number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                </div>
            </div>

            <!-- Crossing Delivery Additional Fields -->
            <div id="crossingFields" class="hidden space-y-4 pt-4 border-t border-slate-100">
                <h4 class="text-xs font-black uppercase tracking-wider text-blue-600 flex items-center gap-1.5">
                    <i data-lucide="map-pin" class="h-4 w-4"></i> Crossing Location & Google Map
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Place / Transport Name</label>
                        <input type="text" id="place" name="place" placeholder="e.g. Navrang Transport Godown" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Google Map URL</label>
                        <input type="text" id="google_map_url" name="google_map_url" oninput="updateMapPreview(this.value)" placeholder="Paste Google Maps Share Link or Address" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Full Address</label>
                    <textarea id="full_address" name="full_address" rows="2" placeholder="Full Delivery / Crossing Address" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50"></textarea>
                </div>

                <!-- Google Map Preview Card -->
                <div id="mapPreviewCard" class="hidden">
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-2">Google Map Live Preview</label>
                    <div class="map-preview-container relative shadow-inner">
                        <iframe id="mapFrame" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen src=""></iframe>
                    </div>
                </div>
            </div>

            <!-- Remarks -->
            <div>
                <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">General Remarks</label>
                <input type="text" id="remarks" name="remarks" placeholder="Any loading instructions or remarks" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
            </div>
        </div>

        <!-- 3. Customer Order Selection -->
        <div class="dispatch-card space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">3. Select Approved Orders</h3>
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-200">
                    Select single or multiple orders
                </span>
            </div>

            <div class="space-y-3 max-h-[420px] overflow-y-auto pr-1">
                @forelse($approvedOrders as $order)
                    <label class="flex items-start gap-3 p-4 border border-slate-200 rounded-2xl hover:bg-slate-50 transition cursor-pointer order-checkbox-card">
                        <input type="checkbox" name="marketing_order_ids[]" value="{{ $order->id }}" class="mt-1 h-5 w-5 text-blue-600 rounded border-slate-300 focus:ring-blue-500 order-checkbox" onchange="recalculateSummary()">
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <span class="font-mono text-xs font-black text-blue-700">{{ $order->order_number }}</span>
                                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                    {{ ucfirst($order->priority) }} Priority
                                </span>
                            </div>
                            
                            <div class="font-extrabold text-sm text-slate-900 truncate">{{ $order->party_name }}</div>
                            <div class="text-xs font-bold text-slate-500 mt-0.5">
                                📍 {{ $order->city ?: 'N/A' }} | 📅 Order Date: {{ $order->order_date?->format('d/m/Y') }}
                            </div>

                            <!-- Items summary badge -->
                            <div class="mt-2 flex flex-wrap gap-1.5">
                                @foreach($order->items as $item)
                                    <span class="inline-flex items-center text-[11px] font-bold bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md border border-slate-200">
                                        {{ $item->product_name }} - {{ $item->quantity_bags }} {{ $item->unit_label }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </label>
                @empty
                    <div class="p-8 text-center text-slate-400 font-bold text-xs bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        No approved orders found. You can still create manual dispatch.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 4. Payment & Release Controls -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Payment Section -->
            <div class="dispatch-card space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 flex items-center gap-2">
                    <i data-lucide="credit-card" class="h-4 w-4 text-slate-600"></i>
                    4. Payment Section
                </h3>

                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    <div>
                        <span class="text-sm font-black text-slate-900 block">Payment Required?</span>
                        <p class="text-xs font-bold text-slate-500">Enable if payment is required before dispatch.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="payment_required" name="payment_required" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>

            <!-- Release Control Section -->
            <div class="dispatch-card space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 flex items-center gap-2">
                    <i data-lucide="shield-check" class="h-4 w-4 text-emerald-600"></i>
                    5. Release Control (Marketing Approval)
                </h3>

                <div class="p-4 bg-emerald-50/50 rounded-2xl border border-emerald-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm font-black text-slate-900 block">Release Goods for Loading?</span>
                            <p class="text-xs font-bold text-slate-500">If set to NO, warehouse staff cannot load truck.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="is_released" name="is_released" value="1" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>
            </div>

        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
            <a href="{{ route('dispatch.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-700 font-extrabold text-xs hover:bg-slate-200 transition">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 text-white font-extrabold text-xs shadow-lg shadow-blue-600/25 hover:bg-blue-700 transition">
                Create Dispatch Planning
            </button>
        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
    var approvedOrdersData = @json($approvedOrders);

    function setDispatchType(type) {
        $('.type-selector-label').removeClass('border-blue-600 bg-blue-50/30').addClass('border-slate-200');
        $('#label_' + type).addClass('border-blue-600 bg-blue-50/30').removeClass('border-slate-200');

        if (type === 'crossing_delivery') {
            $('#crossingFields').slideDown(200);
        } else {
            $('#crossingFields').slideUp(200);
        }
    }

    $(document).ready(function() {
        setDispatchType('factory_pickup');
    });

    function togglePaymentFields(checked) {
        if (checked) {
            $('#paymentFields').slideDown(200);
        } else {
            $('#paymentFields').slideUp(200);
        }
    }

    var mapTimer = null;
    function updateMapPreview(val) {
        val = val.trim();
        if (mapTimer) clearTimeout(mapTimer);

        if (val.length > 5) {
            mapTimer = setTimeout(function() {
                $.ajax({
                    url: "{{ route('dispatch.api.preview_map') }}",
                    type: "GET",
                    data: { url: val },
                    success: function(res) {
                        if (res.success && res.embed_url) {
                            $('#mapFrame').attr('src', res.embed_url);
                            $('#mapPreviewCard').slideDown(200);
                        }
                    }
                });
            }, 400);
        } else {
            $('#mapPreviewCard').slideUp(200);
        }
    }

    function recalculateSummary() {
        var selectedOrderIds = [];
        $('.order-checkbox:checked').each(function() {
            selectedOrderIds.push(parseInt($(this).val()));
        });

        if (selectedOrderIds.length === 0) {
            $('#summaryContent').html('<p class="text-xs text-slate-400 font-bold italic">Select customer orders to calculate totals automatically.</p>');
            $('#summaryTotalBags').text('0 Bags');
            $('#summaryTotalWeight').text('0.0 KG');
            return;
        }

        var totalBags = 0;
        var totalWeight = 0;
        var deptSummary = {};

        approvedOrdersData.forEach(function(order) {
            if (selectedOrderIds.includes(order.id)) {
                order.items.forEach(function(item) {
                    var deptCode = item.department_code || '';
                    var dept = item.department_label || (deptCode === 'TAD' ? 'Adhesive' : (deptCode === 'GRT' ? 'Grout' : (deptCode === 'EPX' ? 'Epoxy' : deptCode)));
                    var isBucket = deptCode === 'EPX' || (item.packing || '').toLowerCase().includes('bucket');
                    var deptUnit = isBucket ? 'Buckets' : 'Bags';

                    if (!deptSummary[dept]) {
                        deptSummary[dept] = { bags: 0, weight: 0, items: [], unit: deptUnit };
                    }
                    var bags = parseInt(item.quantity_bags) || 0;
                    var weight = parseFloat(item.calculated_weight_kg);

                    if (deptCode === 'GRT') {
                        weight = bags * 25;
                    } else if (isNaN(weight) || weight <= 0) {
                        var rawKg = parseFloat(item.quantity_kg);
                        if (!isNaN(rawKg) && rawKg > 0) {
                            weight = rawKg;
                        } else {
                            var packingStr = (item.packing || '').toString();
                            var match = packingStr.match(/(\d+(?:\.\d+)?)/);
                            var pkgSize = match ? parseFloat(match[1]) : (deptCode === 'TAD' ? 20 : 1);
                            weight = bags * pkgSize;
                        }
                    }

                    var prodName = item.product_name || (item.grade ? item.grade.name : (item.color ? item.color.name : (item.epoxy_product ? item.epoxy_product.name : 'Product')));
                    var itemUnit = isBucket ? (bags === 1 ? 'Bucket' : 'Buckets') : (bags === 1 ? 'Bag' : 'Bags');

                    deptSummary[dept].bags += bags;
                    deptSummary[dept].weight += weight;
                    deptSummary[dept].items.push(prodName + ' (' + bags + ' ' + itemUnit + ')');

                    totalBags += bags;
                    totalWeight += weight;
                });
            }
        });

        var html = '';
        for (var dept in deptSummary) {
            html += '<div class="p-3 bg-white/5 rounded-xl border border-white/10 space-y-1.5">';
            html += '<div class="flex items-center justify-between text-xs font-black text-white">';
            html += '<span>' + dept + '</span>';
            html += '<span class="text-blue-400 font-extrabold">' + deptSummary[dept].bags + ' ' + deptSummary[dept].unit + ' (' + deptSummary[dept].weight.toFixed(1) + ' KG)</span>';
            html += '</div>';
            html += '<div class="text-[11px] text-slate-300 font-bold truncate">' + deptSummary[dept].items.join(', ') + '</div>';
            html += '</div>';
        }

        var totalTons = (totalWeight / 1000).toFixed(2);
        $('#summaryContent').html(html);
        $('#summaryTotalBags').text(totalBags + ' Units (Bags/Buckets)');
        $('#summaryTotalWeight').text(totalWeight.toFixed(1) + ' KG');
        $('#summaryTotalTon').text(totalTons + ' Ton');
    }

    $('#dispatchCreateForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: res.message,
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        window.location.href = res.redirect_url || "{{ route('dispatch.index') }}";
                    });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function(xhr) {
                var msg = 'Failed to create dispatch planning.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire('Error', msg, 'error');
            }
        });
    });
</script>
@endsection
