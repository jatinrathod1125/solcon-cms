@extends('layouts.app')

@section('title', 'Machines')
@section('header-title', 'Machine Master')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('admin.machines.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center w-full lg:max-w-3xl gap-3">
            <!-- Search Text -->
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-10 pr-4 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                    placeholder="Search by name or code...">
            </div>

            <!-- Department Filter -->
            <select name="department_id" 
                class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-sm text-slate-350 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }} ({{ $dept->code }})
                    </option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-950 border border-slate-800 hover:bg-slate-900 rounded-xl text-sm font-medium transition-colors text-slate-300 flex-1 sm:flex-initial">
                    Filter
                </button>
                @if(request('search') || request('department_id'))
                    <a href="{{ route('admin.machines.index') }}" class="px-3 py-2 bg-slate-900 text-slate-400 hover:text-white rounded-xl text-sm transition-colors text-center" title="Clear Filters">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        <!-- Create Button -->
        <a href="{{ route('admin.machines.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 transform active:scale-[0.98] shadow-lg shadow-cyan-500/10 text-sm gap-2 shrink-0">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add Machine</span>
        </a>
    </div>

    <!-- Data Table -->
    <div class="bg-slate-950 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-850 bg-slate-900/50 text-slate-400 font-semibold">
                        <th class="p-4 w-20">Code</th>
                        <th class="p-4">Machine Name</th>
                        <th class="p-4">Department</th>
                        <th class="p-4">Description</th>
                        <th class="p-4 w-32">Status</th>
                        <th class="p-4 w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50">
                    @forelse($machines as $machine)
                        <tr class="hover:bg-slate-900/30 text-slate-200 transition-colors">
                            <td class="p-4 font-mono font-bold text-cyan-400">
                                {{ $machine->code }}
                            </td>
                            <td class="p-4 font-semibold text-white">
                                {{ $machine->name }}
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-800 text-xs font-semibold text-indigo-300">
                                    {{ $machine->department->name }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-400 max-w-xs truncate">
                                {{ $machine->description ?? '-' }}
                            </td>
                            <td class="p-4">
                                @if($machine->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700/50">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.machines.edit', $machine) }}" class="p-1.5 bg-slate-900 border border-slate-800 hover:border-cyan-500/40 text-slate-400 hover:text-cyan-400 rounded-lg transition-all" title="Edit">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.machines.destroy', $machine) }}" class="delete-form inline-block">
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
                            <td colspan="6" class="p-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i data-lucide="folder-open" class="w-8 h-8 text-slate-700"></i>
                                    <span>No machines found.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($machines->hasPages())
            <div class="px-6 py-4 border-t border-slate-850 bg-slate-900/20">
                {{ $machines->links() }}
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
                text: "Deleting this machine cannot be undone.",
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
