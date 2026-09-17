@extends('layouts.app')

@section('title', 'Order Details')
@section('header-title', 'Order Details')

@section('styles')
<style>
    .marketing-orders-page {
        --marketing-blue: #2563eb;
        --marketing-ink: #0f172a;
    }

    .page-content .marketing-orders-page input.compact-input:not([type="checkbox"]):not([type="radio"]) {
        min-height: 24px !important;
        height: 24px !important;
        border-radius: 6px !important;
        border: 1px solid #cbd5e1 !important;
        background: #f8fafc !important;
        padding: 2px 6px !important;
        font-size: 11px !important;
        text-align: center !important;
        box-shadow: none !important;
        width: 100% !important;
        color: #334155 !important;
        font-weight: 800 !important;
    }

    .page-content .marketing-orders-page input.highlighted-qty-input {
        background-color: #ecfdf5 !important;
        border-color: #10b981 !important;
        color: #047857 !important;
    }

    .page-content .marketing-orders-page tr.edited-product-row {
        background-color: #fef3c7 !important;
        border-left: 4px solid #f59e0b !important;
    }

    .page-content .marketing-orders-page input.edited-qty-input {
        background-color: #fef3c7 !important;
        border-color: #f59e0b !important;
        color: #92400e !important;
        font-weight: 900 !important;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.25) !important;
    }

    .erp-table th {
        font-weight: 800;
        text-transform: uppercase;
        font-size: 9px !important;
        letter-spacing: 0.08em;
        color: #64748b !important;
        background-color: #f8fafc !important;
        padding: 6px 8px !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .erp-table td {
        padding: 5px 8px !important;
        border-bottom: 1px solid #f1f5f9 !important;
        font-size: 11px !important;
        font-weight: 700;
        color: #334155;
    }

    .erp-table tr:hover {
        background-color: #f8fafc !important;
    }

    @media (max-width: 767px) {
        .page-content .marketing-orders-page table.responsive-table thead {
            display: table-header-group !important;
        }

        .page-content .marketing-orders-page table.responsive-table {
            display: table !important;
            width: 100% !important;
        }

        .page-content .marketing-orders-page table.responsive-table tbody {
            display: table-row-group !important;
            width: 100% !important;
        }

        .page-content .marketing-orders-page table.responsive-table tr {
            display: table-row !important;
            margin-bottom: 0 !important;
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
        }

        .page-content .marketing-orders-page table.responsive-table td {
            display: table-cell !important;
            text-align: center !important;
            padding: 4px 6px !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }

        .page-content .marketing-orders-page table.responsive-table th {
            display: table-cell !important;
            text-align: center !important;
            padding: 6px 8px !important;
        }

        .page-content .marketing-orders-page table.responsive-table th.text-left {
            text-align: left !important;
        }

        .page-content .marketing-orders-page table.responsive-table td::before {
            display: none !important;
        }

        .marketing-orders-page section {
            padding: 12px !important;
            border-radius: 16px !important;
        }

        .marketing-orders-page .bg-slate-50\/50 {
            padding: 12px !important;
            border-radius: 16px !important;
        }

        .marketing-orders-page .grid {
            gap: 12px !important;
        }

        .marketing-orders-page .flex-col.gap-6 {
            gap: 12px !important;
        }
    }
</style>
@endsection

@section('content')
<div class="marketing-orders-page mx-auto max-w-[1700px]">

    <section class="bg-white border border-slate-200 rounded-[24px] p-5 shadow-sm space-y-5">
        <header class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                    <i data-lucide="eye" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                        <span>Order details: {{ $order->order_number }}</span>
                        @if($order->is_edited)
                            <span class="inline-flex items-center gap-0.5 rounded bg-amber-100 px-2 py-0.5 text-xs font-black text-amber-800 border border-amber-300 shadow-sm" title="Order was updated/edited">
                                ✏️ Edited
                            </span>
                        @endif
                    </h2>
                    <p class="text-xs text-slate-400 font-bold">Created by {{ $order->creator->name ?? 'System' }} on {{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if(($order->status !== 'completed' && $order->status !== 'cancelled') || auth()->user()->isAdmin())
                <a href="{{ route('marketing.orders.edit', $order->id) }}" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50 px-4 text-xs font-bold text-blue-700 hover:bg-blue-100 transition">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    Edit Order
                </a>
                @endif
                <a href="{{ route('marketing.orders.index') }}" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back to Orders
                </a>
            </div>
        </header>

        <!-- Order Header Fields -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Order No.</label>
                <input type="text" value="{{ $order->order_number }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-500 focus:outline-none" readonly>
            </div>
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Order Date</label>
                <input type="text" value="{{ $order->order_date?->format('d M Y') }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-500 focus:outline-none" readonly>
            </div>
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Party Name</label>
                <input type="text" value="{{ $order->party_name }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-500 focus:outline-none" readonly>
            </div>
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">City / Vehicle</label>
                <input type="text" value="{{ $order->vehicle_number ?: 'N/A' }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-500 focus:outline-none" readonly>
            </div>
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Priority / Status</label>
                <div class="flex gap-2">
                    <span class="inline-flex h-9 items-center justify-center rounded-xl bg-slate-100 px-3 text-xs font-bold text-slate-700 capitalize border border-slate-200">{{ $order->priority }}</span>
                    <span class="inline-flex h-9 items-center justify-center rounded-xl px-3 text-xs font-bold capitalize border" 
                          style="background-color: {{ $order->status_info['bg'] }}; color: {{ $order->status_info['color'] }}; border-color: {{ $order->status_info['color'] }}40;">
                        {{ $order->status_info['label'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- View Toggle & Summary -->
        <div class="flex items-center justify-between flex-wrap gap-3 pt-1">
            <div class="flex items-center gap-2">
                <span class="text-xs font-black uppercase tracking-wider text-slate-700">Ordered Products</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-blue-100 text-blue-800 border border-blue-200" id="ordered-items-count">
                    {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                </span>
                <span class="text-xs font-bold text-slate-400">|</span>
                <span class="text-xs font-bold text-slate-500">Total Units: <strong class="text-slate-800">{{ $order->items->sum('quantity_bags') }}</strong></span>
            </div>
            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200 text-xs font-bold">
                <button type="button" id="btn-show-ordered" class="px-3 py-1.5 rounded-lg transition bg-white text-blue-700 shadow-sm font-black border border-slate-200/60">
                    Ordered Only
                </button>
                <button type="button" id="btn-show-all" class="px-3 py-1.5 rounded-lg transition text-slate-500 hover:text-slate-900 font-bold">
                    Show All
                </button>
            </div>
        </div>

        <!-- 4-Column Product Grid -->
        <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 transition-all duration-200">

            <!-- COLUMN 1 -->
            <div class="flex flex-col gap-6 order-col">
                <!-- TILE ADHESIVE -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200 product-card">
                    <div class="px-4 py-3 flex items-center gap-2 border-b border-slate-100">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-800">Tile Adhesive</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full erp-table text-center min-w-[200px]">
                            <thead>
                                <tr>
                                    <th class="text-left">Product</th>
                                    <th class="w-16 text-center">Bag</th>
                                    <th class="w-24 text-center">Coupon</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($adhesives as $grade)
                                @php $packing = $grade->bagSize->name ?? '20KG'; @endphp
                                <tr>
                                    <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                        {{$grade->name }}@if($grade->brand) <span class="text-[9px] font-bold text-amber-600">[{{ $grade->brand->name }}]</span>@endif
                                    </td>
                                    <td class="w-16">
                                        <input type="text" class="compact-input qty-input" 
                                               data-dept="TAD" 
                                               data-product-id="{{ $grade->id }}" 
                                               data-packing="{{ $packing }}" readonly>
                                    </td>
                                    <td class="w-24">
                                        <input type="text" class="compact-input coupon-code-input uppercase" 
                                               placeholder="None" 
                                               data-dept="TAD" 
                                               data-product-id="{{ $grade->id }}" 
                                               data-packing="{{ $packing }}" readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- DYNAMIC COMPONENT CATEGORIES (COLUMN 1) --}}
                @if(isset($componentCategoriesGrouped) && $componentCategoriesGrouped->has(1))
                    @foreach($componentCategoriesGrouped->get(1) as $cat)
                        @if($cat->components->isNotEmpty())
                            <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200 product-card">
                                <div class="px-4 py-3 flex items-center justify-between border-b border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                        <span class="text-xs font-black uppercase tracking-wider text-slate-800">{{ $cat->name }}</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">{{ $cat->default_unit }}</span>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full erp-table text-center min-w-[200px]">
                                        <thead>
                                            <tr>
                                                <th class="text-left">{{ $cat->name }}</th>
                                                <th class="w-20 text-center">{{ $cat->default_unit }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cat->components as $comp)
                                                <tr>
                                                    <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                                        {{ $comp->name }}
                                                    </td>
                                                    <td class="w-20">
                                                        <input type="text" class="compact-input qty-input"
                                                            data-dept="EPX"
                                                            data-component-id="{{ $comp->id }}"
                                                            data-packing="{{ $comp->default_packing ?? $cat->default_unit }}" readonly>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

                <!-- 700GM FILLER POUCH -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200 product-card">
                    <div class="px-4 py-3 flex items-center justify-between border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                            <span class="text-xs font-black uppercase tracking-wider text-slate-800">Filler Pouch (700 GM)</span>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">700 GM</span>
                    </div>
                    <div class="overflow-x-auto max-h-[360px] overflow-y-auto">
                        <table class="w-full erp-table text-center min-w-[200px]">
                            <thead class="sticky top-0 bg-slate-50 z-10">
                                <tr>
                                    <th class="text-left">Color</th>
                                    <th class="w-20 text-center">Pouch</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($fillerPouchComponents))
                                @foreach($fillerPouchComponents as $fComp)
                                <tr>
                                    <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                        {{ $fComp->color?->code ?? $fComp->code }} - {{ $fComp->color?->name ?? str_replace('700gm ', '', str_replace(' Filler Pouch', '', $fComp->name)) }}
                                    </td>
                                    <td class="w-20">
                                        <input type="text" class="compact-input qty-input"
                                            data-dept="EPX"
                                            data-component-id="{{ $fComp->id }}"
                                            data-filler-color-id="{{ $fComp->epoxy_filler_color_id }}"
                                            data-packing="700 GM" readonly>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- COLUMN 2 -->
            <div class="flex flex-col gap-6 order-col">
                <!-- TILES GROUT -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200 product-card">
                    <div class="px-4 py-3 flex items-center gap-2 border-b border-slate-100">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-800">Tiles Grout</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full erp-table text-center min-w-[200px]">
                            <thead>
                                <tr>
                                    <th class="text-left">Color</th>
                                    <th class="w-16 text-center">1 KG</th>
                                    <th class="w-16 text-center">500GM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groutColors as $color)
                                <tr>
                                    <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                        {{ $color->name }}@if($color->brand) <span class="text-[9px] font-bold text-amber-600">[{{ $color->brand->name }}]</span>@endif
                                    </td>
                                    <td class="w-16">
                                        <input type="text" class="compact-input qty-input" 
                                               data-dept="GRT" 
                                               data-product-id="{{ $color->id }}" 
                                               data-packing="1 KG" readonly>
                                    </td>
                                    <td class="w-16">
                                        <input type="text" class="compact-input qty-input" 
                                               data-dept="GRT" 
                                               data-product-id="{{ $color->id }}" 
                                               data-packing="500GM" readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                    <!-- RESIN KIT -->
                    <div
                        class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200 product-card">
                        <div class="px-4 py-3 flex items-center gap-2 border-b border-slate-100">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                            <span class="text-xs font-black uppercase tracking-wider text-slate-800">Resin Kit</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full erp-table text-center min-w-[200px]">
                                <thead>
                                    <tr>
                                        <th class="text-left">Product</th>
                                        <th class="w-16 text-center">Box</th>
                                        <th class="w-24 text-center">Coupon</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $resinKitSizes = [
                                            '0.3KG' => $resinKitProduct->id ?? '',
                                            '1.5KG' => $resinKit15Product->id ?? '',
                                        ];
                                    @endphp
                                    @foreach($resinKitSizes as $size => $productId)
                                    <tr>
                                        <td
                                            class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                            {{ $size }}</td>
                                        <td class="w-16">
                                            <input type="text" class="compact-input qty-input"
                                                data-dept="EPX"
                                                data-product-id="{{ $productId }}"
                                                data-packing="{{ $size }}" readonly>
                                        </td>
                                        <td class="w-24">
                                            <input type="text" class="compact-input coupon-code-input uppercase"
                                                placeholder="None" data-dept="EPX"
                                                data-product-id="{{ $productId }}"
                                                data-packing="{{ $size }}" readonly>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- DYNAMIC COMPONENT CATEGORIES (COLUMN 2) --}}
                    @if(isset($componentCategoriesGrouped) && $componentCategoriesGrouped->has(2))
                        @foreach($componentCategoriesGrouped->get(2) as $cat)
                            @if($cat->components->isNotEmpty())
                                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200 product-card">
                                    <div class="px-4 py-3 flex items-center justify-between border-b border-slate-100">
                                        <div class="flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                            <span class="text-xs font-black uppercase tracking-wider text-slate-800">{{ $cat->name }}</span>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">{{ $cat->default_unit }}</span>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="w-full erp-table text-center min-w-[200px]">
                                            <thead>
                                                <tr>
                                                    <th class="text-left">{{ $cat->name }}</th>
                                                    <th class="w-20 text-center">{{ $cat->default_unit }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cat->components as $comp)
                                                    <tr>
                                                        <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                                            {{ $comp->name }}
                                                        </td>
                                                        <td class="w-20">
                                                            <input type="text" class="compact-input qty-input"
                                                                data-dept="EPX"
                                                                data-component-id="{{ $comp->id }}"
                                                                data-packing="{{ $comp->default_packing ?? $cat->default_unit }}" readonly>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

                <!-- COLUMN 3 -->
                <div class="flex flex-col gap-6 order-col">
                    <!-- EPOXY -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200 product-card">
                        <div class="px-4 py-3 flex items-center gap-2 border-b border-slate-100">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                            <span class="text-xs font-black uppercase tracking-wider text-slate-800">Epoxy</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full erp-table text-center min-w-[320px]">
                                <thead>
                                    <tr>
                                        <th class="text-left">Color</th>
                                        <th class="w-14 text-center">1KG</th>
                                        <th class="w-20 text-center">Coupon</th>
                                        <th class="w-14 text-center">5KG</th>
                                        <th class="w-20 text-center">Coupon</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $epoxy1kg = $epoxies->first(fn($p) => str_contains($p->name, '1KG') || $p->code === '1B' || $p->code === '1B-B2');
                                        $epoxy5kg = $epoxies->first(fn($p) => str_contains($p->name, '5KG') || $p->code === '5B' || $p->code === '5B-B2');
                                    @endphp
                                    @foreach($epoxyColors as $color)
                                    <tr>
                                        <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                            {{ $color->code }} - {{ $color->name }}
                                        </td>
                                        <td class="w-14">
                                            <input type="text" class="compact-input qty-input" 
                                                   data-dept="EPX" 
                                                   data-product-id="{{ $epoxy1kg->id ?? '' }}" 
                                                   data-filler-color-id="{{ $color->id }}" 
                                                   data-packing="1KG" readonly>
                                        </td>
                                        <td class="w-20">
                                            <input type="text" class="compact-input coupon-code-input uppercase" 
                                                   placeholder="None" 
                                                   data-dept="EPX" 
                                                   data-product-id="{{ $epoxy1kg->id ?? '' }}" 
                                                   data-filler-color-id="{{ $color->id }}" 
                                                   data-packing="1KG" readonly>
                                        </td>
                                        <td class="w-14">
                                            <input type="text" class="compact-input qty-input" 
                                                   data-dept="EPX" 
                                                   data-product-id="{{ $epoxy5kg->id ?? '' }}" 
                                                   data-filler-color-id="{{ $color->id }}" 
                                                   data-packing="5KG" readonly>
                                        </td>
                                        <td class="w-20">
                                            <input type="text" class="compact-input coupon-code-input uppercase" 
                                                   placeholder="None" 
                                                   data-dept="EPX" 
                                                   data-product-id="{{ $epoxy5kg->id ?? '' }}" 
                                                   data-filler-color-id="{{ $color->id }}" 
                                                   data-packing="5KG" readonly>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- DYNAMIC COMPONENT CATEGORIES (COLUMN 3) --}}
                    @if(isset($componentCategoriesGrouped) && $componentCategoriesGrouped->has(3))
                        @foreach($componentCategoriesGrouped->get(3) as $cat)
                            @if($cat->components->isNotEmpty())
                                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200 product-card">
                                    <div class="px-4 py-3 flex items-center justify-between border-b border-slate-100">
                                        <div class="flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                            <span class="text-xs font-black uppercase tracking-wider text-slate-800">{{ $cat->name }}</span>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">{{ $cat->default_unit }}</span>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="w-full erp-table text-center min-w-[200px]">
                                            <thead>
                                                <tr>
                                                    <th class="text-left">{{ $cat->name }}</th>
                                                    <th class="w-20 text-center">{{ $cat->default_unit }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cat->components as $comp)
                                                    <tr>
                                                        <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                                            {{ $comp->name }}
                                                        </td>
                                                        <td class="w-20">
                                                            <input type="text" class="compact-input qty-input"
                                                                data-dept="EPX"
                                                                data-component-id="{{ $comp->id }}"
                                                                data-packing="{{ $comp->default_packing ?? $cat->default_unit }}" readonly>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

            <!-- COLUMN 4 -->
            <div class="flex flex-col gap-6 order-col">
                {{-- DYNAMIC COMPONENT CATEGORIES (COLUMN 4 & OTHERS) --}}
                @if(isset($componentCategoriesGrouped))
                    @foreach($componentCategoriesGrouped as $colNum => $cats)
                        @if(!in_array($colNum, [1, 2, 3]))
                            @foreach($cats as $cat)
                                @if($cat->components->isNotEmpty())
                                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200 product-card">
                                        <div class="px-4 py-3 flex items-center justify-between border-b border-slate-100">
                                            <div class="flex items-center gap-2">
                                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                                <span class="text-xs font-black uppercase tracking-wider text-slate-800">{{ $cat->name }}</span>
                                            </div>
                                            <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">{{ $cat->default_unit }}</span>
                                        </div>
                                        <div class="overflow-x-auto">
                                            <table class="w-full erp-table text-center min-w-[200px]">
                                                <thead>
                                                    <tr>
                                                        <th class="text-left">{{ $cat->name }}</th>
                                                        <th class="w-20 text-center">{{ $cat->default_unit }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($cat->components as $comp)
                                                        <tr>
                                                            <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                                                {{ $comp->name }}
                                                            </td>
                                                            <td class="w-20">
                                                                <input type="text" class="compact-input qty-input"
                                                                    data-dept="EPX"
                                                                    data-component-id="{{ $comp->id }}"
                                                                    data-packing="{{ $comp->default_packing ?? $cat->default_unit }}" readonly>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            </div>

        </div>

        <!-- Empty State Alert (Hidden by default) -->
        <div id="no-ordered-items-box" class="hidden text-center py-12 bg-slate-50/50 border border-slate-100 rounded-2xl">
            <p class="text-sm font-extrabold text-slate-500">No products found in this order.</p>
        </div>

        <!-- Remarks Notes (Always visible at bottom) -->
        <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Remarks / Notes</label>
            <div class="text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-xl px-3 py-2.5">
                {{ $order->remarks ?: 'No remarks' }}
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Pre-populate quantities from order items
        var isOrderEdited = @json($order->is_edited);
        var orderItems = @json($order->items);

        orderItems.forEach(function(item) {
            var qtyInput = null;
            var isItemEdited = !!item.is_edited;
            
            if (item.grade_id) {
                // TAD
                qtyInput = $('.qty-input[data-dept="TAD"][data-product-id="' + item.grade_id + '"]');
            } else if (item.color_id && item.department_code === 'GRT') {
                // GRT
                qtyInput = $('.qty-input[data-dept="GRT"][data-product-id="' + item.color_id + '"][data-packing="' + item.packing + '"]');
            } else if (item.epoxy_product_id) {
                // EPX Products
                if (item.epoxy_filler_color_id) {
                    qtyInput = $('.qty-input[data-dept="EPX"][data-product-id="' + item.epoxy_product_id + '"][data-filler-color-id="' + item.epoxy_filler_color_id + '"][data-packing="' + item.packing + '"]');
                } else {
                    qtyInput = $('.qty-input[data-dept="EPX"][data-product-id="' + item.epoxy_product_id + '"][data-packing="' + item.packing + '"]');
                }
            } else if (item.epoxy_component_id) {
                // EPX Components
                qtyInput = $('.qty-input[data-dept="EPX"][data-component-id="' + item.epoxy_component_id + '"]');
            }

            if (qtyInput && qtyInput.length) {
                qtyInput.val(item.quantity_bags);

                if (isItemEdited) {
                    qtyInput.removeClass('highlighted-qty-input').addClass('edited-qty-input');
                    var tr = qtyInput.closest('tr');
                    tr.addClass('edited-product-row');
                    var nameTd = tr.find('td:first-child');
                    if (nameTd.length && !nameTd.find('.edited-item-tag').length) {
                        nameTd.append('<span class="edited-item-tag inline-flex items-center gap-0.5 rounded bg-amber-200 px-1 py-0.5 text-[9px] font-black text-amber-900 border border-amber-300 ml-1 shadow-sm">✏️ Updated</span>');
                    }
                } else {
                    qtyInput.addClass('highlighted-qty-input');
                }
            }

            if (item.coupon_raw_material_id && item.coupon_material) {
                var codeInput = null;
                if (item.grade_id) {
                    codeInput = $('.coupon-code-input[data-dept="TAD"][data-product-id="' + item.grade_id + '"]');
                } else if (item.epoxy_product_id) {
                    if (item.epoxy_filler_color_id) {
                        codeInput = $('.coupon-code-input[data-dept="EPX"][data-product-id="' + item.epoxy_product_id + '"][data-filler-color-id="' + item.epoxy_filler_color_id + '"][data-packing="' + item.packing + '"]');
                    } else {
                        codeInput = $('.coupon-code-input[data-dept="EPX"][data-product-id="' + item.epoxy_product_id + '"][data-packing="' + item.packing + '"]');
                    }
                }
                if (codeInput && codeInput.length) {
                    codeInput.val(item.coupon_material.code);
                    if (isItemEdited) {
                        codeInput.removeClass('highlighted-qty-input').addClass('edited-qty-input');
                    } else {
                        codeInput.addClass('highlighted-qty-input');
                    }
                }
            }
        });

        // Function to filter products: Show Ordered Only vs Show All
        function applyFilter(onlyOrdered) {
            if (onlyOrdered) {
                // 1. In every product card, loop through tbody tr
                $('.product-card').each(function() {
                    var $card = $(this);
                    var cardHasOrderedItem = false;

                    $card.find('tbody tr').each(function() {
                        var $tr = $(this);
                        var rowHasQty = false;

                        $tr.find('.qty-input').each(function() {
                            var val = $(this).val();
                            if (val && parseInt(val) > 0) {
                                rowHasQty = true;
                            }
                        });

                        if (rowHasQty) {
                            $tr.show();
                            cardHasOrderedItem = true;
                        } else {
                            $tr.hide();
                        }
                    });

                    // 2. Hide card if no ordered items
                    if (cardHasOrderedItem) {
                        $card.show();
                    } else {
                        $card.hide();
                    }
                });

                // 3. Hide empty columns
                $('.order-col').each(function() {
                    var $col = $(this);
                    var visibleCards = $col.find('.product-card:visible').length;
                    if (visibleCards > 0) {
                        $col.show();
                    } else {
                        $col.hide();
                    }
                });

                // 4. Adapt grid columns based on count of visible columns
                var visibleCols = $('.order-col:visible').length;
                var $grid = $('#products-grid');
                $grid.removeClass('md:grid-cols-2 xl:grid-cols-3 xl:grid-cols-4 max-w-2xl max-w-4xl max-w-6xl');

                if (visibleCols === 1) {
                    $grid.addClass('grid-cols-1 max-w-2xl');
                } else if (visibleCols === 2) {
                    $grid.addClass('grid-cols-1 md:grid-cols-2 max-w-4xl');
                } else if (visibleCols === 3) {
                    $grid.addClass('grid-cols-1 md:grid-cols-2 xl:grid-cols-3 max-w-6xl');
                } else {
                    $grid.addClass('grid-cols-1 md:grid-cols-2 xl:grid-cols-4');
                }

                // Show empty alert if no items
                if (visibleCols === 0) {
                    $('#no-ordered-items-box').removeClass('hidden');
                } else {
                    $('#no-ordered-items-box').addClass('hidden');
                }

                // Update toggle button styles
                $('#btn-show-ordered')
                    .addClass('bg-white text-blue-700 shadow-sm font-black border border-slate-200/60')
                    .removeClass('text-slate-500 font-bold');
                $('#btn-show-all')
                    .removeClass('bg-white text-blue-700 shadow-sm font-black border border-slate-200/60')
                    .addClass('text-slate-500 font-bold');

            } else {
                // Show ALL products
                $('.product-card tbody tr').show();
                $('.product-card').show();
                $('.order-col').show();
                $('#no-ordered-items-box').addClass('hidden');

                var $grid = $('#products-grid');
                $grid.removeClass('max-w-2xl max-w-4xl max-w-6xl xl:grid-cols-3')
                     .addClass('grid-cols-1 md:grid-cols-2 xl:grid-cols-4');

                // Update toggle button styles
                $('#btn-show-all')
                    .addClass('bg-white text-blue-700 shadow-sm font-black border border-slate-200/60')
                    .removeClass('text-slate-500 font-bold');
                $('#btn-show-ordered')
                    .removeClass('bg-white text-blue-700 shadow-sm font-black border border-slate-200/60')
                    .addClass('text-slate-500 font-bold');
            }
        }

        // Apply immediately on page load (Default: show ordered products only)
        applyFilter(true);

        $('#btn-show-ordered').on('click', function() {
            applyFilter(true);
        });

        $('#btn-show-all').on('click', function() {
            applyFilter(false);
        });
    });
</script>
@endsection
