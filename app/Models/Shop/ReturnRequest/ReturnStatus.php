<?php

declare(strict_types=1);

namespace App\Models\Shop\ReturnRequest;

use Illuminate\Database\Eloquent\Relations\HasMany;
use MosseboShopCore\Models\Base\BaseModel;
use MosseboShopCore\Support\Traits\Models\Shop\HasI18n;

/**
 * Class ReturnStatus
 *
 * @package App\Models\Shop\Return
 */
class ReturnStatus extends BaseModel
{
    use HasI18n;

    protected $tableKey = 'ReturnStatuses';
    protected $relationFieldName = 'return_status_id';

    public $timestamps = false;

    protected $fillable = [];

    /**
     * @return HasMany
     */
    public function returns(): HasMany
    {
        return $this->hasMany(ReturnStatus::class, 'status_id');
    }
}
