<?php

namespace App\Http\Resources\Shop\ReturnRequest;

use App\Http\Resources\Shop\Product\Api\ProductEditResource;
use App\Http\Resources\Shop\Product\Api\ProductsTableResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

/**
 * Class ReturnRequestTableResource
 *
 * @package App\Http\Resources\Shop\ReturnRequest
 */
class ReturnRequestTableResource extends JsonResource
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
            'id'          => $this->resource->id,
            'product'     => new ProductsTableResource($this->product),
            'user'        => new UserResource($this->user),
            'status'      => $this->status,
            'order'       => $this->order,
            'description' => $this->description,
            'created_at'  => dateFormatFull($this->resource->created_at),
            'updated_at'  => dateFormatFull($this->resource->updated_at),
        ];

        return $data;
    }
}
