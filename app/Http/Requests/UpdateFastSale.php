<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFastSale extends FormRequest
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
            'time_in_minutes'     => 'bail|required|between:1,9999|nullable',
            'percent_of_discount' => 'integer|min:1|nullable',
            'is_enabled'          => 'boolean',
        ];
    }
}
