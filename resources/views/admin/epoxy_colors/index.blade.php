@extends('layouts.app')

@section('title', 'Epoxy Filler Colors')
@section('header-title', 'Epoxy Filler Color Master')

@section('content')
<div class="space-y-6">
    <!-- Header Actions & Filters -->
    <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-5 md:p-6 space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Search & Filters</h3>
            <a href="{{ route('admin.epoxy-colors.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 transform active:scale-[0.98] shadow-lg shadow-cyan-500/10 text-sm gap-2 shrink-0 self-start md:self-auto">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Add Epoxy Color</span>
            </a>
        </div>

        <form method="GET" action="{{ route('admin.epoxy-colors.index') }}"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
            <!-- Search Text -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="block w-full pl-9 pr-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-xs"
                    placeholder="Search name/code...">
            </div>

            <!-- Status Filter -->
            <select name="status"
                class="bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status')==='active' ? 'selected' : '' }}>Active Only</option>
                <option value="inactive" {{ request('status')==='inactive' ? 'selected' : '' }}>Inactive Only</option>
            </select>

            <!-- Action buttons -->
            <div class="sm:col-span-2 md:col-span-2 flex justify-end gap-2">
                @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.epoxy-colors.index') }}"
                    class="px-4 py-2 bg-slate-900 text-slate-400 hover:text-white rounded-xl text-xs font-semibold transition-colors">
                    Clear Filters
                </a>
                @endif
                <button type="submit"
                    class="px-5 py-2 bg-slate-900 border border-slate-800 hover:border-cyan-500/40 text-slate-300 hover:text-cyan-400 rounded-xl text-xs font-bold transition-all">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-slate-950 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-850 bg-slate-900/50 text-slate-400 font-semibold">
                        <th class="p-4 w-28">Code</th>
                        <th class="p-4">Color Name</th>
                        <th class="p-4">Audit Details</th>
                        <th class="p-4 w-32">Status</th>
                        <th class="p-4 w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50">
                    @forelse($colors as $color)
                    <tr class="hover:bg-slate-900/30 text-slate-200 transition-colors">
                        <!-- Code -->
                        <td class="p-4 font-mono font-bold text-cyan-400">
                            {{ $color->code }}
                        </td>

                        <!-- Name & Description -->
                        <td class="p-4">
                            <div class="font-semibold text-white">{{ $color->name }}</div>
                            <div class="text-xs text-slate-500 max-w-xs truncate">{{ $color->description ?? '-' }}</div>
                        </td>

                        <!-- Audit Details -->
                        <td class="p-4 text-xs text-slate-400">
                            <div>By: <span class="text-white font-medium">{{ $color->creator->name ?? 'System' }}</span>
                            </div>
                            <div class="text-slate-500 font-mono">{{ $color->created_at->format('d M Y, h:i A') }}</div>
                        </td>

                        <!-- Status -->
                        <td class="p-4">
                            @if($color->is_active)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                Active
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700">
                                Inactive
                            </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.epoxy-colors.edit', $color->id) }}"
                                    class="p-1.5 bg-slate-900 border border-slate-800 hover:border-cyan-500/40 text-slate-400 hover:text-cyan-400 rounded-lg transition-all"
                                    title="Edit Color">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.epoxy-colors.destroy', $color->id) }}" method="POST"
                                    class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 bg-slate-900 border border-slate-800 hover:border-rose-500/40 text-slate-400 hover:text-rose-400 rounded-lg transition-all"
                                        title="Delete Color">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i data-lucide="palette" class="w-8 h-8 text-slate-600"></i>
                                <span class="text-sm font-semibold">No colors found matching the search criteria.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($colors->hasPages())
        <div class="bg-slate-900/50 border-t border-slate-850 px-4 py-3">
            {{ $colors->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
