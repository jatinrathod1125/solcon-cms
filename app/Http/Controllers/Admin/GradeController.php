<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Department;
use App\Models\BagSize;
use App\Models\Unit;
use App\Http\Requests\Admin\StoreGradeRequest;
use App\Http\Requests\Admin\UpdateGradeRequest;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of grades.
     */
    public function index(Request $request)
    {
        $query = Grade::with(['department', 'bagSize', 'outputUnit', 'creator', 'updater']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $grades = $query->latest()->paginate(10)->withQueryString();

        return view('admin.grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new grade.
     */
    public function create()
    {
        $departments = Department::getActive();
        $bagSizes = BagSize::getActive();
        $units = Unit::getActive();

        return view('admin.grades.create', compact('departments', 'bagSizes', 'units'));
    }

    /**
     * Store a newly created grade in storage.
     */
    public function store(StoreGradeRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        Grade::create($data);

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade created successfully.');
    }

    /**
     * Show the form for editing the grade.
     */
    public function edit(Grade $grade)
    {
        $departments = Department::getActive();
        $bagSizes = BagSize::getActive();
        $units = Unit::getActive();

        return view('admin.grades.edit', compact('grade', 'departments', 'bagSizes', 'units'));
    }

    /**
     * Update the grade in storage.
     */
    public function update(UpdateGradeRequest $request, Grade $grade)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['updated_by'] = auth()->id();

        $grade->update($data);

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade updated successfully.');
    }

    /**
     * Remove the grade from storage.
     */
    public function destroy(Grade $grade)
    {
        // When production and formula are added, we check if this grade is linked to any formulas or production batches.
        // For now, standard delete:
        $grade->delete();

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade deleted successfully.');
    }
}
