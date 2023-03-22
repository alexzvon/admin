<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'likes' => $this->resource->likesCount,
            'dislikes' => $this->resource->dislikesCount,
            'liked' => $this->resource->liked,
            'disliked' => $this->resource->disliked,
        ];
    }
}