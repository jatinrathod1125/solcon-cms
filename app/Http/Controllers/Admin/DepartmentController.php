<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Http\Requests\Admin\StoreDepartmentRequest;
use App\Http\Requests\Admin\UpdateDepartmentRequest;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index(Request $request)
    {
        $query = Department::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $departments = $query->latest()->paginate(10)->withQueryString();

        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        Department::create($data);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Show the form for editing the department.
     */
    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Update the department in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $department->update($data);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the department from storage.
     */
    public function destroy(Department $department)
    {
        if ($department->machines()->exists()) {
            return redirect()->route('admin.departments.index')
                ->with('error', 'Cannot delete department. It has associated machines.');
        }

        if ($department->users()->exists()) {
            return redirect()->route('admin.departments.index')
                ->with('error', 'Cannot delete department. It has assigned supervisor users.');
        }

        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
