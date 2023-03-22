<?php

namespace App\Http\Resources\Territory\Cities;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ListCitiesTopRegionCollection extends ResourceCollection
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
            })
        ];
    }
}
