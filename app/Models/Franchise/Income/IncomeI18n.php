<?php

namespace App\Models\Franchise\Income;

use Illuminate\Database\Eloquent\Model;

class IncomeI18n extends Model
{
    protected $table = 'franchise_income_i18n';

    protected $fillable = ['income_id', 'language_code', 'title'];

    protected $primaryKey = 'income_id';
}
