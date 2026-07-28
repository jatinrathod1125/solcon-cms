@extends('layouts.app')

@section('title', 'Edit Formula')
@section('header-title', 'Modify Formula')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Back Link -->
    <div>
        <a href="{{ route('admin.formulas.index') }}" class="inline-flex items-center text-sm text-slate-400 hover:text-white transition-colors gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Formulas</span>
        </a>
    </div>

    <!-- Builder Card -->
    <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-6 md:p-8">
        <h3 class="text-lg font-bold text-white mb-6">Modify Formula Version (v{{ $formula->version }})</h3>

        <form method="POST" action="{{ route('admin.formulas.update', $formula) }}" class="space-y-6" id="formula-form">
            @csrf
            @method('PUT')

            @include('admin.formulas._form')

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-850">
                <a href="{{ route('admin.formulas.index') }}" 
                    class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 rounded-xl text-sm font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                    class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 shadow-lg shadow-cyan-500/10 text-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let rowCount = {{ $formula->items->count() }};

        // Raw Material dropdown options
        const rawMaterials = [
            @foreach($rawMaterials as $mat)
                { id: "{{ $mat->id }}", code: "{{ $mat->code }}", name: "{{ $mat->name }}" },
            @endforeach
        ];

        // Packing Material dropdown options
        const packingMaterials = [
            @foreach($packingMaterials as $pm)
                { id: "{{ $pm->id }}", name: "{{ $pm->name }}", category: "{{ $pm->category->name ?? 'Packing' }}" },
            @endforeach
        ];

        // Unit dropdown options
        const units = [
            @foreach($units as $unit)
                { id: "{{ $unit->id }}", code: "{{ $unit->code }}" },
            @endforeach
        ];

        // Item type toggle handler
        $(document).on('change', '.item-type-select', function() {
            const row = $(this).closest('tr');
            if ($(this).val() === 'packing') {
                row.find('.raw-select-wrap').addClass('hidden').find('select').val('');
                row.find('.packing-select-wrap').removeClass('hidden');
            } else {
                row.find('.packing-select-wrap').addClass('hidden').find('select').val('');
                row.find('.raw-select-wrap').removeClass('hidden');
            }
        });

        // Add row event trigger
        $('#add-row').click(function() {
            addRow();
        });

        // Remove row event trigger
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            resequence();
        });

        function addRow() {
            rowCount++;
            let rawMaterialOptions = '<option value="">Select Raw Material</option>';
            rawMaterials.forEach(function(mat) {
                rawMaterialOptions += `<option value="${mat.id}">${mat.name} (${mat.code})</option>`;
            });

            let packingMaterialOptions = '<option value="">Select Packing Material</option>';
            packingMaterials.forEach(function(pm) {
                packingMaterialOptions += `<option value="${pm.id}">${pm.name} (${pm.category})</option>`;
            });

            let unitOptions = '';
            units.forEach(function(unit) {
                let selected = unit.code === 'KG' ? 'selected' : '';
                unitOptions += `<option value="${unit.id}" ${selected}>${unit.code}</option>`;
            });

            const rowHtml = `
                <tr class="hover:bg-slate-900/20 text-slate-200">
                    <td class="p-3 text-center font-mono text-slate-500 sequence-num">${rowCount}</td>
                    <td class="p-3">
                        <select name="items[${rowCount}][item_type]" class="item-type-select block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:border-cyan-500">
                            <option value="raw" selected>Raw Material</option>
                            <option value="packing">Packing Material</option>
                        </select>
                    </td>
                    <td class="p-3">
                        <div class="raw-select-wrap">
                            <select name="items[${rowCount}][raw_material_id]"
                                class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-sm focus:outline-none focus:border-cyan-500">
                                ${rawMaterialOptions}
                            </select>
                        </div>
                        <div class="packing-select-wrap hidden">
                            <select name="items[${rowCount}][packing_material_id]"
                                class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-sm focus:outline-none focus:border-purple-500">
                                ${packingMaterialOptions}
                            </select>
                        </div>
                    </td>
                    <td class="p-3">
                        <input type="number" step="0.0001" name="items[${rowCount}][quantity]" required min="0.0001"
                            class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-right text-sm font-mono focus:outline-none focus:border-cyan-500"
                            placeholder="0.0000">
                    </td>
                    <td class="p-3">
                        <select name="items[${rowCount}][unit_id]" required
                            class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-sm focus:outline-none focus:border-cyan-500">
                            ${unitOptions}
                        </select>
                        <input type="hidden" name="items[${rowCount}][sequence]" value="${rowCount}" class="sequence-input">
                    </td>
                    <td class="p-3">
                        <select name="items[${rowCount}][consumption_method]" required
                            class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-sm focus:outline-none focus:border-cyan-500">
                            <option value="formula">Formula Based</option>
                            <option value="output">Output Based</option>
                        </select>
                    </td>
                    <td class="p-3 text-center">
                        <button type="button" class="text-rose-500 hover:text-rose-400 remove-row" title="Remove Ingredient">
                            <i data-lucide="trash-2" class="w-4 h-4 mx-auto"></i>
                        </button>
                    </td>
                </tr>
            `;

            $('#items-tbody').append(rowHtml);
            resequence();
            lucide.createIcons(); // reload icons
        }

        function resequence() {
            let index = 1;
            $('#items-tbody tr').each(function() {
                $(this).find('.sequence-num').text(index);
                $(this).find('.sequence-input').val(index);
                index++;
            });
        }
    });
</script>
@endsection
