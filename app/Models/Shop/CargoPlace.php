<?php

declare(strict_types=1);

namespace App\Models\Shop;

use App\Models\Shop\Product\Product;
use MosseboShopCore\Models\Base\BaseModel;

/**
 * Class CargoPlace
 *
 * @package App\Models\Shop
 */
class CargoPlace extends BaseModel
{
    protected $tableKey = 'CargoPlaces';

    protected $fillable = [
        'width',
        'height',
        'length',
        'weight',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
