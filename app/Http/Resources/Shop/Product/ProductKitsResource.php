<?php

namespace App\Http\Resources\Shop\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Shop\Price\KitPriceResource;

class ProductKitsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
          'product_id' => $this->product_id,
          'kit_id' => $this->kit_id,
          'enabled' => $this->enabled,
          'prices' => KitPriceResource::collection($this->prices),
        ];

        return $data;
    }
}
