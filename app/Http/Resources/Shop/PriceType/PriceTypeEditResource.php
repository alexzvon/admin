<?php

namespace App\Http\Resources\Shop\PriceType;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceTypeEditResource extends JsonResource
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
            'enabled'    => $this->enabled,
            'created_at' => dateFormatFull($this->created_at),
            'updated_at' => dateFormatFull($this->updated_at),
            'i18n'       => $this->i18n->toArray()
        ];
    }
}
