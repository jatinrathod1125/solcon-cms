<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductionRequest extends FormRequest
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
        $user = $this->user();

        return [
            'machine_id' => [
                'required',
                Rule::exists('machines', 'id')->where(function ($query) use ($user) {
                    $query->where('is_active', true);
                    if ($user && $user->isSupervisor()) {
                        $query->where('department_id', $user->department_id);
                    }
                }),
            ],
            'grade_id' => [
                'required',
                Rule::exists('grades', 'id')->where(function ($query) use ($user) {
                    $query->where('is_active', true);
                    if ($user && $user->isSupervisor()) {
                        $query->where('department_id', $user->department_id);
                    }
                }),
            ],
            'remarks' => ['nullable', 'string', 'max:500'],
            'batch_no' => ['nullable', 'string', 'max:50', 'unique:production_batches,batch_no'],
            'coupon_raw_material_id' => ['nullable', 'exists:raw_materials,id'],
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
            'machine_id.exists' => 'The selected machine is invalid, inactive, or does not belong to your department.',
            'grade_id.exists' => 'The selected grade is invalid, inactive, or does not belong to your department.',
        ];
    }
}
