<?php

namespace App\Models\Shop\Product;

use MosseboShopCore\Models\Shop\Product\ProductAttribute as BaseProductAttribute;
use App\Models\Shop\Attribute\Attribute;

class ProductAttribute extends BaseProductAttribute
{
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}