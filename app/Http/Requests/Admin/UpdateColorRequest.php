<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateColorRequest extends FormRequest
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
        $color = $this->route('grout_color');
        $colorId = is_object($color) ? $color->id : $color;

        return [
            'department_id' => ['required', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:colors,code,' . $colorId],
            'packing_size' => ['required', 'in:500 GM,1 KG'],
            'default_cement' => ['required', 'in:White Cement,Grey Cement'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ];
    }
}
