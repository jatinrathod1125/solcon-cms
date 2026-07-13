<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Http\Requests\Admin\StoreUnitRequest;
use App\Http\Requests\Admin\UpdateUnitRequest;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of units.
     */
    public function index(Request $request)
    {
        $query = Unit::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $units = $query->latest()->paginate(10)->withQueryString();

        return view('admin.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit.
     */
    public function create()
    {
        return view('admin.units.create');
    }

    /**
     * Store a newly created unit in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        Unit::create($data);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit created successfully.');
    }

    /**
     * Show the form for editing the unit.
     */
    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    /**
     * Update the unit in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $unit->update($data);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the unit from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit deleted successfully.');
    }
}
