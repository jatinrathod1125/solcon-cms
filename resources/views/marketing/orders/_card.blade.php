@php
    $availBadge = $order->availability_badge;
    $priority = $order->priority_info;
    $status = $order->status_info;
    $items = $order->items;
    $itemCount = $items->count();
    $totalBags = $items->sum('quantity_bags');
    $departments = $items->pluck('department_code')->filter()->unique()->values();
    $couponsUsed = $items->filter(fn ($item) => $item->coupon_raw_material_id !== null)
        ->map(fn ($item) => $item->couponMaterial->name ?? 'Coupon')
        ->unique()
        ->values();
    $availabilityClass = $availBadge['class'] ?? $order->availability ?? 'unknown';
    $stockLabels = [
        'available' => 'Stock Available',
        'partial' => 'Partial Stock',
        'unavailable' => 'Waiting Production',
        'unknown' => 'Insufficient Stock',
    ];
    $statusLabels = [
        'pending' => 'Pending',
        'in_progress' => 'Production',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];
    $searchText = strtolower(trim(implode(' ', [
        $order->order_number,
        $order->party_name,
        $order->vehicle_number,
        $order->remarks,
        $order->priority,
        $order->status,
        $availabilityClass,
        $order->items_summary,
        $couponsUsed->implode(' '),
    ])));
@endphp

<article
    id="order-{{ $order->id }}"
    role="button"
    tabindex="0"
    onclick="openOrderDetails({{ $order->id }})"
    onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); openOrderDetails({{ $order->id }}); }"
    class="mobile-order-card rounded-[24px] border border-slate-200 bg-white p-4 shadow-sm transition active:scale-[.99]"
    data-id="{{ $order->id }}"
    data-status="{{ $order->status }}"
    data-priority="{{ $order->priority }}"
    data-availability="{{ $availabilityClass }}"
    data-date="{{ $order->order_date?->toDateString() }}"
    data-departments="{{ $departments->implode(',') }}"
    data-search="{{ $searchText }}"
>
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="font-mono text-[11px] font-black uppercase tracking-tight text-blue-700">{{ $order->order_number }}</p>
            <h4 class="mt-1 truncate text-base font-black text-slate-950">{{ $order->party_name }}</h4>
            <p class="mt-1 truncate text-xs font-bold text-slate-500">
                {{ $order->vehicle_number ?: 'Vehicle not added' }}
            </p>
        </div>
        <span class="marketing-status-badge marketing-status-{{ $order->status }}">
            {{ $statusLabels[$order->status] ?? $status['label'] }}
        </span>
    </div>

    <div class="mt-4 grid grid-cols-3 gap-2">
        <div class="rounded-2xl bg-slate-50 p-3">
            <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Delivery</p>
            <p class="mt-1 text-xs font-black text-slate-900">{{ $order->order_date?->format('d M') ?? '-' }}</p>
        </div>
        <div class="rounded-2xl bg-slate-50 p-3">
            <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Items</p>
            <p class="mt-1 text-xs font-black text-slate-900">{{ $itemCount }}</p>
        </div>
        <div class="rounded-2xl bg-slate-50 p-3">
            <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Bags</p>
            <p class="mt-1 text-xs font-black text-slate-900">{{ number_format($totalBags) }}</p>
        </div>
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
        @foreach($departments as $department)
            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-black text-slate-600">{{ $department }}</span>
        @endforeach
        <span class="marketing-priority-badge priority-{{ $order->priority }}">
            {{ $priority['label'] ?? ucfirst($order->priority) }}
        </span>
        <span class="marketing-stock-badge stock-{{ $availabilityClass }}">
            {{ $stockLabels[$availabilityClass] ?? $availBadge['label'] ?? ucfirst($availabilityClass) }}
        </span>
    </div>

    <div class="mt-4 flex items-center justify-between gap-3 border-t border-slate-100 pt-3">
        <p class="line-clamp-1 text-xs font-bold text-slate-500">{{ $order->items_summary ?: 'No item summary available' }}</p>
        <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
            <i data-lucide="chevron-right" class="h-4 w-4"></i>
        </span>
    </div>
</article>
