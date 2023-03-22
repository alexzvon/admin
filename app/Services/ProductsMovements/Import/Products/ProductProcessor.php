<?php

declare(strict_types = 1);

namespace App\Services\ProductsMovements\Import\Products;

use App\Jobs\AddMediaToProduct;
use App\Models\Shop\Attribute\Attribute;
use App\Models\Shop\Attribute\AttributeOption;
use App\Models\Shop\Attribute\LinkAttributeRelation;
use App\Models\Shop\Attribute\LinkAttributeRelationOption;
use App\Models\Shop\Product\Product;

use App\Services\ProductsMovements\ProductsMovements;
use Illuminate\Support\Facades\Log;

/**
 * Class ProductProcessor
 *
 * @package App\Services\ProductsMovements\Import\Products
 */
abstract class ProductProcessor
{
    /**
     * @var Product $product
     */
    protected $product;

    /**
     * @var ParametersBag
     */
    protected $parametersBag;

    /**
     * ProductProcessor constructor.
     *
     * @param Product       $product
     * @param ParametersBag $parametersBag
     */
    public function __construct(Product $product, ParametersBag $parametersBag)
    {
        $this->product = $product;
        $this->parametersBag = $parametersBag;
    }

    protected abstract function getReceivedImagesHashes(): array;

    protected abstract function saveImages();

    /**
     * Сохранение, используя данные, полученные из импорта.
     *
     * @return self
     */
    public function saveFromImportData(): self
    {
        $data = $this->parametersBag->getParameters();

        $this->product->saveFromImportData($data);
        $this->saveAttributes();
        $this->saveImages();

        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * Saves Attribute to Product relations
     * and saves AttributeOption to Attribute relations.
     */
    protected function saveAttributes()
    {
        $checkedAttributes = $this->findExistentAttrs($this->prepareAttrs($this->parametersBag->getAttributes()));
        $optionsToDelete = [];

        foreach ($checkedAttributes as $id => $options) {
            $relationsToDelete = [];
            $isProducts = ($id === ProductsMovements::RELATED_PRODUCTS_ATTRIBUTE_ID);
            $oldRelations = LinkAttributeRelation::with('linkedOptions')
                ->where('attribute_id', '=', $id)
                ->where('relation', ProductsMovements::RELATION_TYPE)
                ->where('relation_id', $this->product->getKey())
                ->get();

            foreach ($oldRelations as $relation) {
                $relationsToDelete[] = $relation->getKey();
                $linkedOptions = $relation->linkedOptions;
                foreach ($linkedOptions as $option) {
                    $optionsToDelete[] = $option->getKey();
                }
            }

            LinkAttributeRelationOption::whereIn('id', $optionsToDelete)->delete();
            LinkAttributeRelation::whereIn('id', $relationsToDelete)->delete();

            $created = LinkAttributeRelation::create([
                'attribute_id' => $id,
                'relation_id'  => $this->product->getKey(),
                'relation'     => ProductsMovements::RELATION_TYPE,
                'selectable'   => true,
                'enabled'      => true,
                'is_products'  => $isProducts,
            ]);

            foreach ($options as $id) {
                try {
                    $option = AttributeOption::findOrFail($id);

                    $model = new LinkAttributeRelationOption();
                    $model->option_id = $option->getKey();
                    $model->attribute_relation_id = $created->getKey();

                    $model->save();
                } catch (\Throwable $exception) {
                    Log::error('Trying to get option that does not exist. During Import Process. Option ID:' . $id);
                }
            }
        }
    }

    /**
     * @param array $attrs
     *
     * @return array
     */
    protected function prepareAttrs(array $attrs): array
    {
        $result = [];

        $attrs = collect($attrs)
            ->filter(function ($item) {
                return $item !== null;
            })
            ->map(function ($item) {
                return $this->prepareOptions(explode(';', (string) $item));
            })
            ->toArray();

        foreach ($attrs as $name => $options) {
            preg_match_all("/(^\d+)/", $name, $out, PREG_UNMATCHED_AS_NULL);

            if (!empty($out[1]) && !is_array($out[1][0]) && !is_null($out[1][0]) && strlen($out[1][0])) {
                $result[(int) $out[1][0]] = $options;
            }
        }

        return $result;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function prepareOptions(array $options): array
    {
        $arrayIds = [];
        $options = array_unique($options);

        foreach ($options as $option) {
            $option = trim($option);
            preg_match_all("/(^\d+)/", $option, $out, PREG_UNMATCHED_AS_NULL);

            if (!empty($out[1]) && !is_array($out[1][0]) && !is_null($out[1][0]) && strlen($out[1][0])) {
                array_push($arrayIds, (int)$out[1][0]);
            }
        }

        return array_unique($arrayIds);
    }

    /**
     * @param array $attributeIds
     *
     * @return array
     */
    protected function findExistentAttrs(array $attributeIds): array
    {
        $ids = array_keys($attributeIds);

        $attrs = Attribute::select(['id'])
            ->whereIn('id', $ids)
            ->get()
            ->map(function ($item) {
                return $item->id;
            })
            ->toArray();

        $filtered = array_filter($attributeIds, function ($item) use ($attrs) {
            return in_array($item, $attrs);
        }, ARRAY_FILTER_USE_KEY);

        return $filtered;
    }

    /**
     * @param array $hashes
     */
    protected function addImagesToProduct(array $hashes)
    {
        dispatch(new AddMediaToProduct($this->product, $hashes));
    }
}
