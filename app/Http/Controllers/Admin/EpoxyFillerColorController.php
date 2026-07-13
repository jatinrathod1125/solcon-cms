<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EpoxyFillerColor;
use App\Http\Requests\Admin\StoreEpoxyColorRequest;
use App\Http\Requests\Admin\UpdateEpoxyColorRequest;
use Illuminate\Http\Request;

class EpoxyFillerColorController extends Controller
{
    /**
     * Display a listing of epoxy filler colors.
     */
    public function index(Request $request)
    {
        $query = EpoxyFillerColor::with(['creator', 'updater']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $colors = $query->latest()->paginate(10)->withQueryString();

        return view('admin.epoxy_colors.index', compact('colors'));
    }

    /**
     * Show the form for creating a new color.
     */
    public function create()
    {
        return view('admin.epoxy_colors.create');
    }

    /**
     * Store a newly created color in storage.
     */
    public function store(StoreEpoxyColorRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        EpoxyFillerColor::create($data);

        return redirect()->route('admin.epoxy-colors.index')
            ->with('success', 'Epoxy Filler Color created successfully.');
    }

    /**
     * Show the form for editing the specified color.
     */
    public function edit(EpoxyFillerColor $epoxy_color)
    {
        $color = $epoxy_color;
        return view('admin.epoxy_colors.edit', compact('color'));
    }

    /**
     * Update the specified color in storage.
     */
    public function update(UpdateEpoxyColorRequest $request, EpoxyFillerColor $epoxy_color)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['updated_by'] = auth()->id();

        $epoxy_color->update($data);

        return redirect()->route('admin.epoxy-colors.index')
            ->with('success', 'Epoxy Filler Color updated successfully.');
    }

    /**
     * Remove the specified color from storage.
     */
    public function destroy(EpoxyFillerColor $epoxy_color)
    {
        try {
            $epoxy_color->delete();
            return redirect()->route('admin.epoxy-colors.index')
                ->with('success', 'Epoxy Filler Color deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.epoxy-colors.index')
                ->with('error', 'Cannot delete this color as it is linked to other records.');
        }
    }
}
