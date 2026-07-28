@extends('layouts.app')

@section('title', 'Packing Materials')
@section('header-title', 'Packing Material Inventory')

@section('content')
<div class="space-y-6">
    <!-- Action Bar & Search Filters -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-955 border border-slate-850 p-4 rounded-2xl">
        <!-- Search & Filter Form -->
        <form method="GET" action="{{ route('admin.packing-materials.index') }}" class="flex flex-col sm:flex-row items-center gap-3 flex-1 flex-wrap">
            <!-- Search Keyword -->
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search material, code, or size..."
                    class="block w-full pl-10 pr-4 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
            </div>

            <!-- Filter Category -->
            <div class="w-full sm:w-48">
                <select name="category_id" onchange="this.form.submit()"
                    class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div class="w-full sm:w-40">
                <select name="status" onchange="this.form.submit()"
                    class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>⚠️ Low Stock Only</option>
                </select>
            </div>

            @if(request('search') || request('category_id') || request('status'))
                <a href="{{ route('admin.packing-materials.index') }}" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-semibold transition-colors flex items-center gap-1">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i> Clear
                </a>
            @endif
        </form>

        <!-- Buttons -->
        <div class="flex items-center gap-2 flex-wrap">
            <button onclick="openImportModal()"
                class="inline-flex items-center px-4 py-2 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 font-semibold rounded-xl transition-all text-sm gap-2">
                <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                <span>Import CSV</span>
            </button>
            <a href="{{ route('admin.packing-materials.export') }}"
                class="inline-flex items-center px-4 py-2 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 font-semibold rounded-xl transition-all text-sm gap-2">
                <i data-lucide="download-cloud" class="w-4 h-4"></i>
                <span>Export CSV</span>
            </a>
            <a href="{{ route('admin.packing-materials.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 shadow-lg shadow-cyan-500/10 text-sm gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Add Packing Material</span>
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-slate-955 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-850 bg-slate-900/50 text-slate-400 font-semibold">
                        <th class="p-4 w-28">Code</th>
                        <th class="p-4">Material Name</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Size</th>
                        <th class="p-4">Stock</th>
                        <th class="p-4">Min. Stock</th>
                        <th class="p-4 w-28">Status</th>
                        <th class="p-4 w-28 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50">
                    @forelse($packingMaterials as $material)
                        <tr class="hover:bg-slate-900/30 text-slate-200 transition-colors">
                            <!-- Code -->
                            <td class="p-4 font-mono font-bold text-cyan-400">
                                {{ $material->code ?? '-' }}
                            </td>

                            <!-- Name -->
                            <td class="p-4">
                                <div class="font-semibold text-white">{{ $material->name }}</div>
                                @if($material->remarks)
                                    <div class="text-xs text-slate-500 max-w-xs truncate">{{ $material->remarks }}</div>
                                @endif
                            </td>

                            <!-- Category -->
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-800 text-xs font-semibold text-indigo-300">
                                    {{ $material->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>

                            <!-- Size -->
                            <td class="p-4 font-mono text-slate-300">
                                {{ $material->size ?? '-' }}
                            </td>

                            <!-- Stock -->
                            <td class="p-4">
                                <div class="font-bold font-mono {{ $material->isLowStock() ? 'text-amber-500 animate-pulse' : 'text-white' }}">
                                    {{ format_quantity($material->current_stock) }} <span class="text-xs text-slate-450 font-normal">{{ $material->unit->code ?? 'PCS' }}</span>
                                </div>
                                @if($material->isLowStock())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-amber-400 bg-amber-500/10 border border-amber-500/20 px-1.5 py-0.5 rounded">
                                        <i data-lucide="alert-triangle" class="w-3 h-3"></i> Low Stock
                                    </span>
                                @endif
                            </td>

                            <!-- Minimum Stock -->
                            <td class="p-4 text-slate-400 font-mono">
                                {{ format_quantity($material->minimum_stock) }} <span class="text-xs text-slate-500">{{ $material->unit->code ?? 'PCS' }}</span>
                            </td>

                            <!-- Status -->
                            <td class="p-4">
                                @if($material->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Inactive
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.packing-materials.edit', $material) }}"
                                        class="p-2 text-slate-400 hover:text-cyan-400 hover:bg-slate-900 rounded-lg transition-colors" title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>

                                    <form method="POST" action="{{ route('admin.packing-materials.destroy', $material) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this packing material?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-900 rounded-lg transition-colors" title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i data-lucide="package-x" class="w-8 h-8 stroke-1"></i>
                                    <p class="font-medium text-slate-400">No packing materials found</p>
                                    <p class="text-xs">Try adjusting search query or add a new packing material.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($packingMaterials->hasPages())
            <div class="p-4 border-t border-slate-850">
                {{ $packingMaterials->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="font-bold text-white text-lg flex items-center gap-2">
                <i data-lucide="upload-cloud" class="w-5 h-5 text-cyan-400"></i> Import Packing Materials
            </h3>
            <button onclick="closeImportModal()" class="text-slate-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('admin.packing-materials.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">CSV File</label>
                <input type="file" name="csv_file" accept=".csv, .txt" required
                    class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-cyan-500/10 file:text-cyan-400 hover:file:bg-cyan-500/20 cursor-pointer border border-slate-800 rounded-xl bg-slate-955 p-2">
                <p class="text-xs text-slate-500 mt-2">
                    Expected columns: Category, Code, Name, Size, Unit Code, Opening Stock, Current Stock, Minimum Stock, Status, Remarks.
                </p>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeImportModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-sm transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-semibold rounded-xl text-sm transition-colors flex items-center gap-2">
                    <i data-lucide="upload" class="w-4 h-4"></i> Start Import
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
}
function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
}
</script>
@endsection
