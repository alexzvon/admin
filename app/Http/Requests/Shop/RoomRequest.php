<?php

namespace App\Http\Requests\Shop;

use App\Http\Requests\ApiRequest;

use Illuminate\Validation\Rule;
use App\Support\Traits\Requests\HasImages;

class RoomRequest extends ApiRequest
{
    use HasImages;

    protected $model = \App\Models\Shop\Room\Room::class;
    protected $permissionsNamespace = 'shop.rooms';

    protected $withoutRulesPathes = [
        '*/sort'
    ];

    protected function getEntityRules()
    {
        $tableName = \Config::get('tables.Rooms');

        $rules = [
            'enabled' => 'boolean',
            'slug' => ['bail', 'required', 'between:3,255']
        ];

        if ($this->isStore()) {
            $rules['slug'][] = Rule::unique($tableName);
        }
        else {
            $rules['slug'][] = Rule::unique($tableName)->ignore($this->getId());
        }

        foreach (\Languages::enabled() as $language) {
            $rules["i18n.{$language['code']}"]                  = "required|array";
            $rules["i18n.{$language['code']}.title"]            = 'bail|required|max:255';
            $rules["i18n.{$language['code']}.description"]      = 'max:65000';
            $rules["i18n.{$language['code']}.meta_title"]       = 'max:255';
            $rules["i18n.{$language['code']}.meta_description"] = 'max:65000';
        }

        $this->setImagesRule($rules);

        return $rules;
    }
}