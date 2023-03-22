<?php

namespace App\Http\Requests\Shop;

use App\Http\Requests\ApiRequest;
use App\Models\Shop\Attribute\AttributeOption;

class AttributeRequest extends ApiRequest
{
    protected $model = \App\Models\Shop\Attribute\Attribute::class;
    protected $permissionsNamespace = 'shop.attributes';

    protected $withoutRulesPathes = [
        '*/sort',
        '*/options'
    ];

    protected function getEntityRules()
    {
        $rules = [
            'enabled' => 'boolean',
            'selectable' => 'nullable|boolean',
            'layout_class' => 'nullable|max:255'
        ];

        foreach (\Languages::enabled() as $language) {
            $rules["i18n.{$language['code']}"]       = "required|array";
            $rules["i18n.{$language['code']}.title"] = 'bail|required|max:255';
        }

        foreach ($this->input('options') as $optionId => $option) {
            if ($this->isUpdate()) {
                $existingAttributeOptionsIds = array_column(AttributeOption::where('attribute_id', $this->getId())->get(['id'])->toArray(), 'id');
            }
            else {
                $existingAttributeOptionsIds = [];
            }

            $rules["options.{$optionId}"] = [
                function($attribute, $optionData, $fail) use($optionId, $existingAttributeOptionsIds) {
                    $optionRules = [
                        "options.{$optionId}.position" => 'integer',
                        "options.{$optionId}.enabled" => 'boolean',
                    ];

                    if (isset($optionData['isNew']) && $optionData['isNew']) {
                        foreach (\Languages::enabled() as $language) {
                            $rules["options.{$optionId}.i18n.{$language['code']}.value"] = 'bail|required|max:255';
                        }
                    }
                    else {
                        if (!in_array($optionId, $existingAttributeOptionsIds)) {
                            return $fail(\Lang::get("validation.attributes.options.exists", ['attribute' => "#{$optionId}"]));
                        }
                    }

                    $validator = \Validator::make($this->all(), $optionRules);

                    if ($validator->fails()) {
                        foreach ($validator->errors()->messages() as $fieldName => $messages) {
                            return $fail($messages[0]);
                        }
                    }
                }
            ];
        }

        return $rules;
    }
}
