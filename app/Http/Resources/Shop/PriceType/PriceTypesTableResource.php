<?php

namespace App\Http\Resources\Shop\PriceType;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceTypesTableResource extends JsonResource
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
            'id'       => $this['id'],
            'title'    => $this['title'],
            'enabled'  => $this['enabled'],
            'position' => $this['position'],
            'i18n'     => $this->i18n->toArray()
        ];
    }
}
