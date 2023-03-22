<?php
declare(strict_types=1);

namespace App\Http\Resources\Shop\Product\Api;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * Class ProductSearchResource
 *
 * @package App\Http\Resources\Shop\Product\Api
 */
class ProductSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        $images = MediaResource::collection($this->getMedia());
        $pathes = json_decode($images->first()->pathes);

        return [
            'id'          => $this->resource->id,
            'enabled'     => $this->resource->enabled,
            'i18n'        => $this->resource->i18n->toArray(),
            'image'       => Storage::url($pathes->original),
            'image_thumb' => Storage::url($pathes->thumb->src),
        ];
    }
}
