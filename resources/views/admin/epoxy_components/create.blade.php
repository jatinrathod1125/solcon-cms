@extends('layouts.app')

@section('title', 'Add Epoxy Component')
@section('header-title', 'Create Epoxy Component')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.epoxy-components.index') }}" class="p-2 hover:bg-slate-900 rounded-xl text-slate-400 hover:text-white transition-all">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white">Add Epoxy Component</h2>
            <p class="text-xs text-slate-500">Configure a new ready component and optional ready-stock mapping.</p>
        </div>
    </div>

    <div class="bg-slate-955 border border-slate-850 p-6 rounded-2xl shadow-xl">
        <form method="POST" action="{{ route('admin.epoxy-components.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                        placeholder="e.g. 700gm Black Filler Pouch">
                </div>

                <!-- Code -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Code</label>
                    <input type="text" name="code" value="{{ old('code') }}" required
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                        placeholder="e.g. EPX-FIL-700-BLK">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Category</label>
                    <select name="category" required
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
                        <option value="Bottle">Bottle</option>
                        <option value="Pouch">Pouch</option>
                        <option value="Packet">Packet</option>
                        <option value="Liquid">Liquid</option>
                        <option value="Powder">Powder</option>
                        <option value="Plastic">Plastic</option>
                        <option value="Accessory">Accessory</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Purpose -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Component Purpose</label>
                    <select name="purpose" required
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
                        <option value="Assembly Component">Assembly Component (Increments Component Inventory)</option>
                        <option value="Direct Finished Product">Direct Finished Product (Increments Finished Goods)</option>
                    </select>
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Unit</label>
                    <select name="unit_id" required
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->code }})</option>
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
                            <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->code }})</option>
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
                            <option value="{{ $color->id }}">{{ $color->name }} ({{ $color->code }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Active Toggle -->
                <div class="flex items-center pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
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
                    placeholder="Enter description..."></textarea>
            </div>

            <div class="pt-4 border-t border-slate-900 flex justify-end gap-3">
                <a href="{{ route('admin.epoxy-components.index') }}"
                    class="px-5 py-2.5 bg-slate-900 hover:bg-slate-850 text-slate-300 hover:text-white rounded-xl text-sm font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-550 hover:to-indigo-550 text-white rounded-xl text-sm font-extrabold transition-all shadow-lg shadow-cyan-500/10">
                    Save Component
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
