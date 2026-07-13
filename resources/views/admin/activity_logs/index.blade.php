@extends('layouts.app')

@section('title', 'System Logs')
@section('header-title', 'Activity Audit Trails')

@section('content')
<div class="space-y-6">
    <!-- Top Header Banner -->
    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-5 h-5 text-indigo-650"></i>
                <span>Activity Logs & Audit Trails</span>
            </h1>
            <p class="text-sm text-slate-500 mt-0.5">Audit actions performed by supervisors and system tasks across factory modules.</p>
        </div>

        <div class="flex items-center">
            <!-- CSV Export -->
            <a href="{{ route('admin.activity-logs', array_merge(request()->all(), ['export' => 'csv'])) }}" 
               class="inline-flex items-center px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 font-bold border border-slate-350 rounded-xl text-sm transition-colors gap-1.5 shadow-sm">
                <i data-lucide="download-cloud" class="w-4 h-4 text-slate-500"></i>
                <span>Export Audit CSV</span>
            </a>
        </div>
    </div>

    <!-- Advanced Filters Card -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200 bg-slate-50/40">
            <form method="GET" action="{{ route('admin.activity-logs') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 items-end">
                <!-- User Filter -->
                <div>
                    <label for="user_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">User / Actor</label>
                    <select id="user_id" name="user_id" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                        <option value="">All Users</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Module Filter -->
                <div>
                    <label for="module" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Module</label>
                    <select id="module" name="module" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                        <option value="">All Modules</option>
                        @foreach($modules as $mod)
                            <option value="{{ $mod }}" {{ request('module') === $mod ? 'selected' : '' }}>{{ $mod }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter -->
                <div>
                    <label for="action" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Action Name</label>
                    <input type="text" name="action" id="action" value="{{ request('action') }}" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm" placeholder="e.g. BATCH_...">
                </div>

                <!-- IP Address -->
                <div>
                    <label for="ip_address" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">IP Address</label>
                    <input type="text" name="ip_address" id="ip_address" value="{{ request('ip_address') }}" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm" placeholder="e.g. 127.0.0.1">
                </div>

                <!-- User Agent -->
                <div>
                    <label for="user_agent" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">User Agent</label>
                    <input type="text" name="user_agent" id="user_agent" value="{{ request('user_agent') }}" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm" placeholder="e.g. Mozilla...">
                </div>

                <!-- Date Filter -->
                <div>
                    <label for="date" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Date</label>
                    <input type="date" id="date" name="date" value="{{ request('date') }}" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm">
                </div>

                <!-- Action buttons -->
                <div class="lg:col-span-6 flex justify-end gap-2 mt-2">
                    <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-250 text-slate-700 font-bold border border-slate-300 rounded-xl text-sm transition-colors flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="filter" class="w-4 h-4 text-slate-550"></i>
                        <span>Apply Filters</span>
                    </button>
                    @if(request()->anyFilled(['user_id', 'date', 'module', 'action', 'ip_address', 'user_agent']))
                        <a href="{{ route('admin.activity-logs') }}" class="px-4 py-2 bg-slate-150 hover:bg-slate-200 text-slate-550 hover:text-slate-800 font-semibold border border-slate-300 rounded-xl text-sm transition-colors flex items-center justify-center gap-1">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            <span>Clear</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Log List Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/85 text-slate-500 font-semibold text-xs uppercase tracking-wider">
                        <th class="p-4 w-12 text-center">ID</th>
                        <th class="p-4 w-40">User</th>
                        <th class="p-4 w-44">Action</th>
                        <th class="p-4 w-36 text-center">Module</th>
                        <th class="p-4">Description</th>
                        <th class="p-4 w-32 font-mono">IP Address</th>
                        <th class="p-4 w-48">Date Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 text-center font-mono text-slate-400">{{ $log->id }}</td>
                            <td class="p-4">
                                <div class="font-bold text-slate-800">{{ $log->user->name ?? 'System/Guest' }}</div>
                                <div class="text-xs text-slate-500 truncate max-w-[150px]">{{ $log->user->email ?? '-' }}</div>
                            </td>
                            <td class="p-4 font-mono font-bold text-indigo-650 text-xs">
                                {{ $log->action }}
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 border border-slate-200 text-slate-650 uppercase tracking-wide">
                                    {{ $log->module ?? 'System' }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-650 leading-relaxed font-medium">
                                {{ $log->description }}
                                <span class="block text-[9px] text-slate-450 mt-1 truncate max-w-[280px]" title="{{ $log->user_agent }}">
                                    {{ $log->user_agent }}
                                </span>
                            </td>
                            <td class="p-4 font-mono text-xs text-slate-500">
                                {{ $log->ip_address }}
                            </td>
                            <td class="p-4 font-mono text-xs text-slate-500">
                                {{ $log->created_at->format('d M Y, h:i:s A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i data-lucide="clipboard" class="w-8 h-8 text-slate-300"></i>
                                    <span>No activity audit logs found matching criteria.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
