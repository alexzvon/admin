<?php

namespace App\Models\Shop\Price;

use MosseboShopCore\Models\Shop\Price\Price as BasePrice;
use App\Models\Shop\PriceType\PriceType;
use App\Models\Shop\Product\Product;
use App\Models\Shop\Currency\Currency;

class Price extends BasePrice
{
    public function type()
    {
        return $this->hasOne(PriceType::class, 'price_type_id');
    }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'item');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }
}