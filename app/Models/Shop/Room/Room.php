<?php

namespace App\Models\Shop\Room;

use App\Support\Traits\Models\StructureTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use MosseboShopCore\Models\Shop\Room\Room as BaseRoom;
use App\Models\Shop\Product\Product;
use App\Models\Shop\Product\ProductCount;

class Room extends BaseRoom implements HasMedia
{
    use StructureTrait;

    protected $mediaCollectionName = 'image';

    protected $needsToSaveFromRequest = [
        'i18n',
        'images'
    ];

    public function productsRelations()
    {
        return $this->hasMany(RoomProduct::class, $this->relationFieldName);
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class, RoomProduct::class,
            $this->relationFieldName, 'id', 'id', 'product_id'
        );
    }

    public function productCounts()
    {
        return $this->hasMany(ProductCount::class, $this->relationFieldName);
    }
}