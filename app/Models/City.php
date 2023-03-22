<?php

namespace App\Models;

use MosseboShopCore\Models\City as BaseCity;

class City extends BaseCity
{
    public function region()
    {
        return $this->hasOne(Region::class, 'id', 'region_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'code', 'countries_code');
    }

    public function countryI18n()
    {
        return $this->hasOne(CountryI18n::class, 'country_code', 'countries_code');
    }
}
