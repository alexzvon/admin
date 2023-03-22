<?php

namespace App\Models\Shop\Badge;

use MosseboShopCore\Models\Shop\Badge\Badge as BaseBadge;

class Badge extends BaseBadge
{
    public function type()
    {
        return $this->hasOne(BadgeType::class, 'id', 'badge_type_id');
    }
}