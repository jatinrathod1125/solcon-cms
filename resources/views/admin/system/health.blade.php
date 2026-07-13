@extends('layouts.app')

@section('title', 'System Health & Utilities')
@section('header-title', 'Server Diagnostics')

@section('content')
<div class="space-y-8">
    <!-- Top banner -->
    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 flex items-center gap-2">
                <i data-lucide="heart-pulse" class="w-5 h-5 text-rose-500 animate-pulse"></i>
                <span>Server Diagnostics & Cache Utilities</span>
            </h1>
            <p class="text-sm text-slate-500 mt-0.5">Monitor server configurations, disk spaces, queues buffers, and execute optimization script routines.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Diagnostic Statistics Column (col-span-2) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Health Grid -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-100 pb-3">
                    <i data-lucide="monitor" class="w-4 h-4 text-indigo-650"></i>
                    <span>Environment Diagnostics</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                    <!-- Laravel Version -->
                    <div class="flex justify-between p-3.5 bg-slate-50 border border-slate-200 rounded-xl items-center">
                        <span class="text-slate-500 font-medium">Laravel Framework</span>
                        <span class="font-mono font-bold text-slate-800 bg-white border border-slate-300 px-2 py-0.5 rounded-lg text-xs">{{ $health['laravel_version'] }}</span>
                    </div>

                    <!-- PHP Version -->
                    <div class="flex justify-between p-3.5 bg-slate-50 border border-slate-200 rounded-xl items-center">
                        <span class="text-slate-500 font-medium">PHP Version</span>
                        <span class="font-mono font-bold text-slate-800 bg-white border border-slate-300 px-2 py-0.5 rounded-lg text-xs">{{ $health['php_version'] }}</span>
                    </div>

                    <!-- DB Version -->
                    <div class="flex justify-between p-3.5 bg-slate-50 border border-slate-200 rounded-xl items-center">
                        <span class="text-slate-500 font-medium">Database Server</span>
                        <span class="font-mono font-bold text-slate-800 bg-white border border-slate-300 px-2 py-0.5 rounded-lg text-xs truncate max-w-[200px]" title="{{ $health['db_version'] }}">{{ $health['db_version'] }}</span>
                    </div>

                    <!-- Cache Status -->
                    <div class="flex justify-between p-3.5 bg-slate-50 border border-slate-200 rounded-xl items-center">
                        <span class="text-slate-500 font-medium">Cache Driver Status</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold border {{ $health['cache_status'] === 'Healthy' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100' }}">
                            {{ $health['cache_status'] }}
                        </span>
                    </div>

                    <!-- Queue Status -->
                    <div class="flex justify-between p-3.5 bg-slate-50 border border-slate-200 rounded-xl items-center">
                        <span class="text-slate-500 font-medium">Queue Driver</span>
                        <span class="font-mono font-semibold text-slate-700">{{ $health['queue_status'] }}</span>
                    </div>

                    <!-- Scheduler Status -->
                    <div class="flex justify-between p-3.5 bg-slate-50 border border-slate-200 rounded-xl items-center">
                        <span class="text-slate-500 font-medium">Cron Scheduler</span>
                        <span class="font-mono font-semibold text-slate-700">{{ $health['scheduler_status'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Storage Disk space -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-100 pb-3">
                    <i data-lucide="hard-drive" class="w-4 h-4 text-indigo-650"></i>
                    <span>Storage Allocation</span>
                </h3>

                <div class="space-y-4">
                    <!-- Progress Bar -->
                    <div>
                        <div class="flex justify-between text-xs font-semibold text-slate-500 mb-1.5">
                            <span>Disk Usage Space</span>
                            <span>{{ $health['disk_usage_percent'] }}% Used</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden border border-slate-200">
                            <div class="h-full bg-indigo-650 transition-all" style="width: {{ $health['disk_usage_percent'] }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 text-center text-xs divide-x divide-slate-100 pt-2">
                        <div>
                            <span class="block text-slate-450 uppercase tracking-wider font-semibold">Total Space</span>
                            <span class="block text-base font-bold text-slate-800 font-mono mt-0.5">{{ $health['disk_total'] }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-450 uppercase tracking-wider font-semibold">Used Space</span>
                            <span class="block text-base font-bold text-slate-800 font-mono mt-0.5 text-indigo-650">{{ $health['disk_used'] }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-450 uppercase tracking-wider font-semibold">Free Space</span>
                            <span class="block text-base font-bold text-slate-800 font-mono mt-0.5 text-emerald-650">{{ $health['disk_free'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cache Utilities Column (col-span-1) -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 md:p-6 shadow-sm space-y-5">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-100 pb-3">
                    <i data-lucide="sparkles" class="w-4 h-4 text-indigo-650"></i>
                    <span>Cache Management Utilities</span>
                </h3>

                <!-- utility buttons forms -->
                <div class="space-y-3">
                    <!-- Clear Cache -->
                    <form action="{{ route('admin.system.clear-cache') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="cache">
                        <button type="submit" class="w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold border border-slate-300 rounded-xl text-xs transition-colors flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5 text-slate-500"></i>
                                <span>Clear Application Cache</span>
                            </span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                        </button>
                    </form>

                    <!-- Clear Config -->
                    <form action="{{ route('admin.system.clear-cache') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="config">
                        <button type="submit" class="w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold border border-slate-300 rounded-xl text-xs transition-colors flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i data-lucide="settings" class="w-3.5 h-3.5 text-slate-500"></i>
                                <span>Clear Configuration Cache</span>
                            </span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                        </button>
                    </form>

                    <!-- Clear Route Cache -->
                    <form action="{{ route('admin.system.clear-cache') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="route">
                        <button type="submit" class="w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold border border-slate-300 rounded-xl text-xs transition-colors flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i data-lucide="milestone" class="w-3.5 h-3.5 text-slate-500"></i>
                                <span>Clear Route Cache</span>
                            </span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                        </button>
                    </form>

                    <!-- Clear View Cache -->
                    <form action="{{ route('admin.system.clear-cache') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="view">
                        <button type="submit" class="w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold border border-slate-300 rounded-xl text-xs transition-colors flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i data-lucide="file-code" class="w-3.5 h-3.5 text-slate-500"></i>
                                <span>Clear Views Cache</span>
                            </span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                        </button>
                    </form>

                    <!-- Optimize -->
                    <form action="{{ route('admin.system.clear-cache') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="optimize">
                        <button type="submit" class="w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold border border-slate-300 rounded-xl text-xs transition-colors flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i data-lucide="zap" class="w-3.5 h-3.5 text-slate-500"></i>
                                <span>Optimize Application</span>
                            </span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                        </button>
                    </form>

                    <!-- Optimize Clear -->
                    <form action="{{ route('admin.system.clear-cache') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="optimize_clear">
                        <button type="submit" class="w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold border border-slate-300 rounded-xl text-xs transition-colors flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-slate-500"></i>
                                <span>Optimize Clear (Reset All)</span>
                            </span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Form submits Swal loader triggers
        $('form').on('submit', function() {
            let button = $(this).find('button');
            button.prop('disabled', true).addClass('opacity-70');
            
            Swal.fire({
                title: 'Executing Artisan Commands...',
                html: 'Clearing compiled files and resetting caches indices. Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    });
</script>
@endsection
