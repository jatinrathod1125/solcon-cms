<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Name -->
    <div>
        <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Grade
            Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $grade->name ?? '') }}" required autofocus
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
            placeholder="e.g. Tile Adhesive F101">
        @error('name')
        <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Code -->
    <div>
        <label for="code" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Grade
            Code</label>
        <input type="text" id="code" name="code" value="{{ old('code', $grade->code ?? '') }}" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm font-mono uppercase"
            placeholder="e.g. F101">
        @error('code')
        <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
        <label for="brand_id"
            class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Brand</label>
        <select name="brand_id" id="brand_id"
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
            <option value="" {{ old('brand_id', $grade->brand_id ?? '') == '' ? 'selected' : '' }}></option>
            @foreach($brands as $brand)
            <option value="{{ $brand->id }}" {{ old('brand_id', $grade->brand_id ?? '') == $brand->id ? 'selected' : ''
                }}>
                {{ $brand->name }}
            </option>
            @endforeach
        </select>
        @error('brand_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
    </div>
    <!-- Department Assignment -->
    <div>
        <label for="department_id"
            class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Department</label>
        <select id="department_id" name="department_id" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
            <option value="">Select Department</option>
            @foreach($departments as $dept)
            <option value="{{ $dept->id }}" {{ old('department_id', $grade->department_id ?? '') == $dept->id ?
                'selected' : '' }}>
                {{ $dept->name }} ({{ $dept->code }})
            </option>
            @endforeach
        </select>
        @error('department_id')
        <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Bag Size Mapping -->
    <div>
        <label for="bag_size_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Bag
            Size</label>
        <select id="bag_size_id" name="bag_size_id" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
            <option value="">Select Bag Size</option>
            @foreach($bagSizes as $bagSize)
            <option value="{{ $bagSize->id }}" {{ old('bag_size_id', $grade->bag_size_id ?? '') == $bagSize->id ?
                'selected' : '' }}>
                {{ $bagSize->name }} ({{ number_format($bagSize->value, 1) }} KG)
            </option>
            @endforeach
        </select>
        @error('bag_size_id')
        <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Output Unit Mapping -->
    <div>
        <label for="output_unit_id"
            class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Output Unit</label>
        <select id="output_unit_id" name="output_unit_id" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
            <option value="">Select Output Unit</option>
            @foreach($units as $unit)
            <option value="{{ $unit->id }}" {{ old('output_unit_id', $grade->output_unit_id ?? '') == $unit->id ?
                'selected' : '' }}>
                {{ $unit->name }} ({{ $unit->code }})
            </option>
            @endforeach
        </select>
        @error('output_unit_id')
        <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Description -->
<div>
    <label for="description"
        class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Description</label>
    <textarea id="description" name="description" rows="4"
        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
        placeholder="Provide details about the grade formulation (e.g. applications, special constraints)...">{{ old('description', $grade->description ?? '') }}</textarea>
    @error('description')
    <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Active Status Toggle -->
<div class="flex items-center">
    <label class="flex items-center text-sm text-slate-400 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $grade->is_active ?? true) ? 'checked' :
        '' }}
        class="w-4 h-4 rounded border-slate-800 bg-slate-900 text-cyan-500 focus:ring-cyan-500/30 focus:ring-offset-0
        mr-2 cursor-pointer">
        <span>Mark as Active (Available for Formula mapping and Production batches)</span>
    </label>
    @error('is_active')
    <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
