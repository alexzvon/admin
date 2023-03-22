<?php

namespace App\Http\Resources\Shop\Interior;

use Illuminate\Http\Resources\Json\JsonResource;

class InteriorTableResource extends JsonResource
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
            'image'      => $this->resource->image,
            'i18n'       => $this->resource->i18n->toArray(),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
