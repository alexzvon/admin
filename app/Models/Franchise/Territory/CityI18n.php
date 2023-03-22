<?php

namespace App\Models\Franchise\Territory;

use Illuminate\Database\Eloquent\Model;

class CityI18n extends Model
{
    protected $table = 'franchise_city_i18n';

    protected $fillable = ['city_id', 'language_code', 'title'];

    protected $primaryKey = 'city_id';
}
