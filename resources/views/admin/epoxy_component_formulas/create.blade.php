@extends('layouts.app')

@section('title', 'Define Component Formula')
@section('header-title', 'Create Component Formula')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.epoxy-component-formulas.index') }}" class="p-2 hover:bg-slate-900 rounded-xl text-slate-400 hover:text-white transition-all">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white">Define Component Formula</h2>
            <p class="text-xs text-slate-500">Map the raw material and packaging ingredients to produce 1 unit of this component.</p>
        </div>
    </div>

    <div class="bg-slate-955 border border-slate-850 p-6 rounded-2xl shadow-xl">
        <form method="POST" action="{{ route('admin.epoxy-component-formulas.store') }}" class="space-y-6">
            @csrf

            <!-- Component & Version Header -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Target Component</label>
                    <select name="epoxy_component_id" required
                        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 text-sm">
                        <option value="">-- Select Component --</option>
                        @foreach($components as $comp)
                            <option value="{{ $comp->id }}" {{ old('epoxy_component_id', $preselectedComponentId) == $comp->id ? 'selected' : '' }}>
                                {{ $comp->name }} ({{ $comp->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Version Number</label>
                    <input type="number" name="version" value="{{ old('version', 1) }}" min="1" required
                        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 text-sm font-mono font-bold">
                </div>

                <div class="flex items-center pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-600"></div>
                        <span class="ml-3 text-sm font-semibold text-slate-300">Set as Active Formula</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Version Remarks / Description</label>
                <textarea name="description" rows="2" placeholder="Describe this formula variation..."
                    class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 text-sm">{{ old('description') }}</textarea>
            </div>

            <!-- Dynamic Items Builder -->
            <div class="space-y-4 pt-4 border-t border-slate-850">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Formula Ingredients (per 1 unit of component)</h3>
                    <button type="button" id="add-item-btn" class="inline-flex items-center px-3 py-1.5 bg-slate-900 hover:bg-slate-850 text-xs font-bold rounded-lg text-cyan-400 transition-colors border border-slate-800 gap-1.5">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Add Ingredient</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-slate-850 text-slate-400 font-semibold text-xs">
                                <th class="pb-3 pr-2 w-36">Type</th>
                                <th class="pb-3 pr-2">Material / Item Name</th>
                                <th class="pb-3 pr-2 w-32 text-right">Qty Required</th>
                                <th class="pb-3 pr-2 w-40">Unit</th>
                                <th class="pb-3 w-16 text-right">Remove</th>
                            </tr>
                        </thead>
                        <tbody id="formula-items-body" class="divide-y divide-slate-850/50">
                            <!-- Dynamic rows will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-900 flex justify-end gap-3">
                <a href="{{ route('admin.epoxy-component-formulas.index') }}"
                    class="px-5 py-2.5 bg-slate-900 hover:bg-slate-850 text-slate-300 hover:text-white rounded-xl text-sm font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-550 hover:to-indigo-550 text-white rounded-xl text-sm font-extrabold transition-all shadow-lg shadow-cyan-500/10">
                    Save Formula
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Template Row for items builder -->
<template id="item-row-template">
    <tr class="item-row">
        <!-- Item Type Selection -->
        <td class="py-3 pr-2">
            <select name="items[INDEX][item_type]" class="item-type-select block w-full px-2.5 py-2 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:ring-1 focus:ring-cyan-500">
                <option value="raw">Raw Material</option>
                <option value="packing">Packing Material</option>
            </select>
        </td>

        <!-- Material Select -->
        <td class="py-3 pr-2">
            <!-- Raw Material Select -->
            <div class="raw-select-wrap">
                <select name="items[INDEX][raw_material_id]" class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:ring-1 focus:ring-cyan-500">
                    <option value="">-- Select Raw Material --</option>
                    @foreach($rawMaterials as $rm)
                        <option value="{{ $rm->id }}">{{ $rm->name }} ({{ $rm->code }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Packing Material Select -->
            <div class="packing-select-wrap hidden">
                <select name="items[INDEX][packing_material_id]" class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:ring-1 focus:ring-purple-500">
                    <option value="">-- Select Packing Material --</option>
                    @foreach($packingMaterials as $pm)
                        <option value="{{ $pm->id }}">{{ $pm->name }} ({{ $pm->category->name ?? 'Packing' }})</option>
                    @endforeach
                </select>
            </div>
        </td>

        <!-- Quantity -->
        <td class="py-3 pr-2 text-right">
            <input type="number" name="items[INDEX][quantity]" step="0.0001" min="0.0001" required
                class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs text-right focus:outline-none focus:ring-1 focus:ring-cyan-500 font-mono text-emerald-400 font-bold">
        </td>

        <!-- Unit -->
        <td class="py-3 pr-2">
            <select name="items[INDEX][unit_id]" required class="block w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:ring-1 focus:ring-cyan-500">
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ $unit->code === 'KG' ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->code }})</option>
                @endforeach
            </select>
        </td>

        <!-- Remove -->
        <td class="py-3 text-right">
            <button type="button" class="remove-item-btn p-1.5 hover:bg-rose-500/10 text-slate-500 hover:text-rose-400 rounded-lg transition-colors">
                <i data-lucide="trash" class="w-4 h-4"></i>
            </button>
        </td>
    </tr>
</template>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var itemIndex = 0;

        function addRow() {
            var template = $('#item-row-template').html();
            var html = template.replace(/INDEX/g, itemIndex);
            var $row = $(html);
            $('#formula-items-body').append($row);
            
            if (typeof lucide !== 'undefined') {
                lucide.createIcons($row[0]);
            }
            
            itemIndex++;
        }

        $(document).on('change', '.item-type-select', function() {
            var $row = $(this).closest('tr');
            var type = $(this).val();
            if (type === 'raw') {
                $row.find('.raw-select-wrap').removeClass('hidden').find('select').prop('required', true);
                $row.find('.packing-select-wrap').addClass('hidden').find('select').prop('required', false).val('');
            } else {
                $row.find('.packing-select-wrap').removeClass('hidden').find('select').prop('required', true);
                $row.find('.raw-select-wrap').addClass('hidden').find('select').prop('required', false).val('');
            }
        });

        $('#add-item-btn').click(function() {
            addRow();
        });

        $(document).on('click', '.remove-item-btn', function() {
            $(this).closest('tr').remove();
        });

        // Add 2 initial rows
        addRow();
        addRow();
    });
</script>
@endsection
