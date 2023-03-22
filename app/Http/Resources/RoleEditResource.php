<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleEditResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'importance'  => $this->importance,
            'created_at'  => dateFormatFull($this->created_at),
            'updated_at'  => dateFormatFull($this->updated_at),
            'permissions' => array_column($this->permissionRelations()->get(['admin_role_permission_id'])->toArray(), 'admin_role_permission_id')
        ];
    }
}
