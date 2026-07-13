<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse text-xs">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-100 text-slate-450 uppercase font-extrabold tracking-wider text-[9px]">
                <th class="px-5 py-3">Product Name</th>
                <th class="px-5 py-3 text-right">Available Qty</th>
                <th class="px-5 py-3 text-right">Min Stock</th>
                <th class="px-5 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium text-slate-750">
            @forelse($items as $item)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-5 py-3 font-bold text-slate-900">{{ $item->product_name }}</td>
                    <td class="px-5 py-3 text-right font-mono font-bold text-slate-900">{{ number_format($item->available_bags) }}</td>
                    <td class="px-5 py-3 text-right font-mono text-slate-400">{{ number_format($item->minimum_stock) }}</td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <button onclick="openAdjustModal({{ $item->id }}, '{{ addslashes($item->product_name) }}', '{{ $item->packing }}', {{ $item->available_bags }}, {{ $item->available_weight }}, 'increase')" class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold px-2.5 py-1 rounded-lg transition text-[11px] border border-emerald-200" title="Add Stock (+)">
                                <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                                <span>+ Add</span>
                            </button>
                            <button onclick="openAdjustModal({{ $item->id }}, '{{ addslashes($item->product_name) }}', '{{ $item->packing }}', {{ $item->available_bags }}, {{ $item->available_weight }}, 'decrease')" class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold px-2.5 py-1 rounded-lg transition text-[11px] border border-rose-200" title="Minus Stock (-)">
                                <i data-lucide="minus-circle" class="w-3.5 h-3.5 text-rose-600"></i>
                                <span>- Minus</span>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-10 text-slate-400 font-semibold">No finished goods found in stock. Complete production runs on the floor to log inventory.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($items->hasPages())
    <div class="px-5 py-4 border-t border-slate-100 pagination-container">
        {{ $items->links() }}
    </div>
@endif
