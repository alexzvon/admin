<?php

namespace App\Http\Resources\Shop\Price;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
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
          'formatted' => $this->getFormatted(), // нужно ли?
          'value' => $this->resource->value,
          'price_type_id' => $this->resource->price_type_id,
          'currency_code' => $this->resource->currency_code,
        ];
    }
}
