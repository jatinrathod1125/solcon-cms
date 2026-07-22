@extends('layouts.app')

@section('title', 'Formula Details')
@section('header-title', 'Formula Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Navigation Back Link -->
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.formulas.index') }}" class="inline-flex items-center text-sm text-slate-400 hover:text-white transition-colors gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Formulas</span>
        </a>
        <a href="{{ route('admin.formulas.edit', $formula) }}" class="inline-flex items-center px-3.5 py-1.5 bg-slate-900 border border-slate-800 hover:bg-slate-850 rounded-xl text-xs font-semibold text-white gap-2 transition-all">
            <i data-lucide="edit-2" class="w-3.5 h-3.5 text-cyan-400"></i>
            <span>Edit Version</span>
        </a>
    </div>

    <!-- Details Card -->
    <div class="bg-slate-955 border border-slate-850 rounded-2xl shadow-xl p-6 md:p-8 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-850 pb-6">
            <div>
                <h3 class="text-xl font-bold text-white flex items-center gap-3">
                    <span>{{ $formula->grade->name }}</span>
                    <span class="px-2 py-0.5 rounded bg-indigo-950 text-indigo-300 border border-indigo-900 text-xs font-mono font-bold">
                        v{{ $formula->version }}
                    </span>
                </h3>
                <p class="text-sm text-slate-500 mt-1">Grade Code: <span class="font-mono text-slate-350">{{ $formula->grade->code }}</span></p>
            </div>
            <div>
                @if($formula->is_active)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        Active Formula
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-800 text-slate-400 border border-slate-700/50">
                        Inactive Formula
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">Remarks / Revision Notes</span>
                <p class="text-slate-300 bg-slate-900/40 border border-slate-850 rounded-xl p-3">
                    {{ $formula->remarks ?? 'No remarks provided.' }}
                </p>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">Audit Trail</span>
                <div class="text-slate-300 space-y-1 bg-slate-900/40 border border-slate-850 rounded-xl p-3">
                    <div>Created By: <span class="text-white font-medium">{{ $formula->creator->name ?? 'System' }}</span></div>
                    <div>Created At: <span class="text-white font-mono">{{ $formula->created_at->format('d M Y, h:i:s A') }}</span></div>
                    <div>Last Update: <span class="text-white font-mono">{{ $formula->updated_at->format('d M Y, h:i:s A') }}</span></div>
                </div>
            </div>
        </div>

        <!-- Formula Items Table -->
        <div class="space-y-3">
            <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Raw Material Composition</h4>
            <div class="border border-slate-850 rounded-xl overflow-hidden">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-850 bg-slate-900/60 text-slate-400 font-semibold">
                            <th class="p-3 w-16 text-center">Seq</th>
                            <th class="p-3 w-28">Material Code</th>
                            <th class="p-3">Material Name</th>
                            <th class="p-3 text-right">Quantity</th>
                            <th class="p-3 w-24">Unit</th>
                            <th class="p-3 w-32">Consumption</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-850/50">
                        @php $totalQty = 0; @endphp
                        @forelse($formula->items as $item)
                            <tr class="hover:bg-slate-900/30 text-slate-200">
                                <td class="p-3 text-center font-mono text-slate-500">
                                    {{ $item->sequence }}
                                </td>
                                <td class="p-3 font-mono font-semibold text-cyan-400">
                                    {{ $item->rawMaterial->code }}
                                </td>
                                <td class="p-3 font-semibold text-white">
                                    {{ $item->rawMaterial->name }}
                                </td>
                                <td class="p-3 text-right font-mono font-bold text-white">
                                    {{ format_quantity($item->quantity) }}
                                </td>
                                <td class="p-3 font-semibold text-indigo-300">
                                    {{ $item->unit->code }}
                                </td>
                                <td class="p-3">
                                    @if(($item->consumption_method ?? 'formula') === 'output')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                            Output Based
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-800 text-slate-400 border border-slate-700/50">
                                            Formula Based
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @php $totalQty += $item->quantity; @endphp
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-slate-500">
                                    No ingredients added.
                                </td>
                            </tr>
                        @endforelse
                        <!-- Total Row -->
                        <tr class="bg-slate-900/40 font-bold border-t border-slate-800 text-white">
                            <td colspan="3" class="p-3 text-right">Total Quantity:</td>
                            <td class="p-3 text-right font-mono text-cyan-400">{{ format_quantity($totalQty) }}</td>
                            <td colspan="2" class="p-3 text-slate-400">KG</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
