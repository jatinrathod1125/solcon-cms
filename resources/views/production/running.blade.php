@extends('layouts.app')

@section('title', 'Batch Run Details')
@section('header-title', 'Active Production Batch')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- ISO Start Time for JS Timer -->
    <div id="start-time-iso" class="hidden">{{ $batch->start_time->toIso8601String() }}</div>

    <!-- Back to Dashboard -->
    <div class="flex items-center justify-between">
        <a href="{{ route('production.index') }}"
            class="inline-flex items-center text-sm text-slate-450 hover:text-cyan-400 transition-colors gap-2 group">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform"></i>
            <span>Back to Dashboard</span>
        </a>
        <span class="text-xs text-slate-500 font-mono">Batch #{{ $batch->batch_no }}</span>
    </div>

    <!-- Live Status & Timer Card -->
    <div
        class="bg-gradient-to-br from-slate-950 via-slate-950 to-slate-900 border border-cyan-500/20 rounded-2xl shadow-xl shadow-cyan-500/5 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-3 text-center md:text-left">
            @if($batch->status === 'paused')
            <span
                class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-amber-500/10 text-amber-400 border border-amber-500/25 tracking-wide uppercase">
                <span class="w-2 h-2 rounded-full bg-amber-400 mr-2"></span>
                PAUSED / ON HOLD
            </span>
            @else
            <span
                class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 animate-pulse tracking-wide uppercase">
                <span class="w-2 h-2 rounded-full bg-cyan-400 mr-2"></span>
                RUNNING
            </span>
            @endif
            <h2 class="text-2xl font-extrabold text-white tracking-tight">{{ $batch->grade->name }}</h2>
            <p class="text-sm text-slate-400">Processed on Mixer Machine: <span class="text-slate-200 font-semibold">{{
                    $batch->machine->name }}</span> ({{ $batch->machine->code }})</p>
        </div>

        <!-- Live Digital Up-Timer -->
        @php
        $isPaused = $batch->status === 'paused';
        $elapsedSeconds = $isPaused ? $batch->start_time->diffInSeconds($batch->updated_at) :
        $batch->start_time->diffInSeconds(now());
        @endphp
        <div id="batch-timer-container" data-status="{{ $batch->status }}" data-elapsed="{{ $elapsedSeconds }}"
            class="bg-slate-900/60 border border-slate-800 rounded-2xl p-5 flex flex-col items-center justify-center min-w-[200px] shadow-inner">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                {{ $isPaused ? 'Paused Run Time' : 'Elapsed Batch Run Time' }}
            </span>
            <div class="flex items-center space-x-2 text-white font-mono text-3xl font-bold tracking-tight">
                <div class="flex flex-col items-center">
                    <span id="timer-hours">00</span>
                    <span
                        class="text-[10px] text-slate-500 font-sans font-semibold uppercase tracking-wider mt-1">HR</span>
                </div>
                <span class="text-slate-650 -mt-5">:</span>
                <div class="flex flex-col items-center">
                    <span id="timer-minutes">00</span>
                    <span
                        class="text-[10px] text-slate-500 font-sans font-semibold uppercase tracking-wider mt-1">MIN</span>
                </div>
                <span class="text-slate-650 -mt-5">:</span>
                <div class="flex flex-col items-center">
                    <span id="timer-seconds">00</span>
                    <span
                        class="text-[10px] text-slate-500 font-sans font-semibold uppercase tracking-wider mt-1">SEC</span>
                </div>
            </div>
        </div>
    </div>

    @if($batch->start_time->lt(now()->startOfDay()))
    <div
        class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs flex items-start gap-2.5">
        <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-500 shrink-0 mt-0.5 animate-pulse"></i>
        <div>
            <p class="font-bold uppercase tracking-wider text-amber-500">Overnight / Leftover Batch Note</p>
            <p class="mt-0.5 font-semibold text-slate-350 leading-relaxed">
                This batch was started on a previous shift/day ({{ $batch->start_time->format('d M Y') }}).
                When completing, please set the **End Time** to the actual historical date/time the production finished.
                This ensures raw material deductions and finished goods are correctly accounted for in that day's
                reports.
            </p>
        </div>
    </div>
    @endif

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Log & Run Metadata -->
        <div class="bg-slate-955 border border-slate-850 rounded-2xl p-6 shadow-md space-y-4">
            <h3 class="text-xs font-semibold text-slate-450 uppercase tracking-wider flex items-center gap-1.5">
                <i data-lucide="clipboard-list" class="w-4 h-4 text-cyan-500"></i>
                <span>Production Run Logs</span>
            </h3>

            <div class="divide-y divide-slate-850/50 text-sm">
                <div class="py-2.5 flex justify-between">
                    <span class="text-slate-500">Supervisor</span>
                    <span class="text-white font-medium">{{ $batch->supervisor->name }}</span>
                </div>
                <div class="py-2.5 flex justify-between">
                    <span class="text-slate-500">Start Time</span>
                    <span class="text-white font-mono">{{ $batch->start_time->format('d M Y, h:i:s A') }}</span>
                </div>
                <div class="py-2.5 flex justify-between">
                    <span class="text-slate-500">Department</span>
                    <span class="text-white capitalize">{{ $batch->machine->department->name ?? 'N/A' }}</span>
                </div>
                <div class="py-2.5 flex justify-between">
                    <span class="text-slate-500">Batch Number</span>
                    <span class="text-cyan-400 font-mono font-semibold">{{ $batch->batch_no }}</span>
                </div>
            </div>

            @if($batch->remarks)
            <div class="pt-3 border-t border-slate-850 text-sm">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1.5">Remarks /
                    Initialization Notes</span>
                <p class="text-slate-350 bg-slate-900/40 border border-slate-800 p-3 rounded-xl font-mono text-xs">{{
                    $batch->remarks }}</p>
            </div>
            @endif
        </div>

        <!-- Disabled Output Section -->
        <div
            class="bg-slate-955 border border-slate-850 rounded-2xl p-6 shadow-md flex flex-col justify-between space-y-5">
            <div class="space-y-4">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="package-check" class="w-4 h-4 text-slate-650"></i>
                    <span>Output Quantities (Disabled)</span>
                </h3>

                <div class="space-y-4 opacity-50 select-none pointer-events-none">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Output Bags</label>
                            <input type="text" disabled placeholder="e.g. 50"
                                class="block w-full px-3.5 py-2 bg-slate-900 border border-slate-800 rounded-xl text-slate-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Bag Size</label>
                            <input type="text" disabled value="{{ $batch->grade->bagSize->name }}"
                                class="block w-full px-3.5 py-2 bg-slate-900/60 border border-slate-850 rounded-xl text-slate-550 text-sm font-semibold">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Calculated Output
                            KG</label>
                        <input type="text" disabled placeholder="-"
                            class="block w-full px-3.5 py-2 bg-slate-900 border border-slate-800 rounded-xl text-slate-500 text-sm font-mono font-bold">
                    </div>
                </div>
            </div>

            <!-- Active Trigger Button to Complete Batch Screen -->
            <div class="pt-4 border-t border-slate-850/50 space-y-3">
                @if($batch->status === 'paused')
                <!-- Resume Button -->
                <form method="POST" action="{{ route('production.resume', $batch->id) }}">
                    @csrf
                    <button type="submit"
                        class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-650 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl text-sm transition-all flex items-center justify-center gap-1.5 shadow-lg shadow-blue-950/20 active:scale-[0.99] cursor-pointer">
                        <i data-lucide="play" class="w-4 h-4"></i>
                        <span>Resume Batch Run</span>
                    </button>
                </form>
                @else
                <!-- Pause Button -->
                <form method="POST" action="{{ route('production.pause', $batch->id) }}">
                    @csrf
                    <button type="submit"
                        class="w-full py-3 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white font-bold rounded-xl text-sm transition-all flex items-center justify-center gap-1.5 shadow-lg shadow-amber-950/20 active:scale-[0.99] cursor-pointer">
                        <i data-lucide="pause" class="w-4 h-4"></i>
                        <span>Pause / Hold Batch</span>
                    </button>
                </form>
                @endif

                <!-- Complete Button (Disabled if paused) -->
                @if($batch->status === 'paused')
                <button type="button" disabled
                    class="w-full py-3 bg-slate-800 text-slate-500 font-bold rounded-xl text-sm flex items-center justify-center gap-1.5 cursor-not-allowed select-none opacity-50">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    <span>Complete Batch (Resume first)</span>
                </button>
                @else
                <a href="{{ route('production.complete.form', $batch->id) }}"
                    class="w-full py-3 bg-gradient-to-r from-emerald-600 to-teal-650 hover:from-emerald-500 hover:to-teal-500 text-white font-bold rounded-xl text-sm transition-all flex items-center justify-center gap-1.5 shadow-lg shadow-emerald-950/20 active:scale-[0.99]">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    <span>Complete Batch Run</span>
                </a>
                @endif

                <!-- Cancel / Discard Button -->
                <button type="button" onclick="confirmCancelBatch()"
                    class="w-full py-2.5 border border-rose-500/30 hover:bg-rose-500/10 text-rose-450 hover:text-rose-400 font-bold rounded-xl text-sm transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    <span>Cancel / Discard Batch</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Coupon override packaging panel -->
    @php
        $currentCouponItem = null;
        if (!empty($batch->formula_snapshot)) {
            foreach ($batch->formula_snapshot as $item) {
                $rawMat = \App\Models\RawMaterial::where('code', $item['raw_material_code'])->first();
                if ($rawMat && $rawMat->is_coupon) {
                    $currentCouponItem = $item;
                    break;
                }
            }
        }
    @endphp
    
    <div class="bg-slate-955 border border-slate-850 rounded-2xl p-6 shadow-md space-y-4">
        <div class="flex items-center justify-between border-b border-slate-850 pb-3">
            <h3 class="text-xs font-semibold text-slate-450 uppercase tracking-wider flex items-center gap-1.5">
                <i data-lucide="ticket" class="w-4 h-4 text-cyan-500"></i>
                <span>Promo Coupon Packaging</span>
            </h3>
            @if($currentCouponItem)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-extrabold bg-cyan-550/10 text-cyan-400 border border-cyan-500/20">
                    Active: {{ $currentCouponItem['raw_material_name'] }}
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-900 text-slate-450 border border-slate-800">
                    No Coupon Packed
                </span>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row items-end gap-4">
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Change/Select Coupon</label>
                <select id="update_coupon_id" class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-sm">
                    <option value="">— No Coupon / Remove Coupon —</option>
                    @foreach($coupons as $coupon)
                        <option value="{{ $coupon->id }}" {{ ($currentCouponItem && $currentCouponItem['raw_material_id'] == $coupon->id) ? 'selected' : '' }}>
                            {{ $coupon->name }} (Code: {{ $coupon->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="button" onclick="updateBatchCoupon()" class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-650 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl text-sm transition-all flex items-center justify-center gap-1.5 shadow-lg shadow-blue-950/20 active:scale-[0.99] cursor-pointer min-h-[42px]">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Update Coupon</span>
            </button>
        </div>
        <p class="text-[11px] text-slate-450 leading-relaxed">Selecting a coupon here will dynamically modify this batch's packaging formula recipe in real-time. The coupon stock will be deducted when completing this batch.</p>
    </div>

    <!-- Active Formula preview (Snapshot) -->
    <div class="bg-slate-955 border border-slate-850 rounded-2xl overflow-hidden shadow-md">
        <div class="border-b border-slate-850 px-5 py-4 bg-slate-900/30 flex items-center justify-between">
            <h3 class="text-xs font-semibold text-slate-405 uppercase tracking-wider flex items-center gap-1.5">
                <i data-lucide="sheet" class="w-4 h-4 text-cyan-550"></i>
                <span>Formula Snapshot Preview (Version {{ $batch->formula->version ?? 'N/A' }})</span>
            </h3>
            <span
                class="px-2 py-0.5 rounded bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 text-[10px] font-mono font-bold">
                READ ONLY
            </span>
        </div>

        <div class="p-5">
            <div class="border border-slate-850 rounded-xl overflow-hidden">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-850 bg-slate-900/60 text-slate-400 font-semibold">
                            <th class="p-3 w-16 text-center">Seq</th>
                            <th class="p-3 w-32">Material Code</th>
                            <th class="p-3">Material Name</th>
                            <th class="p-3 text-right">Recipe Quantity</th>
                            <th class="p-3 w-28">Unit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-850/50 text-slate-200">
                        @if(!empty($batch->formula_snapshot))
                        @foreach($batch->formula_snapshot as $index => $item)
                        <tr class="hover:bg-slate-900/30 transition-colors">
                            <td class="p-3 text-center font-mono text-slate-500">{{ $index + 1 }}</td>
                            <td class="p-3 font-mono font-semibold text-cyan-400">{{ $item['raw_material_code'] }}</td>
                            <td class="p-3 font-semibold text-white">{{ $item['raw_material_name'] }}</td>
                            <td class="p-3 text-right font-mono font-bold text-white">{{
                                number_format($item['quantity'], 4) }}</td>
                            <td class="p-3 font-semibold text-indigo-300">{{ $item['unit_code'] }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-550">
                                No formula items snapshotted for this batch.
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Digital Timer calculation function
        function updateTimer() {
            var timerContainer = $('#batch-timer-container');
            var status = timerContainer.data('status');
            var elapsed = parseInt(timerContainer.data('elapsed') || 0);

            var diffSecs = 0;
            if (status === 'paused') {
                diffSecs = elapsed;
            } else {
                var startText = $('#start-time-iso').text();
                if (!startText) return;
                var startTime = new Date(startText);
                var now = new Date();
                var diffMs = now - startTime;
                if (diffMs < 0) diffMs = 0;
                diffSecs = Math.floor(diffMs / 1000);
            }

            var hours = Math.floor(diffSecs / 3600);
            var minutes = Math.floor((diffSecs % 3600) / 60);
            var seconds = diffSecs % 60;

            $('#timer-hours').text(String(hours).padStart(2, '0'));
            $('#timer-minutes').text(String(minutes).padStart(2, '0'));
            $('#timer-seconds').text(String(seconds).padStart(2, '0'));
        }

        // Trigger updates every 1 second
        var status = $('#batch-timer-container').data('status');
        if (status !== 'paused') {
            setInterval(updateTimer, 1000);
        }
        updateTimer();

        window.confirmCancelBatch = function() {
            Swal.fire({
                title: 'Cancel & Discard Batch?',
                text: 'This will completely discard this production batch and free up the mixer machine. Raw material stocks will NOT be deducted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, discard it',
                background: '#0f172a',
                color: '#ffffff',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('production.cancel', $batch->id) }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            remarks: 'Aborted by supervisor'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Cancelled',
                                text: response.message,
                                icon: 'success',
                                background: '#0f172a',
                                color: '#ffffff',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = response.redirect_url;
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error',
                                text: xhr.responseJSON ? xhr.responseJSON.message : 'Something went wrong.',
                                icon: 'error',
                                background: '#0f172a',
                                color: '#ffffff',
                            });
                        }
                    });
                }
            });
        };

        window.updateBatchCoupon = function() {
            var couponId = $('#update_coupon_id').val();
            
            Swal.fire({
                title: 'Update Batch Coupon?',
                text: 'Are you sure you want to change or remove the promotional coupon for this active batch?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, update it',
                background: '#0f172a',
                color: '#ffffff',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('production.update_coupon', $batch->id) }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            coupon_raw_material_id: couponId
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Updated',
                                text: response.message,
                                icon: 'success',
                                background: '#0f172a',
                                color: '#ffffff',
                                timer: 1200,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error',
                                text: xhr.responseJSON ? xhr.responseJSON.message : 'Something went wrong.',
                                icon: 'error',
                                background: '#0f172a',
                                color: '#ffffff',
                            });
                        }
                    });
                }
            });
        };
    });
</script>
@endsection
