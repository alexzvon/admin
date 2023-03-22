<?php

namespace App\Http\Requests\Shop\QuickFilter;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'category' => 'required|integer|gt:0',
            'name' => 'required|max:250',
            'displayed_name' => 'nullable|string|max:250',
            'address' => 'required|max:1000',
            'file' => 'required|file',
            'slug' => 'required|string|unique:shop_quick_filter',
        ];
    }
}
