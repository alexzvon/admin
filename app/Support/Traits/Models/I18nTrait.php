<?php

namespace App\Support\Traits\Models;

trait I18nTrait
{
    protected function _saveI18n(Array $translates = [])
    {
        $this->i18n()->whereIn('language_code', array_keys($translates))->delete();

        $i18nModelClassName = $this->getI18nModelName();

        foreach ($translates as $languageCode => $i18nData) {
            $i18nData['language_code'] = $languageCode;
            $i18nData[$this->relationFieldName] = $this->id;
            (new $i18nModelClassName($i18nData))->save();
        }
    }
}