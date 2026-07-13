@extends('layouts.app')

@section('title', 'Start Grout Batch')
@section('header-title', 'Start Grout Production')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Back Link -->
    <div>
        <a href="{{ route('grout-production.index') }}" class="inline-flex items-center text-sm text-slate-400 hover:text-white transition-colors gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Production Floor</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-6 md:p-8">
        <h3 class="text-lg font-bold text-white mb-6">Initialize Batch Details</h3>

        <!-- Form -->
        <form method="POST" action="{{ route('grout-production.store') }}" class="space-y-6" id="start-batch-form">
            @csrf

            <!-- Machine Selection (Visual Cards) -->
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Select Grout Mixer/Packer</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($machines as $machine)
                        @php
                            $isM01 = $machine->code === 'M-01';
                            $running = $machine->status === 'running';
                        @endphp
                        <label class="relative flex flex-col p-4 rounded-xl border {{ $running ? 'border-red-900 bg-red-950/10 opacity-60 cursor-not-allowed' : 'border-slate-800 bg-slate-900/50 hover:border-cyan-500/50 cursor-pointer' }} transition-all select-none">
                            <input type="radio" name="machine_id" value="{{ $machine->id }}" {{ $running ? 'disabled' : '' }} required
                                class="sr-only machine-radio" data-code="{{ $machine->code }}">
                            
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-bold text-white uppercase">{{ $machine->code }}</span>
                                @if($running)
                                    <span class="px-1.5 py-0.5 rounded bg-red-950 text-rose-450 border border-red-900 text-[9px] font-bold">RUNNING</span>
                                @else
                                    <span class="px-1.5 py-0.5 rounded bg-slate-950 text-slate-500 border border-slate-850 text-[9px] font-bold">READY</span>
                                @endif
                            </div>
                            
                            <div class="mt-3">
                                <p class="text-xs font-bold text-slate-300">{{ $machine->name }}</p>
                                <p class="text-[10px] text-slate-500 mt-1 leading-normal">
                                    {{ $isM01 ? 'Restricted to White & Ivory. Dynamic packing.' : 'Manual mixer with 1-hour dry mix timers.' }}
                                </p>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('machine_id')
                    <p class="text-rose-455 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Color Assignment -->
                <div>
                    <label for="color_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Grout Color</label>
                    <select id="color_id" name="color_id" required
                        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm">
                        <option value="">Select Color</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" data-name="{{ $color->name }}" data-code="{{ $color->code }}">
                                {{ $color->name }} ({{ $color->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('color_id')
                        <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Batch Number (Editable / Auto-generated) -->
                <div>
                    <label for="batch_no" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                        Batch ID <span class="text-slate-500 font-normal lowercase">(auto or custom)</span>
                    </label>
                    <input type="text" id="batch_no" name="batch_no" value="{{ old('batch_no', $batchNo) }}" placeholder="{{ $batchNo }}"
                        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white font-mono font-bold text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all">
                    @error('batch_no')
                        <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Remarks -->
            <div>
                <label for="remarks" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Remarks / Notes</label>
                <input type="text" id="remarks" name="remarks" value="{{ old('remarks') }}"
                    class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                    placeholder="e.g. Mixing under standard humidity conditions.">
                @error('remarks')
                    <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Validation/M-01 notice banner -->
            <div id="m01-restriction-alert" class="hidden bg-amber-500/10 border border-amber-500/25 rounded-xl p-4 flex items-start gap-3 text-amber-400 text-xs">
                <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                <div>
                    <strong class="font-semibold">M-01 Automatic Packer restrictions:</strong>
                    <p class="mt-1 leading-normal">Automatic packer is strictly limited to White and Ivory grout colors. Selecting any other color will fail start validation.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-850">
                <a href="{{ route('grout-production.index') }}" 
                    class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 rounded-xl text-sm font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                    class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 shadow-lg shadow-cyan-500/10 text-sm">
                    Start Batch
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle visual card selections
        $('.machine-radio').change(function() {
            var selectedRadio = $(this);
            // Highlight selected card and unhighlight others
            selectedRadio.closest('.grid').find('label').removeClass('border-cyan-500 ring-2 ring-cyan-500/30 bg-cyan-950/10');
            selectedRadio.closest('label').addClass('border-cyan-500 ring-2 ring-cyan-500/30 bg-cyan-950/10');

            // Trigger restrictions display if M-01 selected
            var code = selectedRadio.attr('data-code');
            if (code === 'M-01') {
                $('#m01-restriction-alert').slideDown(200);
            } else {
                $('#m01-restriction-alert').slideUp(200);
            }
        });

        // Client-side quick validation for M-01 color restrictions
        $('#start-batch-form').submit(function(e) {
            var selectedMachine = $('input[name="machine_id"]:checked').attr('data-code');
            var selectedColorOption = $('#color_id option:selected');
            var colorCode = selectedColorOption.attr('data-code');
            var colorName = selectedColorOption.attr('data-name');

            if (selectedMachine === 'M-01' && colorCode && colorName) {
                var codeUpper = colorCode.toUpperCase();
                var nameUpper = colorName.toUpperCase();
                
                var isWhiteOrIvory = codeUpper.includes('WHT') || codeUpper.includes('IVO') || nameUpper.includes('WHITE') || nameUpper.includes('IVORY');
                
                if (!isWhiteOrIvory) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Invalid Selection',
                        text: 'Machine M-01 is limited to White & Ivory colors only.',
                        icon: 'error',
                        confirmButtonColor: '#f43f5e',
                        background: '#090d16',
                        color: '#f1f5f9'
                    });
                }
            }
        });
    });
</script>
@endsection
