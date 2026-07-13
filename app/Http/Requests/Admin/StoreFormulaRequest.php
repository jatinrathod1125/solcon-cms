<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormulaRequest extends FormRequest
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
        return [
            'grade_id' => ['required', 'exists:grades,id'],
            'remarks' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.raw_material_id' => ['required', 'exists:raw_materials,id', 'distinct'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.0001'],
            'items.*.unit_id' => ['required', 'exists:units,id'],
            'items.*.consumption_method' => ['nullable', 'in:formula,output'],
            'items.*.consumption_per_unit' => ['nullable', 'numeric', 'min:0.0001'],
            'items.*.sequence' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'items.*.raw_material_id.distinct' => 'Each raw material can only be added once to the formula.',
            'items.*.quantity.min' => 'Quantity must be greater than zero.',
        ];
    }
}
