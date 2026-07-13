<!-- Product & Version Header -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label for="epoxy_product_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Target Product</label>
        <select name="epoxy_product_id" id="epoxy_product_id" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 text-sm">
            <option value="">-- Select Product --</option>
            @foreach($products as $prd)
                <option value="{{ $prd->id }}" {{ old('epoxy_product_id', $epoxyFormula->epoxy_product_id ?? '') == $prd->id ? 'selected' : '' }}>
                    {{ $prd->name }} ({{ $prd->code }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="version" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Version Number</label>
        <input type="number" name="version" id="version" value="{{ old('version', $epoxyFormula->version ?? 1) }}" min="1" required
            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 text-sm font-mono">
    </div>

    <div class="flex items-center pt-6">
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $epoxyFormula->is_active ?? true) ? 'checked' : '' }}
                class="h-4.5 w-4.5 rounded border-slate-800 bg-slate-900 text-cyan-600 focus:ring-cyan-500/50">
            <span class="text-sm font-semibold text-slate-300">Set as Active version</span>
        </label>
    </div>
</div>

<div>
    <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Version Remarks / Description</label>
    <textarea name="description" id="description" rows="2" placeholder="Approved formulation changes or notes..."
        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 text-sm">{{ old('description', $epoxyFormula->description ?? '') }}</textarea>
</div>

<!-- Dynamic Items Builder -->
<div class="space-y-4 pt-4 border-t border-slate-850">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">Formula Components</h3>
        <button type="button" id="add-item-btn" class="inline-flex items-center px-3 py-1.5 bg-slate-900 hover:bg-slate-850 text-xs font-bold rounded-lg text-cyan-400 transition-colors border border-slate-800 gap-1.5">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            <span>Add Ingredient</span>
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="border-b border-slate-850 text-slate-400 font-semibold">
                    <th class="pb-3 pr-2">Raw Material</th>
                    <th class="pb-3 pr-2 w-28 text-right">Quantity</th>
                    <th class="pb-3 pr-2 w-32">Unit</th>
                    <th class="pb-3 pr-2 w-36">Type</th>
                    <th class="pb-3 pr-2 w-28 text-center">Dynamic Color</th>
                    <th class="pb-3 w-16 text-right">Remove</th>
                </tr>
            </thead>
            <tbody id="formula-items-body" class="divide-y divide-slate-850/50">
                @if(isset($epoxyFormula))
                    @foreach($epoxyFormula->items as $idx => $item)
                        <tr class="item-row">
                            <td class="py-3 pr-2">
                                <select name="items[{{ $idx }}][raw_material_id]" required class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:ring-1 focus:ring-cyan-500">
                                    <option value="">-- Material --</option>
                                    @foreach($rawMaterials as $rm)
                                        <option value="{{ $rm->id }}" {{ $item->raw_material_id == $rm->id ? 'selected' : '' }}>{{ $rm->name }} ({{ $rm->code }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-3 pr-2 text-right">
                                <input type="number" name="items[{{ $idx }}][quantity]" value="{{ (float)$item->quantity }}" step="0.0001" min="0.0001" required
                                    class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs text-right focus:outline-none focus:ring-1 focus:ring-cyan-500 font-mono font-bold text-emerald-400">
                            </td>
                            <td class="py-3 pr-2">
                                <select name="items[{{ $idx }}][unit_id]" required class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:ring-1 focus:ring-cyan-500">
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ $item->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->code }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-3 pr-2">
                                <select name="items[{{ $idx }}][material_type]" required class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:ring-1 focus:ring-cyan-500">
                                    <option value="Bottle" {{ $item->material_type === 'Bottle' ? 'selected' : '' }}>Bottle</option>
                                    <option value="Pouch" {{ $item->material_type === 'Pouch' ? 'selected' : '' }}>Pouch</option>
                                    <option value="Accessory" {{ $item->material_type === 'Accessory' ? 'selected' : '' }}>Accessory</option>
                                    <option value="Bucket" {{ $item->material_type === 'Bucket' ? 'selected' : '' }}>Bucket</option>
                                </select>
                            </td>
                            <td class="py-3 pr-2 text-center">
                                <input type="checkbox" name="items[{{ $idx }}][is_dynamic_color]" value="1" {{ $item->is_dynamic_color ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-slate-800 bg-slate-900 text-cyan-600 focus:ring-cyan-500">
                            </td>
                            <td class="py-3 text-right">
                                <button type="button" class="remove-item-btn p-1.5 hover:bg-rose-500/10 text-slate-500 hover:text-rose-400 rounded-lg transition-colors">
                                    <i data-lucide="trash" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Template Row for items builder -->
<template id="item-row-template">
    <tr class="item-row">
        <td class="py-3 pr-2">
            <select name="items[INDEX][raw_material_id]" required class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:ring-1 focus:ring-cyan-500">
                <option value="">-- Material --</option>
                @foreach($rawMaterials as $rm)
                    <option value="{{ $rm->id }}">{{ $rm->name }} ({{ $rm->code }})</option>
                @endforeach
            </select>
        </td>
        <td class="py-3 pr-2 text-right">
            <input type="number" name="items[INDEX][quantity]" step="0.0001" min="0.0001" required
                class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs text-right focus:outline-none focus:ring-1 focus:ring-cyan-500 font-mono">
        </td>
        <td class="py-3 pr-2">
            <select name="items[INDEX][unit_id]" required class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:ring-1 focus:ring-cyan-500">
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ $unit->code === 'PCS' ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->code }})</option>
                @endforeach
            </select>
        </td>
        <td class="py-3 pr-2">
            <select name="items[INDEX][material_type]" required class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:ring-1 focus:ring-cyan-500">
                <option value="Bottle">Bottle</option>
                <option value="Pouch">Pouch</option>
                <option value="Accessory">Accessory</option>
                <option value="Bucket">Bucket</option>
            </select>
        </td>
        <td class="py-3 pr-2 text-center">
            <input type="checkbox" name="items[INDEX][is_dynamic_color]" value="1"
                class="h-4 w-4 rounded border-slate-800 bg-slate-900 text-cyan-600 focus:ring-cyan-500">
        </td>
        <td class="py-3 text-right">
            <button type="button" class="remove-item-btn p-1.5 hover:bg-rose-500/10 text-slate-500 hover:text-rose-400 rounded-lg transition-colors">
                <i data-lucide="trash" class="w-4 h-4"></i>
            </button>
        </td>
    </tr>
</template>
