<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Resources\Json\JsonResource;

class CargoPlaceResource extends JsonResource
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
            'id'               => $this->getKey(),
            'width'            => $this->width,
            'height'           => $this->height,
            'length'           => $this->length,
            'weight'           => $this->weight,
        ];
    }
}
