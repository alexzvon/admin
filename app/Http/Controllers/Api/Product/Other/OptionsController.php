<?php

namespace App\Http\Controllers\Api\Product\Other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Product\OtherProductOptionsService;
use App\Http\Resources\Product\Other\GetOtherProductsResource;

class OptionsController extends Controller
{
    protected $otherProductOptionsService;

    public function __construct()
    {
        $this->otherProductOptionsService = app(OtherProductOptionsService::class);
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAddOptions(Request $request)
    {
        $result = $this->otherProductOptionsService->getAddOtherProductOptions($request->all());

        return response()->json(['data' => $result]);
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLinkOptions(Request $request)
    {
        $result = $this->otherProductOptionsService->addLinksOtherProductOptions($request->all());

        return response()->json(['data' => $result]);
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delLinkOptions(Request $request)
    {
        $result = $this->otherProductOptionsService->delLinksOtherProductOptions($request->all());

        return response()->json(['data' => $result]);
    }

    /**
     * @param $idProduct
     * @return GetOtherProductsResource
     */
    public function getOtherProducts($idProduct)
    {
        $result = $this->otherProductOptionsService->getOtherProductOption($idProduct);

        return GetOtherProductsResource::make($result);
    }
}
