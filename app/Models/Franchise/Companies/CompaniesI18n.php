<?php

namespace App\Models\Franchise\Companies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompaniesI18n extends Model
{
    use SoftDeletes;

    protected $table = 'franchise_companies_i18n';

    protected $fillable = ['companies_id', 'language_code', 'title'];

    protected $primaryKey = 'companies_id';
}
