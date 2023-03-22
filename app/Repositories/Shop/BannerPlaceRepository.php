<?php

namespace App\Repositories\Shop;

use Illuminate\Support\Collection;
use MosseboShopCore\Repositories\Shop\BannerPlaceRepository as BaseBannerPlaceRepository;
use App\Models\Shop\Banner\BannerPlace;

class BannerPlaceRepository extends BaseBannerPlaceRepository
{
    public function getCollectionRaw(): Collection
    {
        return BannerPlace::orderBy('position', 'asc')
            ->get();
    }
}
