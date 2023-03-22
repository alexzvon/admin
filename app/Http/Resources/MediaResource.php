<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge($this->getImagePathes(), [
            'id'   => $this->id,
            'size' => $this->size,
            'type' => $this->mime_type,
        ]);
    }
}