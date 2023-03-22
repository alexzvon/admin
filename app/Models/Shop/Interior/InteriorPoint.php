<?php

namespace App\Models\Shop\Interior;

use MosseboShopCore\Models\Shop\Interior\InteriorPoint as BaseInteriorPoint;
use App\Models\Shop\Product\Product;

class InteriorPoint extends BaseInteriorPoint
{
    public function interior()
    {
        return $this->hasOne(Interior::class, 'id', 'interior_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}