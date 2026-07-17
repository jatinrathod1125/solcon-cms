@extends('layouts.app')

@section('title', 'Marketing Orders')
@section('header-title', 'Marketing Orders')

@section('styles')
<style>
    .marketing-orders-page {
        --marketing-blue: #2563eb;
        --marketing-ink: #0f172a;
    }
    .tab-btn {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .tab-btn.active {
        background-color: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }
</style>
@endsection

@section('content')
<div class="marketing-orders-page mx-auto max-w-[1700px] space-y-6">

    <!-- ORDERS LIST SECTION CARD -->
    <section class="bg-white border border-slate-200 rounded-[24px] p-5 shadow-sm space-y-5">
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                    <i data-lucide="list" class="w-5 h-5"></i>
                </div>
                <h2 class="text-base font-extrabold text-slate-900">Orders List</h2>
            </div>

            <!-- Controls (Search, Filter, Export, Create) -->
            <div class="flex flex-wrap items-center gap-2.5">
                <label class="relative block w-full sm:w-64">
                    <span class="sr-only">Search orders</span>
                    <i data-lucide="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input id="marketingOrderSearch" type="search" placeholder="Search orders..." class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-xs font-bold text-slate-800 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100">
                </label>

                <button type="button" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                    <i data-lucide="filter" class="h-4 w-4 text-slate-400"></i>
                    <span>Filter</span>
                </button>

                <button type="button" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white px-4 text-xs font-bold transition shadow-sm">
                    <i data-lucide="download" class="h-4 w-4"></i>
                    <span>Download Excel</span>
                </button>

                <a href="{{ route('marketing.orders.create') }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-4 text-xs font-bold transition shadow-sm">
                    <i data-lucide="plus-circle" class="h-4 w-4"></i>
                    <span>Create Order</span>
                </a>
            </div>
        </header>

        <!-- Date Tabs -->
        <div class="flex gap-1.5 overflow-x-auto pb-1 bg-slate-100 p-1.5 rounded-xl border border-slate-200">
            <button type="button" class="tab-btn active inline-flex min-h-8 shrink-0 items-center px-4 py-1.5 text-xs font-extrabold rounded-lg border border-transparent">All Orders</button>
            @foreach(['July 2025', 'June 2025', 'May 2025', 'April 2025', 'March 2025', 'February 2025', 'January 2025', 'December 2024'] as $month)
                <button type="button" class="tab-btn inline-flex min-h-8 shrink-0 items-center px-4 py-1.5 text-xs font-extrabold rounded-lg border border-transparent text-slate-500 hover:text-slate-900">{{ $month }}</button>
            @endforeach
            <button type="button" class="tab-btn inline-flex min-h-8 shrink-0 items-center px-4 py-1.5 text-xs font-extrabold rounded-lg border border-transparent text-slate-500 hover:text-slate-900">More <i data-lucide="chevron-down" class="w-3.5 h-3.5 ml-1"></i></button>
        </div>

        <!-- Orders Table -->
        <div class="overflow-x-auto border border-slate-200 rounded-2xl bg-white shadow-sm">
            <table class="w-full text-left text-xs font-semibold text-slate-700">
                <thead class="bg-slate-50 text-slate-500 uppercase font-extrabold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="p-3.5">Order No.</th>
                        <th class="p-3.5">Delivery Date</th>
                        <th class="p-3.5">Party Name</th>
                        <th class="p-3.5">City</th>
                        <th class="p-3.5 text-center">Total Items</th>
                        <th class="p-3.5">Created By</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-150">
                    @php
                        $staticOrders = [
                            ['no' => '01', 'date' => '16/07/2025', 'party' => 'Shree Ram Tiles', 'city' => 'Morbi', 'items' => 24, 'creator' => 'Admin'],
                            ['no' => '02', 'date' => '15/07/2025', 'party' => 'Jay Mataji Traders', 'city' => 'Rajkot', 'items' => 18, 'creator' => 'Admin'],
                            ['no' => '03', 'date' => '14/07/2025', 'party' => 'Om Tiles', 'city' => 'Surendranagar', 'items' => 15, 'creator' => 'User'],
                            ['no' => '04', 'date' => '13/07/2025', 'party' => 'Krishna Hardware', 'city' => 'Wankaner', 'items' => 21, 'creator' => 'Admin'],
                            ['no' => '05', 'date' => '12/07/2025', 'party' => 'Shakti Traders', 'city' => 'Jamnagar', 'items' => 12, 'creator' => 'User'],
                        ];
                    @endphp
                    @foreach($staticOrders as $o)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-3.5 font-bold text-blue-600">{{ $o['no'] }}</td>
                        <td class="p-3.5 text-slate-500 font-mono">{{ $o['date'] }}</td>
                        <td class="p-3.5 font-bold text-slate-800">{{ $o['party'] }}</td>
                        <td class="p-3.5 font-bold text-slate-700">{{ $o['city'] }}</td>
                        <td class="p-3.5 text-center font-black text-slate-800">{{ $o['items'] }}</td>
                        <td class="p-3.5 font-bold text-slate-600">{{ $o['creator'] }}</td>
                        <td class="p-3.5 text-right">
                            <div class="inline-flex items-center gap-1.5">
                                <button type="button" class="h-8 w-8 inline-flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition" title="View details">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <button type="button" class="h-8 w-8 inline-flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Edit order">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <button type="button" class="h-8 w-8 inline-flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition" title="Delete order">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <footer class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
            <p class="text-xs font-bold text-slate-500">Showing 1 to 5 of 128 orders</p>

            <div class="flex items-center gap-1 font-bold text-xs select-none">
                <button type="button" class="h-8 w-8 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>
                <button type="button" class="h-8 w-8 inline-flex items-center justify-center rounded-lg bg-emerald-600 text-white">1</button>
                <button type="button" class="h-8 w-8 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">2</button>
                <button type="button" class="h-8 w-8 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">3</button>
                <button type="button" class="h-8 w-8 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">4</button>
                <button type="button" class="h-8 w-8 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">5</button>
                <span class="px-1 text-slate-400 font-normal">...</span>
                <button type="button" class="h-8 w-8 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">26</button>
                <button type="button" class="h-8 w-8 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>
            </div>
        </footer>
    </section>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Toggle tab styles
        $('.tab-btn').on('click', function() {
            $('.tab-btn').removeClass('active');
            $(this).addClass('active');
        });
    });
</script>
@endsection
