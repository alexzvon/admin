<?php

namespace App\Http\Resources\Territory\ShowRoomGroup;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ListShowRoomGroupCollection extends ResourceCollection
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
                    'region' => $item->region->name . ' ' . $item->region->short_name,
                    'name' => $item->i18n[0]->name,
                    'name_vin' => $item->i18n[0]->name_vin,
                    'status' => $item->status ? 'активен' : 'не активен',
                ];
            })
        ];
    }
}
