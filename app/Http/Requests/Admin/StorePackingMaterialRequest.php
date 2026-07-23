<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePackingMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:packing_material_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', 'unique:packing_materials,code'],
            'size' => ['nullable', 'string', 'max:100'],
            'unit_id' => ['required', 'exists:units,id'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'opening_stock' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
