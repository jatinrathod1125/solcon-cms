<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\EpoxyProduct;
use App\Http\Requests\Admin\StoreEpoxyProductRequest;
use App\Http\Requests\Admin\UpdateEpoxyProductRequest;
use Illuminate\Http\Request;

class EpoxyProductController extends Controller
{
    public function index(Request $request)
    {
        $query = EpoxyProduct::with('brand')->withCount('formulas');

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

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query->orderBy('name')->paginate(15)->withQueryString();
        $brands = Brand::active()->orderBy('name')->get();

        return view('admin.epoxy_products.index', compact('products', 'brands'));
    }

    public function create()
    {
        $brands = Brand::active()->orderBy('name')->get();
        return view('admin.epoxy_products.create', compact('brands'));
    }

    public function store(StoreEpoxyProductRequest $request)
    {
        $data = $request->validated();
        $data['code'] = strtoupper($data['code']);
        $data['requires_color'] = $request->boolean('requires_color');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['created_by'] = auth()->id();

        EpoxyProduct::create($data);

        return redirect()->route('admin.epoxy-products.index')->with('success', 'Epoxy Product created successfully.');
    }

    public function edit(EpoxyProduct $epoxyProduct)
    {
        $brands = Brand::active()->orderBy('name')->get();
        return view('admin.epoxy_products.edit', compact('epoxyProduct', 'brands'));
    }

    public function update(UpdateEpoxyProductRequest $request, EpoxyProduct $epoxyProduct)
    {
        $data = $request->validated();
        $data['code'] = strtoupper($data['code']);
        $data['requires_color'] = $request->boolean('requires_color');
        $data['is_active'] = $request->boolean('is_active');
        $data['updated_by'] = auth()->id();

        $epoxyProduct->update($data);

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
