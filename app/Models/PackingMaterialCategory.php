<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'name',
])]
class PackingMaterialCategory extends Model
{
    /**
     * Get the packing materials in this category.
     */
    public function packingMaterials(): HasMany
    {
        return $this->hasMany(PackingMaterial::class, 'category_id');
    }
}
