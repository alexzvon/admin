<?php

namespace App\Http\Resources\Shop\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\Shop\Price\PriceResource;

class ProductInteriorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id'    => $this->resource->id,
            'i18n'  => $this->resource->i18n->toArray(),
            'image' => new MediaResource($this->resource->image),
            'price' => new PriceResource($this->resource->prices->first())
        ];

        return $data;
    }
}