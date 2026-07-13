<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Register a new device FCM token.
     */
    public function registerDevice(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
            'browser_name' => 'nullable|string|max:255',
            'platform' => 'nullable|string|max:255',
            'device_name' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        
        $device = $this->notificationService->registerDevice($user, [
            'device_token' => $request->input('device_token'),
            'browser_name' => $request->input('browser_name'),
            'platform' => $request->input('platform'),
            'device_name' => $request->input('device_name'),
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Device registered successfully.',
            'device' => $device,
        ]);
    }

    /**
     * Remove a registered device token.
     */
    public function removeDevice(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
        ]);

        $removed = $this->notificationService->removeDevice($request->input('device_token'));

        return response()->json([
            'success' => true,
            'message' => $removed ? 'Device removed successfully.' : 'Device not found.',
        ]);
    }

    /**
     * Fetch unread notifications list and unread count for navbar dropdown.
     */
    public function getUnread(Request $request)
    {
        $user = Auth::user();
        
        // Find notifications sent to this user or department notifications matching user department
        $query = Notification::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere(function ($sub) use ($user) {
                  $sub->whereNull('user_id')
                      ->whereNotNull('department_id')
                      ->whereIn('department_id', $user->departmentIds());
              });
        });

        $unreadCount = (clone $query)->whereNull('read_at')->count();
        
        $notifications = $query->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'body' => $notif->body,
                    'type' => $notif->type,
                    'created_at_human' => $notif->created_at->diffForHumans(),
                    'is_read' => !is_null($notif->read_at),
                    'click_url' => $notif->payload['click_action'] ?? '#',
                ];
            });

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        $user = Auth::user();
        
        // Security check: ensure user owns or has access to department
        if ($notification->user_id !== $user->id && 
            ($notification->department_id && !$user->canAccessDepartment($notification->department_id))) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.'
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        $user = Auth::user();

        Notification::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere(function ($sub) use ($user) {
                  $sub->whereNull('user_id')
                      ->whereNotNull('department_id')
                      ->whereIn('department_id', $user->departmentIds());
              });
        })
        ->whereNull('read_at')
        ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.'
        ]);
    }

    /**
     * Display Notification History page.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Notification::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere(function ($sub) use ($user) {
                  $sub->whereNull('user_id')
                      ->whereNotNull('department_id')
                      ->whereIn('department_id', $user->departmentIds());
              });
        });

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('status') && $request->input('status') === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('notifications.index', compact('notifications'));
    }
}
