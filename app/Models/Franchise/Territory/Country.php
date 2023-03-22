<?php

namespace App\Models\Franchise\Territory;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'franchise_country';

    protected $fillable = [
        'id',
        'iso_num',
        'iso_2',
        'iso_3',
    ];

    public function i18n()
    {
        return $this->hasMany(CountryI18n::class, 'country_id', 'id');
    }
}
