<div>
    <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Product Name</label>
    <input type="text" name="name" id="name" value="{{ old('name', $epoxyProduct->name ?? '') }}" required
        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
</div>

<div>
    <label for="code" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Product Code</label>
    <input type="text" name="code" id="code" value="{{ old('code', $epoxyProduct->code ?? '') }}" required
        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm font-mono">
</div>

<div class="space-y-3 pt-2">
    <label class="flex items-center gap-3 cursor-pointer">
        <input type="checkbox" name="requires_color" value="1" {{ old('requires_color', $epoxyProduct->requires_color ?? '') ? 'checked' : '' }}
            class="h-4.5 w-4.5 rounded border-slate-800 bg-slate-900 text-cyan-600 focus:ring-cyan-500/50 focus:ring-offset-slate-950">
        <span class="text-sm font-medium text-slate-300">Requires Color (Involves Dynamic Filler Pouch)</span>
    </label>

    <label class="flex items-center gap-3 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $epoxyProduct->is_active ?? true) ? 'checked' : '' }}
            class="h-4.5 w-4.5 rounded border-slate-800 bg-slate-900 text-cyan-600 focus:ring-cyan-500/50 focus:ring-offset-slate-950">
        <span class="text-sm font-medium text-slate-300">Active</span>
    </label>
</div>

<div>
    <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Description</label>
    <textarea name="description" id="description" rows="3"
        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">{{ old('description', $epoxyProduct->description ?? '') }}</textarea>
</div>
