<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Name -->
    <div>
        <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Bag Description / Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $bagSize->name ?? '') }}" required autofocus
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
            placeholder="e.g. 25 KG Bag">
        @error('name')
            <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Value in KG -->
    <div>
        <label for="value" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Weight Value (KG)</label>
        <input type="number" step="0.01" id="value" name="value" value="{{ old('value', $bagSize->value ?? '') }}" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm font-mono"
            placeholder="e.g. 25.00">
        @error('value')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Description -->
<div>
    <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Detailed Description</label>
    <textarea id="description" name="description" rows="4"
        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
        placeholder="Provide optional details (e.g., standard packaging details)...">{{ old('description', $bagSize->description ?? '') }}</textarea>
    @error('description')
        <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Active Status Toggle -->
<div class="flex items-center">
    <label class="flex items-center text-sm text-slate-400 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $bagSize->is_active ?? true) ? 'checked' : '' }}
            class="w-4 h-4 rounded border-slate-800 bg-slate-900 text-cyan-500 focus:ring-cyan-500/30 focus:ring-offset-0 mr-2 cursor-pointer">
        <span>Mark as Active (Available for Grade Master mapping)</span>
    </label>
    @error('is_active')
        <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
