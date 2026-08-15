@extends('layouts.app')

@section('title', 'Raw Materials')
@section('header-title', 'Raw Material Inventory')

@section('content')
<div class="space-y-6">
    <!-- Action Bar & Search Filters -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-955 border border-slate-850 p-4 rounded-2xl">
        <!-- Search & Filter Form -->
        <form method="GET" action="{{ route('admin.raw-materials.index') }}" class="flex flex-col sm:flex-row items-center gap-3 flex-1">
            <!-- Search Keyword -->
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search material or code..."
                    class="block w-full pl-10 pr-4 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
            </div>

            <!-- Filter Department -->
            <div class="w-full sm:w-48">
                <select name="department_id" onchange="this.form.submit()"
                    class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if(request('search') || request('department_id'))
                <a href="{{ route('admin.raw-materials.index') }}" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-semibold transition-colors flex items-center gap-1">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i> Clear
                </a>
            @endif
        </form>

        <!-- Add New Raw Material, Import, Export Buttons -->
        <div class="flex items-center gap-2 flex-wrap">
            <button onclick="openImportModal()" 
                class="inline-flex items-center px-4 py-2 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 font-semibold rounded-xl transition-all text-sm gap-2">
                <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                <span>Import CSV</span>
            </button>
            <a href="{{ route('admin.raw-materials.export') }}" 
                class="inline-flex items-center px-4 py-2 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 font-semibold rounded-xl transition-all text-sm gap-2">
                <i data-lucide="download-cloud" class="w-4 h-4"></i>
                <span>Export CSV</span>
            </a>
            <a href="{{ route('admin.raw-materials.create') }}" 
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 shadow-lg shadow-cyan-500/10 text-sm gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Add Material</span>
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-slate-955 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-850 bg-slate-900/50 text-slate-400 font-semibold">
                        <th class="p-4 w-20">Code</th>
                        <th class="p-4">Material Name</th>
                        <th class="p-4">Department</th>
                        <th class="p-4">Stock (Base Unit)</th>
                        <th class="p-4">Limits (Min / Max)</th>
                        <th class="p-4 w-28">Status</th>
                        <th class="p-4 w-28 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50">
                    @forelse($rawMaterials as $material)
                        <tr class="hover:bg-slate-900/30 text-slate-200 transition-colors">
                            <!-- Code -->
                            <td class="p-4 font-mono font-bold text-cyan-400">
                                {{ $material->code }}
                            </td>
                            
                            <!-- Name & Brand -->
                            <td class="p-4">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-semibold text-white">{{ $material->name }}</span>
                                    @if($material->brand)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold bg-cyan-500/15 text-cyan-300 border border-cyan-500/30 shadow-sm">
                                            {{ $material->brand->name }}
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-500 max-w-xs truncate mt-0.5">{{ $material->description ?? 'No description' }}</div>
                            </td>
                            
                            <!-- Department -->
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-800 text-xs font-semibold text-indigo-300">
                                    {{ $material->department->name }}
                                </span>
                            </td>

                            <!-- Stock -->
                            <td class="p-4">
                                <div class="font-bold font-mono {{ $material->current_stock <= $material->minimum_stock ? 'text-amber-500 animate-pulse' : 'text-white' }}">
                                    {{ format_quantity($material->current_stock) }} <span class="text-xs text-slate-450 font-normal">{{ $material->stockUnit->code }}</span>
                                </div>
                                <div class="text-xs text-slate-500 font-mono">
                                    Opening: {{ format_quantity($material->opening_stock) }}
                                </div>
                            </td>

                            <!-- Limits -->
                            <td class="p-4 font-mono text-xs">
                                <div>Min: <span class="text-rose-400">{{ format_quantity($material->minimum_stock) }}</span></div>
                                <div>Max: <span class="text-emerald-500">{{ format_quantity($material->maximum_stock) }}</span></div>
                            </td>

                            <!-- Status -->
                            <td class="p-4">
                                @if($material->is_active)
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
                                    <a href="{{ route('admin.raw-materials.edit', $material) }}" class="p-1.5 bg-slate-900 border border-slate-800 hover:border-cyan-500/40 text-slate-400 hover:text-cyan-400 rounded-lg transition-all" title="Edit">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.raw-materials.destroy', $material) }}" class="delete-form inline-block">
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
                            <td colspan="7" class="p-8 text-center text-slate-400">
                                No raw materials found. Click "Add Material" to create your first entry.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rawMaterials->hasPages())
            <div class="p-4 border-t border-slate-850">
                {{ $rawMaterials->links() }}
            </div>
        @endif
    </div>
</div>

<!-- IMPORT CSV MODAL -->
<div id="importModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 w-full max-w-md shadow-2xl relative">
        <button onclick="closeImportModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-200 transition p-1.5 rounded-full hover:bg-slate-800">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="flex items-center gap-3 mb-4">
            <div class="p-2.5 bg-slate-900 border border-slate-800 rounded-2xl">
                <i data-lucide="upload-cloud" class="w-5 h-5 text-cyan-400"></i>
            </div>
            <div>
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">CSV Data Import</h3>
                <p class="text-[11px] text-slate-500">Import raw materials spreadsheet details directly</p>
            </div>
        </div>

        <p class="text-slate-400 text-[11px] mb-4">
            Upload a CSV file containing raw materials details. Expected headers: 
            <code class="bg-slate-900 px-1 py-0.5 rounded font-mono text-[10px] text-cyan-400">Code, Name, Department Code, Stock Unit Code, Purchase Unit Code, Purchase Conversion, Opening Stock, Minimum Stock, Maximum Stock, Active, Is Coupon, Description</code>.
        </p>

        <form method="POST" action="{{ route('admin.raw-materials.import') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf

            <div class="border-2 border-dashed border-slate-800 rounded-2xl p-6 flex flex-col items-center justify-center bg-slate-900/20 hover:bg-slate-900/40 transition cursor-pointer relative">
                <input type="file" name="csv_file" accept=".csv" class="absolute inset-0 opacity-0 cursor-pointer" required onchange="updateFileLabel(this)">
                <i data-lucide="file-spreadsheet" class="w-8 h-8 text-slate-500 mb-2"></i>
                <span id="fileLabel" class="text-slate-300 font-bold">Select CSV File</span>
                <span class="text-slate-500 text-[10px] mt-0.5">Maximum size 4MB</span>
            </div>

            <div class="pt-2 border-t border-slate-850 flex justify-end gap-2">
                <button type="button" onclick="closeImportModal()" class="px-4 py-2 bg-slate-900 border border-slate-800 text-slate-350 hover:bg-slate-800 rounded-xl transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all shadow-lg shadow-cyan-500/10">
                    Upload & Import
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Deleting this raw material cannot be undone.",
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
            } else {
                if (confirm('Are you sure you want to delete this raw material?')) {
                    form.submit();
                }
            }
        });
    });

    function openImportModal() {
        $('#importModal').removeClass('hidden');
    }

    function closeImportModal() {
        $('#importModal').addClass('hidden');
    }

    function updateFileLabel(input) {
        if (input.files && input.files.length > 0) {
            $('#fileLabel').text(input.files[0].name);
        } else {
            $('#fileLabel').text('Select CSV File');
        }
    }
</script>
@endsection
