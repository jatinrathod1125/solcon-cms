<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Category Name -->
    <div>
        <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Category Name <span class="text-rose-400">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name', $componentCategory->name ?? '') }}" required autofocus
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
            placeholder="e.g. Tiles Cleaner, Tile Leveler">
        @error('name')
            <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Slug / Identifier -->
    <div>
        <label for="slug" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Slug / System Code</label>
        <input type="text" id="slug" name="slug" value="{{ old('slug', $componentCategory->slug ?? '') }}"
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm font-mono lowercase"
            placeholder="e.g. tiles-cleaner (leave empty to auto-generate)">
        <p class="text-[11px] text-slate-500 mt-1">Unique identifier used for ordering & grouping.</p>
        @error('slug')
            <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Default Unit -->
    <div>
        <label for="default_unit" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Default Packaging Unit <span class="text-rose-400">*</span></label>
        <input type="text" id="default_unit" name="default_unit" value="{{ old('default_unit', $componentCategory->default_unit ?? 'Box') }}" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
            placeholder="e.g. Box, Bucket, Bag">
        <p class="text-[11px] text-slate-500 mt-1">Direct components are packed in boxes (e.g. 1 Box, 20 Boxes).</p>
        @error('default_unit')
            <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Column Position on Order Board -->
    <div>
        <label for="column_no" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Order Board Column <span class="text-rose-400">*</span></label>
        <select id="column_no" name="column_no" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
            <option value="1" {{ old('column_no', $componentCategory->column_no ?? 4) == 1 ? 'selected' : '' }}>Column 1 (Below Adhesive)</option>
            <option value="2" {{ old('column_no', $componentCategory->column_no ?? 4) == 2 ? 'selected' : '' }}>Column 2 (Below Grout & Resin Kit)</option>
            <option value="3" {{ old('column_no', $componentCategory->column_no ?? 4) == 3 ? 'selected' : '' }}>Column 3 (Below Epoxy Buckets)</option>
            <option value="4" {{ old('column_no', $componentCategory->column_no ?? 4) == 4 ? 'selected' : '' }}>Column 4 (Dedicated Components Column)</option>
        </select>
        <p class="text-[11px] text-slate-500 mt-1">Select which column this category card appears in on the Order Board.</p>
        @error('column_no')
            <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Display Order -->
    <div>
        <label for="display_order" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Order in Column (Display Order) <span class="text-rose-400">*</span></label>
        <input type="number" id="display_order" name="display_order" value="{{ old('display_order', $componentCategory->display_order ?? 0) }}" min="0" step="1" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm font-mono"
            placeholder="e.g. 1, 2, 3">
        <p class="text-[11px] text-slate-500 mt-1">Lower numbers appear first within the chosen column.</p>
        @error('display_order')
            <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Active Status Toggle -->
<div class="flex items-center pt-2">
    <label class="flex items-center text-sm text-slate-400 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $componentCategory->is_active ?? true) ? 'checked' : '' }}
            class="w-4 h-4 rounded border-slate-800 bg-slate-900 text-cyan-500 focus:ring-cyan-500/30 focus:ring-offset-0 mr-2 cursor-pointer">
        <span>Active (Show this category and its items on the Order Board)</span>
    </label>
    @error('is_active')
        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
