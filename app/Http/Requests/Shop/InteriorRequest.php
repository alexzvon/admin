<?php

namespace App\Http\Requests\Shop;

use App\Http\Requests\ApiRequest;
use App\Validation\ValidatorExtend;

class InteriorRequest extends ApiRequest
{
    protected $model = \App\Models\Shop\Interior\Interior::class;
    protected $permissionsNamespace = 'shop.interiors';

    protected function getEntityRules()
    {
        ValidatorExtend::manyRecordsExists();

        $rules = [
            'image'  => 'bail|required|max:255',
            'styles' => "nullable|many_records_exists:\App\Models\Shop\Style\Style",
            'rooms'  => "nullable|many_records_exists:\App\Models\Shop\Room\Room",
        ];

        foreach (\Languages::enabled() as $language) {
            $rules["i18n.{$language['code']}"]       = "required|array";
            $rules["i18n.{$language['code']}.title"] = 'bail|required|max:255';
        }

        $tableName = config('tables.Products');

        foreach ($this->input('points') as $key => $point) {
            $rules["points.{$key}.product_id"] = "exists:{$tableName},id";
            $rules["points.{$key}.position_x"] = "bail|required|min:0|max:100";
            $rules["points.{$key}.position_y"] = "bail|required|min:0|max:100";
            $rules["points.{$key}.is_similar"] = "boolean";
        }

        return $rules;
    }
}