@extends('layouts.app')

@section('title', 'Add Formula Version')
@section('header-title', 'Create Formula')

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
        <h3 class="text-lg font-bold text-white mb-6">Create Formula Version</h3>

        <form method="POST" action="{{ route('admin.formulas.store') }}" class="space-y-6" id="formula-form">
            @csrf

            @include('admin.formulas._form')

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-850">
                <a href="{{ route('admin.formulas.index') }}" 
                    class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 rounded-xl text-sm font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                    class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 shadow-lg shadow-cyan-500/10 text-sm">
                    Create Formula
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let rowCount = 0;

        // Raw Material dropdown options
        const rawMaterials = [
            @foreach($rawMaterials as $mat)
                { id: "{{ $mat->id }}", code: "{{ $mat->code }}", name: "{{ $mat->name }}" },
            @endforeach
        ];

        // Unit dropdown options
        const units = [
            @foreach($units as $unit)
                { id: "{{ $unit->id }}", code: "{{ $unit->code }}" },
            @endforeach
        ];

        // Add initial row on load
        addRow();

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
            let materialOptions = '<option value="">Select Material</option>';
            rawMaterials.forEach(function(mat) {
                materialOptions += `<option value="${mat.id}">${mat.name} (${mat.code})</option>`;
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
                        <select name="items[${rowCount}][raw_material_id]" required
                            class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-sm focus:outline-none focus:border-cyan-500">
                            ${materialOptions}
                        </select>
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
