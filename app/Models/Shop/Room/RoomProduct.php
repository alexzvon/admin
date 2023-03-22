<?php

namespace App\Models\Shop\Room;

use MosseboShopCore\Models\Shop\Room\RoomProduct as BaseRoomProduct;
use App\Models\Shop\Product\Product;

class RoomProduct extends BaseRoomProduct
{
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
