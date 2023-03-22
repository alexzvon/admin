<?php

namespace App\Http\Resources\Territory\City;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CityNotPagenationCollection extends ResourceCollection
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
                    'value' => $item->id,
                    'label' => $item->i18n[0]->title,
                    'percent' => $item->percent,
                ];
            })
        ];
    }
}
