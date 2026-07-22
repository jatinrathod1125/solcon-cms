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
                    <h2 class="text-base font-extrabold text-slate-900">Order details: {{ $order->order_number }}</h2>
                    <p class="text-xs text-slate-400 font-bold">Created by {{ $order->creator->name ?? 'System' }} on {{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($order->status === 'pending' || auth()->user()->isAdmin())
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

        <!-- 4-Column Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <!-- COLUMN 1 -->
            <div class="flex flex-col gap-6">
                <!-- TILE ADHESIVE -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm">
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
                                        {{$grade->name }}
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

                <!-- TILES CLEANER -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                    <div class="px-4 py-3 flex items-center gap-2 border-b border-slate-100">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-800">Tiles Cleaner</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full erp-table text-center min-w-[200px]">
                            <thead>
                                <tr>
                                    <th class="text-left">Product</th>
                                    <th class="w-20 text-center">Box</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['1-LTR', '5-LTR'] as $prod)
                                <tr>
                                    <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">{{ $prod }}</td>
                                    <td class="w-20">
                                        <input type="text" class="compact-input qty-input" 
                                               data-dept="EPX" 
                                               data-product-id="{{ $tilesCleanerProduct->id ?? '' }}" 
                                               data-packing="{{ $prod }}" readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- GROUT ADMIX -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                    <div class="px-4 py-3 flex items-center gap-2 border-b border-slate-100">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-800">Grout Admix</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full erp-table text-center min-w-[200px]">
                            <thead>
                                <tr>
                                    <th class="text-left">Product</th>
                                    <th class="w-20 text-center">Box</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">200GM Admix</td>
                                    <td class="w-20">
                                        <input type="text" class="compact-input qty-input" 
                                               data-dept="EPX" 
                                               data-product-id="{{ $groutAdmixProduct->id ?? '' }}" 
                                               data-packing="200GM" readonly>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- COLUMN 2 -->
            <div class="flex flex-col gap-6">
                <!-- TILES GROUT -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm">
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
                                        {{ $color->name }}
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

                <!-- Solitite/Resin Kit & Jari Powder -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Left Stack (Solitite & Resin Kit) -->
                    <div class="flex flex-col gap-4">
                        <!-- SOLITITE -->
                        <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                            <div class="px-3 py-2 flex items-center gap-2 border-b border-slate-100 bg-slate-50/50">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-850">Solitite</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full erp-table text-center min-w-[100px]">
                                    <tbody>
                                        @foreach(['1.8KG', '900 GM', '450 GM'] as $size)
                                        <tr>
                                            <td class="text-left font-bold text-slate-700 text-[10px] whitespace-nowrap">{{ $size }}</td>
                                            <td class="w-14">
                                                <input type="text" class="compact-input qty-input" 
                                                       data-dept="EPX" 
                                                       data-product-id="{{ $solititeProduct->id ?? '' }}" 
                                                       data-packing="{{ $size }}" readonly>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- RESIN KIT -->
                        <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                            <div class="px-3 py-2 flex items-center gap-2 border-b border-slate-100 bg-slate-50/50">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-855">Resin Kit</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full erp-table text-center min-w-[100px]">
                                    <tbody>
                                        @foreach(['0.3KG', '1.5KG'] as $size)
                                        <tr>
                                            <td class="text-left font-bold text-slate-700 text-[10px] whitespace-nowrap">{{ $size }}</td>
                                            <td class="w-14">
                                                <input type="text" class="compact-input qty-input" 
                                                       data-dept="EPX" 
                                                       data-product-id="{{ $resinKitProduct->id ?? '' }}" 
                                                       data-packing="{{ $size }}" readonly>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Right Stack (Jari Powder) -->
                    <div>
                        <!-- JARI POWDER -->
                        <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm h-full flex flex-col">
                            <div class="px-3 py-2 flex items-center gap-2 border-b border-slate-100 bg-slate-50/50">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-850">Jari Powder</span>
                            </div>
                            <div class="overflow-x-auto flex-1">
                                <table class="w-full erp-table text-center min-w-[100px]">
                                    <thead>
                                        <tr>
                                            <th class="text-left">Color</th>
                                            <th class="w-14 text-center">Pckt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(['SILVER', 'COPPER', 'GOLD', 'RED'] as $color)
                                        @php 
                                            $comp = $jariComponents->first(fn($c) => str_contains(strtolower($c->name), strtolower($color)));
                                        @endphp
                                        <tr>
                                            <td class="text-left font-bold text-slate-700 text-[10px] whitespace-nowrap">{{ $color }}</td>
                                            <td class="w-14">
                                                <input type="text" class="compact-input qty-input" 
                                                       data-dept="EPX" 
                                                       data-component-id="{{ $comp->id ?? '' }}" 
                                                       data-packing="Pckt" readonly>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COLUMN 3 -->
            <div class="flex flex-col gap-6">
                <!-- EPOXY -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                    <div class="px-4 py-3 flex items-center gap-2 border-b border-slate-100">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-800">Epoxy</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full erp-table text-center min-w-[220px]">
                            <thead>
                                <tr>
                                    <th class="text-left">Color</th>
                                    <th class="w-14 text-center">1KG</th>
                                    <th class="w-14 text-center">5KG</th>
                                    <th class="w-20 text-center">Coupon</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($epoxyColors as $color)
                                @php 
                                    $epoxy1kg = $epoxies->where('code', '1B')->first();
                                    $epoxy5kg = $epoxies->where('code', '5B')->first();
                                @endphp
                                <tr>
                                    <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                        {{ $color->code }} - {{ $color->name }}
                                    </td>
                                    <td class="w-14">
                                        <input type="text" class="compact-input qty-input" 
                                               data-dept="EPX" 
                                               data-product-id="{{ $epoxy1kg->id ?? 1 }}" 
                                               data-filler-color-id="{{ $color->id }}" 
                                               data-packing="1KG" readonly>
                                    </td>
                                    <td class="w-14">
                                        <input type="text" class="compact-input qty-input" 
                                               data-dept="EPX" 
                                               data-product-id="{{ $epoxy5kg->id ?? 2 }}" 
                                               data-filler-color-id="{{ $color->id }}" 
                                               data-packing="5KG" readonly>
                                    </td>
                                    <td class="w-20">
                                        <input type="text" class="compact-input coupon-code-input uppercase" 
                                               placeholder="None" 
                                               data-dept="EPX" 
                                               data-product-id="{{ $epoxy1kg->id ?? 1 }}" 
                                               data-filler-color-id="{{ $color->id }}" 
                                               data-packing="1KG" readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- COLUMN 4 -->
            <div class="flex flex-col gap-6">
                <!-- SPACER -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                    <div class="px-4 py-3 flex items-center gap-2 border-b border-slate-100">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-800">Spacer</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full erp-table text-center min-w-[200px]">
                            <thead>
                                <tr>
                                    <th class="text-left">Spacer</th>
                                    <th class="w-20 text-center">Pckt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['2MM', '3MM', '4MM', '5MM', '6MM'] as $size)
                                <tr>
                                    <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">{{ $size }}</td>
                                    <td class="w-20">
                                        <input type="text" class="compact-input qty-input" 
                                               data-dept="EPX" 
                                               data-product-id="{{ $spacerProduct->id ?? '' }}" 
                                               data-packing="{{ $size }}" readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TILES LEVELER -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                    <div class="px-4 py-3 flex items-center gap-2 border-b border-slate-100">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-800">Tiles Leveler</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full erp-table text-center min-w-[200px]">
                            <thead>
                                <tr>
                                    <th class="text-left">Tiles Leveler</th>
                                    <th class="w-20 text-center">Pckt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['CLIP 2MM', 'CLIP 3MM', 'CLIP 4MM', 'WEDGE', 'LEVELLING JACK SPACER', 'TROWEL', 'PLIER', 'VACUUM'] as $type)
                                <tr>
                                    <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">{{ $type }}</td>
                                    <td class="w-20">
                                        <input type="text" class="compact-input qty-input" 
                                               data-dept="EPX" 
                                               data-product-id="{{ $levelerProduct->id ?? '' }}" 
                                               data-packing="{{ $type }}" readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SB+ -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                    <div class="px-4 py-3 flex items-center gap-2 border-b border-slate-100">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-800">SB+</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full erp-table text-center min-w-[200px]">
                            <thead>
                                <tr>
                                    <th class="text-left">SB+</th>
                                    <th class="w-20 text-center">Box</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['1 KG', '5 KG', '20 KG'] as $size)
                                @php 
                                    $comp = $sbPlusComponents->first(fn($c) => str_contains($c->name, $size));
                                @endphp
                                <tr>
                                    <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">{{ $size }}</td>
                                    <td class="w-20">
                                        <input type="text" class="compact-input qty-input" 
                                               data-dept="EPX" 
                                               data-component-id="{{ $comp->id ?? '' }}" 
                                               data-packing="Box" readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SB++ -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                    <div class="px-4 py-3 flex items-center gap-2 border-b border-slate-100">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-800">SB++</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full erp-table text-center min-w-[200px]">
                            <thead>
                                <tr>
                                    <th class="text-left">SB++</th>
                                    <th class="w-20 text-center">Box</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['1 KG', '5 KG', '20 KG'] as $size)
                                @php 
                                    $comp = $sbPlusPlusComponents->first(fn($c) => str_contains($c->name, $size));
                                @endphp
                                <tr>
                                    <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">{{ $size }}</td>
                                    <td class="w-20">
                                        <input type="text" class="compact-input qty-input" 
                                               data-dept="EPX" 
                                               data-component-id="{{ $comp->id ?? '' }}" 
                                               data-packing="Box" readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SK+ -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                    <div class="px-4 py-3 flex items-center gap-2 border-b border-slate-100">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-800">SK+</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full erp-table text-center min-w-[200px]">
                            <thead>
                                <tr>
                                    <th class="text-left">SK+</th>
                                    <th class="w-20 text-center">Box</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['1 LTR', '5 LTR', '20 LTR'] as $size)
                                @php 
                                    $comp = $skPlusComponents->first(fn($c) => str_contains($c->name, $size));
                                @endphp
                                <tr>
                                    <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">{{ $size }}</td>
                                    <td class="w-20">
                                        <input type="text" class="compact-input qty-input" 
                                               data-dept="EPX" 
                                               data-component-id="{{ $comp->id ?? '' }}" 
                                               data-packing="Box" readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Remarks Notes -->
                <div class="pt-2">
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Remarks / Notes</label>
                    <textarea id="remarks" name="remarks" rows="2" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-500 focus:outline-none" readonly>{{ $order->remarks ?: 'No remarks' }}</textarea>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Pre-populate quantities from order items
        var orderItems = @json($order->items);
        orderItems.forEach(function(item) {
            var qtyInput = null;
            
            if (item.grade_id) {
                // TAD
                qtyInput = $('.qty-input[data-dept="TAD"][data-product-id="' + item.grade_id + '"]');
                qtyInput.val(item.quantity_bags).addClass('highlighted-qty-input');
                if (item.coupon_raw_material_id && item.coupon_material) {
                    var codeInput = $('.coupon-code-input[data-dept="TAD"][data-product-id="' + item.grade_id + '"]');
                    codeInput.val(item.coupon_material.code).addClass('highlighted-qty-input');
                }
            } else if (item.color_id && item.department_code === 'GRT') {
                // GRT
                qtyInput = $('.qty-input[data-dept="GRT"][data-product-id="' + item.color_id + '"][data-packing="' + item.packing + '"]');
                qtyInput.val(item.quantity_bags).addClass('highlighted-qty-input');
            } else if (item.epoxy_product_id) {
                // EPX Products
                if (item.epoxy_filler_color_id) {
                    qtyInput = $('.qty-input[data-dept="EPX"][data-product-id="' + item.epoxy_product_id + '"][data-filler-color-id="' + item.epoxy_filler_color_id + '"][data-packing="' + item.packing + '"]');
                    qtyInput.val(item.quantity_bags).addClass('highlighted-qty-input');
                    if (item.coupon_raw_material_id && item.coupon_material) {
                        var codeInput = $('.coupon-code-input[data-dept="EPX"][data-product-id="' + item.epoxy_product_id + '"][data-filler-color-id="' + item.epoxy_filler_color_id + '"]');
                        codeInput.val(item.coupon_material.code).addClass('highlighted-qty-input');
                    }
                } else {
                    qtyInput = $('.qty-input[data-dept="EPX"][data-product-id="' + item.epoxy_product_id + '"][data-packing="' + item.packing + '"]');
                    qtyInput.val(item.quantity_bags).addClass('highlighted-qty-input');
                }
            } else if (item.epoxy_component_id) {
                // EPX Components
                qtyInput = $('.qty-input[data-dept="EPX"][data-component-id="' + item.epoxy_component_id + '"]');
                qtyInput.val(item.quantity_bags).addClass('highlighted-qty-input');
            }
        });
    });
</script>
@endsection
