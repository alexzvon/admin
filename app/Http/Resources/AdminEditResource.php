<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminEditResource extends JsonResource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'enabled'    => $this->enabled,
            'roles'      => array_column($this->roles()->get(['id'])->toArray(), 'id'),
            'created_at' => dateFormatFull($this->created_at),
            'updated_at' => dateFormatFull($this->updated_at),
            'image'      => new MediaResource($this->getMedia($this->mediaCollectionName)->first()),
        ];
    }
}
