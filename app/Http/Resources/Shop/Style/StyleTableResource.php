<?php

namespace App\Http\Resources\Shop\Style;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MediaResource;

class StyleTableResource extends JsonResource
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
            'id'             => $this->id,
            'slug'           => $this->slug,
            'enabled'        => $this->enabled,
            'products_count' => $this->products_count,
            'position'       => $this->position,
            'i18n'           => $this->i18n()->get()->toArray(),
            'image'          => new MediaResource($this->getMedia()->first())
        ];

        return $data;
    }
}