<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRawMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rawMaterialId = $this->route('raw_material')?->id ?? $this->route('raw_material');

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:raw_materials,code,' . $rawMaterialId],
            'department_id' => ['required', 'exists:departments,id'],
            'stock_unit_id' => ['required', 'exists:units,id'],
            'purchase_unit_id' => ['nullable', 'exists:units,id'],
            'purchase_conversion' => ['nullable', 'numeric', 'min:0.0001'],
            'opening_stock' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'maximum_stock' => ['required', 'numeric', 'gte:minimum_stock'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
