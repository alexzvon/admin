<?php

namespace App\Http\Resources\Territory\ShowRoomGroup;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ListRegionTopCollection extends ResourceCollection
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
                    'name_full' => $item->name . ' ' . $item->short_name . ', ' . $item->countryI18n->name,
                ];
            })
        ];
    }
}
