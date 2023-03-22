<?php

namespace App\Models\Shop\Banner;

use MosseboShopCore\Models\Shop\Banner\BannerPlace as BaseBannerPlace;

class BannerPlace extends BaseBannerPlace
{
    public function banners()
    {
        return $this->hasManyThrough(
            Banner::class, BannerPlaceRelation::class,
            'place_id', 'id', 'id', 'banner_id'
        );
    }
}
