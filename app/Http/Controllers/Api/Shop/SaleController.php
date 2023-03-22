<?php

namespace App\Http\Controllers\Api\Shop;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

use App\Models\Shop\Product\Product;
use App\Models\Shop\Sale\Sale;
use App\Models\Shop\Price\Price;

use App\Http\Resources\Shop\Sale\SaleResource;
use App\Http\Resources\Shop\Product\ProductSearchResource;
use App\Http\Resources\Shop\Price\PriceResource;

use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Updatable;
use App\Support\Traits\Controllers\Deleteable;
use App\Support\Traits\Controllers\StatusChangeable;
use App\Support\Traits\Controllers\PositionChangeable;
use App\Support\Traits\ProductSearch;

class SaleController extends ApiController
{
    use Creatable,
        Updatable,
        Deleteable,
        StatusChangeable,
        PositionChangeable,
        ProductSearch;

    protected static $modelClass = Sale::class;
    protected static $entityName = 'sale';
    protected static $editResource = SaleResource::class;
    protected static $tableResource = SaleResource::class;


    public function index()
    {
        return [
            'sales' => SaleResource::collection(Sale::get()),
        ];
    }


    /**
     * Поиск товаров
     */
    public function query()
    {
        if (mb_strlen(request()->input('q'), 'utf8') > 2) {
            $ids = $this->searchResultToIds(
                $this->excludingActiveInPromos(
                    $this->searchProducts(request()->input('q'))->take(50)
                )
            );
        } else {
            $ids = [];
        }


        $products = Product::whereIn('id', $ids)
            ->with('i18n')
            ->get();

        return [
            'status' => 'success',
            'result' => ProductSearchResource::collection($products)
        ];
    }


    public function price($productId)
    {
        $prices = Price::where('price_type_id', config('shop.price_types.sale'))
            ->where('item_id', $productId)
            ->where('item_type', 'product')
            ->get();

        return [
            'status' => 'success',
            'prices' => PriceResource::collection($prices)
        ];
    }


    protected function searchResultToIds($result)
    {
        return array_column($result->toArray(),'id');
    }

    /**
     * @param Collection $products
     *
     * @return Collection
     */
    protected function excludingActiveInPromos(Collection $products): Collection
    {
        $activeIds = Sale::all()->map(function ($sale) {
            return $sale->item->id;
        });

        return $products->filter(function ($item) use ($activeIds) {
            return !in_array($item->id, $activeIds->toArray());
        });
    }
}
