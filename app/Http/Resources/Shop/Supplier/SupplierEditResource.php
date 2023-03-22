<?php

namespace App\Http\Resources\Shop\Supplier;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($this);
        $data['created_at'] = dateFormatFull($this->created_at);
        $data['updated_at'] = dateFormatFull($this->updated_at);

        return $data;
    }
}
