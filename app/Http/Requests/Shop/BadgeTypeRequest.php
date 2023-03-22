<?php

namespace App\Http\Requests\Shop;

use App\Http\Requests\ApiRequest;

class BadgeTypeRequest extends ApiRequest
{
    protected $model = \App\Models\Shop\Badge\BadgeType::class;
    protected $permissionsNamespace = 'shop.badges';

    protected $withoutRulesPathes = [
        '*/sort'
    ];

    protected function getEntityRules()
    {
        $rules = [
            'icon'      => 'required|max:255',
            'color'     => 'required|max:255',
            'has_value' => 'boolean',
        ];

        foreach (\Languages::enabled() as $language) {
            $rules["i18n.{$language['code']}"]       = "required|array";
            $rules["i18n.{$language['code']}.title"] = 'bail|required|max:255';
        }

        return $rules;
    }
}