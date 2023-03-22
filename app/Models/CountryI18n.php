<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryI18n extends Model
{
    protected $table = 'countries_i18n';

    protected $fillable = ['country_code', 'language_code', 'name'];

    public $timestamps = false;
}
