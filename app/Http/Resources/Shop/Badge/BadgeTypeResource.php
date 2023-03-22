<?php

namespace App\Http\Resources\Shop\Badge;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BadgeTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = $this->resource->toArray();
        $data['i18n'] = $this->resource->i18n()->get()->toArray();

        return $data;
    }
}















