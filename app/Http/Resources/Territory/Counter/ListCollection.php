<?php

namespace App\Http\Resources\Territory\Counter;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ListCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($item){
                return [
                    'id' => $item->id,
                    'name' => $item->i18n[0]->title,
                ];
            }),
        ];
    }
}
