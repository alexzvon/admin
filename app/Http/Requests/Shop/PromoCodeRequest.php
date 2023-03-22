<?php

namespace App\Http\Requests\Shop;

use App\Http\Requests\ApiRequest;

use Illuminate\Validation\Rule;
use Currencies;
use Languages;
use App\Support\Traits\Requests\HasDates;

class PromoCodeRequest extends ApiRequest
{
    use HasDates;

    protected $model = \App\Models\Shop\Room\Room::class;
    protected $permissionsNamespace = 'shop.promo-codes';
    protected $currencyCodes;

    public function __construct()
    {
        parent::__construct();

        $this->currencyCodes = Currencies::enabled()->map(function($currency) {
            return $currency->code;
        });
    }

    protected function prepareForValidation()
    {
        $this->json->set('name', strtoupper($this->json->get('name')));

        $this->prepareDate('date_start');
        $this->prepareDate('date_finish');
    }

    protected function getEntityRules()
    {
        $tableName = \Config::get('tables.PromoCodes');

        $quantityCheck =function($attribute, $value, $fail) {
            if ($value === 0) {
                return $fail('Укажите количество использований промокода');
            }
        };

        $rules = [
            'name'              => ['bail', 'trim', 'required', 'between:3,255'],
            'enabled'           => 'bail|required|boolean',
            'quantity'          => [
                'bail', 'integer', 'nullable', $quantityCheck
            ],
            'quantity_per_user' => [
                'bail', 'integer', 'nullable', $quantityCheck
            ],
        ];

        if ($this->isStore()) {
            $rules['name'][] = Rule::unique($tableName, 'name');
        }
        else {
            $rules['name'][] = Rule::unique($tableName, 'name')->ignore($this->getId());
        }

        $amount = $this->input('amount');
        $currencyCode = $this->input('currency_code');

        if ($amount || $currencyCode) {
            $maxPercent = config('shop.promo.discount.amount.max_percent');

            $rules['currency_code'] = [
                'bail', 'required',
                Rule::in($this->currencyCodes)
            ];

            $this->_setPriceRule(
                $rules,
                'amount',
                $currencyCode
            );

            $rules['percent'] = 'bail|required|integer|between:1,' . $maxPercent;
        }
        else {
            $maxPercent = config('shop.promo.discount.percent.max_percent');

            $rules['percent'] = 'bail|required|integer|between:1,' . $maxPercent;
        }

        $dateStartExist = !! $this->input('date_start');
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

        $conditions = $this->input('conditions');

        if (empty($conditions)) {
            return $rules;
        }

        $existingConditionTypes = config('shop.promo.conditions.types');

        foreach ($existingConditionTypes as $conditionName) {
            if (! isset($conditions[$conditionName])) {
                continue;
            }

            $methodName = str_replace('_', '', ucwords($conditionName, '_'));

            $methodName = '_set' . $methodName . 'Rules';

            if (method_exists($this, $methodName)) {
                call_user_func_array([$this, $methodName], [& $rules]);
            }
        }

        foreach (Languages::enabled() as $language) {
            $rules["i18n.{$language['code']}"]             = "required|array";
            $rules["i18n.{$language['code']}.title"]       = 'bail|required|max:255';
            $rules["i18n.{$language['code']}.description"] = 'max:65000';
        }

        return $rules;
    }

    public function _setMinSummRules(& $rules)
    {
        $rules['conditions.min_summ.currency_code'] = [
            'bail', 'required',
            Rule::in($this->currencyCodes)
        ];

        $this->_setPriceRule(
            $rules,
            'conditions.min_summ.value',
            $this->input('conditions.min_summ.currency_code')
        );
    }

    public function _setProductExpensiveRules(& $rules)
    {
        $rules['conditions.product_expensive.currency_code'] = [
            'bail', 'required',
            Rule::in($this->currencyCodes)
        ];

        $this->_setPriceRule(
            $rules,
            'conditions.product_expensive.value',
            $this->input('conditions.product_expensive.currency_code')
        );
    }

    public function _setProductsQuantityRules(& $rules)
    {
        $rules['conditions.products_quantity.value'] = 'bail|integer|min:1|max:2147483647';
    }

    protected function _setPriceRule(& $rules, $attribute, $currencyCode)
    {
        $currency = \Currencies::enabled()
            ->where('code', $currencyCode)
            ->first();

        if (! $currency) {
            return;
        }

        $rules[$attribute] = [
            'bail', 'required', 'integer', 'min:1',
            'max:' . floor($currency->getMaxPriceValue()),
        ];
    }
}