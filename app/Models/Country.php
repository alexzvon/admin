<?php

namespace App\Models;

use MosseboShopCore\Models\Country as BaseCountry;

use App\Models\Shop\Currency\Currency;

class Country extends BaseCountry
{
    public function cities()
    {
        return $this->hasMany(City::class, 'countries_code', 'code');
    }

    public function language()
    {
        return $this->hasOne(Language::class, 'code', 'language_code');
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'code', 'currency_code');
    }

    public function i18n()
    {
        return $this->hasOne(CountryI18n::class, 'country_code', 'code');
    }
}
