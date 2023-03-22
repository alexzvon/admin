<?php

namespace App\Http\Resources\Shop\Attribute;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeOptionResource extends JsonResource
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
            'id'           => $this->id,
            'enabled'      => $this->enabled,
            'position'     => $this->position,
            'attribute_id' => $this->attribute_id,
            'i18n'         => $this->i18n->toArray(),
            'value'        => $this->value->toArray(),
        ];
    }
}
