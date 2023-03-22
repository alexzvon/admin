<?php

namespace App\Repositories\Shop;

use Illuminate\Support\Collection;
use MosseboShopCore\Repositories\Shop\BadgeTypeRepository as BaseBadgeTypeRepository;
use App\Models\Shop\PriceType\PriceType;

class PriceTypeRepository extends BaseBadgeTypeRepository
{
    public function getCollectionRaw(): Collection
    {
        return PriceType::orderBy('position', 'asc')
            ->with('i18n')
            ->get();
    }
}
