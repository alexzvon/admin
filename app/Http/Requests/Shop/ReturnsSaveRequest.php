<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class ReturnsSaveRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $input = $this->all();

        $input[ 'status_id' ] = isset($input[ 'status_id' ]) ? (int)$input[ 'status_id' ] : 0;
        $input[ 'return_id' ] = isset($input[ 'return_id' ]) ? (int)$input[ 'return_id' ] : 0;

        $this->replace($input);
    }

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
            'status_id' => 'required|integer',
            'return_id' => 'required|integer|gt:0',
        ];
    }
}
