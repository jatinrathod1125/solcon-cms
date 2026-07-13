@extends('layouts.app')

@section('title', 'Edit Epoxy Component')
@section('header-title', 'Modify Epoxy Component')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.epoxy-components.index') }}" class="p-2 hover:bg-slate-900 rounded-xl text-slate-400 hover:text-white transition-all">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white">Edit Epoxy Component</h2>
            <p class="text-xs text-slate-500">Update configuration settings and classifications.</p>
        </div>
    </div>

    <div class="bg-slate-955 border border-slate-850 p-6 rounded-2xl shadow-xl">
        <form method="POST" action="{{ route('admin.epoxy-components.update', $epoxyComponent->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Name</label>
                    <input type="text" name="name" value="{{ old('name', $epoxyComponent->name) }}" required
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
                </div>

                <!-- Code -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Code</label>
                    <input type="text" name="code" value="{{ old('code', $epoxyComponent->code) }}" required
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Category</label>
                    <select name="category" required
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
                        <option value="Bottle" {{ old('category', $epoxyComponent->category) === 'Bottle' ? 'selected' : '' }}>Bottle</option>
                        <option value="Pouch" {{ old('category', $epoxyComponent->category) === 'Pouch' ? 'selected' : '' }}>Pouch</option>
                        <option value="Packet" {{ old('category', $epoxyComponent->category) === 'Packet' ? 'selected' : '' }}>Packet</option>
                        <option value="Liquid" {{ old('category', $epoxyComponent->category) === 'Liquid' ? 'selected' : '' }}>Liquid</option>
                        <option value="Powder" {{ old('category', $epoxyComponent->category) === 'Powder' ? 'selected' : '' }}>Powder</option>
                        <option value="Plastic" {{ old('category', $epoxyComponent->category) === 'Plastic' ? 'selected' : '' }}>Plastic</option>
                        <option value="Accessory" {{ old('category', $epoxyComponent->category) === 'Accessory' ? 'selected' : '' }}>Accessory</option>
                        <option value="Other" {{ old('category', $epoxyComponent->category) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Purpose -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Component Purpose</label>
                    <select name="purpose" required
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
                        <option value="Assembly Component" {{ old('purpose', $epoxyComponent->purpose) === 'Assembly Component' ? 'selected' : '' }}>Assembly Component (Increments Component Inventory)</option>
                        <option value="Direct Finished Product" {{ old('purpose', $epoxyComponent->purpose) === 'Direct Finished Product' ? 'selected' : '' }}>Direct Finished Product (Increments Finished Goods)</option>
                    </select>
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Unit</label>
                    <select name="unit_id" required
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ old('unit_id', $epoxyComponent->unit_id) == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->code }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Parent Component (Optional) -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Parent Component (Optional)</label>
                    <select name="parent_component_id"
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
                        <option value="">None (Is Base Component)</option>
                        @foreach($parentComponents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_component_id', $epoxyComponent->parent_component_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }} ({{ $parent->code }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filler Color (Optional) -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Epoxy Filler Color (Optional)</label>
                    <select name="epoxy_filler_color_id"
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
                        <option value="">None (Color Neutral)</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" {{ old('epoxy_filler_color_id', $epoxyComponent->epoxy_filler_color_id) == $color->id ? 'selected' : '' }}>{{ $color->name }} ({{ $color->code }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Active Toggle -->
                <div class="flex items-center pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $epoxyComponent->is_active) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-600"></div>
                        <span class="ml-3 text-sm font-medium text-slate-300">Active</span>
                    </label>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Description / Remarks</label>
                <textarea name="description" rows="3"
                    class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                    placeholder="Enter description...">{{ old('description', $epoxyComponent->description) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-900 flex justify-end gap-3">
                <a href="{{ route('admin.epoxy-components.index') }}"
                    class="px-5 py-2.5 bg-slate-900 hover:bg-slate-850 text-slate-300 hover:text-white rounded-xl text-sm font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-550 hover:to-indigo-550 text-white rounded-xl text-sm font-extrabold transition-all shadow-lg shadow-cyan-500/10">
                    Update Component
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
