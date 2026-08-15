<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Brand -->
    <div>
        <label for="brand_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Brand</label>
        <select name="brand_id" id="brand_id"
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
            <option value="" {{ old('brand_id', $packingMaterial->brand_id ?? '') == '' ? 'selected' : '' }}></option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id', $packingMaterial->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>
        @error('brand_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
    </div>

    <!-- Category -->
    <div>
        <label for="category_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Category <span class="text-rose-500">*</span></label>
        <select name="category_id" id="category_id" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $packingMaterial->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
    </div>

    <!-- Code -->
    <div>
        <label for="code" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Item Code</label>
        <input type="text" name="code" id="code" value="{{ old('code', $packingMaterial->code ?? '') }}" placeholder="e.g. BAG-F101"
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white font-mono placeholder-slate-600 focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
        @error('code') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
    </div>

    <!-- Name -->
    <div>
        <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Material Name <span class="text-rose-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $packingMaterial->name ?? '') }}" required placeholder="e.g. F101 Bag"
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
        @error('name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
    </div>

    <!-- Size -->
    <div>
        <label for="size" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Size / Variant</label>
        <input type="text" name="size" id="size" value="{{ old('size', $packingMaterial->size ?? '') }}" placeholder="e.g. 1Kg, 2mm, 5L"
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
        @error('size') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
    </div>

    <!-- Unit -->
    <div>
        <label for="unit_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Unit of Measure <span class="text-rose-500">*</span></label>
        <select name="unit_id" id="unit_id" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
            <option value="">Select Unit</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}" {{ old('unit_id', $packingMaterial->unit_id ?? '') == $unit->id ? 'selected' : '' }}>
                    {{ $unit->name }} ({{ $unit->code }})
                </option>
            @endforeach
        </select>
        @error('unit_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
    </div>

    <!-- Minimum Stock -->
    <div>
        <label for="minimum_stock" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Minimum Stock <span class="text-rose-500">*</span></label>
        <input type="number" step="0.0001" min="0" name="minimum_stock" id="minimum_stock" value="{{ old('minimum_stock', $packingMaterial->minimum_stock ?? 0) }}" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white font-mono focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
        @error('minimum_stock') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
    </div>

    @if(!isset($packingMaterial))
        <!-- Opening Stock (Only during creation) -->
        <div>
            <label for="opening_stock" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Opening Stock <span class="text-rose-500">*</span></label>
            <input type="number" step="0.0001" min="0" name="opening_stock" id="opening_stock" value="{{ old('opening_stock', 0) }}" required
                class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white font-mono focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
            @error('opening_stock') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>
    @endif

    <!-- Status -->
    <div>
        <label for="status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Status <span class="text-rose-500">*</span></label>
        <select name="status" id="status" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
            <option value="active" {{ old('status', $packingMaterial->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $packingMaterial->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
    </div>
</div>

<!-- Remarks -->
<div class="mt-6">
    <label for="remarks" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Remarks / Notes</label>
    <textarea name="remarks" id="remarks" rows="3" placeholder="Optional notes or details..."
        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">{{ old('remarks', $packingMaterial->remarks ?? '') }}</textarea>
    @error('remarks') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
</div>
