<?php

if (!function_exists('currentDepartment')) {
    /**
     * Get the currently active department for the logged in user.
     *
     * @return \App\Models\Department|null
     */
    function currentDepartment()
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        $sessionKey = 'current_department_id_' . $user->id;
        $currentId = session($sessionKey);

        $availableDepartments = availableDepartments();
        if ($availableDepartments->isEmpty()) {
            return null;
        }

        // 1. If the session department is in the available departments, use it
        if ($currentId && $availableDepartments->contains('id', $currentId)) {
            return $availableDepartments->firstWhere('id', $currentId);
        }

        // 2. Otherwise, default to the first available department
        $defaultDept = $availableDepartments->first();
        session([$sessionKey => $defaultDept->id]);
        return $defaultDept;
    }
}

if (!function_exists('availableDepartments')) {
    /**
     * Get the available departments for the logged in user.
     *
     * @return \Illuminate\Support\Collection
     */
    function availableDepartments()
    {
        $user = auth()->user();
        if (!$user) {
            return collect();
        }

        return app(\App\Services\DepartmentAccessService::class)->getAvailableDepartments($user);
    }
}

if (!function_exists('userDepartmentIds')) {
    /**
     * Get the IDs of the departments the logged in user is assigned to.
     *
     * @return array
     */
    function userDepartmentIds()
    {
        $user = auth()->user();
        if (!$user) {
            return [];
        }

        return app(\App\Services\DepartmentAccessService::class)->getUserDepartmentIds($user)->toArray();
    }
}
