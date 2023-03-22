<?php

namespace App\Http\Resources\Shop\Product\Api;

use App\Models\Shop\Price\Price;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\MediaResource;
use Illuminate\Support\Facades\Storage;

/**
 * Class ProductEditResource
 *
 * @package App\Http\Resources\Shop\Product\Api
 */
class ProductEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $images = MediaResource::collection($this->getMedia());
        $pathes = json_decode($images->first()->pathes);

        $price = collect($this->prices)->filter(function ($item) {
            return $item->price_type_id === 7;
        })->map(function ($item) {
            /** @var $item Price */
            return $item->getFormatted();
        })->first();

        $salePrice = collect($this->prices)->filter(function ($item) {
            return $item->price_type_id === 1;
        })->map(function ($item) {
            /** @var $item Price */
            return $item->getFormatted();
        })->first();

        if ($salePrice !== null) {
            $tmp = $salePrice;
            $salePrice = $price;
            $price = $tmp;
        }

        return [
            'id'          => $this->id,
            'url'         => config('app.client_app_url') . '/' . app()->getLocale() . '/products/' . $this->slug . '-' . $this->id,
            'image'       => Storage::url($pathes->original),
            'image_thumb' => Storage::url($pathes->thumb->src),
            'price'       => $price,
            'sale_price'  => $salePrice,
            'title'       => $this->i18n()->get()->toArray()[0]['title'] ?? 'Product has no title',
            'description' => $this->i18n()->get()->toArray()[0]['description'] ?? 'Product has no description',
        ];
    }
}