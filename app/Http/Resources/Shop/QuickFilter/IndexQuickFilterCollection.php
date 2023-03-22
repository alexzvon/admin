<?php

namespace App\Http\Resources\Shop\QuickFilter;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class IndexQuickFilterCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->map(function($item){
                return [
                    'id' => $item->id,
                    'name' => $item->i18n[0]->title,
                    'displayed_name' => $item->i18n[0]->displayed_name ?? null,
                    'category' => $item->category->i18n[0]->title,
                    'status' => $item->status ? 'активен' : 'не активен'
                ];
            }),
        ];
    }
}
