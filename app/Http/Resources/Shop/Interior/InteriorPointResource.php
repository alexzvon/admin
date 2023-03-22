<?php

namespace App\Http\Resources\Shop\Interior;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Shop\Product\ProductSearchResource;

class InteriorPointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->resource->id,
            'product'    => new ProductSearchResource($this->resource->product),
            'position_x' => $this->resource->position_x,
            'position_y' => $this->resource->position_y,
            'is_similar' => $this->resource->is_similar,
        ];
    }
}
