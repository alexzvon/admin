<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminPermissionGroupEditResource extends JsonResource
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
            'name'        => $this->name,
            'parent_id'   => $this->parent_id,

            'created_at'  => dateFormatFull($this->created_at),
            'updated_at'  => dateFormatFull($this->updated_at),
            'permissions' => $this->permissions()->get()->toArray()
        ];
    }
}