@extends('layouts.app')

@section('title', 'Add Grout Formula Version')
@section('header-title', 'Create Grout Formula')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Back Link -->
    <div>
        <a href="{{ route('admin.grout-formulas.index') }}" class="inline-flex items-center text-sm text-slate-400 hover:text-white transition-colors gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Formulas</span>
        </a>
    </div>

    <!-- Builder Card -->
    <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-6 md:p-8">
        <h3 class="text-lg font-bold text-white mb-6">Create Grout Formula Version</h3>

        <form method="POST" action="{{ route('admin.grout-formulas.store') }}" class="space-y-6" id="formula-form">
            @csrf

            @include('admin.grout_formulas._form')

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-850">
                <a href="{{ route('admin.grout-formulas.index') }}" 
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

        // Add 2 initial rows on load
        addRow();
        addRow();

        // Add row event trigger
        $('#add-row').click(function() {
            addRow();
        });

        // Remove row event trigger
        $(document).on('click', '.remove-row', function() {
            var rowsCount = $('#items-tbody tr').length;
            if (rowsCount <= 1) {
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'error',
                    title: 'At least one ingredient is required.',
                    showConfirmButton: false, timer: 3000,
                    background: '#090d16', color: '#f1f5f9'
                });
                return;
            }
            $(this).closest('tr').remove();
            resequence();
        });

        // Reorder Row Up
        $(document).on('click', '.move-up', function() {
            var row = $(this).closest('tr');
            var prev = row.prev();
            if (prev.length) {
                row.insertBefore(prev);
                resequence();
            }
        });

        // Reorder Row Down
        $(document).on('click', '.move-down', function() {
            var row = $(this).closest('tr');
            var next = row.next();
            if (next.length) {
                row.insertAfter(next);
                resequence();
            }
        });

        function addRow() {
            let materialOptions = '<option value="">Select Material</option>';
            rawMaterials.forEach(function(mat) {
                materialOptions += `<option value="${mat.id}">${mat.name} (${mat.code})</option>`;
            });

            let unitOptions = '';
            units.forEach(function(unit) {
                let selected = unit.code === 'KG' ? 'selected' : '';
                unitOptions += `<option value="${unit.id}" ${selected}>${unit.code}</option>`;
            });

            // Default stage alternates just for layout assistance
            let alternateStage = ($('#items-tbody tr').length % 2 === 0) ? 'Stage 1' : 'Stage 2';

            const rowHtml = `
                <tr class="hover:bg-slate-900/20 text-slate-200">
                    <td class="p-3 text-center font-mono text-slate-500 sequence-num"></td>
                    <td class="p-3">
                        <select name="items[TEMP_INDEX][raw_material_id]" required
                            class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:border-cyan-500">
                            ${materialOptions}
                        </select>
                    </td>
                    <td class="p-3">
                        <input type="number" step="0.0001" name="items[TEMP_INDEX][quantity]" required min="0.0001"
                            class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-right text-xs font-mono focus:outline-none focus:border-cyan-500"
                            placeholder="0.0000">
                    </td>
                    <td class="p-3">
                        <select name="items[TEMP_INDEX][unit_id]" required
                            class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:border-cyan-500">
                            ${unitOptions}
                        </select>
                    </td>
                    <td class="p-3">
                        <select name="items[TEMP_INDEX][mix_stage]" required
                            class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-white text-xs focus:outline-none focus:border-cyan-500">
                            <option value="Stage 1" ${alternateStage === 'Stage 1' ? 'selected' : ''}>Stage 1</option>
                            <option value="Stage 2" ${alternateStage === 'Stage 2' ? 'selected' : ''}>Stage 2</option>
                        </select>
                        <input type="hidden" name="items[TEMP_INDEX][display_order]" value="0" class="display-order-input">
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
            `;

            $('#items-tbody').append(rowHtml);
            resequence();
            lucide.createIcons();
        }

        function resequence() {
            let index = 0;
            $('#items-tbody tr').each(function() {
                var row = $(this);
                // Update sequence display
                row.find('.sequence-num').text(index + 1);
                
                // Update inputs names indexes
                row.find('select, input').each(function() {
                    var el = $(this);
                    var name = el.attr('name');
                    if (name) {
                        el.attr('name', name.replace(/items\[\d*\]|items\[TEMP_INDEX\]/, 'items[' + index + ']'));
                    }
                });

                // Update hidden display order
                row.find('.display-order-input').val(index);
                index++;
            });
        }
    });
</script>
@endsection
