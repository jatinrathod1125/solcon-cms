@extends('layouts.app')

@section('title', 'Manual Epoxy Assembly')
@section('header-title', 'Epoxy Assembly Floor')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('epoxy.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-white transition-colors uppercase tracking-wider font-semibold mb-2">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Back to Floor</span>
            </a>
            <h2 class="text-2xl font-black text-white tracking-tight">Manual Assembly Floor</h2>
            <p class="text-xs text-slate-400">Select target kit, enter quantity, check formula scaling, and complete assembly run.</p>
        </div>
    </div>

    <!-- Validation alerts -->
    @if ($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/20 p-4 rounded-xl text-rose-400 text-sm space-y-1">
            <span class="font-bold">Please correct the following errors:</span>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="assembly-form" action="{{ route('epoxy.bucket-assembly.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="epoxy_product_id" id="epoxy_product_id" value="{{ old('epoxy_product_id') }}">

        <!-- Step 1: Product Selection Cards -->
        <div class="bg-slate-955 border border-slate-850 p-6 rounded-2xl shadow-xl space-y-4">
            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">Step 1: Select Epoxy Product</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($products as $product)
                    @php
                        $hasFormula = $product->activeFormula()->exists();
                    @endphp
                    <div data-product-id="{{ $product->id }}" 
                         data-requires-color="{{ $product->requires_color ? '1' : '0' }}"
                         data-has-formula="{{ $hasFormula ? '1' : '0' }}"
                         class="product-card border border-slate-850 hover:border-purple-500/50 bg-slate-900/40 rounded-xl p-5 cursor-pointer transition-all flex flex-col justify-between space-y-4 relative overflow-hidden group {{ old('epoxy_product_id') == $product->id ? 'border-purple-500 bg-purple-500/5 ring-2 ring-purple-500/20' : '' }} {{ !$hasFormula ? 'opacity-40 cursor-not-allowed' : '' }}">
                        
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-mono text-slate-500 uppercase tracking-wider group-hover:text-purple-400 transition-colors">{{ $product->code }}</span>
                                @if($product->requires_color)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-blue-500/10 text-blue-400 uppercase tracking-wider">Color Based</span>
                                @endif
                            </div>
                            <h4 class="text-base font-extrabold text-white group-hover:text-purple-300 transition-colors">{{ $product->name }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $product->description }}</p>
                        </div>

                        <div>
                            @if($hasFormula)
                                <span class="text-[10px] text-emerald-400 font-semibold flex items-center gap-1.5">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>Active recipe loaded
                                </span>
                            @else
                                <span class="text-[10px] text-rose-500 font-semibold flex items-center gap-1.5">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>No active formula
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Step 2: Options (Color & Quantity) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Dynamic Color Selector (Hidden by default) -->
            <div id="color-select-panel" class="bg-slate-955 border border-slate-850 p-6 rounded-2xl shadow-xl space-y-4 hidden">
                <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">Step 2: Choose Color</h3>
                
                <div class="space-y-2">
                    <label for="epoxy_filler_color_id" class="block text-xs text-slate-500 font-bold">Select Epoxy Filler Color</label>
                    <select name="epoxy_filler_color_id" id="epoxy_filler_color_id"
                        class="block w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500/50 text-sm">
                        <option value="">-- Choose Color --</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" {{ old('epoxy_filler_color_id') == $color->id ? 'selected' : '' }}>
                                {{ $color->name }} ({{ $color->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Quantity Touch Increment Selector -->
            <div class="bg-slate-955 border border-slate-850 p-6 rounded-2xl shadow-xl space-y-4">
                <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">Step 3: Enter Quantity</h3>
                
                <div class="flex items-center gap-3">
                    <button type="button" data-change="-1" class="h-12 w-12 flex items-center justify-center bg-slate-900 hover:bg-slate-850 border border-slate-800 rounded-xl text-slate-400 hover:text-white transition-all text-xl font-bold select-none">-</button>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" min="1" required
                        class="h-12 flex-1 text-center bg-slate-900 border border-slate-800 rounded-xl text-white text-lg font-black focus:outline-none focus:ring-2 focus:ring-purple-500/50 font-mono font-bold text-cyan-400">
                    <button type="button" data-change="1" class="h-12 w-12 flex items-center justify-center bg-slate-900 hover:bg-slate-850 border border-slate-800 rounded-xl text-slate-400 hover:text-white transition-all text-xl font-bold select-none">+</button>
                </div>

                <div class="flex items-center gap-1.5 flex-wrap">
                    <button type="button" data-set="5" class="px-3 py-1 bg-slate-900 hover:bg-slate-850 border border-slate-800 rounded-lg text-xs font-semibold text-slate-400 hover:text-white transition-colors">5</button>
                    <button type="button" data-set="10" class="px-3 py-1 bg-slate-900 hover:bg-slate-850 border border-slate-800 rounded-lg text-xs font-semibold text-slate-400 hover:text-white transition-colors">10</button>
                    <button type="button" data-set="25" class="px-3 py-1 bg-slate-900 hover:bg-slate-850 border border-slate-800 rounded-lg text-xs font-semibold text-slate-400 hover:text-white transition-colors">25</button>
                    <button type="button" data-set="50" class="px-3 py-1 bg-slate-900 hover:bg-slate-850 border border-slate-800 rounded-lg text-xs font-semibold text-slate-400 hover:text-white transition-colors">50</button>
                    <button type="button" data-set="100" class="px-3 py-1 bg-slate-900 hover:bg-slate-850 border border-slate-800 rounded-lg text-xs font-semibold text-slate-400 hover:text-white transition-colors">100</button>
                </div>
            </div>
        </div>

        <!-- Step 3: Recipe Preview Drawer -->
        <div id="recipe-preview-panel" class="bg-slate-955 border border-slate-850 p-6 rounded-2xl shadow-xl space-y-4 hidden">
            <div class="flex items-center justify-between border-b border-slate-900 pb-3">
                <div>
                    <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">Formula Scaling Preview</h3>
                    <p class="text-[10px] text-slate-500">Live preview of components and stocks based on target quantity.</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-purple-400"></span>Live Calculator
                </span>
            </div>

            <div class="overflow-x-auto border border-slate-900 rounded-xl">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-900 bg-slate-900/40 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="p-3">Material</th>
                            <th class="p-3">Type</th>
                            <th class="p-3 text-right">Scaled Needed</th>
                            <th class="p-3 text-right">Stock Available</th>
                            <th class="p-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="recipe-preview-body" class="divide-y divide-slate-900/50">
                        <!-- Items rendered by AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Remarks Accordion Drawer -->
        <div class="bg-slate-955 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
            <button type="button" id="toggle-remarks" class="w-full flex items-center justify-between p-5 text-sm font-bold text-slate-400 hover:text-white transition-colors">
                <span class="flex items-center gap-2"><i data-lucide="message-square" class="w-4 h-4"></i> Add Assembly Remarks</span>
                <i data-lucide="chevron-down" id="remarks-chevron" class="w-4 h-4 transition-transform"></i>
            </button>
            <div id="remarks-drawer" class="p-5 pt-0 hidden border-t border-slate-900">
                <textarea name="remarks" rows="2" placeholder="Enter batch runs, packaging lot numbers, or comments..."
                    class="block w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-purple-500/50 text-xs">{{ old('remarks') }}</textarea>
            </div>
        </div>

        <!-- Complete Button -->
        <button type="submit" id="complete-btn" class="w-full py-4.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-black uppercase tracking-widest rounded-2xl transition-all duration-205 shadow-xl shadow-purple-500/10 text-sm transform active:scale-[0.99] disabled:opacity-40 disabled:cursor-not-allowed">
            Complete Manual Assembly
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        function escapeHtml(text) {
            return text ? $('<div>').text(text).html() : '';
        }
        // Toggle remarks
        $('#toggle-remarks').click(function() {
            $('#remarks-drawer').slideToggle(200);
            $('#remarks-chevron').toggleClass('rotate-180');
        });

        // Product Selection
        $('.product-card').click(function() {
            var $card = $(this);
            if ($card.data('has-formula') !== 1) return;

            $('.product-card').removeClass('border-purple-500 bg-purple-500/5 ring-2 ring-purple-500/20');
            $card.addClass('border-purple-500 bg-purple-500/5 ring-2 ring-purple-500/20');
            
            var productId = $card.data('product-id');
            $('#epoxy_product_id').val(productId);

            // Handle color requirements
            var reqColor = $card.data('requires-color') === 1;
            if (reqColor) {
                $('#color-select-panel').slideDown(200);
                $('#epoxy_filler_color_id').attr('required', 'required');
            } else {
                $('#color-select-panel').slideUp(200);
                $('#epoxy_filler_color_id').removeAttr('required').val('');
            }

            fetchFormulaPreview();
        });

        // Quantity Adjusters
        $('[data-change]').click(function() {
            var diff = parseInt($(this).data('change'));
            var val = parseInt($('#quantity').val()) || 1;
            var newVal = Math.max(1, val + diff);
            $('#quantity').val(newVal);
            fetchFormulaPreview();
        });

        $('[data-set]').click(function() {
            var setVal = parseInt($(this).data('set'));
            $('#quantity').val(setVal);
            fetchFormulaPreview();
        });

        $('#quantity, #epoxy_filler_color_id').on('input change', function() {
            fetchFormulaPreview();
        });

        // AJAX Preview
        function fetchFormulaPreview() {
            var productId = $('#epoxy_product_id').val();
            if (!productId) {
                $('#recipe-preview-panel').slideUp(200);
                return;
            }

            var qty = parseInt($('#quantity').val()) || 1;
            var colorId = $('#epoxy_filler_color_id').val();

            $.ajax({
                url: `/epoxy-production/products/${productId}/formula-preview`,
                method: 'GET',
                data: {
                    quantity: qty,
                    epoxy_filler_color_id: colorId
                },
                success: function(response) {
                    $('#recipe-preview-body').empty();
                    var hasErrors = false;

                    response.items.forEach(function(item) {
                        var statusClass = 'bg-emerald-500/10 text-emerald-400';
                        if (item.status === 'Insufficient Stock') {
                            statusClass = 'bg-rose-500/10 text-rose-400';
                            hasErrors = true;
                        } else if (item.status === 'Missing Component') {
                            statusClass = 'bg-yellow-500/10 text-yellow-400';
                            hasErrors = true;
                        }

                        var rowHtml = `
                            <tr>
                                <td class="p-3 font-semibold text-white">
                                    <span class="block">${escapeHtml(item.name)}</span>
                                    <span class="block text-[10px] text-slate-500 font-mono">${escapeHtml(item.code)}</span>
                                </td>
                                <td class="p-3 text-slate-400">${escapeHtml(item.type)}</td>
                                <td class="p-3 text-right font-mono font-bold text-slate-300">${item.quantity.toLocaleString()} ${escapeHtml(item.unit)}</td>
                                <td class="p-3 text-right font-mono text-slate-400">${item.stock.toLocaleString()} ${escapeHtml(item.unit)}</td>
                                <td class="p-3 text-center">
                                    <span class="inline-flex px-2 py-0.5 rounded-full font-bold text-[9px] uppercase tracking-wider ${statusClass}">
                                        ${escapeHtml(item.status)}
                                    </span>
                                </td>
                            </tr>
                        `;
                        $('#recipe-preview-body').append(rowHtml);
                    });

                    $('#recipe-preview-panel').slideDown(200);
                    
                    // Enable/Disable complete button
                    if (hasErrors) {
                        $('#complete-btn').prop('disabled', true).text('Cannot Assemble: Components Unresolved/Out of Stock');
                    } else {
                        $('#complete-btn').prop('disabled', false).text('Complete Manual Assembly');
                    }
                },
                error: function(xhr) {
                    $('#recipe-preview-panel').slideUp(200);
                    $('#complete-btn').prop('disabled', true).text('Cannot Assemble');
                }
            });
        }

        // Trigger initial click on old or first product
        var initialProd = $('#epoxy_product_id').val();
        if (initialProd) {
            $(`.product-card[data-product-id="${initialProd}"]`).click();
        } else {
            // Click first active product card by default
            $('.product-card[data-has-formula="1"]').first().click();
        }
    });
</script>
@endsection
