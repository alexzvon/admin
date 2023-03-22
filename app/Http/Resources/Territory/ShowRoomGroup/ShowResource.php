<?php

namespace App\Http\Resources\Territory\ShowRoomGroup;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'region_id' => $this->region_id,
                'name' => $this->i18n[0]->name,
                'name_vin' => $this->i18n[0]->name_vin,
                'status' => $this->status ? true : false,
            ]
        ];
    }
}
