<?php namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Attribute\AttributeOption;
use App\Models\Shop\Attribute\AttributeOptionI18n;
use App\Models\Shop\Attribute\LinkAttributeRelationOption;
use App\Models\Shop\Attribute\LinkAttributeRelation;
use App\Http\Controllers\Api\Shop\Attributor\AttributorSync;
use App\Models\Shop\Product\Product;
use Illuminate\Support\Collection;

class AttributorController extends Controller
{
    use AttributorSync;

    const RELATED_PRODUCTS_ATTRIBUTE_ID = 37;

    public static function getById($id)
    {
        return LinkAttributeRelation::with('linkedOptions')->find($id);
    }


    public static function getOptionById($id)
    {
        return LinkAttributeRelationOption::find($id);
    }


    public function getByRelation($relation, $relation_id)
    {
        return LinkAttributeRelation::with('linkedOptions')
            ->where('enabled', '=', true)
            ->where('relation', $relation)
            ->where('relation_id', $relation_id)
            ->get();
    }


    public function create()
    {
        $sameCollectionAttrId = (int) env('SAME_COLLECTION_KIT_ATTR_ID');
        $is_products = +request('attribute_id') === self::RELATED_PRODUCTS_ATTRIBUTE_ID || +request('attribute_id') === $sameCollectionAttrId;

        $created = LinkAttributeRelation::create([
            'attribute_id' => request('attribute_id'),
            'relation_id' => request('relation_id'),
            'relation' => request('relation'),
            'selectable' => true,
            'enabled' => true,
            'is_products' => $is_products,
        ]);

        return self::getById($created->id);
    }


    public function update()
    {
        $updated = LinkAttributeRelation::find(request('id'))->update([
            'selectable'  => request('selectable'),
            'is_products' => request('is_products'),
            'enabled'     => request('enabled'),
        ]);

        if ($updated) {
            return self::getById(request('id'));
        }
    }


    public function delete()
    {
        LinkAttributeRelationOption::where('attribute_relation_id', request('id'))->delete();

        $deleted = LinkAttributeRelation::destroy(request('id'));

        return response()->json([
            'status' => 'success',
            'deleted' => $deleted
        ]);
    }


    public function createOption()
    {
        $sameCollectionAttrId = config('shop.same_collection.same_collection_attr_id', null);
        $requestProductId = request('option_product_id');
        $requestAttributeRelationId = request('attribute_relation_id');
        $parentProductId = LinkAttributeRelation::find($requestAttributeRelationId)->relation_id;
        $requestAttributeId = LinkAttributeRelation::find($requestAttributeRelationId)->attribute_id;

        if (request()->has('sync') && request('sync')
            && (
                $requestAttributeId === self::RELATED_PRODUCTS_ATTRIBUTE_ID ||
                $requestAttributeId === $sameCollectionAttrId
            )
        ) {
            $prodAttrRel = LinkAttributeRelation::whereRelation('product')->whereId($requestAttributeRelationId)->first();
            // опции, связанные с текущим продуктом
            $relatedOptions = LinkAttributeRelationOption::where('attribute_relation_id', $requestAttributeRelationId)->get();

            // ID товаров, связанных с текущим продуктом
            $relatedProductIds = $this->getRelatedProductIds($relatedOptions, $parentProductId, $requestProductId);

            // комплектные аттрибуты товаров, связанных с текущим продуктом, которые надо удалить
            $complectRelationIds = LinkAttributeRelation::whereIn('relation_id', $relatedProductIds)
                ->where('relation', 'product')
                ->where('attribute_id', $requestAttributeId)
                ->get()
                ->filter(function ($item) use ($prodAttrRel) {
                    return $item->getKey() !== $prodAttrRel->getKey();
                })
                ->map(function ($item) {
                    return $item->getKey();
                });

            $this->clearAttributesAndOptions($complectRelationIds);

            $attributesArray = collect();

            //  создаём для каждого товара новые комплектные аттрибуты товаров для каждого связанного товара
            foreach ($relatedProductIds as $id) {
                if ($id === $parentProductId) {
                    $attribute = LinkAttributeRelation::find($requestAttributeRelationId);
                } else {
                    $attributes = LinkAttributeRelation::where('relation_id', $id)
                        ->where('attribute_id', $requestAttributeId)
                        ->get();
                    if ($attributes->count()) {
                        $attribute = $attributes->first();
                    } else {
                        $attribute = new LinkAttributeRelation([
                            'relation_id'  => $id,
                            'relation'     => 'product',
                            'attribute_id' => $requestAttributeId,
                            'is_products'  => true,
                            'selectable'   => true,
                            'enabled'      => true,
                        ]);
                        $attribute->push();
                    }
                }

                $attributesArray->push($attribute);
            }

            foreach ($attributesArray as $attribute) {
                $relatedProductIds->map(function($item) use ($parentProductId, $attribute) {
                    $this->createRelatedOption($item, $attribute);
                });

                /** @var LinkAttributeRelation $attribute */
                $toDelete = $attribute->linkedOptions
                    ->filter(function ($option) {
                        return $option->option_product_id === null;
                    })
                    ->map(function ($option) {
                        return $option->id;
                    })
                    ->toArray();

                $dup = [];
                $notDup = [];
                foreach ($attribute->linkedOptions as $option) {
                    if (!array_key_exists($option->option_product_id, $notDup)) {
                        $notDup[$option->option_product_id] = $option->id;
                    } else {
                        $dup[] = $option->id;
                    }
                }

                LinkAttributeRelationOption::whereIn('id', $toDelete)->delete();
                LinkAttributeRelationOption::whereIn('id', $dup)->delete();
            }
        } else {
            $model = new LinkAttributeRelationOption;
            $model->option_id = request('option_id');
            $model->attribute_relation_id = $requestAttributeRelationId;
            $model->option_product_id = request()->has('option_product_id')
                ? $requestProductId
                : null;
            $model->save();
        }

        return self::getById($requestAttributeRelationId);
    }


    public function updateOption()
    {
        $updated = LinkAttributeRelationOption::find(request('id'))->update([
            'fix_price' => request('fix_price')
        ]);

        if ($updated) {
            return self::getById(request('attribute_relation_id'));
        }
    }


    public function deleteOption()
    {
        $deleted = LinkAttributeRelationOption::where('id', request('id'))->delete();

        if ($deleted) {
            return self::getById(request('attribute_relation_id'));
        }
    }

    /**
     * @param Collection $relatedOptions
     * @param int        $parentId
     * @param int        $requestId
     *
     * @return Collection
     */
    protected function getRelatedProductIds(Collection $relatedOptions, int $parentId, int $requestId): Collection
    {
        $relatedProductIds = $relatedOptions->map(function ($item) {
            return $item->option_product_id;
        });
        $relatedProductIds->push($parentId);
        $relatedProductIds->push($requestId);
        $relatedProductIds = collect(array_filter(array_unique($relatedProductIds->toArray())));

        return $relatedProductIds;
    }

    /**
     * @param $complectRelationIds
     */
    protected function clearAttributesAndOptions($complectRelationIds)
    {
        foreach ($complectRelationIds as $id) {
            $optionIds = LinkAttributeRelationOption::where('attribute_relation_id', $id)
                ->get()
                ->map(function($item) {
                    return $item->id;
                });

            LinkAttributeRelationOption::whereIn('id', $optionIds)->delete();
        }

        LinkAttributeRelation::whereIn('id', $complectRelationIds)->delete();
    }

    /**
     * @param int                   $resource
     * @param LinkAttributeRelation $attr
     */
    protected function createRelatedOption(int $resource, LinkAttributeRelation $attr)
    {
        if ($resource !== $attr->relation_id) {
            $product = Product::find($resource);

            if ($product->id !== null) {
                $option = AttributeOption::create([
                    'attribute_id' => $attr->attribute_id,
                    'enabled'      => true,
                ]);
                $option->save();

                $i18n = AttributeOptionI18n::create([
                    'option_id'     => $option->id,
                    'language_code' => 'ru',
                    'value'         => $product->currentI18n->title
                ]);
                $i18n->save();

                $model = new LinkAttributeRelationOption;
                $model->option_id = $option->getKey();
                $model->attribute_relation_id = $attr->getKey();
                $model->option_product_id = $product->id;

                $model->save();
            }
        }
    }
}
