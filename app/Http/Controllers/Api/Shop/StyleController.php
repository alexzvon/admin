<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Api\ApiController;

use Styles;

use App\Models\Shop\Style\Style;

use App\Http\Resources\Shop\Style\StyleEditResource;
use App\Http\Resources\Shop\Style\StyleTableResource;

use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Updatable;
use App\Support\Traits\Controllers\Deleteable;
use App\Support\Traits\Controllers\StatusChangeable;
use App\Support\Traits\Controllers\PositionChangeable;
use App\Support\Traits\Controllers\Sluggable;
use App\Support\Traits\Controllers\ImageUploadable;


class StyleController extends ApiController
{
    use Creatable, Updatable, Deleteable, StatusChangeable, Sluggable, PositionChangeable, ImageUploadable;

    protected static $modelClass = Style::class;
    protected static $entityName = 'style';
    protected static $editResource = StyleEditResource::class;
    protected static $tableResource = StyleTableResource::class;

    public function index()
    {
        return [
            'styles' => static::toResource(Styles::getCollection(['i18n', 'productCount'])),
        ];
    }
}