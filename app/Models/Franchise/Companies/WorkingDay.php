<?php
namespace App\Models\Franchise\Companies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkingDay extends Model
{

    protected $fillable = [
        'franchise_company_id',
        'finished_at',
        'day',
    ];

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'franchise_company_id', 'id');
    }
}
