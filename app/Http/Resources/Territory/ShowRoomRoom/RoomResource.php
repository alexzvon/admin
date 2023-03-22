<?php

namespace App\Http\Resources\Territory\ShowRoomRoom;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
                'group_id' => $this->group_id,
                'name' => $this->i18n[0]->name,
                'address' => $this->i18n[0]->address,
                'work_time' => $this->i18n[0]->work_time,
                'phone' => $this->phone,
                'email' => $this->email,
                'lat' => $this->lat,
                'lon' => $this->lon,
                'video_youtube' => $this->video_youtube,
                'video_vimeo' => $this->video_vimeo,
                'status' => $this->status,
                'images' => MediaResource::collection($this->getMedia($this->getCollection())),
            ]
        ];
    }
}
