<?php

namespace App\Http\Resources\Shop\Product;

use App\Http\Resources\Shop\Price\PriceResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * Class ProductSaleResource
 *
 * @package App\Http\Resources\Shop\Product
 */
class ProductSaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id'          => $this->resource->id,
            'enabled'     => $this->resource->enabled,
            'position'    => $this->resource->position,
        ];

        if ($this->resource->date_start) {
            $data['date_start'] = $this->resource->date_start->timestamp;
        }

        if ($this->resource->date_finish) {
            $data['date_finish'] = $this->resource->date_finish->timestamp;
        }

        // todo: Может быть не только товар
        $product = $this->resource
            ->item()
            ->with('i18n')
            ->first();

        if ($product) {
            $data['product'] = [
                'id' => $product->id,
                'i18n' => $product->i18n()->get()->toArray()
            ];

            $data['prices'] = PriceResource::collection($product->prices()->where('price_type_id', config('shop.price_types.sale'))->get());
        }

        return $data;
    }
}
