<?php

namespace App\Http\Resources\Shop\Returns;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Carbon;

class ReturnsListCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return [
                    'id'         => $item->id,
                    'name'       => $item->product->i18n[0]->title,
                    'status'     => $item->status->i18n[0]->name,
                    'created_at' => Carbon::parse($item->created_at)->format('d/m/Y'),    //format('d-m-Y H:i:s'),
                    'product_id' => $item->product_id,
                    'status_id'  => $item->status_id,
                ];
            }),
        ];
    }
}
