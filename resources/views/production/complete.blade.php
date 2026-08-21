@extends('layouts.app')

@section('title', 'Complete Production Batch')
@section('header-title', 'Complete Batch Run')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back link to active batch -->
    <div class="flex items-center justify-between">
        <a href="{{ route('production.show', $batch->id) }}" class="inline-flex items-center text-sm text-slate-450 hover:text-cyan-400 transition-colors gap-2 group">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform"></i>
            <span>Back to Active Batch</span>
        </a>
        <span class="text-xs text-slate-500 font-mono">Batch #{{ $batch->batch_no }}</span>
    </div>

    <!-- Completion Form Card -->
    <div class="bg-slate-955 border border-slate-850 rounded-2xl shadow-xl p-6 md:p-8 space-y-6">
        <div>
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <i data-lucide="check-circle" class="w-6 h-6 text-emerald-500"></i>
                <span>Complete Batch Production Logging</span>
            </h3>
            <p class="text-sm text-slate-450 mt-1">Specify final output bags and end time to complete the run. Stocks will not be affected.</p>
        </div>

        <form id="complete-batch-form" method="POST" action="{{ route('production.complete', $batch->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Form Error Box (Generic) -->
            <div id="generic-error-box" class="hidden p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-450 text-sm flex items-start gap-2.5">
                <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
                <span id="generic-error-message"></span>
            </div>

            <!-- Readonly info summary grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-gray-200 p-4 rounded-xl border border-slate-850 text-sm">
                <div>
                    <span class="text-slate-500 text-xs uppercase block mb-0.5">Grade</span>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-white font-semibold">{{ $batch->grade->name }}</span>
                        @if($batch->grade?->brand)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/15 text-amber-400 border border-amber-500/30">
                                {{ $batch->grade->brand->name }}
                            </span>
                        @endif
                    </div>
                </div>
                <div>
                    <span class="text-slate-500 text-xs uppercase block mb-0.5">Mixer Machine</span>
                    <span class="text-white font-semibold">{{ $batch->machine->name }} ({{ $batch->machine->code }})</span>
                </div>
                <div>
                    <span class="text-slate-500 text-xs uppercase block mb-0.5">Started At</span>
                    <span class="text-white font-mono">{{ $batch->start_time->format('d M Y, h:i:s A') }}</span>
                </div>
            </div>

            <!-- Inputs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Output Bags -->
                <div>
                    <label for="output_bags" class="block text-xs font-semibold text-slate-450 uppercase tracking-wider mb-2">Total Output Bags <span class="text-cyan-500">*</span></label>
                    <input type="number" step="any" id="output_bags" name="output_bags" required min="0.0001" value="{{ old('output_bags') }}"
                        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white font-mono text-sm focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 transition-all"
                        placeholder="e.g. 50">
                    <p class="field-error text-rose-450 text-xs mt-1.5 hidden" data-error-field="output_bags"></p>
                </div>

                <!-- Bag Size (Read Only) -->
                <div>
                    <label for="bag_size_name" class="block text-xs font-semibold text-slate-450 uppercase tracking-wider mb-2">Configured Bag Size</label>
                    <!-- Displayed label -->
                    <input type="text" id="bag_size_name" value="{{ $batch->grade->bagSize->name }} ({{ (float)$batch->grade->bagSize->value }} KG)" readonly
                        class="block w-full px-4 py-2.5 bg-slate-900/60 border border-slate-850 rounded-xl text-slate-400 font-semibold text-sm focus:outline-none cursor-not-allowed select-none">
                    <!-- Hidden input to store bag size value for JS math -->
                    <input type="hidden" id="bag_size_val" value="{{ (float)$batch->grade->bagSize->value }}">
                </div>

                <!-- Calculated Output KG (Auto Calculate) -->
                <div>
                    <label for="output_kg" class="block text-xs font-semibold text-slate-450 uppercase tracking-wider mb-2">Calculated Output KG</label>
                    <input type="text" id="output_kg" readonly placeholder="0.00"
                        class="block w-full px-4 py-2.5 bg-slate-900/60 border border-slate-850 rounded-xl text-cyan-400 font-mono font-bold text-sm focus:outline-none cursor-not-allowed select-none">
                    <p class="text-[10px] text-slate-550 mt-1">Automatically calculated as Output Bags &times; Bag Size.</p>
                </div>

                <!-- End Time (12-Hour AM/PM Picker) -->
                <div>
                    <label for="end_time_picker" class="block text-xs font-semibold text-slate-450 uppercase tracking-wider mb-2">End Time <span class="text-cyan-500">*</span></label>
                    <div class="relative">
                        <input type="text" id="end_time_picker" placeholder="Select end date & time..." required
                            class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white font-mono text-sm focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 transition-all cursor-pointer">
                        <i data-lucide="clock" class="w-4 h-4 text-cyan-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>
                    <input type="hidden" id="end_time" name="end_time" value="{{ now()->timezone('Asia/Kolkata')->format('Y-m-d H:i') }}">
                    <p class="field-error text-rose-450 text-xs mt-1.5 hidden" data-error-field="end_time"></p>
                </div>
            </div>

            <!-- Remarks -->
            <div>
                <label for="remarks" class="block text-xs font-semibold text-slate-450 uppercase tracking-wider mb-2">Production Remarks / Notes</label>
                <textarea id="remarks" name="remarks" rows="3"
                    class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-650 focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 transition-all text-sm"
                    placeholder="e.g. Completed with standard density. Mixer cleaned post run.">{{ old('remarks', $batch->remarks) }}</textarea>
                <p class="field-error text-rose-450 text-xs mt-1.5 hidden" data-error-field="remarks"></p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-slate-850">
                <a href="{{ route('production.show', $batch->id) }}"
                    class="px-4 py-2.5 bg-slate-900 border border-slate-800 hover:bg-slate-850 text-slate-350 rounded-xl text-sm font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit" id="submit-btn"
                    class="px-5 py-2.5 bg-gradient-to-r from-emerald-650 to-teal-650 hover:from-emerald-605 hover:to-teal-605 text-white font-bold rounded-xl transition-all duration-200 shadow-lg shadow-emerald-950/20 text-sm flex items-center gap-2">
                    <span id="btn-icon">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                    </span>
                    <span id="btn-text">Complete Batch</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
        flatpickr("#end_time_picker", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            altInput: true,
            altFormat: "d M Y, h:i K", // 12-Hour AM/PM format
            time_24hr: false, // 12-Hour AM/PM Selector
            defaultDate: new Date(),
            theme: "dark",
            onChange: function(selectedDates, dateStr) {
                $('#end_time').val(dateStr);
            }
        });

        var bagSizeVal = parseFloat($('#bag_size_val').val()) || 0;

        // Auto calculate output KG in real-time
        function calculateKg() {
            var bags = parseFloat($('#output_bags').val());
            if (!isNaN(bags) && bags > 0) {
                var kg = bags * bagSizeVal;
                $('#output_kg').val(kg.toFixed(2));
            } else {
                $('#output_kg').val('0.00');
            }
        }

        // Trigger on load (in case of old inputs) and input events
        calculateKg();
        $('#output_bags').on('input keyup change', calculateKg);

        // Submit form with SweetAlert confirmation and AJAX
        $('#complete-batch-form').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);
            var $submitBtn = $('#submit-btn');
            var $btnIcon = $('#btn-icon');
            var $btnText = $('#btn-text');
            var $genericErrorBox = $('#generic-error-box');

            var bags = parseFloat($('#output_bags').val()) || 0;
            var kg = (bags * bagSizeVal).toFixed(2);

            // Double check values
            if (bags <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Output',
                    text: 'Bags produced must be greater than zero.',
                    confirmButtonColor: '#f43f5e',
                    background: '#090d16',
                    color: '#f1f5f9'
                });
                return;
            }

            // Confirm Completion via SweetAlert
            Swal.fire({
                title: 'Complete Batch Run?',
                html: 'Finalize this batch with an output of <strong class="text-emerald-450 font-bold">' + bags + ' Bags</strong> (' + kg + ' KG)?<br><br><small class="text-slate-500 font-medium">This changes batch status to Completed and is irreversible.</small>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Yes, complete batch',
                cancelButtonText: 'Cancel',
                background: '#090d16',
                color: '#f1f5f9'
            }).then(function(result) {
                if (result.isConfirmed) {
                    // Reset validation errors
                    $('.field-error').addClass('hidden').text('');
                    $genericErrorBox.addClass('hidden');
                    $('#generic-error-message').text('');

                    // Set loading state
                    $submitBtn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
                    $btnIcon.html('<i data-lucide="loader" class="w-4 h-4 animate-spin"></i>');
                    $btnText.text('Completing batch...');
                    lucide.createIcons();

                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        data: $form.serialize(),
                        success: function(response) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: response.message || 'Production batch completed successfully!',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                background: '#090d16',
                                color: '#f1f5f9',
                                iconColor: '#10b981'
                            }).then(function() {
                                window.location.href = response.redirect_url;
                            });
                        },
                        error: function(xhr) {
                            // Restore button state
                            $submitBtn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                            $btnIcon.html('<i data-lucide="check-circle" class="w-4 h-4"></i>');
                            $btnText.text('Complete Batch');
                            lucide.createIcons();

                            if (xhr.status === 422) {
                                var response = xhr.responseJSON;

                                // Show field errors
                                if (response.errors) {
                                    $.each(response.errors, function(field, messages) {
                                        var $errLabel = $('.field-error[data-error-field="' + field + '"]');
                                        if ($errLabel.length) {
                                            $errLabel.text(messages[0]).removeClass('hidden');
                                        }
                                    });
                                }

                                var message = response.message || 'Validation failed. Please correct the fields.';
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: message,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    background: '#090d16',
                                    color: '#f1f5f9',
                                    iconColor: '#f43f5e'
                                });
                            } else {
                                var msg = 'An unexpected server error occurred.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                $genericErrorBox.removeClass('hidden');
                                $('#generic-error-message').text(msg);

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Completion Failed',
                                    text: msg,
                                    confirmButtonColor: '#f43f5e',
                                    background: '#090d16',
                                    color: '#f1f5f9'
                                });
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
