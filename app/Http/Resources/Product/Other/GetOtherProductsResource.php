<?php

namespace App\Http\Resources\Product\Other;

use Illuminate\Http\Resources\Json\JsonResource;

class GetOtherProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $products = [];

        foreach ($this->resource as $item) {
            $products[] = [
                'id' => $item->childrenProductI18n->product_id,
                'title' => $item->childrenProductI18n->title,
            ];
        }

        return ['data' => ['products' => $products]];
    }
}
