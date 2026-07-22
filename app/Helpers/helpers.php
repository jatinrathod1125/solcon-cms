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

if (!function_exists('format_quantity')) {
    /**
     * Format number dynamically without unnecessary trailing zeros after decimal point.
     * Examples:
     *   10000.0000 -> "10000"
     *   1000.0470  -> "1000.047"
     *   0.0000     -> "0"
     */
    function format_quantity($value, int $maxDecimals = 4, bool $useComma = false): string
    {
        if ($value === null || $value === '') {
            return '0';
        }
        $val = (float) $value;
        $formatted = number_format($val, $maxDecimals, '.', $useComma ? ',' : '');
        if (strpos($formatted, '.') !== false) {
            $formatted = rtrim(rtrim($formatted, '0'), '.');
        }
        return $formatted;
    }
}

