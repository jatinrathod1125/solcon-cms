<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroutFormulaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'color_id' => ['required', 'exists:colors,id'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.raw_material_id' => ['required', 'exists:raw_materials,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.0001'],
            'items.*.unit_id' => ['required', 'exists:units,id'],
            'items.*.mix_stage' => ['required', 'in:Stage 1,Stage 2'],
            'items.*.display_order' => ['nullable', 'integer'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'items.required' => 'At least one ingredient/raw material is required in the formula.',
            'items.array' => 'Formula items must be specified as an array.',
            'items.*.raw_material_id.required' => 'Each ingredient must have a raw material selected.',
            'items.*.raw_material_id.exists' => 'The selected raw material is invalid.',
            'items.*.quantity.required' => 'Each ingredient must have a quantity specified.',
            'items.*.quantity.numeric' => 'Quantity must be a valid number.',
            'items.*.quantity.min' => 'Quantity must be at least 0.0001.',
            'items.*.unit_id.required' => 'Each ingredient must have a unit specified.',
            'items.*.unit_id.exists' => 'The selected unit is invalid.',
            'items.*.mix_stage.required' => 'Each ingredient must have a mix stage specified.',
            'items.*.mix_stage.in' => 'The selected mix stage must be Stage 1 or Stage 2.',
        ];
    }
}
