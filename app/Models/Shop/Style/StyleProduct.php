<?php

namespace App\Models\Shop\Style;

use MosseboShopCore\Models\Shop\Style\StyleProduct as BaseStyleProduct;
use App\Models\Shop\Product\Product;

class StyleProduct extends BaseStyleProduct
{
    public function style()
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
