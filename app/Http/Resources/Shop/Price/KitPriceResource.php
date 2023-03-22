<?php

namespace App\Http\Resources\Shop\Price;

use Illuminate\Http\Resources\Json\JsonResource;

class KitPriceResource extends JsonResource
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
          'item_type' => $this->resource->item_type,
          'item_id' => $this->resource->item_id,
          'formatted' => $this->getFormatted(), // нужно ли?
          'value' => $this->resource->value,
          'price_type_id' => $this->resource->price_type_id,
          'currency_code' => $this->resource->currency_code,
        ];
    }
}
