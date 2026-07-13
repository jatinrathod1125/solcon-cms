<?php

namespace App\Services;

use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Cache;

class DepartmentAccessService
{
    /**
     * Check if a user has access to a specific department.
     *
     * @param  User  $user
     * @param  int|string  $departmentId
     * @return bool
     */
    public function checkAccess(User $user, $departmentId): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->getUserDepartmentIds($user)->contains($departmentId);
    }

    /**
     * Get the available departments for a user.
     *
     * @param  User  $user
     * @return \Illuminate\Support\Collection
     */
    public function getAvailableDepartments(User $user): \Illuminate\Support\Collection
    {
        if ($user->isAdmin()) {
            // Admin can access all active departments - cache only the raw array of IDs
            $ids = Cache::remember('active_department_ids', 3600, function () {
                return Department::where('is_active', true)->orderBy('name')->pluck('id')->toArray();
            });
            return Department::whereIn('id', $ids)->orderBy('name')->get();
        }

        // Supervisor can access assigned active departments - cache only the raw array of IDs
        $userId = $user->id;
        $ids = Cache::remember("user_departments_{$userId}", 3600, function () use ($user) {
            return $user->departments()
                ->where('is_active', true)
                ->pluck('departments.id')
                ->toArray();
        });

        if (empty($ids)) {
            return collect();
        }

        return Department::whereIn('id', $ids)->orderBy('name')->get();
    }

    /**
     * Get the IDs of the departments a user is assigned to.
     *
     * @param  User  $user
     * @return \Illuminate\Support\Collection
     */
    public function getUserDepartmentIds(User $user): \Illuminate\Support\Collection
    {
        if ($user->isAdmin()) {
            return $this->getAvailableDepartments($user)->pluck('id');
        }

        $userId = $user->id;
        $ids = Cache::remember("user_department_ids_{$userId}", 3600, function () use ($user) {
            return $user->departments()->pluck('departments.id')->toArray();
        });

        return collect($ids);
    }

    /**
     * Clear the cache for a user's departments.
     *
     * @param  User  $user
     * @return void
     */
    public function clearUserCache(User $user): void
    {
        $userId = $user->id;
        Cache::forget("user_departments_{$userId}");
        Cache::forget("user_department_ids_{$userId}");
        Cache::forget('active_department_ids');
    }
}
