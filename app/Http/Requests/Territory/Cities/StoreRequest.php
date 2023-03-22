<?php

namespace App\Http\Requests\Territory\Cities;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $arrRoles = [];

        foreach($this->user()->roles as $roles) {
            $arrRoles[] = $roles->id;
        }

        return in_array(1, $arrRoles) || in_array(2, $arrRoles);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:250',
            'country_code' => 'required|max:2',
            'regions_id' => 'required|integer|gte:0',
            'regions_parent_id' => 'required|integer|gte:0',
            'cities_id' => 'required|integer|gt:0',
            'gmt_id' => 'required|integer|gt:0',
            'percent' => 'integer|gte:0',
        ];
    }
}
