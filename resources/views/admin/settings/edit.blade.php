@extends('layouts.app')

@section('title', 'Factory Settings')
@section('header-title', 'Global Factory Configuration')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white border border-slate-200 p-6 rounded-2xl shadow-sm">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 flex items-center gap-2">
                <i data-lucide="settings" class="w-5 h-5 text-indigo-650"></i>
                <span>Factory Profile & Reports Settings</span>
            </h1>
            <p class="text-sm text-slate-500 mt-1">Manage global parameters, reporting headers, footer details, default sizing, and timezone settings.</p>
        </div>
    </div>

    <!-- Form Container -->
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm" class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        @csrf
        
        <div class="p-6 md:p-8 space-y-6">
            <!-- Profile Section Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Title column -->
                <div class="md:col-span-1">
                    <h3 class="text-sm font-bold text-slate-900">General Identity</h3>
                    <p class="text-xs text-slate-500 mt-1">Solcon factory naming brand and public logo details shown across summary reports.</p>
                </div>
                
                <!-- Inputs column -->
                <div class="md:col-span-2 space-y-4">
                    <!-- Factory Name -->
                    <div>
                        <label for="factory_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Factory Name</label>
                        <input type="text" name="factory_name" id="factory_name" value="{{ old('factory_name', $settings['factory_name']) }}" 
                            class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm @error('factory_name') border-rose-500 @enderror">
                        @error('factory_name')
                            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Factory Logo File Picker -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Factory Logo</label>
                        <div class="flex items-center gap-4">
                            @if($settings['factory_logo'])
                                <div class="w-16 h-16 rounded-xl border border-slate-200 overflow-hidden shrink-0 bg-slate-50 flex items-center justify-center">
                                    <img src="{{ asset($settings['factory_logo']) }}" class="max-w-full max-h-full object-contain" alt="Logo">
                                </div>
                            @else
                                <div class="w-16 h-16 rounded-xl border border-dashed border-slate-300 shrink-0 bg-slate-50 flex items-center justify-center text-slate-400">
                                    <i data-lucide="image" class="w-6 h-6"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="factory_logo" id="factory_logo" accept="image/*" class="hidden">
                                <label for="factory_logo" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 font-semibold rounded-xl text-xs transition-colors cursor-pointer gap-1.5">
                                    <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                                    <span>Upload New Image</span>
                                </label>
                                <span class="block text-[10px] text-slate-500 mt-1.5">PNG, JPG, or SVG. Maximum file size 2MB.</span>
                            </div>
                        </div>
                        @error('factory_logo')
                            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="border-slate-200">

            <!-- PDF Reports Layout Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Title column -->
                <div class="md:col-span-1">
                    <h3 class="text-sm font-bold text-slate-900">PDF Report Formatting</h3>
                    <p class="text-xs text-slate-500 mt-1">Configure layout headers and footer footnotes when generating summaries or printing records.</p>
                </div>
                
                <!-- Inputs column -->
                <div class="md:col-span-2 space-y-4">
                    <!-- Report Header -->
                    <div>
                        <label for="report_header" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Default PDF Report Title</label>
                        <input type="text" name="report_header" id="report_header" value="{{ old('report_header', $settings['report_header']) }}"
                            class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm @error('report_header') border-rose-500 @enderror">
                        @error('report_header')
                            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Footer Text -->
                    <div>
                        <label for="footer_text" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Report Footer Copyright / Notes</label>
                        <input type="text" name="footer_text" id="footer_text" value="{{ old('footer_text', $settings['footer_text']) }}"
                            class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm @error('footer_text') border-rose-500 @enderror">
                        @error('footer_text')
                            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="border-slate-200">

            <!-- Defaults & Locale Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Title column -->
                <div class="md:col-span-1">
                    <h3 class="text-sm font-bold text-slate-900">Defaults & Localization</h3>
                    <p class="text-xs text-slate-500 mt-1">Configure default bag sizing metrics and localized timezone offsets for database operations.</p>
                </div>
                
                <!-- Inputs column -->
                <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Default Bag Size -->
                    <div>
                        <label for="default_bag_size" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Default Output Bag Size (KG)</label>
                        <select id="default_bag_size" name="default_bag_size" 
                            class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm">
                            @foreach($bagSizes as $bs)
                                <option value="{{ (float) $bs->value }}" {{ $settings['default_bag_size'] == $bs->value ? 'selected' : '' }}>
                                    {{ $bs->name }} ({{ number_format($bs->value, 0) }} KG)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label for="timezone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Factory Timezone</label>
                        <select id="timezone" name="timezone" 
                            class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm">
                            @foreach($timezones as $tzKey => $tzLabel)
                                <option value="{{ $tzKey }}" {{ $settings['timezone'] === $tzKey ? 'selected' : '' }}>
                                    {{ $tzLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end items-center gap-2">
            <button type="submit" id="saveBtn" class="inline-flex items-center px-4 py-2.5 bg-indigo-650 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition-all shadow-md shadow-indigo-600/10 gap-1.5">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Save Settings</span>
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Factory logo preview name change helper
        $('#factory_logo').on('change', function() {
            let filename = $(this).val().split('\\').pop();
            if(filename) {
                $(this).next().find('span').text(filename);
            }
        });

        // Add form loading states & SweetAlert confirmation
        $('#settingsForm').on('submit', function() {
            let btn = $('#saveBtn');
            btn.prop('disabled', true).addClass('opacity-70');
            btn.find('span').text('Saving Configurations...');
            btn.prepend('<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>');
        });
    });
</script>
@endsection
