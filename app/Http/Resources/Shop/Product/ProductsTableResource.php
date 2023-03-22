<?php

namespace App\Http\Resources\Shop\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\Shop\Price\PriceResource;

class ProductsTableResource extends JsonResource
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
            'id'         => $this->id,
            'is_payable' => $this->is_payable,
            'enabled'    => $this->enabled,
            'created_at' => dateFormatFull($this->created_at),
            'i18n'       => $this->i18n->toArray()
        ];

        if ($this->relationNotEmpty('prices')) {
            $data['prices'] = PriceResource::collection($this->prices);
        }

        if ($this->relationNotEmpty('image')) {
            $data['image'] =  new MediaResource($this->image);
        }

        return $data;
    }
}
