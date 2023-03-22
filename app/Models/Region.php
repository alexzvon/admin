<?php

namespace App\Models;

use MosseboShopCore\Models\Region as BaseRegion;

class Region extends BaseRegion
{
    protected $fillable = [
        'country_code',
        'name',
        'short_name',
        'aoguid',

        'area_code',
        'region_code',
        'place_code',
        'city_code',

        'parent_id',
        'enabled',
    ];

    public function country()
    {
        return $this->hasOne(Country::class, 'code', 'country_code');
    }

    public function countryI18n()
    {
        return $this->hasOne(CountryI18n::class, 'country_code', 'country_code');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'region_id', 'id');
    }

    public function district()
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

}
