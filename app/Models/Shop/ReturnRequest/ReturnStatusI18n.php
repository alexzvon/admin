<?php

declare(strict_types=1);

namespace App\Models\Shop\ReturnRequest;

use MosseboShopCore\Models\Base\BaseModel;

/**
 * Class ReturnStatusI18n
 *
 * @package App\Models\Shop\Return
 */
class ReturnStatusI18n extends BaseModel
{
    protected $tableKey = 'ReturnStatusesI18n';

    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'return_status_id',
        'language_code',
        'name',
    ];

    public $timestamps = false;
}
