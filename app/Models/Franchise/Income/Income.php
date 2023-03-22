<?php

namespace App\Models\Franchise\Income;

use Illuminate\Database\Eloquent\Model;
use App\Models\Franchise\Companies\Companies;

class Income extends Model
{
    protected $table = 'franchise_income';

    protected $fillable = ['id', 'percent', 'type', 'status'];

    public function i18n()
    {
        return $this->hasMany(IncomeI18n::class, 'income_id', 'id');
    }

    public function partners()
    {
        return $this->belongsToMany(Companies::class, 'franchise_companies_income', 'income_id', 'companies_id')
            ->withPivot(['type_income', 'percent']);
    }

    public function scopeStatus($query)
    {
        return $query->where('status', true);
    }
}
