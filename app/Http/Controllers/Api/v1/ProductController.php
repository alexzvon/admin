<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\ApiProductsArray;
use App\Http\Requests\ApiRequest;
use App\Http\Resources\Shop\Product\Api\ProductEditResource;
use App\Models\Shop\Product\Product;
use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\ImageUploadable;
use App\Support\Traits\Controllers\Sluggable;
use App\Support\Traits\Controllers\StatusChangeable;
use App\Support\Traits\Controllers\Updatable;
use App\Support\Traits\ProductSearch;
use Config;

/**
 * Class ProductController
 *
 * @package App\Http\Controllers\Api\v1
 */
class ProductController extends ApiController
{
    use Creatable,
        Updatable,
        Sluggable,
        StatusChangeable,
        ImageUploadable,
        ProductSearch;

    protected static $modelClass = Product::class;
    protected static $entityName = 'product';
    protected static $editResource = ProductEditResource::class;
    protected static $tableResource = ProductEditResource::class;

    public function __construct(ApiRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @param ApiProductsArray $request
     *
     * @return array
     */
    public function find(ApiProductsArray $request)
    {
        $ids = $request->get('ids');

        $prods = [];

        if (!empty($ids)) {
            $prods = Product::whereIn('id', $ids)->get();
        }

        return [
            "products" => static::toResource($prods),
        ];
    }
}
