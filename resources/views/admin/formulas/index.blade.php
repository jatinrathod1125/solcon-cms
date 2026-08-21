@extends('layouts.app')

@section('title', 'Formulas')
@section('header-title', 'Formula Master')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('admin.formulas.index') }}"
            class="flex items-center w-full sm:max-w-xl gap-2">
            <!-- Search Text -->
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="block w-full pl-10 pr-4 py-2 bg-slate-955 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                    placeholder="Search by grade name or code...">
            </div>

            <!-- Brand Filter -->
            <select name="brand_id"
                class="bg-slate-955 border border-slate-800 rounded-xl px-4 py-2 text-sm text-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all">
                <option value="">All Brands</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                class="px-4 py-2 bg-slate-955 border border-slate-800 hover:bg-slate-900 rounded-xl text-sm font-medium transition-colors text-slate-300">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'brand_id']))
            <a href="{{ route('admin.formulas.index') }}"
                class="px-3 py-2 bg-slate-900 text-slate-400 hover:text-white rounded-xl text-sm transition-colors"
                title="Clear Filters">
                Clear
            </a>
            @endif
        </form>

        <!-- Create Button -->
        <a href="{{ route('admin.formulas.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 transform active:scale-[0.98] shadow-lg shadow-cyan-500/10 text-sm gap-2 shrink-0">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add Formula Version</span>
        </a>
    </div>

    <!-- Data Table -->
    <div class="bg-slate-955 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-850 bg-slate-900/50 text-slate-400 font-semibold">
                        <th class="p-4 w-28">Grade Code</th>
                        <th class="p-4">Grade Name</th>
                        <th class="p-4 w-24">Version</th>
                        <th class="p-4">Remarks / Revision Notes</th>
                        <th class="p-4">Created By</th>
                        <th class="p-4 w-32">Status</th>
                        <th class="p-4 w-36 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50">
                    @forelse($formulas as $formula)
                        <tr class="hover:bg-slate-900/30 text-slate-200 transition-colors">
                            <!-- Grade Code -->
                            <td class="p-4 font-mono font-bold text-cyan-400">
                                {{ $formula->grade->code }}
                            </td>
                            
                            <!-- Grade Name & Brand -->
                            <td class="p-4">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-semibold text-white">{{ $formula->grade->name }}</span>
                                    @if($formula->brand)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold bg-cyan-500/15 text-cyan-300 border border-cyan-500/30 shadow-sm">
                                        {{ $formula->brand->name }}
                                    </span>
                                    @elseif($formula->grade?->brand)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold bg-cyan-500/15 text-cyan-300 border border-cyan-500/30 shadow-sm">
                                        {{ $formula->grade->brand->name }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Version -->
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded bg-slate-900 border border-slate-800 text-xs font-mono text-indigo-300 font-bold">
                                    v{{ $formula->version }}
                                </span>
                            </td>

                            <!-- Remarks -->
                            <td class="p-4 text-slate-450 max-w-xs truncate">
                                {{ $formula->remarks ?? '-' }}
                            </td>

                            <!-- Creator -->
                            <td class="p-4 text-xs text-slate-400">
                                <div>{{ $formula->creator->name ?? 'System' }}</div>
                                <div class="text-slate-500 font-mono">{{ $formula->created_at->format('d M Y, h:i A') }}</div>
                            </td>
                            
                            <!-- Status -->
                            <td class="p-4">
                                @if($formula->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20" title="Active Formula used for Production">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700/50" title="Inactive version">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Actions -->
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.formulas.show', $formula) }}" class="p-1.5 bg-slate-900 border border-slate-800 hover:border-indigo-500/40 text-slate-400 hover:text-indigo-400 rounded-lg transition-all" title="View Details">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('admin.formulas.edit', $formula) }}" class="p-1.5 bg-slate-900 border border-slate-800 hover:border-cyan-500/40 text-slate-400 hover:text-cyan-400 rounded-lg transition-all" title="Edit">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.formulas.destroy', $formula) }}" class="delete-form inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="p-1.5 bg-slate-900 border border-slate-800 hover:border-rose-500/40 text-slate-400 hover:text-rose-500 rounded-lg transition-all delete-btn" title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i data-lucide="scale" class="w-8 h-8 text-slate-700"></i>
                                    <span>No formulas defined.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($formulas->hasPages())
            <div class="px-6 py-4 border-t border-slate-850 bg-slate-900/20">
                {{ $formulas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "Deleting this formula version cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Yes, delete it!',
                background: '#090d16',
                color: '#f1f5f9'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
