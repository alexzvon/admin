<?php

namespace App\Http\Requests\Shop;

use App\Http\Requests\ApiRequest;
use App\Support\Traits\Requests\HasDates;

class SaleRequest extends ApiRequest
{
    use HasDates;

    protected $model = \App\Models\Shop\Product\Sale::class;
    protected $permissionsNamespace = 'shop.sale';

    protected $withoutRulesPathes = [
        '*/sort'
    ];

    protected function prepareForValidation()
    {
        $this->prepareDate('date_start');
        $this->prepareDate('date_finish');
    }

    protected function getEntityRules()
    {
        $productTable = config('tables.Products');

        $rules = [
            'product_id' => "bail|required|integer|exists:{$productTable},id",
            'enabled'    => 'bail|required|boolean',
        ];

        $dateStartExist  = !! $this->input('date_start');
        $dateFinishExist = !! $this->input('date_finish');

        $checkDate = function ($attribute, $value, $fail) {
            return $this->checkDate($attribute, $value, $fail);
        };

        if ($dateStartExist) {
            $rules['date_start'] = [
                'bail', $checkDate
            ];
        }

        if ($dateFinishExist) {
            $rules['date_start'] = [
                'bail', $checkDate
            ];

            if ($dateStartExist) {
                $rules['date_start'][] = 'lt:date_finish';
            }
        }

        return $rules;
    }
}