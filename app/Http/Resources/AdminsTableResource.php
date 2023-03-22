<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminsTableResource extends JsonResource
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
          'id'      => $this->id,
          'name'    => $this->name,
          'email'   => $this->email,
          'enabled' => $this->enabled,
          'roles'   => array_column($this->roles()->get(['id'])->toArray(), 'id'),
          'image'   => new MediaResource($this->getMedia()->first()),
        ];
    }
}