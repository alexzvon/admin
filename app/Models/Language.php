<?php

namespace App\Models;

use MosseboShopCore\Models\Language as BaseLanguage;
use App\Models\Shop\Currency\Currency;


class Language extends BaseLanguage
{
    public function currency()
    {
        return $this
          ->hasOne(Currency::class, 'code', 'currency_code');
    }
}
