@extends('layouts.app')

@section('title', 'Epoxy Formulas')
@section('header-title', 'Epoxy Formulas')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-white">Epoxy Formulas</h2>
            <p class="text-xs text-slate-500">Define component bills-of-materials and packaging specifications for manual assemblies.</p>
        </div>
        <a href="{{ route('admin.epoxy-formulas.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 shadow-lg text-sm gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Define Formula</span>
        </a>
    </div>

    <!-- Filters and Search -->
    <div class="bg-slate-955 border border-slate-850 p-4 rounded-2xl">
        <form method="GET" action="{{ route('admin.epoxy-formulas.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product name or code..."
                    class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-colors">
            </div>
            <div>
                <select name="brand_id" onchange="this.form.submit()"
                    class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-cyan-500 transition-colors">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-2">
                @if(request()->hasAny(['search', 'brand_id']))
                    <a href="{{ route('admin.epoxy-formulas.index') }}" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white rounded-xl transition-colors flex items-center justify-center">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
                <button type="submit" class="px-5 py-2 bg-slate-900 border border-slate-800 hover:border-cyan-500/40 text-slate-300 hover:text-cyan-400 rounded-xl text-xs font-bold transition-all">
                    Apply
                </button>
            </div>
        </form>
    </div>

    <div class="bg-slate-955 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-850 bg-slate-900/50 text-slate-400 font-semibold">
                        <th class="p-4">Product</th>
                        <th class="p-4">Brand</th>
                        <th class="p-4 w-32">Version</th>
                        <th class="p-4">Description</th>
                        <th class="p-4 w-32">Status</th>
                        <th class="p-4 w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50 text-slate-200">
                    @forelse($formulas as $formula)
                        <tr>
                            <td class="p-4 font-semibold text-white">{{ $formula->product->name }}</td>
                            <td class="p-4">
                                @if($formula->product->brand)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                        {{ $formula->product->brand->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700">
                                        Common
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 font-mono font-bold text-cyan-400">v{{ $formula->version }}</td>
                            <td class="p-4 text-slate-400">{{ $formula->description ?: 'N/A' }}</td>
                            <td class="p-4">
                                @if($formula->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-500/10 text-slate-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.epoxy-formulas.show', $formula->id) }}" class="p-1.5 hover:bg-slate-900 rounded-lg text-slate-400 hover:text-white transition-colors" title="View recipe items">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('admin.epoxy-formulas.edit', $formula->id) }}" class="p-1.5 hover:bg-slate-900 rounded-lg text-slate-400 hover:text-white transition-colors" title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.epoxy-formulas.destroy', $formula->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this formula?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 hover:bg-rose-500/10 rounded-lg text-slate-400 hover:text-rose-400 transition-colors" title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500">No Epoxy Formulas defined. Click Define Formula to configure.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($formulas->hasPages())
        <div class="p-4 border-t border-slate-850">
            {{ $formulas->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
