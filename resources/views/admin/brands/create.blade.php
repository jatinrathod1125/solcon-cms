@extends('layouts.app')

@section('title', 'Add Brand')
@section('header-title', 'Create Brand')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="{{ route('brands.index') }}" class="inline-flex items-center text-sm text-slate-400 hover:text-white transition-colors gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Brands</span>
        </a>
    </div>

    <div class="bg-slate-950 border border-slate-850 rounded-2xl shadow-xl p-6 md:p-8">
        <h3 class="text-lg font-bold text-white mb-6">Brand Details</h3>
        <form method="POST" action="{{ route('brands.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Code</label>
                    <input type="text" name="code" value="{{ old('code') }}" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    @error('code') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    @error('name') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    @error('slug') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center pt-8">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 bg-slate-900 border border-slate-800 rounded text-cyan-500 focus:ring-cyan-500 focus:ring-offset-slate-950">
                    <label class="ml-2 text-sm font-medium text-slate-300">Active</label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-850">
                <a href="{{ route('brands.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 rounded-xl text-sm font-semibold transition-colors">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl shadow-lg text-sm">Create Brand</button>
            </div>
        </form>
    </div>
</div>
@endsection
