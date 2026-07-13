@extends('layouts.app')

@section('title', 'Log Component Entry')
@section('header-title', 'Epoxy Component Entry')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('epoxy.index') }}" class="p-2 hover:bg-slate-900 rounded-xl text-slate-400 hover:text-white transition-all">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white">Log Prepared Components</h2>
            <p class="text-xs text-slate-500">Record prepared ready components. Raw materials will be deducted dynamically.</p>
        </div>
    </div>

    <div class="bg-slate-955 border border-slate-850 p-6 rounded-2xl shadow-xl">
        <form method="POST" action="{{ route('epoxy.component-entry.store') }}" class="space-y-4">
            @csrf

            <!-- Component Selection -->
            <div>
                <label for="epoxy_component_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Select Component</label>
                <select name="epoxy_component_id" id="epoxy_component_id" required
                    class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-slate-350 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
                    <option value="">-- Choose Component --</option>
                    @foreach($components as $comp)
                        <option value="{{ $comp->id }}" data-unit="{{ $comp->unit->code }}">
                            {{ $comp->name }} ({{ $comp->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quantity Prepared -->
            <div>
                <label for="quantity" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Quantity Prepared</label>
                <div class="relative rounded-xl shadow-sm">
                    <input type="number" name="quantity" id="quantity" min="1" required
                        class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                        placeholder="0">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider" id="unit-label">Units</span>
                    </div>
                </div>
            </div>

            <!-- Remarks -->
            <div>
                <label for="remarks" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Operator Notes / Remarks</label>
                <textarea name="remarks" id="remarks" rows="3"
                    class="block w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                    placeholder="Enter any supervisor remarks..."></textarea>
            </div>

            <div class="pt-4 border-t border-slate-900 flex justify-end gap-3">
                <a href="{{ route('epoxy.index') }}"
                    class="px-5 py-2.5 bg-slate-900 hover:bg-slate-850 text-slate-300 hover:text-white rounded-xl text-sm font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-550 hover:to-indigo-550 text-white rounded-xl text-sm font-extrabold transition-all shadow-lg shadow-cyan-500/10">
                    Save Transaction
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#epoxy_component_id').change(function() {
            var selected = $(this).find(':selected');
            var unit = selected.data('unit');
            if (unit) {
                $('#unit-label').text(unit);
            } else {
                $('#unit-label').text('Units');
            }
        });
    });
</script>
@endsection
