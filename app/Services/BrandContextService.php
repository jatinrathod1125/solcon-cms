<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Collection;

class BrandContextService
{
    /**
     * Get the currently selected brand from session.
     * Defaults to Solcon if no brand is selected.
     */
    public function current(): Brand
    {
        $user = auth()->user();
        if (!$user) {
            return $this->defaultBrand();
        }

        $sessionKey = 'current_brand_id_' . $user->id;
        $brandId = session($sessionKey);

        if ($brandId) {
            $brand = Brand::active()->find($brandId);
            if ($brand) {
                return $brand;
            }
        }

        // Default to Solcon
        $default = $this->defaultBrand();
        session([$sessionKey => $default->id]);
        return $default;
    }

    /**
     * Switch to a different brand.
     */
    public function switch(Brand $brand): void
    {
        $user = auth()->user();
        if (!$user) {
            return;
        }

        if (!$brand->is_active) {
            throw new \Exception('Cannot switch to an inactive brand.');
        }

        $sessionKey = 'current_brand_id_' . $user->id;
        session([$sessionKey => $brand->id]);
    }

    /**
     * Get all active brands.
     */
    public function available(): Collection
    {
        return Brand::active()->orderBy('name')->get();
    }

    /**
     * Get the default brand (first active brand in database).
     */
    public function defaultBrand(): Brand
    {
        return Brand::active()->first() ?? Brand::firstOrFail();
    }
}
