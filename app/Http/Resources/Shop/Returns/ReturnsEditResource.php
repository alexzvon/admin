<?php

namespace App\Http\Resources\Shop\Returns;

use Illuminate\Http\Resources\Json\JsonResource;

class ReturnsEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'return_id'   => $this->id,
            'order_id'    => $this->order_id,
            'quantity'    => $this->quantity,
            'status_id'   => $this->status_id,
            'price'       => $this->order->orderProducts[0]->final_price,
            'description' => $this->description,
            'comment'     => $this->comment,
            'title'       => $this->product->i18n[0]->title,
            'media'       => $this->collection_media,
        ];

        return $data;
    }
}
