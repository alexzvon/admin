<?php
namespace App\Models\Franchise\Companies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;

use App\Models\Franchise\Territory\Cities;
use App\Models\Franchise\Income\Income;

class Companies extends Model implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;

    protected $table = 'franchise_companies';

    protected $fillable = [
        'id',
        'user_id',
        'city_id',
        'name',
        'delivery_address',
        'status',
        'address',
        'phone',
        'email',
        'bank_name',
        'bank_identification_code',
        'bank_correspondent_account',
        'taxpayer_identification_number',
        'tax_registration_reason_code',
        'account',
        'show_cashback',
        'retail_id',
    ];

    public function i18n()
    {
        return $this->hasMany(CompaniesI18n::class, 'companies_id', 'id');
    }

    public function cities()
    {
        return $this->hasOne(Cities::class, 'id', 'city_id');
    }

    public function income()
    {
        return $this->belongsToMany(Income::class, 'franchise_companies_income', 'companies_id', 'income_id')
            ->withPivot('percent');
    }

    /**
     * @return HasMany
     */
    public function workingDays(): HasMany
    {
        return $this->hasMany(WorkingDay::class, 'franchise_company_id', 'id');
    }

    public function scopeStatus($query)
    {
        return $query->where('status', true);
    }
}
