<?php
declare(strict_types=1);

namespace App\Services\Product\Attribute;

use Illuminate\Http\JsonResponse;

use App\Repositories\Product\Attribute\LinkAttributeRelationRepository;
use App\Repositories\Product\Attribute\AttributeRepository;
use App\Repositories\Product\Attribute\AttributeOptionRepository;
use App\Repositories\Product\Attribute\LinkAttributeRelationOptionRepository;
use App\Http\Resources\Product\Attribute\GetAttributeResource;

class AttributeService
{
    const ATTRIBUTE_ID_114 = 114;
    const GLOBAL_ATTRIBUTE = [2, 4, 12, 14];

    protected $linkAttributeRelationRepository;
    protected $attributeRepository;
    protected $linkAttributeRelationOptionRepository;
    protected $attributeOptionRepository;

    protected $linkAttributeRelation;
    protected $arrIdAttribute;
    protected $allCollectionAtributeI18n;

    public function __construct()
    {
        $this->linkAttributeRelationRepository = app(LinkAttributeRelationRepository::class);
        $this->attributeRepository = app(AttributeRepository::class);
        $this->linkAttributeRelationOptionRepository = app(LinkAttributeRelationOptionRepository::class);
        $this->attributeOptionRepository = app(AttributeOptionRepository::class);
    }

    /**
     * Загрузить опции для добавления в атрибут
     * @param  array  $input
     * @return array|array[]
     */
    public function getAddOptionsAttributeProduct(array $input): array
    {
        $idAttr = (int)($input['attribute_id'] ?? 0);

        $idProduct = (int)$input['relation_id'];
        $relation = $input['relation'];

        $arrAllOptionsAttribute = $this->attributeOptionRepository->getAttributesOptionsInIds([$idAttr]);

        if (!is_null($arrAllOptionsAttribute)) {
            foreach ($arrAllOptionsAttribute as $key => $option) {
                if (0 == count($option->i18n)) {
                    unset($arrAllOptionsAttribute[$key]);
                }
            }

            $linkProductOptionsAttribute = $this->linkAttributeRelationRepository->getLinkAttributeOptionsRelation(
                $relation,
                $idProduct,
                $idAttr
            );

            $idsLinkOptions = [];

            foreach ($linkProductOptionsAttribute->linkedOptions as $option) {
                $idsLinkOptions[$option->option_id] = 0;
            }

            $arrAddOptionsAttribute = [];

            foreach ($arrAllOptionsAttribute as $option) {
                if (!isset($idsLinkOptions[$option->id])) {
                    $arrAddOptionsAttribute[] = [
                        'id' => $option->id,
                        'value' => $option->i18n[0]->value,
                    ];
                }
            }
            $result = ['addOptions' => $arrAddOptionsAttribute];
        } else {
            $result = ['addOptions' => []];
        }

        return $result;
    }

    /**
     * изменить тип поля
     * @param $input
     * @return JsonResponse
     */
    public function updateStateAttributeRealation(array $input): JsonResponse
    {
        $id = $input['id'];
        $selectable = $input['selectable'];
        $isProducts = $input['is_products'];

        $result = $this->linkAttributeRelationRepository->updateStateAttributeRealation($id, $selectable, $isProducts);

        return response()->json(['data' => $result]);
    }

    /**
     * Удалить аттрибут
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAttribute($input): JsonResponse
    {
        $attribute_relation_id = (int)$input['attribute_relation_id'];

        $this->linkAttributeRelationOptionRepository->deleteAttributeRelationOption($attribute_relation_id);
        $deleted = $this->linkAttributeRelationRepository->deleteAttributeRelation($attribute_relation_id);

        return response()->json(['data' => ['status' => 'success', 'deleted' => $deleted]]);
    }

    /**
     * Загрузить атрибуты для добавления
     * @param  array  $input
     * @return JsonResponse
     */
    public function getAddAttributeI18n(array $input): JsonResponse
    {
        $productId = $input['product_id'];

        $arrAttributes = $this->linkAttributeRelationRepository->getAttributeIdProduct($productId);
        $arrIdAttribute = [];

        foreach($arrAttributes as $attr) {
            $arrIdAttribute[] = $attr->attribute_id;
        }

        $arrIdAttribute[] = self::ATTRIBUTE_ID_114;

        $arrIdAttribute = array_merge($arrIdAttribute, self::GLOBAL_ATTRIBUTE);

        return response()->json(['data' => $this->attributeRepository->getNotIdCollection($arrIdAttribute)]);
    }

    /**
     * Загрузить глобальные аттрибуты для добавления
     * @param  array  $input
     * @return JsonResponse
     */
    public function getAddGlobalAttributeI18n(array $input): JsonResponse
    {
        $productId = $input['product_id'];

        $arrAttributes = $this->linkAttributeRelationRepository->getAttributeIdProduct($productId);

        foreach ($arrAttributes as $attr) {
            $arrIdAttribute[] = $attr->attribute_id;
        }

        $arrIdAttribute = array_diff(self::GLOBAL_ATTRIBUTE, $arrIdAttribute);

        return response()->json(['data' => $this->attributeRepository->getAttributeWithI18n($arrIdAttribute)]);
    }

    /**
     * загрузить все аттрибуты
     * @return JsonResponse
     */
    public function getAllAttributeI18n(): JsonResponse
    {
        return response()->json(['data' => $this->attributeRepository->getCollection()]);
    }

    /**
     * получить аттрибуты для продукта
     * @param  array  $input
     * @return GetAttributeResource
     */
    public function prepareAttributeProduct(array $input): GetAttributeResource
    {
        $result = null;

        $product_id = (int)$input['product_id'];

        $this->linkAttributeRelation = $this->linkAttributeRelationRepository->getLinkAttributeRelation('product', $product_id);
        $result['linkAttributeRelation'] = $this->linkAttributeRelation;

        $this->checklinkAttributeRelationLocal();
        $result['arrIdAttribute'] = $this->arrIdAttribute();

        $this->getAttributeWithI18n = $this->attributeRepository->getAttributeWithI18n($result['arrIdAttribute']);
        $result['getAttributeWithI18n'] = $this->getAttributeWithI18n;

        $result['arrIdAttributeOption'] = $this->arrIdAttributeOption();
        $result['arrIdAttributeRelation'] = $this->arrIdAttributeRelation($result['arrIdAttribute'], $result['arrIdAttributeOption']);

        $result['getLinkAttributeOption'] = $this->linkAttributeRelationOptionRepository->getLinkAttributeOption($this->arrIdAttributeOption());

        return GetAttributeResource::make($result);
    }

    /**
     * получить глобальные аттрибуты для продукта
     * @param  array  $input
     * @return GetAttributeResource
     */
    public function prepareGlobalAttributeProduct(array $input): GetAttributeResource
    {
        $result = null;

        $product_id = (int)$input['product_id'];

        $this->linkAttributeRelation = $this->linkAttributeRelationRepository->getLinkAttributeRelation('product', $product_id);
        $result['linkAttributeRelation'] = $this->linkAttributeRelation;

        $this->checklinkAttributeRelationGlobal();
        $result['arrIdAttribute'] = $this->arrIdAttribute();

        $this->getAttributeWithI18n = $this->attributeRepository->getAttributeWithI18n($result['arrIdAttribute']);
        $result['getAttributeWithI18n'] = $this->getAttributeWithI18n;

        $result['arrIdAttributeOption'] = $this->arrIdAttributeOption();
        $result['arrIdAttributeRelation'] = $this->arrIdAttributeRelation($result['arrIdAttribute'], $result['arrIdAttributeOption']);

        $result['getLinkAttributeOption'] = $this->linkAttributeRelationOptionRepository->getLinkAttributeOption($this->arrIdAttributeOption());

        return GetAttributeResource::make($result);
    }

    /**
     * @param $arrIdAttribute
     * @param $arrIdAttributeOption
     * @return array
     */
    private function arrIdAttributeRelation($arrIdAttribute, $arrIdAttributeOption) {
        $result = [];

        for ($i = 0; $i < count($arrIdAttribute); $i++) {
            $result[$arrIdAttribute[$i]] = $arrIdAttributeOption[$i];
        }

        return $result;
    }

    /**
     *
     */
    private function checklinkAttributeRelationGlobal()
    {
        if (0 < count($this->linkAttributeRelation)) {
            foreach ($this->linkAttributeRelation as $key => $link) {
                if (!in_array($link->attribute_id, self::GLOBAL_ATTRIBUTE)) {
                    unset($this->linkAttributeRelation[$key]);
                }
            }
        }
    }

    /**
     *
     */
    private function checklinkAttributeRelationLocal()
    {
        if (0 < count($this->linkAttributeRelation)) {
            foreach ($this->linkAttributeRelation as $key => $link) {
                if (in_array($link->attribute_id, self::GLOBAL_ATTRIBUTE) || $link->attribute_id === self::ATTRIBUTE_ID_114) {
                    unset($this->linkAttributeRelation[$key]);
                }
            }
        }
    }

    /**
     * @return array
     */
    private function arrIdAttribute(): array
    {
        $result = [];

        if (0 < count($this->linkAttributeRelation)) {
            foreach ($this->linkAttributeRelation as $link) {
                $result[] = $link->attribute_id;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    private function arrIdAttributeOption(): array
    {
        $result = [];

        if (0 < count($this->linkAttributeRelation)) {
            foreach ($this->linkAttributeRelation as $link) {
                $result[] = $link->id;
            }
        }

        return $result;
    }
}