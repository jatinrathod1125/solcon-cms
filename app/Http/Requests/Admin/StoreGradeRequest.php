<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGradeRequest extends FormRequest
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
            'brand_id' => ['nullable', Rule::exists('brands', 'id')->where('is_active', true)],
            'name' => ['required', 'string', 'max:255', 'unique:grades,name'],
            'code' => ['required', 'string', 'max:50', 'unique:grades,code'],
            'department_id' => ['required', 'exists:departments,id'],
            'bag_size_id' => ['required', 'exists:bag_sizes,id'],
            'output_unit_id' => ['required', 'exists:units,id'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
