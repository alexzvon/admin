<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Api\ApiController;

use Rooms;

use App\Models\Shop\Room\Room;

use App\Http\Resources\Shop\Room\RoomEditResource;
use App\Http\Resources\Shop\Room\RoomTableResource;

use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Updatable;
use App\Support\Traits\Controllers\Deleteable;
use App\Support\Traits\Controllers\StatusChangeable;
use App\Support\Traits\Controllers\PositionChangeable;
use App\Support\Traits\Controllers\Sluggable;
use App\Support\Traits\Controllers\ImageUploadable;


class RoomController extends ApiController
{
    use Creatable, Updatable, Deleteable, StatusChangeable, Sluggable, PositionChangeable, ImageUploadable;

    protected static $modelClass = Room::class;
    protected static $entityName = 'room';
    protected static $editResource = RoomEditResource::class;
    protected static $tableResource = RoomTableResource::class;

    public function index()
    {
        return [
            'rooms' => static::toResource(Rooms::getCollection(['i18n', 'productCount'])),
        ];
    }
}