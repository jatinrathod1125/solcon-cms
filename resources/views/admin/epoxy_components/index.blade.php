@extends('layouts.app')

@section('title', 'Epoxy Components')
@section('header-title', 'Epoxy Component Master')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-white">Epoxy Components</h2>
            <p class="text-xs text-slate-500">Configure prepared ready components (fillers, hardeners, resins, accessories, spacers, etc.).</p>
        </div>
        <a href="{{ route('admin.epoxy-components.create') }}"
            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-205 shadow-lg text-sm gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add Epoxy Component</span>
        </a>
    </div>

    <div class="bg-slate-955 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-850 bg-slate-900/50 text-slate-400 font-semibold">
                        <th class="p-4 w-32">Code</th>
                        <th class="p-4">Name</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Purpose</th>
                        <th class="p-4">Color Variant</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50 text-slate-200">
                    @forelse($components as $comp)
                    <tr>
                        <td class="p-4 font-mono font-bold text-cyan-400">{{ $comp->code }}</td>
                        <td class="p-4 font-semibold text-white">{{ $comp->name }}</td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 rounded bg-slate-900 text-slate-300 border border-slate-800 text-xs font-semibold">
                                {{ $comp->category }}
                            </span>
                        </td>
                        <td class="p-4 text-xs font-semibold">
                            @if($comp->purpose === 'Direct Finished Product')
                            <span class="text-indigo-400">Direct Finished Product</span>
                            @else
                            <span class="text-teal-450">Assembly Component</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($comp->color)
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-cyan-500/10 text-cyan-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span>{{ $comp->color->name }}
                            </span>
                            @elseif($comp->parentComponent)
                            <span class="text-xs text-slate-500">Child of {{ $comp->parentComponent->name }}</span>
                            @else
                            <span class="text-xs text-slate-650">-</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($comp->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>Active
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>Inactive
                            </span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.epoxy-components.edit', $comp->id) }}"
                                    class="p-1.5 hover:bg-slate-900 rounded-lg text-slate-400 hover:text-white transition-colors"
                                    title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.epoxy-components.destroy', $comp->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this component?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 hover:bg-rose-500/10 rounded-lg text-slate-400 hover:text-rose-400 transition-colors"
                                        title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500">No Epoxy Components defined. Click Add Epoxy Component to get started.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
