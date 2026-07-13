@extends('layouts.app')

@section('title', 'Notification History')
@section('header-title', 'Notification Center')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Summary Card -->
    <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-indigo-950/60 border border-slate-800 p-6 rounded-3xl relative overflow-hidden shadow-2xl">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-white tracking-tight">Notification History</h2>
                <p class="text-xs text-slate-400 mt-1">Review all push notifications and system updates dispatched across your active sessions.</p>
            </div>
            <button type="button" id="historyMarkAllRead" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-extrabold rounded-xl transition-all shadow-lg shadow-blue-500/20 text-xs gap-2 cursor-pointer">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span>Mark All As Read</span>
            </button>
        </div>
    </div>

    <!-- Filters and List Card -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
        <!-- Filter Bar -->
        <div class="border-b border-slate-100 p-4 bg-slate-50/50">
            <form method="GET" action="{{ route('notifications.index') }}" class="flex flex-wrap items-center gap-3">
                <div>
                    <label for="status" class="sr-only">Status</label>
                    <select name="status" id="status" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="all" {{ request('status') !== 'unread' ? 'selected' : '' }}>All Notifications</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread Only</option>
                    </select>
                </div>

                <div>
                    <label for="type" class="sr-only">Type</label>
                    <select name="type" id="type" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option value="grout_mixing_complete" {{ request('type') === 'grout_mixing_complete' ? 'selected' : '' }}>Grout Mixing Complete</option>
                        <option value="low_stock" {{ request('type') === 'low_stock' ? 'selected' : '' }}>Low Stock Alerts</option>
                        <option value="finished_goods_low_stock" {{ request('type') === 'finished_goods_low_stock' ? 'selected' : '' }}>FG Low Stock Alerts</option>
                        <option value="manual_stock_in" {{ request('type') === 'manual_stock_in' ? 'selected' : '' }}>Manual Stock IN</option>
                        <option value="manual_stock_out" {{ request('type') === 'manual_stock_out' ? 'selected' : '' }}>Manual Stock OUT</option>
                    </select>
                </div>

                @if(request()->anyFilled(['status', 'type']))
                    <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 ml-auto">Clear Filters</a>
                @endif
            </form>
        </div>

        <!-- Notifications List -->
        <div class="divide-y divide-slate-100">
            @forelse($notifications as $notif)
                @php
                    $isRead = !is_null($notif->read_at);
                    $readBg = $isRead ? 'bg-white' : 'bg-blue-50/15 font-semibold';
                    $unreadDot = $isRead ? '' : '<span class="h-2 w-2 rounded-full bg-blue-600 animate-pulse mt-1 shrink-0"></span>';
                @endphp
                <div class="p-4 flex items-start gap-4 hover:bg-slate-50 transition-colors {{ $readBg }} notif-row" data-id="{{ $notif->id }}">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $isRead ? 'bg-slate-100 text-slate-500' : 'bg-blue-50 text-blue-650' }}">
                        <i data-lucide="{{ $notif->type === 'grout_mixing_complete' ? 'refresh-cw' : 'bell' }}" class="h-4.5 w-4.5"></i>
                    </div>

                    <div class="flex-1 space-y-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <h4 class="text-sm font-bold text-slate-800">{{ $notif->title }}</h4>
                            <span class="text-[10px] text-slate-400 font-medium whitespace-nowrap">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-slate-500 whitespace-pre-line leading-relaxed">{{ $notif->body }}</p>
                        
                        <div class="flex items-center gap-3 pt-1 text-[10px] font-bold uppercase tracking-wider text-slate-450">
                            <span>Type: {{ str_replace('_', ' ', $notif->type) }}</span>
                            @if($notif->payload && isset($notif->payload['click_action']))
                                <span class="text-slate-300">&bull;</span>
                                <a href="{{ $notif->payload['click_action'] }}" class="text-blue-600 hover:text-blue-700 hover:underline inline-flex items-center gap-1 history-notif-link" data-id="{{ $notif->id }}">
                                    <span>View Details</span>
                                    <i data-lucide="external-link" class="h-3 w-3"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="shrink-0 flex items-center gap-3 self-center">
                        {!! $unreadDot !!}
                        @if(!$isRead)
                            <button type="button" class="history-read-btn p-1 text-slate-400 hover:text-blue-600 transition-colors cursor-pointer" title="Mark as Read" data-id="{{ $notif->id }}">
                                <i data-lucide="check" class="h-4 w-4"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-500">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <i data-lucide="bell-off" class="w-8 h-8 text-slate-350"></i>
                        <span class="text-xs font-semibold">No notifications match your filters.</span>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination Footer -->
        @if($notifications->hasPages())
            <div class="border-t border-slate-100 p-4 bg-slate-55/20">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Mark all as read from page button
        $('#historyMarkAllRead').click(function() {
            $.post('/notifications/read-all', {
                _token: '{{ csrf_token() }}'
            }, function() {
                window.location.reload();
            });
        });

        // Mark individual read via check button
        $('.history-read-btn').click(function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const row = $(this).closest('.notif-row');
            const btn = $(this);

            $.post(`/notifications/${id}/read`, {
                _token: '{{ csrf_token() }}'
            }, function() {
                row.removeClass('bg-blue-50/15 font-semibold').addClass('bg-white opacity-60');
                btn.fadeOut(200, function() {
                    btn.remove();
                });
                row.find('.animate-pulse').remove();
                if (window.fetchUnreadNotifications) {
                    window.fetchUnreadNotifications();
                }
            });
        });

        // Click details link to mark read and redirect
        $('.history-notif-link').click(function(e) {
            const id = $(this).data('id');
            const href = $(this).attr('href');
            if (href && href !== '#') {
                e.preventDefault();
                $.post(`/notifications/${id}/read`, {
                    _token: '{{ csrf_token() }}'
                }, function() {
                    window.location.href = href;
                });
            }
        });
    });
</script>
@endsection
