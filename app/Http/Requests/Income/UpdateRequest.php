<?php

namespace App\Http\Requests\Income;

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
            'name' => 'required|max:250',
            'percent' => 'numeric',
            'status' => 'numeric'
        ];
    }
}
