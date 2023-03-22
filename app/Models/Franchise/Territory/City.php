<?php

namespace App\Models\Franchise\Territory;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'franchise_city';

    protected $fillable = [
        'id',
        'country_id',
        'gmt_id',
        'percent',
        'status',
    ];

    public function gmt()
    {
        return $this->hasOne(Gmt::class, 'id', 'gmt_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function i18n()
    {
        return $this->hasMany(CityI18n::class, 'city_id', 'id');
    }

    public function scopeStatus($query)
    {
        return $query->where('status', true);
    }
}
