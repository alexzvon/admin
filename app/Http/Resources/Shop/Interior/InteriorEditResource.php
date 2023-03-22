<?php

namespace App\Http\Resources\Shop\Interior;

use Illuminate\Http\Resources\Json\JsonResource;

class InteriorEditResource extends JsonResource
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
            'points'     => InteriorPointResource::collection($this->resource->points),
            'rooms'      => array_column($this->roomRelations()->get()->toArray(), 'room_id'),
            'styles'     => array_column($this->styleRelations()->get()->toArray(), 'style_id'),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}

