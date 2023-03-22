<?php

namespace App\Http\Resources\Territory\Cities;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SearchCitiesCollection extends ResourceCollection
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
            'data' => $this->collection->map(function($city){
                $value = '';
                $region_id = 0;
                $region_name = '';
                $district_id = 0;
                $district_name = '';
                $country_code = 'Ru';
                $country_name = '';

                $value .= $city->name . ' ' . $city->short_name;

                if($city->region instanceof \App\Models\Region) {
                    if($city->region->district instanceof \App\Models\Region) {
                        $value .= ', ' . $city->region->district->name . ' ' . $city->region->district->short_name;
                        $district_id = $city->region->district->id;
                        $district_name = $city->region->district->name . ' ' . $city->region->district->short_name;

                        $value .= ', ' . $city->region->name . ' ' . $city->region->short_name;
                        $region_id = $city->region->id;
                        $region_name = $city->region->name . ' ' . $city->region->short_name;
                    }
                    else {
                        $value .= ', ' . $city->region->name . ' ' . $city->region->short_name;
                        $district_id = $city->region->id;
                        $district_name = $city->region->name . ' ' . $city->region->short_name;
                    }



//                    $country_code = $city->region->country_code;
                }

                $value .= ', ' . $city->countryI18n->name;

                return [
                    'id' => $city->id,
                    'value' => $value,
                    'name' => $city->name . ' ' . $city->short_name,
                    'region_id' => $region_id,
                    'region_name' => $region_name,
                    'district_id' => $district_id,
                    'district_name' => $district_name,
                    'country_code' => $city->countryI18n->country_code,
                    'language_code' => $city->countryI18n->language_code,
                    'country_name' => $city->countryI18n->name,
                ];
        })];
    }
}
