<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Brand;
use App\Models\Department;
use App\Http\Requests\Admin\StoreColorRequest;
use App\Http\Requests\Admin\UpdateColorRequest;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of grout colors.
     */
    public function index(Request $request)
    {
        $query = Color::with(['brand', 'department', 'creator', 'updater']);

        if (function_exists('currentBrand') && currentBrand()) {
            $query->forBrand(currentBrand());
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('packing_size')) {
            $query->where('packing_size', $request->input('packing_size'));
        }

        if ($request->filled('default_cement')) {
            $query->where('default_cement', $request->input('default_cement'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $colors = $query->latest()->paginate(10)->withQueryString();
        $brands = Brand::active()->orderBy('name')->get();

        return view('admin.colors.index', compact('colors', 'brands'));
    }

    /**
     * Show the form for creating a new color.
     */
    public function create()
    {
        // Only get active departments, preferably including Grout Department
        $departments = Department::getActive();
        $brands = Brand::active()->orderBy('name')->get();
        return view('admin.colors.create', compact('departments', 'brands'));
    }

    /**
     * Store a newly created color in storage.
     */
    public function store(StoreColorRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        Color::create($data);

        return redirect()->route('admin.grout-colors.index')
            ->with('success', 'Grout Color created successfully.');
    }

    /**
     * Show the form for editing the specified color.
     */
    public function edit(Color $grout_color)
    {
        $departments = Department::getActive();
        $brands = Brand::active()->orderBy('name')->get();
        $color = $grout_color;
        return view('admin.colors.edit', compact('color', 'departments', 'brands'));
    }

    /**
     * Update the specified color in storage.
     */
    public function update(UpdateColorRequest $request, Color $grout_color)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['updated_by'] = auth()->id();

        $grout_color->update($data);

        return redirect()->route('admin.grout-colors.index')
            ->with('success', 'Grout Color updated successfully.');
    }

    /**
     * Remove the specified color from storage.
     */
    public function destroy(Color $grout_color)
    {
        // Check if there are formulas registered for this color
        if ($grout_color->formulas()->exists()) {
            return redirect()->route('admin.grout-colors.index')
                ->with('error', 'Cannot delete Color because it has associated formulas.');
        }

        $grout_color->delete();

        return redirect()->route('admin.grout-colors.index')
            ->with('success', 'Grout Color deleted successfully.');
    }
}
