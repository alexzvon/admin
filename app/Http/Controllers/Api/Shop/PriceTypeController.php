<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Api\ApiController;

use PriceTypes;

use App\Models\Shop\PriceType\PriceType;

use App\Http\Resources\Shop\PriceType\PriceTypeEditResource;
use App\Http\Resources\Shop\PriceType\PriceTypesTableResource;

use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Updatable;
use App\Support\Traits\Controllers\Deleteable;
use App\Support\Traits\Controllers\PositionChangeable;
use App\Support\Traits\Controllers\StatusChangeable;


class PriceTypeController extends ApiController
{
    use Creatable, Updatable, Deleteable, StatusChangeable, PositionChangeable;

    protected static $modelClass = PriceType::class;
    protected static $entityName = 'price-type';
    protected static $editResource = PriceTypeEditResource::class;
    protected static $tableResource = PriceTypesTableResource::class;

    public function index()
    {
        return [
            'price-types' => static::toResource(PriceTypes::getCollection('i18n')),
        ];
    }
}