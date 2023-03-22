<?php

namespace App\Repositories\Shop;

use Illuminate\Support\Collection;
use MosseboShopCore\Repositories\Shop\RoomRepository as BaseRoomRepository;
use App\Models\Shop\Room\Room;

class RoomRepository extends BaseRoomRepository
{
    public function getCollectionRaw(): Collection
    {
        return Room::with('i18n')
            ->withProductCount()
            ->with('image')
            ->get();
    }
}
