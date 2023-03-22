<?php

namespace App\Http\Resources\Territory\Cities;

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
        $region_name = '';
        $district_name = '';
        $country_code = 'Ru';
        $country_name = '';

        if($this->region instanceof \App\Models\Region) {
            if($this->district instanceof \App\Models\Region) {
                $district_name = $this->district->name . ' ' . $this->district->short_name;
                $region_name = $this->region->name . ' ' . $this->region->short_name;
            }
            else {
                $district_name = $this->region->name . ' ' . $this->region->short_name;
            }

            $country_code = $this->region->country_code;
        }

        if('Ru' === $country_code) {
            $country_name = 'Россия';
        }

        return [
            'data' => [
                'id' => $this->id,
                'name' => $this->city->name . ' ' . $this->city->short_name,
                'cities_id' => $this->city->id,
                'region_name' => $region_name,
                'district_name' => $district_name,
                'country_name' => $this->countryI18n->name,
                'gmt_id' => $this->gmt_id,
                'percent' => $this->percent,
                'status' => $this->status,
            ]
        ];
    }
}
