<?php

namespace App\Http\Resources\Shop\Banner;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerTableResource extends JsonResource
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
            'id'          => $this->resource->id,
            'enabled'     => $this->resource->enabled,
            'position'    => $this->resource->position,
            'created_at'  => dateFormatFull($this->resource->created_at),
            'updated_at'  => dateFormatFull($this->resource->updated_at),
            'i18n'        => $this->resource->i18n->toArray(),

            'places'      => array_column($this->resource->places->toArray(), 'id'),
        ];
    }
}
