@extends('layouts.app')

@section('title', 'Create Order')
@section('header-title', 'Create Order')

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
        background: #ffffff !important;
        padding: 2px 6px !important;
        font-size: 11px !important;
        text-align: center !important;
        box-shadow: none !important;
        width: 100% !important;
    }

    .page-content .marketing-orders-page input.compact-input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.12) !important;
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

    .compact-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        font-size: 13px;
        background-color: #ffffff;
        color: #334155;
        outline: none;
        transition: all 0.2s ease;
    }

    .compact-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    /* 📱 MOBILE VIEW: Override the app.js automatic responsive-table card conversions */
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

    <form id="createOrderForm" action="{{ route('marketing.orders.store') }}" method="POST">
        @csrf
        <section class="bg-white border border-slate-200 rounded-[24px] p-5 shadow-sm space-y-5">
            <header class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-base font-extrabold text-slate-900">Create Order</h2>
                </div>
                <a href="{{ route('marketing.orders.index') }}" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back to Orders
                </a>
            </header>

            <!-- Order Header Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Order No. <span class="text-rose-500">*</span></label>
                    <input type="text" value="01" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-800 focus:outline-none" readonly>
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Delivery Date <span class="text-rose-500">*</span></label>
                    <input type="date" value="2025-07-16" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Party Name <span class="text-rose-500">*</span></label>
                    <input type="text" placeholder="Select or type party name" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">City <span class="text-rose-500">*</span></label>
                    <input type="text" placeholder="Select or type city" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                </div>
            </div>

            <!-- 4-Column Product Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

                <!-- COLUMN 1 -->
                <div class="flex flex-col gap-6">
                    <!-- TILE ADHESIVE -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                        <th class="w-20 text-center">Coupen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($adhesives as $grade)
                                    <tr>
                                        <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                            {{$grade->name }}
                                        </td>
                                        <td class="w-16"><input type="text" class="compact-input"></td>
                                        <td class="w-20"><input type="text" class="compact-input"></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TILES CLEANER -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                        <td class="w-20"><input type="text" class="compact-input"></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- GROUT ADMIX -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                        <td class="w-20"><input type="text" class="compact-input"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- COLUMN 2 -->
                <div class="flex flex-col gap-6">
                    <!-- TILES GROUT -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                        <td class="w-16"><input type="text" class="compact-input"></td>
                                        <td class="w-16"><input type="text" class="compact-input"></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Side-by-Side Nested Grid for Solitite/Resin Kit & Jari Powder -->
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
                                                <td class="w-14"><input type="text" class="compact-input"></td>
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
                                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-850">Resin Kit</span>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full erp-table text-center min-w-[100px]">
                                        <tbody>
                                            @foreach(['0.3KG', '1.5KG'] as $size)
                                            <tr>
                                                <td class="text-left font-bold text-slate-700 text-[10px] whitespace-nowrap">{{ $size }}</td>
                                                <td class="w-14"><input type="text" class="compact-input"></td>
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
                                            <tr>
                                                <td class="text-left font-bold text-slate-700 text-[10px] whitespace-nowrap">{{ $color }}</td>
                                                <td class="w-14"><input type="text" class="compact-input"></td>
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
                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                        <th class="w-16 text-center">Coupen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($epoxyColors as $color)
                                    <tr>
                                        <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">
                                            {{ $color->code }} - {{ $color->name }}
                                        </td>
                                        <td class="w-14"><input type="text" class="compact-input"></td>
                                        <td class="w-14"><input type="text" class="compact-input"></td>
                                        <td class="w-16"><input type="text" class="compact-input"></td>
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
                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                        <td class="w-20"><input type="text" class="compact-input"></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TILES LEVELER -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                        <td class="w-20"><input type="text" class="compact-input"></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- SB+ -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                    <tr>
                                        <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">{{ $size }}</td>
                                        <td class="w-20"><input type="text" class="compact-input"></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- SB++ -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                    <tr>
                                        <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">{{ $size }}</td>
                                        <td class="w-20"><input type="text" class="compact-input"></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- SK+ -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
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
                                    <tr>
                                        <td class="text-left font-bold text-slate-700 whitespace-nowrap text-[10px] sm:text-xs">{{ $size }}</td>
                                        <td class="w-20"><input type="text" class="compact-input"></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Form Action Buttons -->
                    <div class="flex gap-3 justify-end pt-2">
                        <button type="submit" class="erp-button bg-[#10b981] hover:bg-emerald-600 text-white !py-2 px-6 text-sm rounded-xl font-bold transition shadow-sm w-full sm:w-auto">Save Order</button>
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
        $('#createOrderForm').on('submit', function(e) {
            e.preventDefault();
            // Static simulated save for this phase
            Swal.fire({
                title: 'Order Created!',
                text: 'The order has been created successfully.',
                icon: 'success',
                confirmButtonColor: '#2563eb'
            }).then(() => {
                window.location.href = "{{ route('marketing.orders.index') }}";
            });
        });
    });
</script>
@endsection
