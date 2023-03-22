<?php
namespace App\Http\Requests\Shop;

use App\Http\Requests\ApiRequest;

class ProductSearchRequest extends ApiRequest
{
    private $rule1;
    private $rule2;
    private $rule3;

    protected function prepareForValidation() {
        $input = $this->all();

        if ( isset($input[ 'selected' ]) && isset($input[ 'search']) && 0 < strlen($input[ 'search' ]) ) {
            if ('product' == $input[ 'selected' ]) {
                $input[ 'search' ] = (int)$input[ 'search' ];
                $this->rule1 = 'numeric';
                $this->rule2 = 'min:100000';
                $this->rule3 = 'max:999999';
            }
            else if ('supplier' == $input[ 'selected' ]) {
                $this->rule1 = 'alpha_num';
                $this->rule2 = 'min:3';
                $this->rule3 = 'max:50';
            }
            else {
                $this->rule1 = '';
                $this->rule2 = '';
                $this->rule3 = '';
            }
        }

        $this->replace($input);
    }

    public function rules()
    {
        return [
            'search' => [ $this->rule1 , $this->rule2, $this->rule3 ]
        ];
    }
}