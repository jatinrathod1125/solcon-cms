<!-- Section 1: Basic Info -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Name -->
    <div class="md:col-span-2">
        <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Material Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $rawMaterial->name ?? '') }}" required autofocus
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
            placeholder="e.g. Ordinary Portland Cement">
        @error('name')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Code -->
    <div>
        <label for="code" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Material Code</label>
        <input type="text" id="code" name="code" value="{{ old('code', $rawMaterial->code ?? '') }}" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm font-mono uppercase"
            placeholder="e.g. OPC-53">
        @error('code')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Section 2: Department and Units -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Department Assignment -->
    <div>
        <label for="department_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Department</label>
        <select id="department_id" name="department_id" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
            <option value="">Select Department</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ old('department_id', $rawMaterial->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }} ({{ $dept->code }})
                </option>
            @endforeach
        </select>
        @error('department_id')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Stock Unit (Base Unit) -->
    <div>
        <label for="stock_unit_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Stock Unit (Base)</label>
        <select id="stock_unit_id" name="stock_unit_id" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
            <option value="">Select Base Unit</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}" {{ old('stock_unit_id', $rawMaterial->stock_unit_id ?? '') == $unit->id ? 'selected' : '' }}>
                    {{ $unit->name }} ({{ $unit->code }})
                </option>
            @endforeach
        </select>
        @error('stock_unit_id')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Section 3: Stock Limits -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Opening Stock -->
    <div>
        <label for="opening_stock" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Opening Stock</label>
        <input type="number" step="0.0001" id="opening_stock" name="opening_stock" value="{{ old('opening_stock', $rawMaterial->opening_stock ?? '0.0000') }}" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm font-mono"
            placeholder="e.g. 100.0000">
        @error('opening_stock')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Minimum Stock -->
    <div>
        <label for="minimum_stock" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Minimum Stock</label>
        <input type="number" step="0.0001" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', $rawMaterial->minimum_stock ?? '0.0000') }}" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm font-mono"
            placeholder="e.g. 20.0000">
        @error('minimum_stock')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Maximum Stock -->
    <div>
        <label for="maximum_stock" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Maximum Stock</label>
        <input type="number" step="0.0001" id="maximum_stock" name="maximum_stock" value="{{ old('maximum_stock', $rawMaterial->maximum_stock ?? '0.0000') }}" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm font-mono"
            placeholder="e.g. 500.0000">
        @error('maximum_stock')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Description -->
<div>
    <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Description</label>
    <textarea id="description" name="description" rows="3"
        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
        placeholder="Provide details about the material specification, supplier detail etc.">{{ old('description', $rawMaterial->description ?? '') }}</textarea>
    @error('description')
        <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Active Status Toggle -->
<div class="flex items-center">
    <label class="flex items-center text-sm text-slate-400 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $rawMaterial->is_active ?? true) ? 'checked' : '' }}
            class="w-4 h-4 rounded border-slate-800 bg-slate-900 text-cyan-500 focus:ring-cyan-500/30 focus:ring-offset-0 mr-2 cursor-pointer">
        <span>Mark as Active (Available for Formula mapping and stock deduction logs)</span>
    </label>
    @error('is_active')
        <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
