<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BagSize;
use App\Http\Requests\Admin\StoreBagSizeRequest;
use App\Http\Requests\Admin\UpdateBagSizeRequest;
use Illuminate\Http\Request;

class BagSizeController extends Controller
{
    /**
     * Display a listing of bag sizes.
     */
    public function index(Request $request)
    {
        $query = BagSize::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $bagSizes = $query->latest()->paginate(10)->withQueryString();

        return view('admin.bag_sizes.index', compact('bagSizes'));
    }

    /**
     * Show the form for creating a new bag size.
     */
    public function create()
    {
        return view('admin.bag_sizes.create');
    }

    /**
     * Store a newly created bag size in storage.
     */
    public function store(StoreBagSizeRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        BagSize::create($data);

        return redirect()->route('admin.bag-sizes.index')
            ->with('success', 'Bag size created successfully.');
    }

    /**
     * Show the form for editing the bag size.
     */
    public function edit(BagSize $bagSize)
    {
        return view('admin.bag_sizes.edit', compact('bagSize'));
    }

    /**
     * Update the bag size in storage.
     */
    public function update(UpdateBagSizeRequest $request, BagSize $bagSize)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $bagSize->update($data);

        return redirect()->route('admin.bag-sizes.index')
            ->with('success', 'Bag size updated successfully.');
    }

    /**
     * Remove the bag size from storage.
     */
    public function destroy(BagSize $bagSize)
    {
        $bagSize->delete();

        return redirect()->route('admin.bag-sizes.index')
            ->with('success', 'Bag size deleted successfully.');
    }
}
