<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Shop\Product\Product;
use App\Http\Resources\Shop\Product\ProductsTableResource;

class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id'            => $this->resource->id,
            'rate'          => $this->resource->rate,
            'language_code' => $this->resource->language_code,
            'advantages'    => $this->resource->advantages,
            'disadvantages' => $this->resource->disadvantages,
            'comment'       => $this->resource->comment,
            'confirmed'     => $this->resource->confirmed,
            'enabled'       => $this->resource->enabled,
            'usage_time'    => $this->resource->usage_time,
            'created_at'    => dateFormatFull($this->resource->created_at),
            'updated_at'    => dateFormatFull($this->resource->updated_at),
        ];

        if (! $this->resource->confirmed) {
            $data['unconfirmed'] = true;
        }

        $item = $this->resource->item;

        if ($item instanceof Product) {
            $data['product'] = new ProductsTableResource($item);
        }

        if ($this->relationNotEmpty('user')) {
            $data['user'] = new UserResource($this->resource->user);
        }

//        if ($this->relationNotEmpty('likesAndDislikes')) {
            $data['likes'] = new LikeResource($this->resource);
//        }

        return $data;
    }
}
