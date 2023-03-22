<?php

namespace App\Http\Resources\Shop\Kit;

use App\Http\Resources\Shop\Price\KitPriceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class KitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id' => $this->resource->id,
          'name' => $this->resource->name,
          'pricing_effort_type_id' => $this->resource->pricing_effort_type_id,
          'supplier_id' => $this->resource->supplier_id,
          'category_id' => $this->resource->category_id,
          'enabled' => $this->resource->enabled,
          'prices' => KitPriceResource::collection($this->resource->prices)
        ];
    }
}
