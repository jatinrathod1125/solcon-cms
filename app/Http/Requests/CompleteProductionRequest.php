<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteProductionRequest extends FormRequest
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
            'output_bags' => ['required', 'numeric', 'min:0.0001'],
            'end_time' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string', 'max:500'],
            'split_breakdown' => ['nullable', 'array'],
            'split_breakdown.*.grade_id' => ['required_with:split_breakdown', 'integer'],
            'split_breakdown.*.bags' => ['required_with:split_breakdown', 'numeric', 'min:0.0001'],
            'split_breakdown.*.coupon_raw_material_id' => ['nullable'],
            'split_breakdown.*.packing_material_id' => ['nullable'],
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
            'output_bags.required' => 'The output bags field is required.',
            'output_bags.numeric' => 'The output bags must be a number.',
            'output_bags.min' => 'Produced bags must be greater than zero.',
            'end_time.required' => 'The end time field is required.',
            'end_time.date' => 'The end time must be a valid date and time.',
        ];
    }
}
