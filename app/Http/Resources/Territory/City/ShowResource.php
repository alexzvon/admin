<?php

namespace App\Http\Resources\Territory\City;

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
                'input_name' => $this->i18n[0]->title,
                'input_country' => $this->country_id,
                'input_gmt' => $this->gmt_id,
                'input_percent' => $this->percent,
                'input_status' => $this->status,
            ]
        ];
    }
}
