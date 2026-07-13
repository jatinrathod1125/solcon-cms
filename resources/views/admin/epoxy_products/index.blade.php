@extends('layouts.app')

@section('title', 'Epoxy Products')
@section('header-title', 'Epoxy Product Master')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-white">Epoxy Products</h2>
            <p class="text-xs text-slate-500">Configure manually assembled Epoxy kits and packages.</p>
        </div>
        <a href="{{ route('admin.epoxy-products.create') }}"
            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 shadow-lg text-sm gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add Epoxy Product</span>
        </a>
    </div>

    <div class="bg-slate-955 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-850 bg-slate-900/50 text-slate-400 font-semibold">
                        <th class="p-4 w-32">Product Code</th>
                        <th class="p-4">Name</th>
                        <th class="p-4">Requires Color</th>
                        <th class="p-4">Active Formulas</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50 text-slate-200">
                    @forelse($products as $product)
                    <tr>
                        <td class="p-4 font-mono font-bold text-cyan-400">{{ $product->code }}</td>
                        <td class="p-4 font-semibold text-white">{{ $product->name }}</td>
                        <td class="p-4">
                            @if($product->requires_color)
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-blue-500/10 text-blue-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>Yes
                            </span>
                            @else
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-500/10 text-slate-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>No
                            </span>
                            @endif
                        </td>
                        <td class="p-4 font-mono">{{ $product->formulas_count }} formulas</td>
                        <td class="p-4">
                            @if($product->is_active)
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>Active
                            </span>
                            @else
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>Inactive
                            </span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.epoxy-products.edit', $product->id) }}"
                                    class="p-1.5 hover:bg-slate-900 rounded-lg text-slate-400 hover:text-white transition-colors"
                                    title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.epoxy-products.destroy', $product->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 hover:bg-rose-500/10 rounded-lg text-slate-400 hover:text-rose-400 transition-colors"
                                        title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-500">No Epoxy products defined. Click Add
                            Epoxy Product to get started.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
