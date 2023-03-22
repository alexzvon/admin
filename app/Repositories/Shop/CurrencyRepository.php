<?php

namespace App\Repositories\Shop;

use Illuminate\Support\Collection;
use MosseboShopCore\Repositories\Shop\CurrencyRepository as BaseCurrencyRepository;
use App\Models\Shop\Currency\Currency;

class CurrencyRepository extends BaseCurrencyRepository
{
    public function getCollectionRaw(): Collection
    {
        return Currency::orderBy('position', 'asc')
            ->get();
    }
}
