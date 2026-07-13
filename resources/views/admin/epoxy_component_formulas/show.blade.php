@extends('layouts.app')

@section('title', 'Component Formula Detail')
@section('header-title', 'View Component Formula')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.epoxy-component-formulas.index') }}" class="p-2 hover:bg-slate-900 rounded-xl text-slate-400 hover:text-white transition-all">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white">Formula Details: {{ $epoxyComponentFormula->component->name }}</h2>
            <p class="text-xs text-slate-500">Active recipe version and required ingredient metrics.</p>
        </div>
    </div>

    <div class="bg-slate-955 border border-slate-850 p-6 rounded-2xl shadow-xl space-y-6">
        <!-- Metadata cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-900/50 p-4 rounded-xl border border-slate-900 text-xs">
            <div>
                <span class="text-slate-500 block uppercase font-bold tracking-wider mb-1">Component</span>
                <span class="text-sm text-white font-bold block">{{ $epoxyComponentFormula->component->name }}</span>
                <span class="font-mono text-cyan-400">{{ $epoxyComponentFormula->component->code }}</span>
            </div>
            <div>
                <span class="text-slate-500 block uppercase font-bold tracking-wider mb-1">Version</span>
                <span class="text-sm text-white font-bold block font-mono">v{{ $epoxyComponentFormula->version }}</span>
            </div>
            <div>
                <span class="text-slate-500 block uppercase font-bold tracking-wider mb-1">Status</span>
                @if($epoxyComponentFormula->is_active)
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 mt-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>Active
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-500/10 text-slate-400 mt-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Inactive
                    </span>
                @endif
            </div>
        </div>

        @if($epoxyComponentFormula->description)
            <div class="bg-slate-900/20 border border-slate-900 p-4 rounded-xl text-xs">
                <span class="text-slate-400 font-bold block mb-1">Remarks:</span>
                <p class="text-slate-300 leading-relaxed">{{ $epoxyComponentFormula->description }}</p>
            </div>
        @endif

        <!-- Ingredients Table -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Ingredients List</h3>
            <div class="bg-slate-950 border border-slate-900 rounded-xl overflow-hidden">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-900 bg-slate-900/30 text-slate-400 font-semibold">
                            <th class="p-3">Raw Material</th>
                            <th class="p-3 w-32 text-right">Quantity Required</th>
                            <th class="p-3 w-40">Unit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-900 text-slate-350">
                        @foreach($epoxyComponentFormula->items as $item)
                        <tr>
                            <td class="p-3">
                                <span class="font-bold text-white block">{{ $item->rawMaterial->name }}</span>
                                <span class="font-mono text-slate-500 text-[10px]">{{ $item->rawMaterial->code }}</span>
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-emerald-400">{{ (float) $item->quantity }}</td>
                            <td class="p-3">{{ $item->unit->name }} ({{ $item->unit->code }})</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
