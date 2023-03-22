<?php

declare(strict_types = 1);

namespace App\Http\Resources\Shop\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProductSuppliersResource
 *
 * @package App\Http\Resources\Shop\Product
 */
class ProductSuppliersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->id;
    }
}
