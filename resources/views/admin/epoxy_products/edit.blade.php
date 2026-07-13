@extends('layouts.app')

@section('title', 'Edit Epoxy Product')
@section('header-title', 'Modify Epoxy Product')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-slate-955 border border-slate-850 rounded-2xl p-6 shadow-xl space-y-6">
        <div>
            <h2 class="text-lg font-bold text-white">Edit Epoxy Product</h2>
            <p class="text-xs text-slate-500">Update the product configuration details.</p>
        </div>

        <form action="{{ route('admin.epoxy-products.update', $epoxyProduct->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            @include('admin.epoxy_products._form')

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-850">
                <a href="{{ route('admin.epoxy-products.index') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-850 text-slate-300 rounded-xl text-sm transition-colors border border-slate-800">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl text-sm transition-all duration-205 shadow-lg shadow-cyan-500/10">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
