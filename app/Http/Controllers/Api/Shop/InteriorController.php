<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Api\ApiController;

use Illuminate\Http\Request;
use Config;

use App\Models\Shop\Interior\Interior;
use App\Models\Shop\Product\Product;

use App\Http\Resources\Shop\Interior\InteriorEditResource;
use App\Http\Resources\Shop\Interior\InteriorTableResource;

use App\Http\Resources\Shop\Product\ProductSearchResource;
use App\Http\Resources\Shop\Product\ProductInteriorResource;

use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Updatable;
use App\Support\Traits\Controllers\Deleteable;
use App\Support\Traits\Controllers\StatusChangeable;
use App\Support\Traits\ProductSearch;

use Illuminate\Pagination\LengthAwarePaginator;

class InteriorController extends ApiController
{
    use Creatable,
        Updatable,
        Deleteable,
        StatusChangeable,
        ProductSearch;

    protected static $modelClass = Interior::class;
    protected static $entityName = 'interior';
    protected static $editResource = InteriorEditResource::class;
    protected static $tableResource = InteriorTableResource::class;

    public function index()
    {
        $pagination = $this->_paginate($this->request->all());

        return [
            'interiors' => static::toResource($pagination->getCollection()),
            'perPage' => $pagination->perPage(),
            'page' => $pagination->currentPage(),
            'totalRows' => $pagination->total(),
        ];
    }

    //todo: Убрать две функции ниже хз куда.
    public function _paginate(Array $params = []): LengthAwarePaginator
    {
        $pagination = $this->_makePagination($params);

        if ($pagination->isEmpty() && $params['page'] > 1) {
            $page = round($pagination->total() / $params['perPage']);
            $params['page'] = $page <= 0 ? 1 : $page;

            $pagination = $this->_makePagination($params);
        }

        return $pagination;
    }

    public function _makePagination(Array $params): LengthAwarePaginator
    {
        extract($params, EXTR_OVERWRITE);

        $query = new static::$modelClass;

        return $query->select("{$query->getTable()}.*")
            ->orderBy('id', 'desc')
            ->with('i18n', 'rooms', 'styles')
            ->paginate($perPage, null, null, $page);
    }

    /**
     * Поиск товаров
     */
    public function query(Request $request)
    {
        $ids = $this->searchProducts($request->input('q'))->take(5);
        $ids = array_column($ids->toArray(),'id');

        $products = Product::with([
            'i18n',
        ])
            ->whereIn('id', $ids)->get();

        return [
            'status' => 'success',
            'result' => ProductSearchResource::collection($products)
        ];
    }

    public function product($productId)
    {
        $product = Product::where('id', $productId)
            ->with('i18n', 'image', 'prices')
            ->first();

        return [
            'status' => 'success',
            'product' => new ProductInteriorResource($product),
        ];
    }
}
