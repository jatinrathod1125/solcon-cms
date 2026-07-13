<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Name -->
    <div>
        <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Department Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $department->name ?? '') }}" required autofocus
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
            placeholder="e.g. Tile Adhesive Dept">
        @error('name')
            <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Code -->
    <div>
        <label for="code" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Department Code</label>
        <input type="text" id="code" name="code" value="{{ old('code', $department->code ?? '') }}" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm font-mono uppercase"
            placeholder="e.g. TAD">
        @error('code')
            <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Description -->
<div>
    <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Description</label>
    <textarea id="description" name="description" rows="4"
        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
        placeholder="Provide a brief description of the department's operations...">{{ old('description', $department->description ?? '') }}</textarea>
    @error('description')
        <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Active Status Toggle -->
<div class="flex items-center">
    <label class="flex items-center text-sm text-slate-400 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $department->is_active ?? true) ? 'checked' : '' }}
            class="w-4 h-4 rounded border-slate-800 bg-slate-900 text-cyan-500 focus:ring-cyan-500/30 focus:ring-offset-0 mr-2 cursor-pointer">
        <span>Mark as Active (Available for Machines and Batches)</span>
    </label>
    @error('is_active')
        <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
