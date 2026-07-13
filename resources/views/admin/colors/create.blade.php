@extends('layouts.app')

@section('title', 'Add Grout Color')
@section('header-title', 'Create Grout Color')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Back Link -->
    <div>
        <a href="{{ route('admin.grout-colors.index') }}" class="inline-flex items-center text-sm text-slate-400 hover:text-white transition-colors gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Grout Colors</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-6 md:p-8">
        <h3 class="text-lg font-bold text-white mb-6">Grout Color Details</h3>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.grout-colors.store') }}" class="space-y-6">
            @csrf

            @include('admin.colors._form')

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-850">
                <a href="{{ route('admin.grout-colors.index') }}" 
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
