<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EpoxyProduct;
use Illuminate\Http\Request;

class EpoxyProductController extends Controller
{
    public function index()
    {
        $products = EpoxyProduct::withCount('formulas')->get();
        return view('admin.epoxy_products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.epoxy_products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:epoxy_products,code|max:50',
            'requires_color' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        EpoxyProduct::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'requires_color' => $request->has('requires_color'),
            'is_active' => $request->has('is_active'),
            'description' => $request->description,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.epoxy-products.index')->with('success', 'Epoxy Product created successfully.');
    }

    public function edit(EpoxyProduct $epoxyProduct)
    {
        return view('admin.epoxy_products.edit', compact('epoxyProduct'));
    }

    public function update(Request $request, EpoxyProduct $epoxyProduct)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:epoxy_products,code,' . $epoxyProduct->id,
            'requires_color' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $epoxyProduct->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'requires_color' => $request->has('requires_color'),
            'is_active' => $request->has('is_active'),
            'description' => $request->description,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.epoxy-products.index')->with('success', 'Epoxy Product updated successfully.');
    }

    public function destroy(EpoxyProduct $epoxyProduct)
    {
        if ($epoxyProduct->formulas()->exists()) {
            return back()->with('error', 'Cannot delete product: formulas exist for this product.');
        }

        $epoxyProduct->delete();
        return redirect()->route('admin.epoxy-products.index')->with('success', 'Epoxy Product deleted successfully.');
    }
}
