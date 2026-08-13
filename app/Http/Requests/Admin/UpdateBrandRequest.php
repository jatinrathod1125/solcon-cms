<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:brands,code,' . $this->route('brand')->id],
            'slug' => ['required', 'string', 'max:50', 'unique:brands,slug,' . $this->route('brand')->id],
        ];
    }
}
