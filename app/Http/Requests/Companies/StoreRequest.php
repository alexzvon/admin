<?php

namespace App\Http\Requests\Companies;

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
            'user' => 'required|integer|gt:0',
            'fio' => 'required|max:250',
            'cities' => 'required|integer|gt:0',
            'address' => 'required|max:250',
            'email' => 'required|email',
            'bank' => 'required|max:250',
            'bik' => 'required|max:250',
            'kor_account' => 'required|max:250',
            'tin' => 'required|max:250',
            'trrc' => 'required|max:250',
            'rach_account' => 'required|max:250',
            'file' => 'required|file',
        ];
    }
}
