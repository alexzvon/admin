<?php

namespace App\Http\Resources\Shop\QuickFilter;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MediaResource;
use Illuminate\Support\Facades\Storage;

class ShowQuickFilterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $images = MediaResource::collection($this->getMedia('adm'));

        $pathes = json_decode($images->first()->pathes ?? null);

        return [
            'category_id' => $this->category_id ?? null,
            'name' => $this->i18n[0]->title ?? null,
            'displayed_name' => $this->i18n[0]->displayed_name ?? null,
            'address' => $this->adress ?? null,
            'status' => $this->status ?? null,
            'file' => Storage::url($pathes->original ?? null) ?? null,
            'slug' => $this->slug,
        ];
    }
}
