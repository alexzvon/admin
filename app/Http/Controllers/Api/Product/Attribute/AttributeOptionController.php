<?php

namespace App\Http\Controllers\Api\Product\Attribute;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Product\Attribute\LinkAttributeRelationOptionRepository;
use App\Services\Product\Attribute\AttributeService;

class AttributeOptionController extends Controller
{
    private $linkAttributeRelationOptionRepository;
    private $attributeService;

    public function __construct()
    {
        $this->linkAttributeRelationOptionRepository = app(LinkAttributeRelationOptionRepository::class);
        $this->attributeService = app(AttributeService::class);
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addOptionToAttribute(Request $request)
    {
        $idAttributeRelation = $request->get('attribute_relation_id');
        $idOption = $request->get('option_id');

        $result = $this->linkAttributeRelationOptionRepository
            ->addLinkAttributeOption($idAttributeRelation, $idOption);

        return response()->json(['data' => ['success' => $result]]);
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function getAddOptionsAttributeProduct(Request $request)
    {
        return response()->json([ 'data' => $this->attributeService->getAddOptionsAttributeProduct($request->all())]);
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function updateState(Request $request)
    {
        return $this->attributeService->updateStateAttributeRealation($request->all());
    }

    /**
     * @param $product_id
     * @return mixed
     */
    public function getAddAttributesI18n($product_id)
    {
        return $this->attributeService->getAddAttributeI18n(['product_id' => $product_id]);
    }

    /**
     * @param $product_id
     * @return mixed
     */
    public function getAddGlobalAttributesI18n($product_id)
    {
        return $this->attributeService->getAddGlobalAttributeI18n(['product_id' => $product_id]);
    }

    /**
     * @param $product_id
     * @return \App\Http\Resources\Product\Attribute\GetAttributeResource
     */
    public function getAttributes($product_id)
    {
        return $this->attributeService->prepareAttributeProduct(['product_id' => $product_id]);
    }

    /**
     * @param $product_id
     * @return mixed
     */
    public function getGlobalAttributes($product_id)
    {
        return $this->attributeService->prepareGlobalAttributeProduct(['product_id' => $product_id]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllAttributesI18n()
    {
        return $this->attributeService->getAllAttributeI18n();
    }

    /**
     * @param $attribute_relation_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAttribute($attribute_relation_id)
    {
        return $this->attributeService->deleteAttribute(['attribute_relation_id' => $attribute_relation_id]);
    }

    /**
     * @param $option_id
     * @return mixed
     */
    public function deleteOption($option_id)
    {
        return $this->linkAttributeRelationOptionRepository->deleteIdLinkAttributeOption($option_id);
    }
}
