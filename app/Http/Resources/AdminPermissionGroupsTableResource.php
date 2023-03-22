<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminPermissionGroupsTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id'         => $this->id,
            'name'       => $this->name,
            'namespace'  => $this->namespace,
            'parent_id'  => $this->parent_id,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if (! empty($this->children)) {
            $data['children'] = AdminPermissionGroupsTableResource::collection($this->children);
        }

        if (! empty($this->resource['permissions'])) {
            $data['permissions'] = AdminPermissions::collection($this->permissions);
        }

        return $data;
    }
}
