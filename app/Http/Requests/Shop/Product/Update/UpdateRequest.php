<?php

namespace App\Http\Requests\Shop\Product\Update;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'gtin' => 'required|max:32',
            'id_from_supplier' => 'required|max:200',
            'cargo_places' => 'required|array',
            'cargo_places.*.width' => 'required|integer|min:1|max:2147483647',
            'cargo_places.*.height' => 'required|integer|min:1|max:2147483647',
            'cargo_places.*.length' => 'required|integer|min:1|max:2147483647',
            'cargo_places.*.weight' => 'required|integer|min:1|max:2147483647',
        ];
    }
}
