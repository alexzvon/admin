<?php

namespace App\Http\Resources\Territory\ShowRoomRoom;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ListRoomsCollection extends ResourceCollection
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
                    'name' => $item->i18n[0]->name,
                    'cities' => $item->cities->i18n[0]->title,
                    'address' => $item->i18n[0]->address,
                    'status' => $item->status ? 'активен' : 'не активен',
                ];
            })
        ];
    }
}
