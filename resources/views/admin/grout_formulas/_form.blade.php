<!-- Color Selection & Remarks -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
        <label for="color_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Color Assignment</label>
        <select id="color_id" name="color_id" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
            <option value="">Select Color</option>
            @foreach($colors as $color)
                <option value="{{ $color->id }}" {{ old('color_id', $formula->color_id ?? '') == $color->id ? 'selected' : '' }}>
                    {{ $color->name }} ({{ $color->code }} - {{ $color->packing_size }}){{ $color->brand ? ' [' . $color->brand->name . ']' : '' }}
                </option>
            @endforeach
        </select>
        @error('color_id')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="remarks" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Revision Remarks</label>
        <input type="text" id="remarks" name="remarks" value="{{ old('remarks', $formula->remarks ?? '') }}"
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
            placeholder="e.g. Initial version matching color master specifications.">
        @error('remarks')
            <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Dynamic Items Builder -->
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Formula Ingredients</h4>
        <button type="button" id="add-row" 
            class="inline-flex items-center px-3 py-1.5 bg-slate-900 border border-slate-800 hover:border-cyan-500/40 text-slate-350 hover:text-cyan-400 rounded-xl text-xs font-semibold gap-1 transition-all">
            <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
            <span>Add Ingredient</span>
        </button>
    </div>
    
    @error('items')
        <div class="bg-red-500/10 border border-red-500/30 text-rose-450 p-3.5 rounded-xl text-xs font-semibold">
            {{ $message }}
        </div>
    @enderror

    <div class="border border-slate-850 rounded-xl overflow-hidden bg-slate-900/10">
        <table class="w-full text-left border-collapse text-sm" id="items-table">
            <thead>
                <tr class="border-b border-slate-850 bg-slate-900/60 text-slate-400 font-semibold">
                    <th class="p-3 w-16 text-center">Seq</th>
                    <th class="p-3">Raw Material</th>
                    <th class="p-3 w-36 text-right">Quantity</th>
                    <th class="p-3 w-32">Unit</th>
                    <th class="p-3 w-36">Mix Stage</th>
                    <th class="p-3 w-28 text-center">Reorder</th>
                    <th class="p-3 w-16 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-850/50" id="items-tbody">
                @if(isset($formula))
                    @foreach($formula->items as $idx => $item)
                        <tr class="hover:bg-slate-900/20 text-slate-200">
                            <td class="p-3 text-center font-mono text-slate-500 sequence-num">{{ $idx + 1 }}</td>
                            <td class="p-3">
                                <select name="items[{{ $idx }}][raw_material_id]" required
                                    class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:border-cyan-500">
                                    <option value="">Select Material</option>
                                    @foreach($rawMaterials as $mat)
                                        <option value="{{ $mat->id }}" {{ $item->raw_material_id == $mat->id ? 'selected' : '' }}>
                                            {{ $mat->name }} ({{ $mat->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-3">
                                <input type="number" step="0.0001" name="items[{{ $idx }}][quantity]" value="{{ format_quantity($item->quantity) }}" required min="0.0001"
                                    class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-right text-xs font-mono focus:outline-none focus:border-cyan-500"
                                    placeholder="e.g. 10.5">
                            </td>
                            <td class="p-3">
                                <select name="items[{{ $idx }}][unit_id]" required
                                    class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:border-cyan-500">
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ $item->unit_id == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-3">
                                <select name="items[{{ $idx }}][mix_stage]" required
                                    class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:border-cyan-500">
                                    <option value="Stage 1" {{ $item->mix_stage === 'Stage 1' ? 'selected' : '' }}>Stage 1</option>
                                    <option value="Stage 2" {{ $item->mix_stage === 'Stage 2' ? 'selected' : '' }}>Stage 2</option>
                                </select>
                                <input type="hidden" name="items[{{ $idx }}][display_order]" value="{{ $item->display_order }}" class="display-order-input">
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <button type="button" class="p-1 hover:bg-slate-800 rounded text-slate-400 hover:text-cyan-400 move-up" title="Move Up">
                                        <i data-lucide="arrow-up" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button type="button" class="p-1 hover:bg-slate-800 rounded text-slate-400 hover:text-cyan-400 move-down" title="Move Down">
                                        <i data-lucide="arrow-down" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="p-3 text-center">
                                <button type="button" class="text-rose-500 hover:text-rose-455 remove-row" title="Remove Ingredient">
                                    <i data-lucide="trash-2" class="w-4 h-4 mx-auto"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Status Toggle -->
<div class="flex items-center">
    <label class="flex items-center text-sm text-slate-400 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $formula->is_active ?? true) ? 'checked' : '' }}
            class="w-4 h-4 rounded border-slate-800 bg-slate-900 text-cyan-500 focus:ring-cyan-500/30 focus:ring-offset-0 mr-2 cursor-pointer">
        <span>Mark as Active (Automatically deactivates existing formula versions for this color)</span>
    </label>
</div>
