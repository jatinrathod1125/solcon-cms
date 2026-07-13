@extends('layouts.app')

@section('title', 'Factory Settings')
@section('header-title', 'Factory Settings & Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Top Header Banner -->
    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 flex items-center gap-2">
                <i data-lucide="sliders" class="w-5 h-5 text-indigo-650"></i>
                <span>Solcon Factory Settings Console</span>
            </h1>
            <p class="text-sm text-slate-500 mt-0.5">Configure global factory profile details, company branding, and reporting preferences.</p>
        </div>
    </div>

    <!-- Main Settings Form -->
    <form action="{{ route('admin.settings.factory.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm" class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        @csrf

        <!-- Factory Profile Section -->
        <div class="p-6 md:p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-sm font-bold text-slate-900">Identity Details</h3>
                    <p class="text-xs text-slate-550 mt-1">Configure company name, address branding, and official logos for exports.</p>
                </div>
                <div class="md:col-span-2 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="company_name" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Company Name</label>
                            <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $settings['company_name'] ?? 'Solcon Industries') }}" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label for="gst_number" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">GST Number</label>
                            <input type="text" name="gst_number" id="gst_number" value="{{ old('gst_number', $settings['gst_number'] ?? '') }}" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="company_address" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Address</label>
                        <input type="text" name="company_address" id="company_address" value="{{ old('company_address', $settings['company_address'] ?? '') }}" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="company_phone" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Phone</label>
                            <input type="text" name="company_phone" id="company_phone" value="{{ old('company_phone', $settings['company_phone'] ?? '') }}" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label for="company_email" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Email</label>
                            <input type="email" name="company_email" id="company_email" value="{{ old('company_email', $settings['company_email'] ?? '') }}" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <!-- Logo Picker -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Company Logo</label>
                        <div class="flex items-center gap-4">
                            @if($settings['company_logo'] ?? null)
                                <div class="w-16 h-16 rounded-xl border border-slate-200 overflow-hidden bg-slate-50 flex items-center justify-center shrink-0">
                                    <img src="{{ asset($settings['company_logo']) }}" class="max-w-full max-h-full object-contain">
                                </div>
                            @else
                                <div class="w-16 h-16 rounded-xl border border-dashed border-slate-300 flex items-center justify-center text-slate-400 shrink-0">
                                    <i data-lucide="image" class="w-6 h-6"></i>
                                </div>
                            @endif
                            <div>
                                <input type="file" name="company_logo" id="company_logo" accept="image/*" class="hidden">
                                <label for="company_logo" class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-lg text-xs font-bold text-slate-700 cursor-pointer gap-1">
                                    <i data-lucide="upload-cloud" class="w-3.5 h-3.5"></i>
                                    <span>Upload Logo</span>
                                </label>
                                <span class="block text-[10px] text-slate-400 mt-1">Accepts PNG, JPG or SVG. Maximum 2MB.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-sm font-bold text-slate-900">Locales & PDF Configs</h3>
                    <p class="text-xs text-slate-550 mt-1">Configure default currencies, default reporting headers, and footers.</p>
                </div>
                <div class="md:col-span-2 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="default_timezone" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Default Timezone</label>
                            <select id="default_timezone" name="default_timezone" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                                @foreach($timezones as $tzKey => $tzLabel)
                                    <option value="{{ $tzKey }}" {{ ($settings['default_timezone'] ?? 'UTC') === $tzKey ? 'selected' : '' }}>{{ $tzLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="default_currency" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Currency</label>
                            <input type="text" name="default_currency" id="default_currency" value="{{ old('default_currency', $settings['default_currency'] ?? 'INR') }}" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm" placeholder="e.g. USD, INR">
                        </div>
                        <div>
                            <label for="default_language" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Language</label>
                            <input type="text" name="default_language" id="default_language" value="{{ old('default_language', $settings['default_language'] ?? 'en') }}" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="report_header" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Report Header Text</label>
                            <input type="text" name="report_header" id="report_header" value="{{ old('report_header', $settings['report_header'] ?? '') }}" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label for="report_footer" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Report Footer Text</label>
                            <input type="text" name="report_footer" id="report_footer" value="{{ old('report_footer', $settings['report_footer'] ?? '') }}" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="default_bag_size" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Default Output Bag Size (KG)</label>
                        <select id="default_bag_size" name="default_bag_size" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                            @foreach($bagSizes as $bs)
                                <option value="{{ $bs->value }}" {{ ($settings['default_bag_size'] ?? '20') == $bs->value ? 'selected' : '' }}>
                                    {{ $bs->name }} ({{ number_format($bs->value, 0) }} KG)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end items-center gap-2">
            <button type="submit" id="saveBtn" class="inline-flex items-center px-4 py-2 bg-indigo-650 hover:bg-indigo-750 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-indigo-600/10 gap-1">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>Save Configuration</span>
            </button>
        </div>
    </form>
</div>
@endsection
