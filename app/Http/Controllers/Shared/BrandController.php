<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\BrandContextService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Switch the active brand for the current session.
     */
    public function switch(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|exists:brands,id',
        ]);

        $brand = Brand::findOrFail($request->brand_id);

        try {
            app(BrandContextService::class)->switch($brand);
            return redirect()->back()->with('success', "Switched to {$brand->name}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
