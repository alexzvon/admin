<?php

namespace App\Http\Requests\Shop;

use App\Http\Requests\ApiRequest;

use App\Validation\ValidatorExtend;
use App\Models\Shop\Attribute\Attribute;
use App\Models\Shop\Attribute\AttributeOption;
use App\Support\Traits\Requests\HasImages;

class ProductRequest extends ApiRequest
{
    use HasImages;

    protected $model = \App\Models\Shop\Product\Product::class;
    protected $permissionsNamespace = 'shop.products';

    protected function getEntityRules()
    {
        ValidatorExtend::manyRecordsExists();

        $suppliersTableName = \Config::get('tables.Suppliers');

        $rules = [
            'suppliers'    => "bail|required|array|exists:{$suppliersTableName},id",
            'enabled'     => 'boolean',
            'is_payable'  => 'boolean',
            'categories'  => "nullable|many_records_exists:\App\Models\Shop\Category\Category",
            'styles'      => "nullable|many_records_exists:\App\Models\Shop\Style\Style",
            'rooms'       => "nullable|many_records_exists:\App\Models\Shop\Room\Room",
            'related'     => [
                'nullable',
                'many_records_exists:\App\Models\Shop\Product\Product',

                function($attribute, $value, $fail) {
                    if (! is_array($value)) {
                        $value = [$value];
                    }

                    if (in_array($this->id, $value)) {
                        return $fail(trans("validation.self_id"));
                    }
                }
            ],
            'width'       => 'nullable|integer|min:0',
            'height'      => 'nullable|integer|min:0',
            'length'      => 'nullable|integer|min:0'
        ];

        foreach (\Languages::enabled() as $language) {
            $rules["i18n.{$language['code']}"]                  = "required|array";
            $rules["i18n.{$language['code']}.title"]            = 'bail|required|max:255';
            $rules["i18n.{$language['code']}.description"]      = 'max:65000';
            $rules["i18n.{$language['code']}.meta_title"]       = 'max:255';
            $rules["i18n.{$language['code']}.meta_description"] = 'max:65000';
        }

        foreach (\PriceTypes::enabled() as $priceType) {
            foreach (\Currencies::enabled() as $currency) {
                $rules["prices.{$priceType->id}.{$currency->code}"] = 'nullable|numeric|max:' . $currency->getMaxPriceValue();
            }
        }

        $this->setImagesRule($rules);

        return $rules;
    }
}