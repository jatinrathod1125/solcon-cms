<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComponentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ComponentCategoryController extends Controller
{
    public function index()
    {
        $categories = ComponentCategory::withCount('components')
            ->ordered()
            ->paginate(20);

        return view('admin.component_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.component_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:component_categories,slug',
            'default_unit' => 'required|string|max:50',
            'column_no' => 'required|integer|in:1,2,3,4',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['display_order'] = $validated['display_order'] ?? 0;

        ComponentCategory::create($validated);

        return redirect()->route('admin.component-categories.index')->with('success', 'Component category created successfully.');
    }

    public function edit(ComponentCategory $componentCategory)
    {
        return view('admin.component_categories.edit', compact('componentCategory'));
    }

    public function update(Request $request, ComponentCategory $componentCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:component_categories,slug,' . $componentCategory->id,
            'default_unit' => 'required|string|max:50',
            'column_no' => 'required|integer|in:1,2,3,4',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['display_order'] = $validated['display_order'] ?? 0;

        $componentCategory->update($validated);

        return redirect()->route('admin.component-categories.index')->with('success', 'Component category updated successfully.');
    }

    public function destroy(ComponentCategory $componentCategory)
    {
        if ($componentCategory->components()->exists()) {
            return back()->with('error', 'Cannot delete this category because components are currently assigned to it. Please reassign or remove the components first.');
        }

        $componentCategory->delete();

        return redirect()->route('admin.component-categories.index')->with('success', 'Component category deleted successfully.');
    }
}
