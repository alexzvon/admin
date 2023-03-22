<?php

namespace App\Models\Franchise\Territory;

use Illuminate\Database\Eloquent\Model;

class CitiesI18n extends Model
{
    protected $table = 'franchise_cities_i18n';

    protected $fillable = ['cities_id', 'language_code', 'title'];

    protected $primaryKey = 'cities_id';

}
