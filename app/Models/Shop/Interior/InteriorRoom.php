<?php

namespace App\Models\Shop\Interior;

use MosseboShopCore\Models\Shop\Interior\InteriorRoom as BaseInteriorRoom;

class InteriorRoom extends BaseInteriorRoom
{
    public function interior()
    {
        return $this->hasOne(Interior::class, 'id', 'interior_id');
    }
}
