@extends('layouts.app')

@section('title', 'Component Categories')
@section('header-title', 'Component Categories')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-white tracking-tight">Direct Component Categories</h2>
            <p class="text-xs text-slate-400 mt-1">Manage categories and their display sequence on the Order Board (e.g. Tiles Cleaner, Levelers, Spacers, Chemicals).</p>
        </div>

        <!-- Create Button -->
        <a href="{{ route('admin.component-categories.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 transform active:scale-[0.98] shadow-lg shadow-cyan-500/10 text-sm gap-2 shrink-0">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add Category</span>
        </a>
    </div>

    <!-- Data Table -->
    <div class="bg-slate-950 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-850 bg-slate-900/50 text-slate-400 font-semibold">
                        <th class="p-4 w-24 text-center">Column</th>
                        <th class="p-4 w-20 text-center">Order</th>
                        <th class="p-4">Category Name</th>
                        <th class="p-4">Slug / Key</th>
                        <th class="p-4">Default Unit</th>
                        <th class="p-4 text-center">Components</th>
                        <th class="p-4 w-32">Status</th>
                        <th class="p-4 w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-900/30 text-slate-200 transition-colors">
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg bg-slate-900 text-purple-400 font-bold border border-slate-800 text-xs">
                                    Col {{ $category->column_no ?? 4 }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-900 text-cyan-400 font-mono font-bold border border-slate-800">
                                    {{ $category->display_order }}
                                </span>
                            </td>
                            <td class="p-4 font-semibold text-white">
                                {{ $category->name }}
                            </td>
                            <td class="p-4 font-mono text-xs text-slate-400">
                                {{ $category->slug }}
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                    {{ $category->default_unit }}
                                </span>
                            </td>
                            <td class="p-4 text-center font-bold text-slate-300">
                                <a href="{{ route('admin.epoxy-components.index', ['component_category_id' => $category->id]) }}" class="hover:text-cyan-400 underline decoration-slate-700">
                                    {{ $category->components_count }}
                                </a>
                            </td>
                            <td class="p-4">
                                @if($category->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.component-categories.edit', $category) }}" class="p-2 hover:bg-slate-900 text-slate-400 hover:text-cyan-400 rounded-lg transition-colors" title="Edit">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    @if($category->components_count == 0)
                                        <form action="{{ route('admin.component-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 hover:bg-slate-900 text-slate-400 hover:text-rose-400 rounded-lg transition-colors" title="Delete">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-500">
                                No component categories found. Click "Add Category" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
            <div class="p-4 border-t border-slate-850">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
