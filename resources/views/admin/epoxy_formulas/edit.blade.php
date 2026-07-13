@extends('layouts.app')

@section('title', 'Edit Epoxy Formula')
@section('header-title', 'Modify Epoxy Formula')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <a href="{{ route('admin.epoxy-formulas.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-700 transition-colors gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Epoxy Formulas</span>
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
        <div>
            <h2 class="text-lg font-extrabold text-slate-900">Edit Epoxy Formula</h2>
            <p class="text-xs text-slate-500">Update ingredients, quantities, and dynamic color flags for Epoxy assembly lines.</p>
        </div>

        <form action="{{ route('admin.epoxy-formulas.update', $epoxyFormula->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            @include('admin.epoxy_formulas._form')

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.epoxy-formulas.index') }}" class="erp-button border border-slate-200 text-slate-650 hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit" class="erp-button bg-blue-600 text-white hover:bg-blue-500">
                    <i data-lucide="save" class="w-4 h-4"></i>Update Formula
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var itemIndex = {{ count($epoxyFormula->items) }};

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

        $('#add-item-btn').click(function() {
            addRow();
        });

        $(document).on('click', '.remove-item-btn', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
@endsection
