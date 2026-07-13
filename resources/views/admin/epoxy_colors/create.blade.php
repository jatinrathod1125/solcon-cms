@extends('layouts.app')

@section('title', 'Add Epoxy Filler Color')
@section('header-title', 'Create Epoxy Filler Color')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Back Link -->
    <div>
        <a href="{{ route('admin.epoxy-colors.index') }}" class="inline-flex items-center text-sm text-slate-400 hover:text-white transition-colors gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Epoxy Filler Colors</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-6 md:p-8">
        <h3 class="text-lg font-bold text-white mb-6">Color Details</h3>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.epoxy-colors.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Color Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                        placeholder="e.g. Chocolate">
                    @error('name')
                        <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code -->
                <div>
                    <label for="code" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Color Code</label>
                    <input type="text" id="code" name="code" value="{{ old('code') }}" required
                        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm font-mono uppercase"
                        placeholder="e.g. EP-CHO">
                    @error('code')
                        <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Active Status -->
            <div class="flex items-center">
                <label class="flex items-center text-sm text-slate-400 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-slate-800 bg-slate-900 text-cyan-500 focus:ring-cyan-500/30 focus:ring-offset-0 mr-2 cursor-pointer">
                    <span>Mark as Active (Available for Epoxy component mapping)</span>
                </label>
                @error('is_active')
                    <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Description</label>
                <textarea id="description" name="description" rows="4"
                    class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all text-sm"
                    placeholder="Provide details about this filler color properties...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-rose-455 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-850">
                <a href="{{ route('admin.epoxy-colors.index') }}" 
                    class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 rounded-xl text-sm font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                    class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 shadow-lg shadow-cyan-500/10 text-sm">
                    Create Color
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
