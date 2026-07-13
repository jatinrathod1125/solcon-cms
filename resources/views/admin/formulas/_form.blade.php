<!-- Grade Selection & Remarks -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
        <label for="grade_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Grade Assignment</label>
        <select id="grade_id" name="grade_id" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
            <option value="">Select Grade</option>
            @foreach($grades as $grade)
                <option value="{{ $grade->id }}" {{ old('grade_id', $formula->grade_id ?? '') == $grade->id ? 'selected' : '' }}>
                    {{ $grade->name }} ({{ $grade->code }})
                </option>
            @endforeach
        </select>
        @error('grade_id')
            <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="remarks" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Revision Remarks</label>
        <input type="text" id="remarks" name="remarks" value="{{ old('remarks', $formula->remarks ?? '') }}"
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
            placeholder="e.g. Initial formula version with Standard Cement.">
        @error('remarks')
            <p class="text-rose-450 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Dynamic Items Builder -->
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Formula Ingredients</h4>
        <button type="button" id="add-row" 
            class="inline-flex items-center px-3 py-1.5 bg-slate-900 border border-slate-800 hover:border-cyan-500/40 text-slate-355 hover:text-cyan-400 rounded-xl text-xs font-semibold gap-1 transition-all">
            <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
            <span>Add Ingredient</span>
        </button>
    </div>
    
    @error('items')
        <p class="text-rose-450 text-xs">{{ $message }}</p>
    @enderror

    <div class="border border-slate-850 rounded-xl overflow-hidden bg-slate-900/10">
        <table class="w-full text-left border-collapse text-sm" id="items-table">
            <thead>
                <tr class="border-b border-slate-850 bg-slate-900/60 text-slate-400 font-semibold">
                    <th class="p-3 w-16 text-center">Seq</th>
                    <th class="p-3">Raw Material</th>
                    <th class="p-3 w-36 text-right">Quantity</th>
                    <th class="p-3 w-28">Unit</th>
                    <th class="p-3 w-40">Consumption</th>
                    <th class="p-3 w-16 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-850/50" id="items-tbody">
                @if(isset($formula))
                    @foreach($formula->items as $index => $item)
                        <tr class="hover:bg-slate-900/20 text-slate-200">
                            <td class="p-3 text-center font-mono text-slate-500 sequence-num">{{ $index + 1 }}</td>
                            <td class="p-3">
                                <select name="items[{{ $index + 1 }}][raw_material_id]" required
                                    class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-sm focus:outline-none focus:border-cyan-500">
                                    <option value="">Select Material</option>
                                    @foreach($rawMaterials as $mat)
                                        <option value="{{ $mat->id }}" {{ $item->raw_material_id == $mat->id ? 'selected' : '' }}>
                                            {{ $mat->name }} ({{ $mat->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-3">
                                <input type="number" step="0.0001" name="items[{{ $index + 1 }}][quantity]" value="{{ number_format($item->quantity, 4, '.', '') }}" required min="0.0001"
                                    class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-right text-sm font-mono focus:outline-none focus:border-cyan-500"
                                    placeholder="0.0000">
                            </td>
                            <td class="p-3">
                                <select name="items[{{ $index + 1 }}][unit_id]" required
                                    class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-sm focus:outline-none focus:border-cyan-500">
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ $item->unit_id == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->code }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="items[{{ $index + 1 }}][sequence]" value="{{ $item->sequence }}" class="sequence-input">
                            </td>
                            <td class="p-3">
                                <select name="items[{{ $index + 1 }}][consumption_method]" required
                                    class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-sm focus:outline-none focus:border-cyan-500">
                                    <option value="formula" {{ ($item->consumption_method ?? 'formula') === 'formula' ? 'selected' : '' }}>Formula Based</option>
                                    <option value="output" {{ ($item->consumption_method ?? 'formula') === 'output' ? 'selected' : '' }}>Output Based</option>
                                </select>
                            </td>
                            <td class="p-3 text-center">
                                <button type="button" class="text-rose-500 hover:text-rose-400 remove-row" title="Remove Ingredient">
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
        <span>Mark as Active (Automatically deactivates existing formula versions for this grade)</span>
    </label>
</div>
