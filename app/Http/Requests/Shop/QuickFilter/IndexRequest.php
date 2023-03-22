<?php

namespace App\Http\Requests\Shop\QuickFilter;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $input = $this->all();

        $input[ 'type' ] = isset($input[ 'type' ]) ? (int)$input[ 'type' ] : 0;
        $input[ 'sortDesc' ] = isset($input[ 'sortDesc' ]) ? (boolean)json_decode(strtolower($input[ 'sortDesc' ])) : false;
        $input[ 'sortType' ] = $input[ 'sortDesc' ] ? 'desc' : 'asc';

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
            'type' => 'required|integer',
            'sortDesc' => 'required|boolean',
            'sortType' => 'required',
            'sortBy' => 'required',
            'perPage' => 'required|numeric',
            'page' => 'required|numeric',
        ];
    }
}
