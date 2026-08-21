@extends('layouts.app')

@section('title', 'Finished Goods Inventory')
@section('header-title', 'Finished Goods Inventory')

@section('content')
<div class="mx-auto max-w-[1600px] space-y-4">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <p class="text-xs font-semibold text-slate-400">Total list of products held in finished goods warehouse, mapped directly to production output.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @if(auth()->user()->isAdmin())
                <button onclick="openCreateModal()" class="erp-button bg-blue-650 text-white hover:bg-blue-600">
                    <i data-lucide="plus" class="w-4 h-4"></i>Add Finished Good
                </button>
            @endif
            <button onclick="openImportModal()" class="erp-button border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">
                <i data-lucide="upload-cloud" class="w-4 h-4"></i>Import CSV
            </button>
            <a href="{{ route('finished-goods.export') }}" class="erp-button bg-slate-900 text-white hover:bg-slate-800">
                <i data-lucide="download-cloud" class="w-4 h-4"></i>Export CSV
            </a>
        </div>
    </div>

    <!-- Alert Blocks -->
    @if(session('success'))
        <div class="bg-emerald-550/10 border border-emerald-500/20 text-emerald-700 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span>{!! session('success') !!}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-550/10 border border-rose-500/20 text-rose-700 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
            <i data-lucide="alert-octagon" class="w-4 h-4 text-rose-600"></i>
            <span>{!! session('error') !!}</span>
        </div>
    @endif

    <!-- Permanent Sleek Filter Bar -->
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
        <form id="filterForm" method="GET" action="{{ route('finished-goods.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div class="relative">
                <label class="block text-slate-500 mb-1 uppercase font-bold tracking-wider text-[9px]">Search Product</label>
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"></i>
                    <input type="text" id="filterSearch" name="search" value="{{ request('search') }}" class="block w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" placeholder="Type name or code...">
                </div>
            </div>

            <div>
                <label class="block text-slate-500 mb-1 uppercase font-bold tracking-wider text-[9px]">Department</label>
                <select id="filterDept" name="department_id" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-slate-500 mb-1 uppercase font-bold tracking-wider text-[9px]">Packing Size</label>
                <select id="filterPacking" name="packing" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <option value="">All Packings</option>
                    @foreach($packingSizes as $size)
                        <option value="{{ $size }}" {{ request('packing') === $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-slate-500 mb-1 uppercase font-bold tracking-wider text-[9px]">Stock Status</label>
                <select id="filterStatus" name="status" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <option value="">All Balances</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Healthy Stock</option>
                    <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock Alerts</option>
                    <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Inventory Table Card -->
    <article class="erp-card overflow-hidden" id="tableContainer">
        @include('finished_goods._table')
    </article>
</div>

<!-- PREMIUM MANUAL ADJUSTMENT MODAL -->
<div id="adjustModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white border border-slate-100 rounded-3xl p-6 w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300">
        
        <button onclick="closeAdjustModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition p-1.5 rounded-full hover:bg-slate-100">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2.5 bg-blue-50 rounded-2xl">
                <i data-lucide="sliders" class="w-5 h-5 text-blue-650"></i>
            </div>
            <div>
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Adjust Stock</h3>
                <p id="modalProductName" class="text-sm font-black text-slate-800"></p>
            </div>
        </div>

        <form id="adjustForm" method="POST" action="" class="space-y-4 text-xs">
            @csrf

            <!-- Current Stock Stats -->
            <div class="grid grid-cols-2 gap-2 p-3 bg-slate-50 border border-slate-100 rounded-2xl text-center">
                <div>
                    <span class="text-[9px] text-slate-450 uppercase font-extrabold tracking-wide">Current Qty</span>
                    <span id="modalCurrentQty" class="block text-sm font-black text-slate-800 font-mono mt-0.5">0 Bags</span>
                </div>
                <div>
                    <span class="text-[9px] text-slate-450 uppercase font-extrabold tracking-wide">Current Weight</span>
                    <span id="modalCurrentWeight" class="block text-sm font-black text-slate-800 font-mono mt-0.5">0.00 KG</span>
                </div>
            </div>

            <!-- Adjustment Type Selection cards -->
            <div>
                <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Adjustment Action</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="border-2 border-slate-100 rounded-2xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50/50 transition relative" id="lblIncrease">
                        <input type="radio" name="type" value="increase" checked class="hidden" onchange="toggleTypeCards('increase')">
                        <i data-lucide="plus-circle" class="w-5 h-5 text-emerald-600 mb-1"></i>
                        <span class="font-extrabold text-[10px] text-slate-700">Increase (+)</span>
                    </label>
                    <label class="border-2 border-slate-100 rounded-2xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50/50 transition relative" id="lblDecrease">
                        <input type="radio" name="type" value="decrease" class="hidden" onchange="toggleTypeCards('decrease')">
                        <i data-lucide="minus-circle" class="w-5 h-5 text-rose-600 mb-1"></i>
                        <span class="font-extrabold text-[10px] text-slate-700">Decrease (-)</span>
                    </label>
                </div>
            </div>

            <!-- Quantity & Weight -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Bags / Units Change</label>
                    <input type="number" name="quantity" min="1" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" placeholder="e.g. 10" required>
                </div>
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Weight Change (KG)</label>
                    <input type="number" step="0.0001" name="weight" min="0.0001" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" placeholder="Auto-calculated">
                </div>
            </div>

            <!-- Reason -->
            <div>
                <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Reason for Adjustment</label>
                <input type="text" name="reason" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" placeholder="e.g. damaged, reconciliation discrepancies" required>
            </div>

            <!-- Remarks -->
            <div>
                <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Remarks / Log Notes</label>
                <textarea name="remarks" rows="2" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" placeholder="Additional remarks (optional)..."></textarea>
            </div>

            <!-- Actions -->
            <div class="pt-2 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="closeAdjustModal()" class="erp-button border border-slate-200 text-slate-650 hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit" class="erp-button bg-blue-650 text-white hover:bg-blue-600">
                    Apply Adjustment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- PREMIUM IMPORT CSV MODAL -->
<div id="importModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-white border border-slate-100 rounded-3xl p-6 w-full max-w-md shadow-2xl relative">
        <button onclick="closeImportModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition p-1.5 rounded-full hover:bg-slate-100">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="flex items-center gap-3 mb-4">
            <div class="p-2.5 bg-slate-50 rounded-2xl">
                <i data-lucide="upload-cloud" class="w-5 h-5 text-slate-700"></i>
            </div>
            <div>
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">CSV Data Import</h3>
                <p class="text-[11px] text-slate-400">Import inventory spreadsheet details directly</p>
            </div>
        </div>

        <p class="text-slate-400 text-[11px] mb-4">Upload a CSV file containing finished goods mapping details. Expected headers: <code class="bg-slate-100 px-1 py-0.5 rounded font-mono text-[10px] text-slate-700">Department, Product, Packing, Quantity</code>.</p>

        <form method="POST" action="{{ route('finished-goods.import') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf

            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 flex flex-col items-center justify-center bg-slate-50/50 hover:bg-slate-50 transition cursor-pointer relative">
                <input type="file" name="csv_file" accept=".csv" class="absolute inset-0 opacity-0 cursor-pointer" required onchange="updateFileLabel(this)">
                <i data-lucide="file-spreadsheet" class="w-8 h-8 text-slate-400 mb-2"></i>
                <span id="fileLabel" class="text-slate-650 font-bold">Select CSV File</span>
                <span class="text-slate-400 text-[10px] mt-0.5">Maximum size 4MB</span>
            </div>

        </form>
    </div>
</div>

@if(auth()->user()->isAdmin())
<!-- PREMIUM CREATE FINISHED GOOD MODAL (ADMIN ONLY) -->
<div id="createModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-white border border-slate-100 rounded-3xl p-6 w-full max-w-lg shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeCreateModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition p-1.5 rounded-full hover:bg-slate-100">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="flex items-center gap-3 mb-4">
            <div class="p-2.5 bg-blue-50 rounded-2xl">
                <i data-lucide="package-plus" class="w-5 h-5 text-blue-650"></i>
            </div>
            <div>
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Manual Creation</h3>
                <p class="text-sm font-black text-slate-800">Add New Finished Good</p>
            </div>
        </div>

        <form id="createForm" method="POST" action="{{ route('finished-goods.store') }}" class="space-y-4 text-xs">
            @csrf

            <!-- Department Selection -->
            <div>
                <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Department <span class="text-rose-500">*</span></label>
                <select name="department_id" id="createDepartmentId" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50" required onchange="handleDepartmentChange()">
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" data-code="{{ strtoupper($dept->code) }}" {{ strtoupper($dept->code) === 'TAD' ? 'selected' : '' }}>
                            {{ $dept->name }} ({{ $dept->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Select Existing Product Container -->
            <div id="fieldGradeSelect">
                <!-- Grade Select (Adhesive) -->
                <div id="containerGradeSelect">
                    <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Adhesive Product Grade <span class="text-rose-500">*</span></label>
                    <select name="grade_id" id="createGradeId" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <option value="">Select Adhesive Product...</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" data-bag-name="{{ $grade->bagSize ? $grade->bagSize->name : '20 KG Bag' }}">
                                {{ $grade->name }} ({{ $grade->code }})@if($grade->brand) [{{ $grade->brand->name }}]@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Color Select (Grout) -->
                <div id="containerColorSelect" class="hidden">
                    <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Grout Product / Color <span class="text-rose-500">*</span></label>
                    <select name="color_id" id="createColorId" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <option value="">Select Grout Color...</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" data-bag-name="{{ $color->packing_size ?: '1 KG Pouch' }}">
                                {{ $color->name }} ({{ $color->code }})@if($color->brand) [{{ $color->brand->name }}]@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Epoxy Product Select (Epoxy) -->
                <div id="containerEpoxySelect" class="hidden">
                    <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Epoxy Product <span class="text-rose-500">*</span></label>
                    <select name="epoxy_product_id" id="createEpoxyProductId" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <option value="">Select Epoxy Product...</option>
                        @foreach($epoxyProducts as $ep)
                            <option value="{{ $ep->id }}">
                                {{ $ep->name }} ({{ $ep->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Optional Coupon Raw Material (Adhesive only) -->
            <div id="containerCouponSelect">
                <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Coupon Raw Material (Optional)</label>
                <select name="coupon_raw_material_id" id="createCouponId" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <option value="">No Coupon Attached</option>
                    @foreach($couponMaterials as $coupon)
                        <option value="{{ $coupon->id }}">{{ $coupon->name }} ({{ $coupon->code }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Packing & Minimum Stock Grid -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Packing Size <span class="text-rose-500">*</span></label>
                    <input type="text" name="packing" id="createPacking" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" placeholder="e.g. 20 KG Bag" required>
                </div>
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Min Stock Alert Limit</label>
                    <input type="number" name="minimum_stock" value="20" min="0" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" required>
                </div>
            </div>

            <!-- Quantity & Weight Grid -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Initial Bags / Units <span class="text-rose-500">*</span></label>
                    <input type="number" name="available_bags" id="createBags" min="0" value="0" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" required>
                </div>
                <div>
                    <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Initial Weight (KG)</label>
                    <input type="number" step="0.0001" name="available_weight" id="createWeight" min="0" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" placeholder="Auto-calculated">
                </div>
            </div>

            <!-- Remarks -->
            <div>
                <label class="block text-slate-500 mb-1.5 uppercase font-bold tracking-wider text-[9px]">Remarks / Log Notes</label>
                <textarea name="remarks" rows="2" class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50" placeholder="Initial creation details (optional)..."></textarea>
            </div>

            <!-- Actions -->
            <div class="pt-2 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="closeCreateModal()" class="erp-button border border-slate-200 text-slate-650 hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit" class="erp-button bg-blue-650 text-white hover:bg-blue-600">
                    Create Finished Good
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
$(function() {
    let debounceTimer;

    function reloadTable(page = 1) {
        const formData = $('#filterForm').serialize() + '&page=' + page;
        $('#tableContainer').addClass('opacity-50 pointer-events-none');
        
        $.get("{{ route('finished-goods.index') }}", formData, function(html) {
            $('#tableContainer').html(html).removeClass('opacity-50 pointer-events-none');
            bindPagination();
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    }

    function bindPagination() {
        $('.pagination-container a').off('click').on('click', function(e) {
            e.preventDefault();
            const url = new URL($(this).attr('href'));
            const page = url.searchParams.get('page') || 1;
            reloadTable(page);
        });
    }

    $('#filterSearch').on('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            reloadTable();
        }, 300);
    });

    $('#filterDept, #filterPacking, #filterStatus').on('change', function() {
        reloadTable();
    });

    $('#createGradeId, #createColorId').on('change', function() {
        const selected = $(this).find('option:selected');
        const bagName = selected.data('bag-name');
        if (bagName && !$('#createPacking').val()) {
            $('#createPacking').val(bagName);
        }
    });

    bindPagination();
    toggleTypeCards('increase'); // initial load state for cards
    handleDepartmentChange();
});

function handleDepartmentChange() {
    const selectedOption = $('#createDepartmentId').find('option:selected');
    const code = (selectedOption.data('code') || '').toString().toUpperCase();

    // Reset selects
    $('#createGradeId, #createColorId, #createEpoxyProductId').val('').prop('required', false);

    if (code === 'GRT') {
        $('#containerGradeSelect, #containerEpoxySelect, #containerCouponSelect').addClass('hidden');
        $('#containerColorSelect').removeClass('hidden');
        $('#createColorId').prop('required', true);
    } else if (code === 'EPX' || code === 'EP') {
        $('#containerGradeSelect, #containerColorSelect, #containerCouponSelect').addClass('hidden');
        $('#containerEpoxySelect').removeClass('hidden');
        $('#createEpoxyProductId').prop('required', true);
    } else { // TAD or default Adhesive
        $('#containerColorSelect, #containerEpoxySelect').addClass('hidden');
        $('#containerGradeSelect, #containerCouponSelect').removeClass('hidden');
        $('#createGradeId').prop('required', true);
    }
}

function openCreateModal() {
    $('#createForm')[0].reset();
    handleDepartmentChange();
    $('#createModal').removeClass('hidden');
}

function closeCreateModal() {
    $('#createModal').addClass('hidden');
}

function toggleTypeCards(type) {
    if (type === 'increase') {
        $('#lblIncrease').addClass('border-blue-600 bg-blue-50/20').removeClass('border-slate-100');
        $('#lblDecrease').removeClass('border-blue-600 bg-blue-50/20').addClass('border-slate-100');
    } else {
        $('#lblDecrease').addClass('border-blue-600 bg-blue-50/20').removeClass('border-slate-100');
        $('#lblIncrease').removeClass('border-blue-600 bg-blue-50/20').addClass('border-slate-100');
    }
}

function openAdjustModal(id, productName, packing, currentQty, currentWeight, type = 'increase') {
    const actionUrl = `{{ url('/finished-goods') }}/${id}/adjust`;
    $('#adjustForm').attr('action', actionUrl);
    
    $('#modalProductName').text(`${productName} (${packing})`);
    $('#modalCurrentQty').text(`${currentQty} Bags`);
    $('#modalCurrentWeight').text(`${currentWeight.toFixed(2)} KG`);
    
    $('#adjustForm').find('input[name="quantity"]').val('');
    $('#adjustForm').find('input[name="weight"]').val('');
    $('#adjustForm').find('input[name="reason"]').val('');
    $('#adjustForm').find('textarea[name="remarks"]').val('');
    
    // Select requested type (increase or decrease)
    const selectedType = (type === 'decrease') ? 'decrease' : 'increase';
    $('#adjustForm').find(`input[value="${selectedType}"]`).prop('checked', true).trigger('change');
    toggleTypeCards(selectedType);
    
    $('#adjustModal').removeClass('hidden').find('.transform').removeClass('scale-95').addClass('scale-100');
}

function closeAdjustModal() {
    $('#adjustModal').addClass('hidden').find('.transform').removeClass('scale-100').addClass('scale-95');
}

function openImportModal() {
    $('#fileLabel').text('Select CSV File');
    $('#importModal').removeClass('hidden');
}

function closeImportModal() {
    $('#importModal').addClass('hidden');
}

function updateFileLabel(input) {
    if (input.files && input.files[0]) {
        $('#fileLabel').text(input.files[0].name);
    } else {
        $('#fileLabel').text('Select CSV File');
    }
}
</script>
@endsection
