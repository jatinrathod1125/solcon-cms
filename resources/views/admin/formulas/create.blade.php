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
                {
                    id: "{{ $mat->id }}",
                    code: "{{ addslashes($mat->code) }}",
                    name: "{{ addslashes($mat->name) }}",
                    brand_id: "{{ $mat->brand_id ?? '' }}",
                    brand_name: "{{ addslashes($mat->brand->name ?? '') }}"
                },
            @endforeach
        ];

        // Packing Material dropdown options
        const packingMaterials = [
            @foreach($packingMaterials as $pm)
                {
                    id: "{{ $pm->id }}",
                    name: "{{ addslashes($pm->name) }}",
                    category: "{{ addslashes($pm->category->name ?? 'Packing') }}",
                    brand_id: "{{ $pm->brand_id ?? '' }}",
                    brand_name: "{{ addslashes($pm->brand->name ?? '') }}"
                },
            @endforeach
        ];

        // Unit dropdown options
        const units = [
            @foreach($units as $unit)
                { id: "{{ $unit->id }}", code: "{{ $unit->code }}" },
            @endforeach
        ];

        // Grades list with brand association
        const allGrades = [
            @foreach($grades as $grade)
                {
                    id: "{{ $grade->id }}",
                    name: "{{ addslashes($grade->name) }}",
                    code: "{{ addslashes($grade->code) }}",
                    brand_id: "{{ $grade->brand_id ?? '' }}",
                    brand_name: "{{ addslashes($grade->brand->name ?? '') }}"
                },
            @endforeach
        ];

        function getFilteredGrades(selectedBrandId) {
            return allGrades.filter(function(grade) {
                return !selectedBrandId || grade.brand_id === selectedBrandId || !grade.brand_id;
            });
        }

        function getFilteredRawMaterials(selectedBrandId) {
            return rawMaterials.filter(function(mat) {
                return !selectedBrandId || mat.brand_id === selectedBrandId || !mat.brand_id;
            });
        }

        function getFilteredPackingMaterials(selectedBrandId) {
            return packingMaterials.filter(function(pm) {
                return !selectedBrandId || pm.brand_id === selectedBrandId || !pm.brand_id;
            });
        }

        function filterAllByBrand(preserveSelected = true) {
            const selectedBrandId = $('#brand_id').val();

            // 1. Filter Grade Assignment dropdown
            const currentGradeId = $('#grade_id').val();
            const $gradeSelect = $('#grade_id');
            $gradeSelect.empty();
            $gradeSelect.append('<option value="">Select Grade</option>');

            const filteredGrades = getFilteredGrades(selectedBrandId);
            let hasCurrentGrade = false;

            filteredGrades.forEach(function(grade) {
                const brandSuffix = grade.brand_name ? ` - [${grade.brand_name}]` : '';
                const isSelected = preserveSelected && currentGradeId && currentGradeId == grade.id;
                if (isSelected) hasCurrentGrade = true;

                $gradeSelect.append(
                    $('<option></option>')
                        .val(grade.id)
                        .attr('data-brand-id', grade.brand_id)
                        .text(`${grade.name} (${grade.code})${brandSuffix}`)
                        .prop('selected', isSelected)
                );
            });

            if (preserveSelected && hasCurrentGrade) {
                $gradeSelect.val(currentGradeId);
            }

            // 2. Filter Raw Materials & Packing Materials in all existing rows
            const filteredRaw = getFilteredRawMaterials(selectedBrandId);
            const filteredPacking = getFilteredPackingMaterials(selectedBrandId);

            $('#items-tbody tr').each(function() {
                const $rawSelect = $(this).find('select[name*="[raw_material_id]"]');
                const $packingSelect = $(this).find('select[name*="[packing_material_id]"]');

                if ($rawSelect.length) {
                    const currentRawId = $rawSelect.val();
                    $rawSelect.empty();
                    $rawSelect.append('<option value="">Select Raw Material</option>');
                    let hasCurrentRaw = false;

                    filteredRaw.forEach(function(mat) {
                        const brandSuffix = mat.brand_name ? ` - [${mat.brand_name}]` : '';
                        const isSelected = preserveSelected && currentRawId && currentRawId == mat.id;
                        if (isSelected) hasCurrentRaw = true;

                        $rawSelect.append(
                            $('<option></option>')
                                .val(mat.id)
                                .text(`${mat.name} (${mat.code})${brandSuffix}`)
                                .prop('selected', isSelected)
                        );
                    });

                    if (preserveSelected && hasCurrentRaw) {
                        $rawSelect.val(currentRawId);
                    }
                }

                if ($packingSelect.length) {
                    const currentPackingId = $packingSelect.val();
                    $packingSelect.empty();
                    $packingSelect.append('<option value="">Select Packing Material</option>');
                    let hasCurrentPacking = false;

                    filteredPacking.forEach(function(pm) {
                        const brandSuffix = pm.brand_name ? ` - [${pm.brand_name}]` : '';
                        const isSelected = preserveSelected && currentPackingId && currentPackingId == pm.id;
                        if (isSelected) hasCurrentPacking = true;

                        $packingSelect.append(
                            $('<option></option>')
                                .val(pm.id)
                                .text(`${pm.name} (${pm.category})${brandSuffix}`)
                                .prop('selected', isSelected)
                        );
                    });

                    if (preserveSelected && hasCurrentPacking) {
                        $packingSelect.val(currentPackingId);
                    }
                }
            });
        }

        // On Brand change, filter grades and ingredient materials
        $('#brand_id').on('change', function() {
            filterAllByBrand(false);
        });

        // When a grade is selected, if brand is not chosen yet, auto-select the grade's brand
        $('#grade_id').on('change', function() {
            const selectedGradeId = $(this).val();
            if (selectedGradeId) {
                const gradeObj = allGrades.find(g => g.id == selectedGradeId);
                if (gradeObj && gradeObj.brand_id && !$('#brand_id').val()) {
                    $('#brand_id').val(gradeObj.brand_id);
                    filterAllByBrand(true);
                }
            }
        });

        // Initialize filtering on page load
        filterAllByBrand(true);

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

        // Add initial row on load if table empty
        if ($('#items-tbody tr').length === 0) {
            addRow();
        }

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
            const selectedBrandId = $('#brand_id').val();
            const filteredRaw = getFilteredRawMaterials(selectedBrandId);
            const filteredPacking = getFilteredPackingMaterials(selectedBrandId);

            let rawMaterialOptions = '<option value="">Select Raw Material</option>';
            filteredRaw.forEach(function(mat) {
                const brandSuffix = mat.brand_name ? ` - [${mat.brand_name}]` : '';
                rawMaterialOptions += `<option value="${mat.id}">${mat.name} (${mat.code})${brandSuffix}</option>`;
            });

            let packingMaterialOptions = '<option value="">Select Packing Material</option>';
            filteredPacking.forEach(function(pm) {
                const brandSuffix = pm.brand_name ? ` - [${pm.brand_name}]` : '';
                packingMaterialOptions += `<option value="${pm.id}">${pm.name} (${pm.category})${brandSuffix}</option>`;
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
