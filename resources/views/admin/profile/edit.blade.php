@extends('layouts.app')

@section('title', 'My Profile')
@section('header-title', 'User Account Settings')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Top banner -->
    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 flex items-center gap-2">
                <i data-lucide="user" class="w-5 h-5 text-indigo-650"></i>
                <span>Profile Management</span>
            </h1>
            <p class="text-sm text-slate-500 mt-0.5">Manage your personal credentials, contact profiles, avatars, and review access login logs.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contact & Avatar Columns (col-span-2) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details Form -->
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                @csrf
                <div class="p-6 md:p-8 space-y-6">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-1.5">
                        <i data-lucide="contact" class="w-4 h-4 text-indigo-650"></i>
                        <span>Contact Information</span>
                    </h3>

                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <!-- Avatar Display & Picker -->
                        <div class="shrink-0 flex flex-col items-center">
                            @if($user->profile_photo)
                                <img src="{{ asset($user->profile_photo) }}" class="w-24 h-24 rounded-full object-cover border-2 border-slate-200 shadow-md">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-cyan-500 to-indigo-600 flex items-center justify-center text-white font-bold text-3xl shadow-md">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                            <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden">
                            <label for="avatar" class="mt-3 inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-lg text-xs font-semibold text-slate-700 cursor-pointer gap-1 transition-colors">
                                <i data-lucide="camera" class="w-3.5 h-3.5"></i>
                                <span>Change Photo</span>
                            </label>
                        </div>

                        <!-- Name & Email Inputs -->
                        <div class="flex-1 w-full space-y-4">
                            <div>
                                <label for="name" class="block text-xs font-semibold text-slate-650 uppercase tracking-wider mb-1.5">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="block w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                                @error('name')
                                    <span class="text-xs text-rose-550 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-semibold text-slate-650 uppercase tracking-wider mb-1.5">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="block w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                                @error('email')
                                    <span class="text-xs text-rose-550 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-650 hover:bg-indigo-755 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-indigo-600/10 gap-1.5">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Save Profile</span>
                    </button>
                </div>
            </form>

            <!-- Password Form -->
            <form action="{{ route('admin.profile.password') }}" method="POST" class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                @csrf
                <div class="p-6 md:p-8 space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-1.5">
                        <i data-lucide="key-round" class="w-4 h-4 text-indigo-650"></i>
                        <span>Change Password</span>
                    </h3>

                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-slate-650 uppercase tracking-wider mb-1.5">Current Password</label>
                        <input type="password" name="current_password" id="current_password" class="block w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                        @error('current_password')
                            <span class="text-xs text-rose-550 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="new_password" class="block text-xs font-semibold text-slate-650 uppercase tracking-wider mb-1.5">New Password</label>
                            <input type="password" name="new_password" id="new_password" class="block w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                            @error('new_password')
                                <span class="text-xs text-rose-550 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="new_password_confirmation" class="block text-xs font-semibold text-slate-650 uppercase tracking-wider mb-1.5">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="block w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-650 hover:bg-indigo-755 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-indigo-600/10 gap-1.5">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                        <span>Update Password</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Login History Sidebar Column (col-span-1) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Accessible Departments Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 md:p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-100 pb-3">
                    <i data-lucide="building" class="w-4 h-4 text-indigo-650"></i>
                    <span>Accessible Departments</span>
                </h3>
                <div class="space-y-2">
                    @forelse(availableDepartments() as $dept)
                        <div class="flex items-center gap-2.5 p-2 bg-slate-50 border border-slate-200/50 rounded-xl">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-[10px] font-black text-indigo-600 border border-indigo-100 uppercase">{{ $dept->code }}</span>
                            <span class="text-xs font-bold text-slate-700">{{ $dept->name }}</span>
                        </div>
                    @empty
                        <div class="py-4 text-center text-slate-450 italic text-xs">
                            No departments assigned.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Access History Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 md:p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-100 pb-3">
                    <i data-lucide="history" class="w-4 h-4 text-indigo-650"></i>
                    <span>Access History</span>
                </h3>

                <div class="relative pl-5 border-l border-slate-200 space-y-4 max-h-[480px] overflow-y-auto pr-1">
                    @forelse($loginHistory as $log)
                        <div class="relative">
                            <span class="absolute -left-[26px] top-1.5 flex items-center justify-center w-2 h-2 rounded-full bg-indigo-500 ring-4 ring-white"></span>
                            <span class="block text-xs font-bold text-slate-800">Successful Login</span>
                            <span class="block text-[10px] text-slate-500 font-mono mt-0.5">{{ $log->created_at->format('Y-m-d H:i:s') }}</span>
                            <div class="block text-[9px] text-slate-450 mt-1 bg-slate-50 p-1.5 rounded-lg border border-slate-200/50 break-all leading-normal">
                                <strong>IP:</strong> {{ $log->ip_address }} <br>
                                <strong>Agent:</strong> {{ Str::limit($log->user_agent, 45) }}
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-450 italic text-xs">
                            No recent login logs found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
