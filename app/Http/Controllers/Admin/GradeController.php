<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGradeRequest;
use App\Http\Requests\Admin\UpdateGradeRequest;
use App\Models\BagSize;
use App\Models\Brand;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Unit;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $query = Grade::with(['department', 'brand', 'bagSize', 'outputUnit', 'creator']);

        if (function_exists('currentBrand') && currentBrand()) {
            $query->forBrand(currentBrand());
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        $grades = $query->orderBy('name')->paginate(15)->withQueryString();
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.grades.index', compact('grades', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::active()->orderBy('name')->get();
        $bagSizes = BagSize::where('is_active', true)->orderBy('value')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();

        return view('admin.grades.create', compact('departments', 'brands', 'bagSizes', 'units'));
    }

    public function store(StoreGradeRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        if ($request->has('is_active')) {
            $data['is_active'] = $request->boolean('is_active');
        }

        Grade::create($data);

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade created successfully.');
    }

    public function edit(Grade $grade)
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::active()->orderBy('name')->get();
        $bagSizes = BagSize::where('is_active', true)->orderBy('value')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();

        return view('admin.grades.edit', compact('grade', 'departments', 'brands', 'bagSizes', 'units'));
    }

    public function update(UpdateGradeRequest $request, Grade $grade)
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();
        if ($request->has('is_active')) {
            $data['is_active'] = $request->boolean('is_active');
        }

        $grade->update($data);

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade deleted successfully.');
    }
}
