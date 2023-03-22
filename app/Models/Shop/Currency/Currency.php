<?php

namespace App\Models\Shop\Currency;

use MosseboShopCore\Models\Shop\Currency\Currency as BaseCurrency;
use App\Models\Shop\Price\Price;

class Currency extends BaseCurrency
{
    public function prices()
    {
        return $this->hasMany(Price::class, 'currency_code');
    }
}