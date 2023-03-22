<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Api\ApiController;

use App\Http\Requests\ApiRequest;
use App\Models\Shop\Attribute\LinkAttributeRelation;
use App\Models\Shop\Attribute\LinkAttributeRelationOption;
use App\Models\Shop\Category\CategoryProduct;
use App\Models\Shop\Product\RelatedProduct;
use App\Models\Shop\Room\RoomProduct;
use App\Models\Shop\Style\StyleProduct;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Config;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;

use App\Models\Shop\Product\Product;
use App\Models\Shop\Product\ProductI18n;

use App\Http\Resources\Shop\Product\ProductEditResource;
use App\Http\Resources\Shop\Product\ProductsTableResource;
use App\Http\Resources\Shop\Product\ProductSearchResource;

use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Updatable;
use App\Support\Traits\Controllers\Sluggable;
use App\Support\Traits\Controllers\StatusChangeable;
use App\Support\Traits\Controllers\ImageUploadable;
use App\Support\Traits\ProductSearch;
use App\Events\Entity\EntityDeleted;

use Illuminate\Pagination\LengthAwarePaginator;

use App\Http\Requests\Shop\ProductSearchRequest;

/**
 * Class ProductController
 *
 * @package App\Http\Controllers\Api\Shop
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
    protected static $tableResource = ProductsTableResource::class;

    public function __construct(ProductSearchRequest $request)
    {
        parent::__construct($request);
    }

    public function create()
    {
        $product = Product::create([
            'is_payable' => 0,
            'enabled' => 0,
        ]);

        ProductI18n::create([
            'product_id' => $product->id,
            'language_code' => 'ru',
            'title' => request('title'),
        ]);

        return $product;
    }

    /**
     * @param Product $product
     *
     * @return Product
     */
    public function clone(Product $product): Product
    {
        /**  @var $clone Product */
        $clone = $product->replicate();
        $clone->slug .= '-1';
        $clone->push();

        $product->load(
            'prices',
            'categoryRelations',
            'suppliers',
            'relatedRelations',
            'related',
            'badges',
            'i18n',
            'roomRelations',
            'styleRelations'
        );

        $testProd = ProductI18n::where('product_id', '=', $product->getKey())->where('language_code', '=', app()->getLocale())->first();

        $numOfCopies = ProductI18n::where('title', 'like', "%$testProd->title%")->get()->count();

        //re-sync everything
        foreach ($product->getRelations() as $relationName => $values) {
            if ($clone->{$relationName}() instanceof MorphMany) {
                if ($relationName === 'badges' || $relationName === 'prices') {
                    foreach ($values as $value) {
                        $cloneRel = $value->replicate();
                        $cloneRel->item_id = $clone->getKey();
                        $cloneRel->push();
                    }
                }
            }

            if ($clone->{$relationName}() instanceof HasMany) {
                if ($relationName === 'categoryRelations') {
                    foreach ($values as $value){
                        $categoryId = $value->category_id;
                        $clone->{$relationName}()->save(new CategoryProduct(['category_id' => $categoryId]));
                    }
                }

                if ($relationName === 'relatedRelations') {
                    foreach ($values as $value){
                        $relatedId = $value->related_id;
                        $clone->{$relationName}()->save(new RelatedProduct(['related_id' => $relatedId ]));
                    }
                }

                if ($relationName === 'roomRelations') {
                    foreach ($values as $value){
                        $roomId = $value->room_id;
                        $clone->{$relationName}()->save(new RoomProduct(['room_id' => $roomId ]));
                    }
                }

                if ($relationName === 'styleRelations') {
                    foreach ($values as $value){
                        $styleId = $value->style_id;
                        $clone->{$relationName}()->save(new StyleProduct(['style_id' => $styleId ]));
                    }
                }

                if ($relationName === 'i18n') {
                    foreach ($values as $value) {
                        $data = $value->toArray();
                        $data['product_id'] = $clone->getKey();
                        $data['title'] = 'Копия_' . ($numOfCopies - 1) . ' ' . $data['title'];

                        $name = $product->getI18nModelName();

                        $clone->{$relationName}()->save(new $name($data));
                    }
                }
            }

            if ($clone->{$relationName}() instanceof BelongsToMany) {
                $ids = $values->map(function ($item) {
                    return $item->getKey();
                })->toArray();
                $clone->{$relationName}()->sync($ids);
            }
        }

        $linkedAttributes = LinkAttributeRelation::where(
                'relation_id',
                '=',
                $product->getKey()
            )
            ->where('relation', '=', 'product')
            ->get();

        foreach ($linkedAttributes as $attribute) {
            $newLinkAttr = LinkAttributeRelation::create([
                'attribute_id' => $attribute->attribute_id,
                'relation_id'  => $clone->getKey(),
                'relation'     => $attribute->relation,
                'selectable'   => $attribute->selectable,
                'enabled'      => $attribute->enabled,
                'is_products'  => $attribute->is_products,
            ]);

            $options = LinkAttributeRelationOption::where(
                    'attribute_relation_id',
                    '=',
                    $attribute->getKey()
                )
                ->get();

            foreach ($options as $option) {
                LinkAttributeRelationOption::create([
                    'option_id'             => $option->option_id,
                    'attribute_relation_id' => $newLinkAttr->getKey(),
                    'option_product_id'     => $option->option_product_id,
                    'enabled'               => $option->enabled,
                    'fix_price'             => $option->fix_price,
                    'position'              => $option->fix_price,
                ]);
            }
        }

        $clone->save();

        return $clone;
    }

    public function index()
    {
        $pagination = $this->_paginate($this->request->all());

        return [
            'products' => static::toResource($pagination->getCollection()),
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

        $filteredByCategories = isset($filteredByCategories)
            ? $filteredByCategories
            : [];

        $filteredBySuppliers = isset($filteredBySuppliers)
            ? $filteredBySuppliers
            : [];

        $productsTableName = Config::get('tables.Products');
        $i18nTableName = Config::get('tables.ProductsI18n');
        $pricesTableName = Config::get('tables.Prices');

        $sortBy = isset($sortBy) ? $sortBy : 'id';

        $query = Product::select("shop_products.*")->localized();

        if ($sortBy === 'price') {
            $query->leftJoin("{$pricesTableName} as prices", function ($join) use ($productsTableName, $priceType) {
                $join->on('prices.item_id', '=', "shop_products.id")
                    ->where('prices.item_type', 'product')
                    ->where('prices.price_type_id', $priceType)
                    ->where('prices.currency_code', 'RUB');
            })->orderBy('prices.value', $sortType);
        } elseif ($sortBy === 'title') {
            $query->orderBy("{$i18nTableName}.title", $sortType);
        } else {
            $query->orderBy($sortBy, $sortType);
        }

        if (!empty($search)) {
            if ($selected === 'standart') {
                $query->where(\DB::raw("lower({$i18nTableName}.title)"), 'like', "%" . mb_strtolower($search) . "%");
            }
            elseif ($selected === 'product') {
                $query->where('shop_products.id', '=', $search);
            }
            elseif ($selected === 'supplier') {
                $query->where(\DB::raw('lower(shop_products.id_from_supplier)'), 'like', mb_strtolower($search));
            }
        }

        if ($type === 'no-image') {
            $query->hasNoImage();
        } elseif ($type === 'sale') {
            $query->leftJoin("{$pricesTableName} as prices", function ($join) use ($productsTableName, $priceType) {
                $join->on('prices.item_id', '=', "shop_products.id")
                    ->where('prices.item_type', 'product');
            })
                ->where('prices.price_type_id', 6)
                ->whereNotNull('prices.value')
                ->orderBy('prices.value', $sortType);
        } elseif ($type !== 'all') {
            $badgeTableName = config('tables.Badges');
            $productTableName = config('tables.Products');

            switch ($type) {
                case 'popular':
                    $badgeTypeId = 1;
                    break;

                case 'new':
                    $badgeTypeId = 6;
                    break;
            }

            $query->join("{$badgeTableName}", function ($join) use ($badgeTableName, $productTableName, $badgeTypeId) {
                $join->on("{$badgeTableName}.item_id", '=', "{$productTableName}.id")
                    ->where("{$badgeTableName}.item_type", 'product')
                    ->where("{$badgeTableName}.badge_type_id", $badgeTypeId);
            });
        }

        if (count($filteredBySuppliers) > 0) {
            $query = $query->whereHas('suppliers', function ($query) use ($filteredBySuppliers) {
                $query->whereIn('supplier_id', $filteredBySuppliers);
            });
        }

        if (count($filteredByCategories) > 0) {
            $query = $query->whereHas('categoryRelations', function ($q) use ($filteredByCategories) {
                return $q->whereIn('category_id', $filteredByCategories);
            });
        }

        $results = $query
            ->orderBy('id', $sortType)
            ->with(['i18n', 'prices', 'image', 'categoryRelations', 'roomRelations', 'styleRelations'])
            ->paginate($perPage, null, null, $page);

        return $results;
    }

    /**
     * Поиск товаров
     */
    public function query(Request $request, $productId)
    {
        if (mb_strlen(request()->input('q'), 'utf8') > 2) {
            $ids = $this->searchProducts($request->input('q'))->take(20);
            $ids = array_column($ids->toArray(), 'id');
        } else {
            $ids = [];
        }

        $products = Product::with([
            'i18n',
        ])
            ->whereIn('id', $ids)
            ->where('id', '!=', $productId)
            ->take(20)
            ->get();

        return [
            'status' => 'success',
            'result' => ProductSearchResource::collection($products),
        ];
    }

    protected function searchResultToIds($result)
    {
        return array_column($result->toArray(), 'id');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        Event::fire(new EntityDeleted($product));

        return response()->json([
            'status' => 'success',
            'message' => $this->lang("deleted", ['id' => $product[$product->getKeyName()]]),
        ], 200);
    }
}
