<?php

namespace App\Models\Shop\Interior;

use MosseboShopCore\Models\Shop\Interior\InteriorStyle as BaseInteriorStyle;

class InteriorStyle extends BaseInteriorStyle
{
    public function interior()
    {
        return $this->hasOne(Interior::class, 'id', 'interior_id');
    }
}
