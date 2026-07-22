<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'email', 'password', 'department_id', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['roles.permissions'];

    protected $_memoizedPermissions = null;
    protected $_memoizedRoleSlugs = null;

    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get the department the user belongs to.
     */
    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the departments assigned to the user.
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'user_departments');
    }

    /**
     * Check if the user is assigned to a specific department.
     */
    public function hasDepartment($departmentId): bool
    {
        return $this->departmentIds()->contains($departmentId);
    }

    /**
     * Get the IDs of the departments assigned to the user.
     */
    public function departmentIds(): \Illuminate\Support\Collection
    {
        return app(\App\Services\DepartmentAccessService::class)->getUserDepartmentIds($this);
    }

    /**
     * Check if the user can access a specific department.
     */
    public function canAccessDepartment($departmentId): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        return $this->hasDepartment($departmentId);
    }

    /**
     * Get the dynamic department ID for active session.
     */
    public function getDepartmentIdAttribute($value)
    {
        if ($this->isSupervisor() && session()->has('current_department_id_' . $this->id)) {
            return session('current_department_id_' . $this->id);
        }
        return $value;
    }

    /**
     * Get the dynamic department model for active session.
     */
    public function getDepartmentAttribute()
    {
        if ($this->isSupervisor() && session()->has('current_department_id_' . $this->id)) {
            $deptId = session('current_department_id_' . $this->id);
            if ($deptId) {
                return Department::find($deptId);
            }
        }
        return $this->getRelationValue('department');
    }

    /**
     * Check if the user has a specific role or any of the given roles.
     */
    public function hasRole(string|array $role): bool
    {
        if ($this->_memoizedRoleSlugs === null) {
            $this->_memoizedRoleSlugs = $this->relationLoaded('roles')
                ? $this->roles->pluck('slug')->toArray()
                : $this->roles()->pluck('slug')->toArray();
        }

        if (is_array($role)) {
            return count(array_intersect($this->_memoizedRoleSlugs, $role)) > 0;
        }
        return in_array($role, $this->_memoizedRoleSlugs);
    }

    /**
     * Check if the user has a specific permission via their roles.
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->_memoizedPermissions === null) {
            if ($this->relationLoaded('roles')) {
                $this->_memoizedPermissions = $this->roles->flatMap(function ($role) {
                    return $role->relationLoaded('permissions') ? $role->permissions->pluck('slug') : $role->permissions()->pluck('slug');
                })->toArray();
            } else {
                $this->_memoizedPermissions = \Illuminate\Support\Facades\DB::table('permissions')
                    ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
                    ->join('role_user', 'permission_role.role_id', '=', 'role_user.role_id')
                    ->where('role_user.user_id', $this->id)
                    ->pluck('permissions.slug')
                    ->toArray();
            }
        }
        return in_array($permission, $this->_memoizedPermissions);
    }

    /**
     * Check if the user is an Admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if the user is a Supervisor.
     */
    public function isSupervisor(): bool
    {
        return $this->hasRole('supervisor');
    }

    /**
     * Check if the user is in Marketing.
     */
    public function isMarketing(): bool
    {
        return $this->hasRole('marketing');
    }

    /**
     * Check if the user is in Dispatch.
     */
    public function isDispatch(): bool
    {
        return $this->hasRole('dispatch');
    }

    /**
     * Check if the user is authorized to skip the mixing timer.
     */
    public function canSkipTimer(): bool
    {
        if ($this->isAdmin() || $this->hasRole('manager')) {
            return true;
        }
        return $this->hasPermission('production-override');
    }

    /**
     * Get all devices registered for this user.
     */
    public function devices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserDevice::class);
    }

    /**
     * Get all notifications sent to this user.
     */
    public function notifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
}
