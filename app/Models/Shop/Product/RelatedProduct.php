<?php

namespace App\Models\Shop\Product;

use MosseboShopCore\Models\Shop\Product\RelatedProduct as BaseRelatedProduct;

class RelatedProduct extends BaseRelatedProduct
{
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function related()
    {
        return $this->belongsTo(Product::class, 'related_id');
    }
}
