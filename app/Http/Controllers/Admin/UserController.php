<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Services\DepartmentAccessService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected $accessService;

    /**
     * Create a new controller instance.
     */
    public function __construct(DepartmentAccessService $accessService)
    {
        $this->accessService = $accessService;
    }

    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'departments']);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest('id')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.users.create', compact('roles', 'departments'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'departments' => 'required|array|min:1',
            'departments.*' => 'exists:departments,id|distinct',
            'is_active' => 'nullable|boolean',
        ], [
            'departments.required' => 'At least one Department is required.',
            'departments.*.distinct' => 'Duplicate Departments are not allowed.',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'is_active' => $request->input('is_active', true),
        ]);

        // Sync Role
        $user->roles()->sync([$request->input('role_id')]);

        // Sync Departments
        $user->departments()->sync($request->input('departments'));

        // Clear cache
        $this->accessService->clearUserCache($user);

        ActivityLogService::log(
            'USER_CREATED',
            "User {$user->name} ({$user->email}) created with role and department assignments.",
            auth()->id(),
            'User Management'
        );

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $userRole = $user->roles()->first();

        return view('admin.users.edit', compact('user', 'roles', 'departments', 'userRole'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'departments' => 'required|array|min:1',
            'departments.*' => 'exists:departments,id|distinct',
            'is_active' => 'nullable|boolean',
        ], [
            'departments.required' => 'At least one Department is required.',
            'departments.*.distinct' => 'Duplicate Departments are not allowed.',
        ]);

        $userData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'is_active' => $request->input('is_active', true),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->input('password'));
        }

        $user->update($userData);

        // Sync Role
        $user->roles()->sync([$request->input('role_id')]);

        // Sync Departments
        $user->departments()->sync($request->input('departments'));

        // Clear cache
        $this->accessService->clearUserCache($user);

        ActivityLogService::log(
            'USER_UPDATED',
            "User {$user->name} ({$user->email}) details and department assignments updated.",
            auth()->id(),
            'User Management'
        );

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }

        $userName = $user->name;
        $userEmail = $user->email;

        // Clear cache before delete
        $this->accessService->clearUserCache($user);

        // Delete user (relations will automatically be cascade deleted)
        $user->delete();

        ActivityLogService::log(
            'USER_DELETED',
            "User {$userName} ({$userEmail}) deleted from system.",
            auth()->id(),
            'User Management'
        );

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
