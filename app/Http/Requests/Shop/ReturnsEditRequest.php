<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class ReturnsEditRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $input = $this->all();
        $input['return_id'] = isset($this->return_id) ? (int)$this->return_id : 0;
        $input['product_id'] = isset($this->product_id) ? (int)$this->product_id : 0;
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
            'return_id' => 'required|integer|gt:0',
            'product_id' => 'required|integer|gt:0',
        ];
    }
}
