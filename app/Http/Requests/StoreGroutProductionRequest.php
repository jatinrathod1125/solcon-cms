<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroutProductionRequest extends FormRequest
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
            'machine_id' => ['required', 'exists:machines,id'],
            'color_id' => ['required', 'exists:colors,id'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'batch_no' => ['nullable', 'string', 'max:50', 'unique:grout_production_batches,batch_no'],
        ];
    }
}
