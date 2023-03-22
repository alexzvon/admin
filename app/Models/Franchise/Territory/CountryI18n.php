<?php

namespace App\Models\Franchise\Territory;

use Illuminate\Database\Eloquent\Model;

class CountryI18n extends Model
{
    protected $table = 'franchise_country_i18n';

    protected $fillable = ['country_id', 'title'];

    protected $primaryKey = 'country_id';
}
