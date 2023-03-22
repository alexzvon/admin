<?php

declare(strict_types=1);

namespace App\Models\Shop\FastSale;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FastSale
 *
 * @package App\Models\Shop\FastSale
 */
class FastSale extends Model
{
    use SoftDeletes;

    protected $table = 'shop_fast_sale';

    protected $fillable = [
        'time_in_minutes',
        'percent_of_discount',
        'is_enabled',
    ];
}
