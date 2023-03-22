<?php

namespace App\Models\Shop\Promo;

use MosseboShopCore\Models\Shop\Promo\PromoCondition as BasePromoCondition;

class PromoCondition extends BasePromoCondition
{
    public function code()
    {
        return $this->hasOne(PromoCode::class, 'id', 'promo_code_id');
    }
}