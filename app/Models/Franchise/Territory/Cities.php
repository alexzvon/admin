<?php

namespace App\Models\Franchise\Territory;

use Illuminate\Database\Eloquent\Model;

use App\Models\Country;
use App\Models\CountryI18n;
use App\Models\Region;
use App\Models\City;

class Cities extends Model
{
    protected $table = 'franchise_cities';

    protected $fillable = [
        'id',
        'country_code',
        'regions_id',
        'regions_parent_id',
        'cities_id',
        'gmt_id',
        'percent',
        'status'
    ];

    /**
     * Страна
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function country()
    {
        return $this->hasOne(Country::class, 'code', 'country_code');
    }

    public function countryI18n()
    {
        return $this->hasOne(CountryI18n::class, 'country_code', 'country_code');
    }

    /**
     * Область, Край, Республика
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function region()
    {
        return $this->hasOne(Region::class, 'id', 'regions_id');
    }

    /**
     * Район
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function district()
    {
        return $this->hasOne(Region::class, 'id', 'regions_parent_id');
    }

    /**
     * Субъект
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'cities_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function gmt()
    {
        return $this->hasOne(Gmt::class, 'id', 'gmt_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function i18n()
    {
        return $this->hasMany(CitiesI18n::class, 'cities_id', 'id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeStatus($query)
    {
        return $query->where('status', true);
    }
}
