<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse text-xs">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-100 text-slate-450 uppercase font-extrabold tracking-wider text-[9px]">
                <th class="px-5 py-3">ID</th>
                <th class="px-5 py-3">Raw Material</th>
                <th class="px-5 py-3">Dept</th>
                <th class="px-5 py-3 text-right">Stock IN / OUT Qty</th>
                <th class="px-5 py-3">Remarks / Reason</th>
                <th class="px-5 py-3 font-semibold">Logged By</th>
                <th class="px-5 py-3">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium text-slate-750">
            @forelse($adjustments as $adj)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-5 py-3 font-mono font-bold text-slate-450">#ADJ-{{ str_pad($adj->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-5 py-3 text-slate-900 font-bold">
                        @if($adj->packingMaterial)
                            {{ $adj->packingMaterial->name }}
                            <span class="text-slate-400 font-semibold font-mono text-[10px]">({{ $adj->packingMaterial->code ?? '-' }})</span>
                            <span class="ml-1 px-1.5 py-0.5 rounded bg-purple-100 text-purple-700 text-[9px] font-bold">Packing</span>
                        @elseif($adj->rawMaterial)
                            {{ $adj->rawMaterial->name }}
                            <span class="text-slate-400 font-semibold font-mono text-[10px]">({{ $adj->rawMaterial->code }})</span>
                            <span class="ml-1 px-1.5 py-0.5 rounded bg-blue-100 text-blue-700 text-[9px] font-bold">Raw</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-5 py-3 text-slate-500 font-semibold">
                        @if($adj->packingMaterial)
                            {{ $adj->packingMaterial->category->name ?? '-' }}
                        @elseif($adj->rawMaterial)
                            {{ $adj->rawMaterial->department->code ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right font-mono font-bold {{ $adj->quantity >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $adj->quantity >= 0 ? '+' : '' }}{{ format_quantity($adj->quantity) }}
                    </td>
                    <td class="px-5 py-3 text-slate-650 font-semibold">{{ $adj->remarks ?: '-' }}</td>
                    <td class="px-5 py-3 text-slate-450 font-semibold">{{ $adj->creator->name }}</td>
                    <td class="px-5 py-3 text-slate-400 font-semibold text-[11px]">{{ $adj->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-10 text-slate-400 font-semibold">No stock adjustments logged.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($adjustments->hasPages())
    <div class="px-5 py-4 border-t border-slate-100 pagination-container">
        {{ $adjustments->links() }}
    </div>
@endif
