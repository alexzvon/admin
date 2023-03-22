<?php

namespace App\Http\Resources\Shop\Banner;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerEditResource extends JsonResource
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
            'id'                      => $this->resource->id,
            'gradient'                => json_decode($this->resource->gradient, true),
            'title_color'             => $this->resource->title_color,
            'caption_color'           => $this->resource->caption_color,
            'button_color'            => $this->resource->button_color,
            'button_background_color' => $this->resource->button_background_color,

            'image'                   => $this->resource->small_image,
            'background_image_1'      => $this->resource->background_image_1,
            'background_image_2'      => $this->resource->background_image_2,

            'enabled'                 => $this->resource->enabled,
            'position'                => $this->resource->position,

            'created_at'              => dateFormatFull($this->resource->created_at),
            'updated_at'              => dateFormatFull($this->resource->updated_at),

            'i18n'                    => $this->resource->i18n->toArray(),

            'places'                  => array_column($this->resource->places()->get()->toArray(), 'id')
        ];
    }
}
