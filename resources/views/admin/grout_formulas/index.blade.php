@extends('layouts.app')

@section('title', 'Grout Formulas')
@section('header-title', 'Grout Formula Engine')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.grout-formulas.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Search Input -->
            <div class="relative min-w-[240px]">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-10 pr-4 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                    placeholder="Search by color name or code...">
            </div>

            <!-- Brand Filter -->
            <select name="brand_id" 
                class="bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all">
                <option value="">All Brands</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 rounded-xl text-sm font-medium transition-colors text-slate-300">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'brand_id']))
                <a href="{{ route('admin.grout-formulas.index') }}" class="px-3 py-2 bg-slate-900 text-slate-400 hover:text-white rounded-xl text-sm transition-colors" title="Clear Filters">
                    Clear
                </a>
            @endif
        </form>

        <!-- Create Button -->
        <a href="{{ route('admin.grout-formulas.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 transform active:scale-[0.98] shadow-lg shadow-cyan-500/10 text-sm gap-2 shrink-0">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add Formula Version</span>
        </a>
    </div>

    <!-- Data Table -->
    <div class="bg-slate-950 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-850 bg-slate-900/50 text-slate-400 font-semibold">
                        <th class="p-4 w-28">Color Code</th>
                        <th class="p-4">Color Name</th>
                        <th class="p-4">Brand</th>
                        <th class="p-4 w-24 text-center">Version</th>
                        <th class="p-4">Remarks</th>
                        <th class="p-4">Created By</th>
                        <th class="p-4 w-32">Status</th>
                        <th class="p-4 w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50">
                    @forelse($formulas as $formula)
                        <tr class="hover:bg-slate-900/30 text-slate-200 transition-colors">
                            <!-- Code -->
                            <td class="p-4 font-mono font-bold text-cyan-400">
                                {{ $formula->color->code ?? 'N/A' }}
                            </td>
                            
                            <!-- Color Name -->
                            <td class="p-4 font-semibold text-white">
                                {{ $formula->color->name ?? 'N/A' }}
                            </td>

                            <!-- Brand -->
                            <td class="p-4">
                                @if($formula->color?->brand)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        {{ $formula->color->brand->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-500 italic">Common</span>
                                @endif
                            </td>
                            
                            <!-- Version -->
                            <td class="p-4 text-center font-mono font-extrabold text-indigo-400">
                                V{{ $formula->version }}
                            </td>

                            <!-- Remarks -->
                            <td class="p-4 text-slate-400 max-w-xs truncate">
                                {{ $formula->remarks ?? '-' }}
                            </td>

                            <!-- Created By -->
                            <td class="p-4 text-xs text-slate-400">
                                <div><span class="text-white font-medium">{{ $formula->creator->name ?? 'System' }}</span></div>
                                <div class="text-slate-500 font-mono mt-0.5">{{ $formula->created_at->format('Y-m-d H:i') }}</div>
                            </td>
                            
                            <!-- Status -->
                            <td class="p-4">
                                @if($formula->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700/50">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Actions -->
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.grout-formulas.show', $formula) }}" class="p-1.5 bg-slate-900 border border-slate-800 hover:border-blue-500/40 text-slate-400 hover:text-blue-500 rounded-lg transition-all" title="View Details">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('admin.grout-formulas.edit', $formula) }}" class="p-1.5 bg-slate-900 border border-slate-800 hover:border-cyan-500/40 text-slate-400 hover:text-cyan-400 rounded-lg transition-all" title="Edit">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.grout-formulas.destroy', $formula) }}" class="delete-form inline-block">
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
                                    <i data-lucide="folder-open" class="w-8 h-8 text-slate-700"></i>
                                    <span>No Grout Formulas found.</span>
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
                confirmButtonText: 'Yes, delete version!',
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
