<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEpoxyComponentRequest extends FormRequest
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
            'brand_id' => ['nullable', Rule::exists('brands', 'id')->where('is_active', true)],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:epoxy_components,code'],
            'category' => ['required', 'string', 'in:Bottle,Pouch,Packet,Liquid,Powder,Plastic,Accessory,Other'],
            'purpose' => ['required', 'string', 'in:Assembly Component,Direct Finished Product'],
            'unit_id' => ['required', 'exists:units,id'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'parent_component_id' => ['nullable', 'exists:epoxy_components,id'],
            'epoxy_filler_color_id' => ['nullable', 'exists:epoxy_filler_colors,id'],
        ];
    }
}
