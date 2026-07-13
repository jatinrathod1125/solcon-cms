<?php

namespace App\Services;

use App\Models\GroutProductionBatch;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\Notification;
use App\Models\Department;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationService
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Fetch all active grout batches that have completed their mixing timer
     * but haven't been transitioned to Stage 2 yet.
     *
     * @return \Illuminate\Support\Collection
     */
    public function checkCompletedTimers(): \Illuminate\Support\Collection
    {
        return GroutProductionBatch::where('status', 'Timer Running')
            ->where('timer_end_time', '<=', now())
            ->with(['machine', 'color'])
            ->get();
    }

    /**
     * Register a user device token.
     */
    public function registerDevice(User $user, array $deviceData): UserDevice
    {
        return UserDevice::updateOrCreate(
            [
                'device_token' => $deviceData['device_token'],
            ],
            [
                'user_id' => $user->id,
                'browser_name' => $deviceData['browser_name'] ?? null,
                'platform' => $deviceData['platform'] ?? null,
                'device_name' => $deviceData['device_name'] ?? null,
                'ip_address' => $deviceData['ip_address'] ?? null,
                'last_seen_at' => now(),
            ]
        );
    }

    /**
     * Remove a registered device token.
     */
    public function removeDevice(string $token): bool
    {
        return UserDevice::where('device_token', $token)->delete() > 0;
    }

    /**
     * Update device last seen timestamp.
     */
    public function touchDevice(string $token): bool
    {
        return UserDevice::where('device_token', $token)->update([
            'last_seen_at' => now()
        ]) > 0;
    }

    /**
     * Send a notification to a specific user across all their devices.
     */
    public function sendToUser(User $user, string $title, string $body, string $type, ?int $departmentId = null, array $payload = []): bool
    {
        // Enforce Granular Permission matching:
        // A Grout Supervisor can only receive Grout notifications.
        // An Adhesive Supervisor can only receive Adhesive notifications.
        // An Epoxy Supervisor can only receive Epoxy notifications.
        // An Admin can receive all notifications.
        if (!$user->isAdmin()) {
            if ($departmentId !== null && !$user->canAccessDepartment($departmentId)) {
                Log::info("Notification skipped for User ID {$user->id} due to department access mismatch.");
                return false;
            }
        }

        $devices = $user->devices;
        if ($devices->isEmpty()) {
            // Log local notification database entry even if no devices are registered
            $this->logNotification($title, $body, $type, $departmentId, $user->id, 'no_device_registered', $payload);
            return false;
        }

        $success = false;
        foreach ($devices as $device) {
            $isSent = $this->firebaseService->sendNotification($device->device_token, $title, $body, $payload);
            
            if ($isSent) {
                $success = true;
                $device->touch();
            } else {
                // If FCM rejects the token (invalid/expired), prune it
                $device->delete();
            }
        }

        $status = $success ? 'sent' : 'failed';
        $this->logNotification($title, $body, $type, $departmentId, $user->id, $status, $payload);

        return $success;
    }

    /**
     * Send a notification to all users belonging to a specific department (e.g. TAD, GRT, EPX).
     */
    public function sendToDepartment(string $deptCode, string $title, string $body, string $type, array $payload = []): int
    {
        $department = Department::where('code', $deptCode)->first();
        if (!$department) {
            Log::error("Department with code {$deptCode} not found for department notification.");
            return 0;
        }

        // Fetch all active supervisors assigned to this department, plus all active admins
        $users = User::where('is_active', true)
            ->where(function ($q) use ($department) {
                // Admin gets all notifications
                $q->whereHas('roles', function ($sub) {
                    $sub->where('slug', 'admin');
                })
                // Supervisors registered to this department
                ->orWhere(function ($sub) use ($department) {
                    $sub->whereHas('roles', function ($role) {
                        $role->where('slug', 'supervisor');
                    })
                    ->where(function ($deptQuery) use ($department) {
                        $deptQuery->where('department_id', $department->id)
                                  ->orWhereHas('departments', function ($subDept) use ($department) {
                                      $subDept->where('departments.id', $department->id);
                                  });
                    });
                });
            })
            ->get();

        $sentCount = 0;
        foreach ($users as $user) {
            if ($this->sendToUser($user, $title, $body, $type, $department->id, $payload)) {
                $sentCount++;
            }
        }

        return $sentCount;
    }

    /**
     * Send a notification to all active admins.
     */
    public function sendToAdmins(string $title, string $body, string $type, ?int $departmentId = null, array $payload = []): int
    {
        $admins = User::where('is_active', true)
            ->whereHas('roles', function ($q) {
                $q->where('slug', 'admin');
            })
            ->get();

        $sentCount = 0;
        foreach ($admins as $admin) {
            if ($this->sendToUser($admin, $title, $body, $type, $departmentId, $payload)) {
                $sentCount++;
            }
        }

        return $sentCount;
    }

    /**
     * Log a notification in database history.
     */
    private function logNotification(
        string $title,
        string $body,
        string $type,
        ?int $departmentId,
        ?int $userId,
        string $status,
        array $payload
    ): Notification {
        return Notification::create([
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'department_id' => $departmentId,
            'user_id' => $userId,
            'status' => $status,
            'sent_at' => now(),
            'payload' => $payload,
        ]);
    }
}
