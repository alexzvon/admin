<?php

namespace App\Http\Resources\Shop\Category;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MediaResource;

class CategoryTableResource extends JsonResource
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
             'id'              => $this->id,
             'parent_id'       => $this->parent_id,
             'slug'            => $this->slug,
             'enabled'         => $this->enabled,
             'products_count'  => $this->products_count,
             'position'        => $this->position,
             'i18n'            => $this->i18n->toArray(),
             'miniature_image' => $this->miniature_image,
         ];

         if ($this->relationNotEmpty('image')) {
             $data['image'] = new MediaResource($this->image);
         }

         if ($this->relationNotEmpty('children')) {
             $data['children'] = CategoryTableResource::collection($this->children);
         }

         return $data;
    }
}