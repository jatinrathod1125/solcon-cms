@extends('layouts.app')

@section('title', 'Grout Formula Details')
@section('header-title', 'Formula Details')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Back Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.grout-formulas.index') }}" class="inline-flex items-center text-sm text-slate-400 hover:text-white transition-colors gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Formulas</span>
        </a>
        <a href="{{ route('admin.grout-formulas.edit', $formula) }}" class="inline-flex items-center px-4 py-2 bg-slate-900 border border-slate-800 hover:border-cyan-500/40 text-slate-300 hover:text-cyan-400 rounded-xl text-xs font-semibold gap-2 transition-all">
            <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
            <span>Edit Formula</span>
        </a>
    </div>

    <!-- Header info card -->
    <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-6 md:p-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Grout Color</p>
            <p class="text-lg font-bold text-white leading-snug">{{ $formula->color->name ?? 'N/A' }}</p>
            <p class="text-xs font-mono text-slate-400 mt-1 uppercase">{{ $formula->color->code ?? 'N/A' }}</p>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Version &amp; Status</p>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-lg font-mono font-extrabold text-indigo-400">V{{ $formula->version }}</span>
                @if($formula->is_active)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-450 border border-emerald-500/20">
                        Active
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700/50">
                        Inactive
                    </span>
                @endif
            </div>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Packaging &amp; Cement</p>
            <p class="text-sm font-semibold text-white mt-1">
                {{ $formula->color->packing_size ?? 'N/A' }} Packaging
            </p>
            <p class="text-xs text-slate-400 mt-1">
                Default: {{ $formula->color->default_cement ?? 'N/A' }}
            </p>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Created By &amp; On</p>
            <p class="text-sm font-semibold text-white mt-1">{{ $formula->creator->name ?? 'System' }}</p>
            <p class="text-xs font-mono text-slate-400 mt-1">{{ $formula->created_at->format('d M Y, h:i A') }}</p>
        </div>

        @if($formula->remarks)
            <div class="col-span-1 md:col-span-4 border-t border-slate-850 pt-4 mt-2">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Revision Remarks</p>
                <p class="text-xs text-slate-350 leading-relaxed">{{ $formula->remarks }}</p>
            </div>
        @endif
    </div>

    <!-- Stage Logic Explanation Banner -->
    <div class="bg-blue-950/30 border border-blue-900/50 rounded-2xl p-5 flex items-start gap-4 shadow-sm text-blue-300">
        <div class="w-10 h-10 rounded-xl bg-blue-900/40 flex items-center justify-center shrink-0 text-blue-400">
            <i data-lucide="info" class="w-5 h-5"></i>
        </div>
        <div class="space-y-1.5 text-xs">
            <h4 class="font-bold text-blue-200">Grout Mixing Stage Logic</h4>
            <p class="leading-relaxed">
                <strong>Stage 1 (Raw Material Dry Mixing):</strong> Ingredients such as Filler sand, MHEC, RDP, Chemicals, and Color pigment must dry mix for 1 hour before adding cement binding.
            </p>
            <p class="leading-relaxed">
                <strong>Stage 2 (Cement Binding):</strong> White Cement or Grey Cement must be added dynamically based on the Color definition.
                (e.g., Color <strong>White</strong> requires <strong>White Cement</strong>; Color <strong>Grey/Black/Chocolate</strong> requires <strong>Grey Cement</strong>).
            </p>
        </div>
    </div>

    <!-- Ingredients Panels -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Stage 1 Ingredients Panel -->
        <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-850 bg-slate-900/40 flex justify-between items-center">
                <h3 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    Stage 1 Ingredients (Dry Mix)
                </h3>
                <span class="px-2 py-0.5 rounded bg-blue-950 text-blue-300 border border-blue-900 text-[10px] font-mono font-bold">1 HOUR</span>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto rounded-xl border border-slate-850 bg-slate-900/10">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-slate-850 bg-slate-900/60 text-slate-400 font-semibold">
                                <th class="p-3 w-12 text-center">#</th>
                                <th class="p-3">Raw Material</th>
                                <th class="p-3 text-right">Quantity</th>
                                <th class="p-3 w-24">Unit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-850/50">
                            @forelse($stage1Items as $item)
                                <tr class="hover:bg-slate-900/20 text-slate-200">
                                    <td class="p-3 text-center font-mono text-slate-500">{{ $item->display_order + 1 }}</td>
                                    <td class="p-3">
                                        <div class="font-semibold text-white">{{ $item->rawMaterial->name }}</div>
                                        <div class="text-[10px] font-mono text-cyan-400 uppercase mt-0.5">{{ $item->rawMaterial->code }}</div>
                                    </td>
                                    <td class="p-3 text-right font-mono font-bold text-white">
                                        {{ number_format($item->quantity, 4) }}
                                    </td>
                                    <td class="p-3 text-slate-400 font-semibold">
                                        {{ $item->unit->code }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-500 italic">No Stage 1 ingredients.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Stage 2 Ingredients Panel -->
        <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-850 bg-slate-900/40 flex justify-between items-center">
                <h3 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Stage 2 Ingredients (Cement Base)
                </h3>
                <span class="px-2 py-0.5 rounded bg-emerald-950 text-emerald-300 border border-emerald-900 text-[10px] font-mono font-bold">{{ strtoupper($formula->color->default_cement ?? 'Grey Cement') }}</span>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto rounded-xl border border-slate-850 bg-slate-900/10">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-slate-850 bg-slate-900/60 text-slate-400 font-semibold">
                                <th class="p-3 w-12 text-center">#</th>
                                <th class="p-3">Raw Material</th>
                                <th class="p-3 text-right">Quantity</th>
                                <th class="p-3 w-24">Unit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-850/50">
                            @forelse($stage2Items as $item)
                                <tr class="hover:bg-slate-900/20 text-slate-200">
                                    <td class="p-3 text-center font-mono text-slate-500">{{ $item->display_order + 1 }}</td>
                                    <td class="p-3">
                                        <div class="font-semibold text-white">{{ $item->rawMaterial->name }}</div>
                                        <div class="text-[10px] font-mono text-cyan-400 uppercase mt-0.5">{{ $item->rawMaterial->code }}</div>
                                    </td>
                                    <td class="p-3 text-right font-mono font-bold text-white">
                                        {{ number_format($item->quantity, 4) }}
                                    </td>
                                    <td class="p-3 text-slate-400 font-semibold">
                                        {{ $item->unit->code }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-500 italic">No Stage 2 ingredients.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
