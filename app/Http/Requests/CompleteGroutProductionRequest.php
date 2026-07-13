<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteGroutProductionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'finished_bags' => ['required', 'integer', 'min:1'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'finished_bags.required' => 'Please enter the finished bag quantity before completing.',
            'finished_bags.integer' => 'Finished bag quantity must be a whole number.',
            'finished_bags.min' => 'Finished bag quantity must be at least 1.',
        ];
    }
}
