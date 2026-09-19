@extends('layouts.app')

@section('title', 'Edit Order')
@section('header-title', 'Edit Order')

@section('styles')
    <style>
        .marketing-orders-page {
            --marketing-blue: #2563eb;
            --marketing-ink: #0f172a;
        }

        .page-content .marketing-orders-page input.compact-input:not([type="checkbox"]):not([type="radio"]) {
            min-height: 38px !important;
            height: 38px !important;
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
            background: #ffffff !important;
            padding: 7px 10px !important;
            font-size: 13px !important;
            font-weight: 800 !important;
            line-height: 1.2 !important;
            text-align: center !important;
            box-shadow: none !important;
            width: 100% !important;
        }

        .page-content .marketing-orders-page input.qty-input {
            min-width: 68px !important;
        }

        .page-content .marketing-orders-page input.coupon-code-input {
            min-width: 118px !important;
            text-align: left !important;
        }

        .page-content .marketing-orders-page input.compact-input:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12) !important;
        }

        .marketing-orders-page form>section>header {
            gap: 1rem;
        }

        .marketing-orders-page label {
            font-size: 11px !important;
            line-height: 1.3 !important;
        }

        .marketing-orders-page .text-\[10px\] {
            font-size: 12px !important;
        }

        .marketing-orders-page .erp-table {
            border-collapse: separate;
            border-spacing: 0;
            table-layout: auto;
        }

        .erp-table th {
            font-weight: 800;
            text-transform: uppercase;
            font-size: 10px !important;
            letter-spacing: 0.08em;
            color: #64748b !important;
            background-color: #f8fafc !important;
            padding: 9px 10px !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .erp-table td {
            padding: 8px 10px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            font-size: 12px !important;
            font-weight: 700;
            line-height: 1.35;
            color: #334155;
        }

        .erp-table td:first-child {
            min-width: 96px;
            white-space: normal !important;
        }

        .erp-table tr:hover {
            background-color: #f8fafc !important;
        }

        .erp-table tr:focus-within {
            background-color: #eff6ff !important;
        }

        .compact-select {
            width: 100%;
            min-height: 46px;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 800;
            background-color: #ffffff;
            color: #334155;
            outline: none;
            transition: all 0.2s ease;
        }

        .compact-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
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

            .page-content .marketing-orders-page input.compact-input:not([type="checkbox"]):not([type="radio"]) {
                min-height: 42px !important;
                height: 42px !important;
                font-size: 14px !important;
            }

            .page-content .marketing-orders-page input.coupon-code-input {
                min-width: 128px !important;
            }

            .marketing-orders-page form>section>header {
                align-items: stretch !important;
                flex-direction: column !important;
            }

            .marketing-orders-page form>section>header a {
                min-height: 44px;
                width: 100%;
            }

            .marketing-orders-page section {
                padding: 16px !important;
                border-radius: 18px !important;
            }

            .marketing-orders-page .bg-slate-50\/50 {
                padding: 16px !important;
                border-radius: 16px !important;
            }

            .marketing-orders-page .grid {
                gap: 16px !important;
            }

            .marketing-orders-page .flex-col.gap-6 {
                gap: 16px !important;
            }

            .marketing-orders-page .overflow-x-auto {
                margin-inline: 0 !important;
                padding-inline: 0 !important;
            }

            .marketing-orders-page .erp-table th {
                font-size: 10px !important;
                padding: 9px 10px !important;
            }

            .marketing-orders-page .erp-table td {
                font-size: 13px !important;
                padding: 8px 10px !important;
            }

            .marketing-orders-page button[type="submit"] {
                min-height: 48px;
                width: 100%;
                font-size: 14px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="marketing-orders-page mx-auto max-w-[1700px]">

        <form id="editOrderForm" action="{{ route('marketing.orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <section class="bg-white border border-slate-200 rounded-[24px] p-5 shadow-sm space-y-5">
                <header
                    class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                            <i data-lucide="edit" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-base font-extrabold text-slate-900">Edit Order: {{ $order->order_number }}</h2>
                    </div>

                    <!-- Live Summary Counter Badges -->
                    <div
                        class="flex items-center gap-2.5 text-xs bg-slate-50 border border-slate-200 px-3.5 py-1.5 rounded-xl font-bold">
                        <span class="text-slate-500">Bags/Units: <strong id="live_top_units"
                                class="text-slate-900 font-black">0</strong></span>
                        <span class="text-slate-300">|</span>
                        <span class="text-slate-500">Weight: <strong id="live_top_kg"
                                class="text-emerald-600 font-black">0.0 KG</strong></span>
                        <span class="text-slate-300">|</span>
                        <span class="text-slate-500">Total: <strong id="live_top_ton" class="text-blue-600 font-black">0.00
                                Ton</strong></span>
                    </div>

                    @if(auth()->user()->isAdmin())
                    <button type="button" onclick="confirmDeleteOrder({{ $order->id }}, '{{ addslashes($order->order_number) }}')"
                        class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-4 text-xs font-bold text-rose-700 hover:bg-rose-100 transition">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Delete Order
                    </button>
                    @endif

                    <a href="{{ route('marketing.orders.index') }}"
                        class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Back to Orders
                    </a>
                </header>

                <datalist id="availableCouponsDatalist">
                    @if (isset($coupons))
                        @foreach ($coupons as $c)
                            <option value="{{ $c->code }}">{{ $c->name }} ({{ $c->code }})</option>
                        @endforeach
                    @endif
                </datalist>

                <!-- Order Header Fields -->
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Order
                            No. <span class="text-rose-500">*</span></label>
                        <input type="text" value="{{ $order->order_number }}"
                            class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-500 focus:outline-none"
                            readonly>
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Order
                            Date <span class="text-rose-500">*</span></label>
                        <input type="date" id="order_date" name="order_date"
                            value="{{ $order->order_date?->format('Y-m-d') }}"
                            class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Party
                            Name <span class="text-rose-500">*</span></label>
                        <input type="text" id="party_name" name="party_name" value="{{ $order->party_name }}"
                            placeholder="Type party name"
                            class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">City
                            <span class="text-rose-500">*</span></label>
                        <input type="text" id="city" name="city" value="{{ $order->city }}"
                            placeholder="Type city name"
                            class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Priority
                            <span class="text-rose-500">*</span></label>
                        <select id="priority" name="priority"
                            class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                            <option value="low" {{ $order->priority === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $order->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $order->priority === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ $order->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                </div>

                <!-- 4-Column Product Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

                    <!-- COLUMN 1 -->
                    <div class="flex flex-col gap-6">
                        <!-- TILE ADHESIVE -->
                        <div
                            class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="px-4 py-3 flex items-center gap-2 border-b border-slate-100">
                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                <span class="text-xs font-black uppercase tracking-wider text-slate-800">Tile
                                    Adhesive</span>
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
                                        @foreach ($adhesives as $grade)
                                            @php $packing = $grade->bagSize->name ?? '20KG'; @endphp
                                            <tr>
                                                <td
                                                    class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                                    {{ $grade->name }}@if ($grade->brand)
                                                        <span
                                                            class="text-[9px] font-bold text-amber-600">[{{ $grade->brand->name }}]</span>
                                                    @endif
                                                </td>
                                                <td class="w-16">
                                                    <input type="number" min="0" class="compact-input qty-input"
                                                        data-dept="TAD" data-product-id="{{ $grade->id }}"
                                                        data-packing="{{ $packing }}">
                                                </td>
                                                <td class="w-24">
                                                    <input type="text" list="availableCouponsDatalist"
                                                        class="compact-input coupon-code-input uppercase"
                                                        placeholder="Coupon" data-dept="TAD"
                                                        data-product-id="{{ $grade->id }}"
                                                        data-packing="{{ $packing }}">
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
                                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                                                <input type="number" min="0" class="compact-input qty-input"
                                                                    data-dept="EPX"
                                                                    data-component-id="{{ $comp->id }}"
                                                                    data-unit-weight="{{ $comp->weight_kg ?? '' }}"
                                                                    data-packing="{{ $comp->default_packing ?? $cat->default_unit }}">
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
                        <div
                            class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                            <td
                                                class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                                {{ $fComp->color?->code ?? $fComp->code }} - {{ $fComp->color?->name ?? str_replace('700gm ', '', str_replace(' Filler Pouch', '', $fComp->name)) }}
                                            </td>
                                            <td class="w-20">
                                                <input type="number" min="0" class="compact-input qty-input"
                                                    data-dept="EPX"
                                                    data-component-id="{{ $fComp->id }}"
                                                    data-filler-color-id="{{ $fComp->epoxy_filler_color_id }}"
                                                    data-unit-weight="{{ $fComp->weight_kg ?? 0.7 }}"
                                                    data-packing="700 GM">
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
                    <div class="flex flex-col gap-6">
                        <!-- TILES GROUT -->
                        <div
                            class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                        @foreach ($groutColors as $color)
                                            <tr>
                                                <td
                                                    class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                                    {{ $color->name }}@if ($color->brand)
                                                        <span
                                                            class="text-[9px] font-bold text-amber-600">[{{ $color->brand->name }}]</span>
                                                    @endif
                                                </td>
                                                <td class="w-16">
                                                    <input type="number" min="0" class="compact-input qty-input"
                                                        data-dept="GRT" data-product-id="{{ $color->id }}"
                                                        data-packing="1 KG">
                                                </td>
                                                <td class="w-16">
                                                    <input type="number" min="0" class="compact-input qty-input"
                                                        data-dept="GRT" data-product-id="{{ $color->id }}"
                                                        data-packing="500GM">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- RESIN KIT -->
                        <div
                            class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                        @foreach ($resinKitSizes as $size => $productId)
                                            <tr>
                                                <td
                                                    class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                                    {{ $size }}</td>
                                                <td class="w-16">
                                                    <input type="number" min="0" class="compact-input qty-input"
                                                        data-dept="EPX" data-product-id="{{ $productId }}"
                                                        data-packing="{{ $size }}">
                                                </td>
                                                <td class="w-24">
                                                    <input type="text" list="availableCouponsDatalist"
                                                        class="compact-input coupon-code-input uppercase"
                                                        placeholder="Coupon" data-dept="EPX"
                                                        data-product-id="{{ $productId }}"
                                                        data-packing="{{ $size }}">
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
                                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                                                <input type="number" min="0" class="compact-input qty-input"
                                                                    data-dept="EPX"
                                                                    data-component-id="{{ $comp->id }}"
                                                                    data-unit-weight="{{ $comp->weight_kg ?? '' }}"
                                                                    data-packing="{{ $comp->default_packing ?? $cat->default_unit }}">
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
                    <div class="flex flex-col gap-6">
                        <!-- EPOXY -->
                        <div
                            class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                            $epoxy1kg = $epoxies->first(
                                                fn($p) => str_contains($p->name, '1KG') ||
                                                    $p->code === '1B' ||
                                                    $p->code === '1B-B2',
                                            );
                                            $epoxy5kg = $epoxies->first(
                                                fn($p) => str_contains($p->name, '5KG') ||
                                                    $p->code === '5B' ||
                                                    $p->code === '5B-B2',
                                            );
                                        @endphp
                                        @foreach ($epoxyColors as $color)
                                            <tr>
                                                <td
                                                    class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                                    {{ $color->code }} - {{ $color->name }}
                                                </td>
                                                <td class="w-14">
                                                    <input type="number" min="0" class="compact-input qty-input"
                                                        data-dept="EPX" data-product-id="{{ $epoxy1kg->id ?? '' }}"
                                                        data-filler-color-id="{{ $color->id }}" data-packing="1KG">
                                                </td>
                                                <td class="w-20">
                                                    <input type="text" list="availableCouponsDatalist"
                                                        class="compact-input coupon-code-input uppercase"
                                                        placeholder="Coupon" data-dept="EPX"
                                                        data-product-id="{{ $epoxy1kg->id ?? '' }}"
                                                        data-filler-color-id="{{ $color->id }}" data-packing="1KG">
                                                </td>
                                                <td class="w-14">
                                                    <input type="number" min="0" class="compact-input qty-input"
                                                        data-dept="EPX" data-product-id="{{ $epoxy5kg->id ?? '' }}"
                                                        data-filler-color-id="{{ $color->id }}" data-packing="5KG">
                                                </td>
                                                <td class="w-20">
                                                    <input type="text" list="availableCouponsDatalist"
                                                        class="compact-input coupon-code-input uppercase"
                                                        placeholder="Coupon" data-dept="EPX"
                                                        data-product-id="{{ $epoxy5kg->id ?? '' }}"
                                                        data-filler-color-id="{{ $color->id }}" data-packing="5KG">
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
                                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                                                <input type="number" min="0" class="compact-input qty-input"
                                                                    data-dept="EPX"
                                                                    data-component-id="{{ $comp->id }}"
                                                                    data-unit-weight="{{ $comp->weight_kg ?? '' }}"
                                                                    data-packing="{{ $comp->default_packing ?? $cat->default_unit }}">
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
                    <div class="flex flex-col gap-6">
                        {{-- DYNAMIC COMPONENT CATEGORIES (COLUMN 4 & OTHERS) --}}
                        @if(isset($componentCategoriesGrouped))
                            @foreach($componentCategoriesGrouped as $colNum => $cats)
                                @if(!in_array($colNum, [1, 2, 3]))
                                    @foreach($cats as $cat)
                                        @if($cat->components->isNotEmpty())
                                            <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                                                        <input type="number" min="0" class="compact-input qty-input"
                                                                            data-dept="EPX"
                                                                            data-component-id="{{ $comp->id }}"
                                                                            data-unit-weight="{{ $comp->weight_kg ?? '' }}"
                                                                            data-packing="{{ $comp->default_packing ?? $cat->default_unit }}">
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

                        <!-- Remarks Notes -->
                        <div class="pt-2">
                            <label
                                class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Remarks
                                / Notes</label>
                            <textarea id="remarks" name="remarks" rows="2" placeholder="Add optional remarks..."
                                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">{{ $order->remarks }}</textarea>
                        </div>

                        <!-- Live Summary Card -->
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-2.5 mt-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">Total Order
                                    Units</span>
                                <span
                                    class="font-black text-slate-900 bg-white border border-slate-200 px-2.5 py-0.5 rounded-md text-xs"
                                    id="live_bottom_units">0</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">Total Weight
                                    (KG)</span>
                                <span
                                    class="font-black text-emerald-600 bg-white border border-slate-200 px-2.5 py-0.5 rounded-md text-xs"
                                    id="live_bottom_kg">0.0 KG</span>
                            </div>
                            <div class="flex items-center justify-between text-xs border-t border-slate-200 pt-2">
                                <span class="font-extrabold text-slate-700 uppercase tracking-wider text-[10px]">Total
                                    Weight (Tons)</span>
                                <span class="font-black text-blue-600 text-sm" id="live_bottom_ton">0.00 Ton</span>
                            </div>
                        </div>

                        <!-- Form Action Buttons -->
                        <div class="flex gap-3 justify-end pt-2">
                            <button type="submit"
                                class="erp-button bg-blue-600 hover:bg-blue-700 text-white !py-2 px-6 text-sm rounded-xl font-bold transition shadow-sm w-full sm:w-auto">Update
                                Order</button>
                        </div>
                    </div>

                </div>
            </section>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            function parsePackingWeight(packing, deptCode, unitWeight) {
                // If explicit unit weight was specified (e.g. from direct finished good component)
                if (unitWeight !== undefined && unitWeight !== null && unitWeight !== '') {
                    var parsedUw = parseFloat(unitWeight);
                    if (!isNaN(parsedUw) && parsedUw > 0) {
                        return parsedUw;
                    }
                }

                // In Grout (GRT), 1 bag is always 25 KG
                if (deptCode === 'GRT') {
                    return 25.0;
                }
                if (!packing) {
                    return deptCode === 'TAD' ? 20.0 : 1.0;
                }
                var str = packing.toString().toUpperCase().trim();

                if (str.includes('700GM') || str.includes('700 GM')) return 0.7;
                if (str.includes('500GM') || str.includes('500 GM')) return 0.5;
                if (str.includes('200GM') || str.includes('200 GM')) return 0.2;
                if (str.includes('100GM') || str.includes('100 GM')) return 0.1;
                if (str.includes('50GM') || str.includes('50 GM')) return 0.05;

                var match = str.match(/(\d+(?:\.\d+)?)/);
                if (match) {
                    var num = parseFloat(match[1]);
                    if (!isNaN(num) && num > 0) {
                        return num;
                    }
                }

                if (deptCode === 'TAD') return 20.0;
                return 1.0;
            }

            function updateLiveTotals() {
                var totalUnits = 0;
                var totalWeightKg = 0;

                $('.qty-input').each(function() {
                    var val = parseFloat($(this).val()) || 0;
                    if (val > 0) {
                        totalUnits += val;
                        var packing = $(this).data('packing') || '';
                        var dept = $(this).data('dept') || '';
                        var unitWeight = $(this).data('unit-weight');

                        var unitWeightVal = parsePackingWeight(packing, dept, unitWeight);
                        totalWeightKg += (val * unitWeightVal);
                    }
                });

                var totalTon = totalWeightKg / 1000.0;

                var formattedUnits = totalUnits.toLocaleString();
                var formattedKg = totalWeightKg.toLocaleString(undefined, {
                    minimumFractionDigits: 1,
                    maximumFractionDigits: 2
                }) + ' KG';
                var formattedTon = totalTon.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 3
                }) + ' Ton';

                $('#live_top_units, #live_bottom_units').text(formattedUnits);
                $('#live_top_kg, #live_bottom_kg').text(formattedKg);
                $('#live_top_ton, #live_bottom_ton').text(formattedTon);
            }

            $(document).on('input change', '.qty-input', function() {
                updateLiveTotals();
            });

            // Pre-populate quantities from order items
            var orderItems = @json($order->items);
            orderItems.forEach(function(item) {
                if (item.grade_id) {
                    // TAD
                    var qtyInput = $('.qty-input[data-dept="TAD"][data-product-id="' + item.grade_id +
                    '"]');
                    qtyInput.val(item.quantity_bags);
                    if (item.coupon_raw_material_id && item.coupon_material) {
                        var codeInput = $('.coupon-code-input[data-dept="TAD"][data-product-id="' + item
                            .grade_id + '"]');
                        codeInput.val(item.coupon_material.code);
                    }
                } else if (item.color_id && item.department_code === 'GRT') {
                    // GRT
                    var qtyInput = $('.qty-input[data-dept="GRT"][data-product-id="' + item.color_id +
                        '"][data-packing="' + item.packing + '"]');
                    qtyInput.val(item.quantity_bags);
                } else if (item.epoxy_product_id) {
                    // EPX Products
                    if (item.epoxy_filler_color_id) {
                        var qtyInput = $('.qty-input[data-dept="EPX"][data-product-id="' + item
                            .epoxy_product_id + '"][data-filler-color-id="' + item
                            .epoxy_filler_color_id + '"][data-packing="' + item.packing + '"]');
                        qtyInput.val(item.quantity_bags);

                        // Populate coupon details if exists on this epoxy color
                        if (item.coupon_raw_material_id && item.coupon_material) {
                            var codeInput = $('.coupon-code-input[data-dept="EPX"][data-product-id="' + item
                                .epoxy_product_id + '"][data-filler-color-id="' + item
                                .epoxy_filler_color_id + '"][data-packing="' + item.packing + '"]');
                            codeInput.val(item.coupon_material.code);
                        }
                    } else {
                        var qtyInput = $('.qty-input[data-dept="EPX"][data-product-id="' + item
                            .epoxy_product_id + '"][data-packing="' + item.packing + '"]');
                        qtyInput.val(item.quantity_bags);
                        // Populate coupon for EPX products without filler color (Resin Kit, etc.)
                        if (item.coupon_raw_material_id && item.coupon_material) {
                            var codeInput = $('.coupon-code-input[data-dept="EPX"][data-product-id="' + item
                                .epoxy_product_id + '"][data-packing="' + item.packing + '"]');
                            codeInput.val(item.coupon_material.code);
                        }
                    }
                } else if (item.epoxy_component_id) {
                    // EPX Components
                    var qtyInput = $('.qty-input[data-dept="EPX"][data-component-id="' + item
                        .epoxy_component_id + '"]');
                    qtyInput.val(item.quantity_bags);
                }
            });

            // Calculate initial live totals for existing order
            updateLiveTotals();

            // Form submit handler
            $('#editOrderForm').on('submit', function(e) {
                e.preventDefault();

                // Prepare items array
                var items = [];

                // Gathers all quantities that are entered (> 0)
                $('.qty-input').each(function() {
                    var val = parseInt($(this).val());
                    if (val > 0) {
                        var dept = $(this).data('dept');
                        var productId = $(this).data('product-id');
                        var colorId = $(this).data('color-id');
                        var fillerColorId = $(this).data('filler-color-id');
                        var componentId = $(this).data('component-id');
                        var packing = $(this).data('packing');

                        var item = {
                            department_code: dept,
                            quantity_bags: val,
                            packing: packing
                        };

                        if (productId) {
                            if (dept === 'TAD') {
                                item.grade_id = productId;

                                // Check for coupon code
                                var couponInput = $(
                                    '.coupon-code-input[data-dept="TAD"][data-product-id="' +
                                    productId + '"]');
                                var cCode = couponInput.val() ? couponInput.val().trim() : '';

                                if (cCode) {
                                    item.coupon_code = cCode;
                                    item.coupon_quantity = val; // Match bag quantity
                                }
                            } else if (dept === 'GRT') {
                                item.color_id = productId;
                            } else if (dept === 'EPX') {
                                item.epoxy_product_id = productId;
                                if (fillerColorId) {
                                    item.epoxy_filler_color_id = fillerColorId;

                                    // Check for coupon code on this Epoxy Color + Packing
                                    var couponInput = $(
                                        '.coupon-code-input[data-dept="EPX"][data-product-id="' +
                                        productId + '"][data-filler-color-id="' +
                                        fillerColorId + '"][data-packing="' + packing + '"]');
                                    var cCode = couponInput.val() ? couponInput.val().trim() : '';

                                    if (cCode) {
                                        item.coupon_code = cCode;
                                        item.coupon_quantity = val;
                                    }
                                } else {
                                    // For EPX products without filler color (Resin Kit, Solitite, etc.)
                                    var couponInput = $(
                                        '.coupon-code-input[data-dept="EPX"][data-product-id="' +
                                        productId + '"][data-packing="' + packing + '"]');
                                    var cCode = couponInput.val() ? couponInput.val().trim() : '';

                                    if (cCode) {
                                        item.coupon_code = cCode;
                                        item.coupon_quantity = val;
                                    }
                                }
                            }
                        } else if (componentId) {
                            // For dynamic Epoxy components (Jari, SB+, SB++, SK+, 700gm Filler Pouch)
                            item.epoxy_component_id = componentId;
                            if (fillerColorId) {
                                item.epoxy_filler_color_id = fillerColorId;
                            }
                            var unitWeight = $(this).data('unit-weight');
                            if (unitWeight !== undefined && unitWeight !== null && unitWeight !== '' && parseFloat(unitWeight) > 0) {
                                item.quantity_kg = val * parseFloat(unitWeight);
                            }
                        }

                        items.push(item);
                    }
                });

                if (items.length === 0) {
                    Swal.fire('Error', 'Please enter a quantity for at least one product.', 'error');
                    return;
                }

                var partyName = $('#party_name').val().trim();
                var orderDate = $('#order_date').val();
                var city = $('#city').val().trim();
                var vehicleNumber = $('#vehicle_number').val() ? $('#vehicle_number').val().trim() : '';
                var priority = $('#priority').val();
                var remarks = $('#remarks').val();

                if (!partyName) {
                    Swal.fire('Error', 'Party name is required.', 'error');
                    return;
                }

                // AJAX call
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT',
                        party_name: partyName,
                        order_date: orderDate,
                        city: city,
                        vehicle_number: vehicleNumber,
                        priority: priority,
                        remarks: remarks,
                        items: items
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Order Updated!',
                                text: 'The order has been updated successfully.',
                                icon: 'success',
                                confirmButtonColor: '#2563eb'
                            }).then(() => {
                                window.location.href =
                                    "{{ route('marketing.orders.index') }}";
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Failed to update order.',
                                'error');
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = 'Failed to update order.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire('Error', errorMsg, 'error');
                    }
                });
            });
        });

        function confirmDeleteOrder(orderId, orderNumber) {
            orderNumber = orderNumber || ('#' + orderId);
            Swal.fire({
                title: 'Delete Order ' + orderNumber + '?',
                text: 'Are you sure you want to permanently delete this order from the database? This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, permanently delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting order...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '/marketing/orders/' + orderId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message || 'The order has been permanently deleted.',
                                    'success'
                                ).then(() => {
                                    window.location.href = "{{ route('marketing.orders.index') }}";
                                });
                            } else {
                                Swal.fire('Error', response.message || 'Failed to delete order.', 'error');
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
