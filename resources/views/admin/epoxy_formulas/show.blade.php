@extends('layouts.app')

@section('title', 'Formula Details')
@section('header-title', 'Formula Specification')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.epoxy-formulas.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-white transition-colors uppercase tracking-wider font-semibold mb-2">
                <i data-lucide="arrow-left" class="w-3 h-3"></i>
                <span>Back to formulas</span>
            </a>
            <h2 class="text-xl font-bold text-white">{{ $epoxyFormula->product->name }}</h2>
            <p class="text-xs text-slate-400">Formula Version {{ $epoxyFormula->version }} Specification</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.epoxy-formulas.edit', $epoxyFormula->id) }}" class="inline-flex items-center px-4 py-2 bg-slate-900 hover:bg-slate-850 text-slate-300 font-semibold rounded-xl text-sm transition-colors border border-slate-800 gap-2">
                <i data-lucide="edit-3" class="w-4 h-4"></i>
                <span>Edit Recipe</span>
            </a>
        </div>
    </div>

    <!-- Details Card -->
    <div class="bg-slate-955 border border-slate-850 p-6 rounded-2xl shadow-xl grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Product Code</span>
            <span class="text-sm font-mono text-cyan-400 font-bold">{{ $epoxyFormula->product->code }}</span>
        </div>
        <div>
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Requires Color</span>
            <span class="text-sm text-white font-medium">{{ $epoxyFormula->product->requires_color ? 'Yes' : 'No' }}</span>
        </div>
        <div>
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Status</span>
            @if($epoxyFormula->is_active)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>Active Version
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-500/10 text-slate-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Inactive Version
                </span>
            @endif
        </div>
        @if($epoxyFormula->description)
            <div class="md:col-span-3 border-t border-slate-850 pt-4">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Notes / Description</span>
                <p class="text-sm text-slate-300">{{ $epoxyFormula->description }}</p>
            </div>
        @endif
    </div>

    <!-- Items table -->
    <div class="bg-slate-955 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
        <div class="px-5 py-4 border-b border-slate-850 bg-slate-900/35">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Formula Ingredients / Components</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-850 bg-slate-900/50 text-slate-400 font-semibold">
                        <th class="p-4">Material Code</th>
                        <th class="p-4">Material Name</th>
                        <th class="p-4 text-center">Material Type</th>
                        <th class="p-4">Dynamic Color</th>
                        <th class="p-4 text-right">Qty per Unit</th>
                        <th class="p-4">Unit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50 text-slate-200">
                    @foreach($epoxyFormula->items as $item)
                        <tr>
                            <td class="p-4 font-mono text-slate-400">{{ $item->rawMaterial->code }}</td>
                            <td class="p-4 font-semibold text-white">{{ $item->rawMaterial->name }}</td>
                            <td class="p-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-850 text-slate-300">
                                    {{ $item->material_type }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if($item->is_dynamic_color)
                                    <span class="text-blue-400 text-xs font-semibold flex items-center gap-1">
                                        <i data-lucide="palette" class="w-3.5 h-3.5"></i> Yes
                                    </span>
                                @else
                                    <span class="text-slate-500 text-xs">No</span>
                                @endif
                            </td>
                            <td class="p-4 text-right font-mono font-bold text-emerald-400">{{ format_quantity($item->quantity) }}</td>
                            <td class="p-4 text-slate-400 font-mono">{{ $item->unit->code }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
