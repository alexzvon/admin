<?php

namespace App\Http\Resources\Companies;

use Illuminate\Http\Resources\Json\ResourceCollection;

class IndexCollection extends ResourceCollection
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
                    'fio' => $item->i18n[0]->title,
                    'name' => $item->name,
                    'cities' => is_null($item->cities) ? 0 : $item->cities->i18n[0]->title,
                    'delivery_address' => $item->delivery_address,
                    'status' => $item->status ? 'активен' : 'не активен'
                ];
            })
        ];
    }
}
