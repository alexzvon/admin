<?php

namespace App\Http\Resources\Shop\Kit;

use App\Http\Resources\Shop\Price\KitPriceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class KitEditResource extends JsonResource
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
          'id' => $this->id,
          'name' => $this->name,
          'pricing_effort_type_id' => $this->pricing_effort_type_id,
          'supplier_id' => $this->supplier_id,
          'category_id' => $this->category_id,
          'enabled' => $this->enabled,
          'prices' => KitPriceResource::collection($this->prices),
          'created_at' => dateFormatFull($this->created_at),
          'updated_at' => dateFormatFull($this->updated_at),
        ];
    }
}
