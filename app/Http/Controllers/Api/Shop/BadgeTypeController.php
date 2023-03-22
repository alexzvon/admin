<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Api\ApiController;

use BadgeTypes;

use App\Models\Shop\Badge\BadgeType;

use App\Http\Resources\Shop\Badge\BadgeTypeResource;

use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Updatable;
use App\Support\Traits\Controllers\Deleteable;
use App\Support\Traits\Controllers\PositionChangeable;

class BadgeTypeController extends ApiController
{
    use Creatable, Updatable, Deleteable, PositionChangeable;

    protected static $modelClass = BadgeType::class;
    protected static $entityName = 'badge-type';
    protected static $editResource = BadgeTypeResource::class;
    protected static $tableResource = BadgeTypeResource::class;

    public function index()
    {
        return [
            'badge-types' => static::toResource(BadgeTypes::getCollection('i18n')),
        ];
    }
}
