<?php

namespace App\Repositories\Shop;

use Illuminate\Support\Collection;
use MosseboShopCore\Repositories\Shop\BadgeTypeRepository as BaseBadgeTypeRepository;
use App\Models\Shop\Badge\BadgeType;

class BadgeTypeRepository extends BaseBadgeTypeRepository
{
    public function getCollectionRaw(): Collection
    {
        return BadgeType::orderBy('position', 'asc')
            ->with('i18n')
            ->get();
    }
}
