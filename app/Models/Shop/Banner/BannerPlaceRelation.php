<?php

namespace App\Models\Shop\Banner;

use MosseboShopCore\Models\Shop\Banner\BannerPlaceRelation as BaseBannerPlaceRelation;

class BannerPlaceRelation extends BaseBannerPlaceRelation
{
    public function banner()
    {
        return $this->hasOne(Banner::class, 'id', 'banner_id');
    }

    public function place()
    {
        return $this->hasOne(BannerPlace::class, 'id', 'place_id');
    }
}
