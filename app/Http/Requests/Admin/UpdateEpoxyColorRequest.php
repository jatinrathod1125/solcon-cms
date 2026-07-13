<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEpoxyColorRequest extends FormRequest
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
        $color = $this->route('epoxy_color');
        $colorId = is_object($color) ? $color->id : $color;

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:epoxy_filler_colors,code,' . $colorId],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ];
    }
}
