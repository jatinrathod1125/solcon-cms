<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Name -->
    <div>
        <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Color Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $color->name ?? '') }}" required autofocus
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
            placeholder="e.g. Ivory">
        @error('name')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Code -->
    <div>
        <label for="code" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Color Code</label>
        <input type="text" id="code" name="code" value="{{ old('code', $color->code ?? '') }}" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm font-mono uppercase"
            placeholder="e.g. GR-IVO">
        @error('code')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <!-- Brand -->
    <div>
        <label for="brand_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Brand</label>
        <select id="brand_id" name="brand_id"
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
            <option value="">Common / All Brands</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id', $color->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }} ({{ $brand->code }})
                </option>
            @endforeach
        </select>
        @error('brand_id')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Department Assignment -->
    <div>
        <label for="department_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Department</label>
        <select id="department_id" name="department_id" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ old('department_id', $color->department_id ?? ($dept->code === 'GRT' ? $dept->id : '')) == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }} ({{ $dept->code }})
                </option>
            @endforeach
        </select>
        @error('department_id')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Packing Size -->
    <div>
        <label for="packing_size" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Packing Size</label>
        <select id="packing_size" name="packing_size" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
            <option value="">Select Packing Size</option>
            <option value="500 GM" {{ old('packing_size', $color->packing_size ?? '') == '500 GM' ? 'selected' : '' }}>500 GM</option>
            <option value="1 KG" {{ old('packing_size', $color->packing_size ?? '') == '1 KG' ? 'selected' : '' }}>1 KG</option>
        </select>
        @error('packing_size')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Default Cement Type -->
    <div>
        <label for="default_cement" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Default Cement Type</label>
        <select id="default_cement" name="default_cement" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
            <option value="">Select Cement Type</option>
            <option value="White Cement" {{ old('default_cement', $color->default_cement ?? '') == 'White Cement' ? 'selected' : '' }}>White Cement</option>
            <option value="Grey Cement" {{ old('default_cement', $color->default_cement ?? '') == 'Grey Cement' ? 'selected' : '' }}>Grey Cement</option>
        </select>
        @error('default_cement')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Active Status -->
<div class="flex items-center">
    <label class="flex items-center text-sm text-slate-400 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $color->is_active ?? true) ? 'checked' : '' }}
            class="w-4 h-4 rounded border-slate-800 bg-slate-900 text-cyan-500 focus:ring-cyan-500/30 focus:ring-offset-0 mr-2 cursor-pointer">
        <span>Mark as Active (Available for Grout Formula mapping)</span>
    </label>
    @error('is_active')
        <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Description -->
<div>
    <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Description</label>
    <textarea id="description" name="description" rows="4"
        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
        placeholder="Provide details about the grout color properties or pigment configurations...">{{ old('description', $color->description ?? '') }}</textarea>
    @error('description')
        <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
