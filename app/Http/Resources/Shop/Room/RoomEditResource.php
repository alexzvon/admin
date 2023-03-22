<?php

namespace App\Http\Resources\Shop\Room;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MediaResource;

class RoomEditResource extends JsonResource
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
            'slug'       => $this->slug,
            'enabled'    => $this->enabled,
            'created_at' => dateFormatFull($this->created_at),
            'updated_at' => dateFormatFull($this->updated_at),
            'i18n'       => $this->i18n->toArray(),
            'image'      => new MediaResource($this->getMedia()->first())
        ];
    }
}
