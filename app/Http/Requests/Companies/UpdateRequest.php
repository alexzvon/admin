<?php

namespace App\Http\Requests\Companies;

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
            'input_rach_account' => 'required|max:250',
            'input_trrc' => 'required|max:250',
            'input_tin' => 'required|max:250',
            'input_kor_account' => 'required|max:250',
            'input_bik' => 'required|max:250',
            'input_bank' => 'required|max:250',
            'input_phone' => 'required|max:250',
            'input_email' => 'required|email',
            'input_address' => 'required|max:250',
            'input_percent' => 'numeric',
            'input_cities' => 'required|integer|gt:0',
            'input_fio' => 'required|max:250',
            'input_user' => 'required|integer|gt:0',
            'input_status' => 'required',
            'input_income' => 'required|array',
        ];
    }
}
