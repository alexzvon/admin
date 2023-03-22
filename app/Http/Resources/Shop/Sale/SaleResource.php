<?php

namespace App\Http\Resources\Shop\Sale;

use App\Http\Resources\Shop\Price\PriceResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class SaleResource extends JsonResource
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
            'enabled'     => $this->resource->enabled,
            'position'    => $this->resource->position,
        ];

        if ($this->resource->date_start) {
            $data['date_start'] = dateFormatFull(new Carbon($this->resource->date_start));
        }

        if ($this->resource->date_finish) {
            $data['date_finish'] = dateFormatFull(new Carbon($this->resource->date_finish));
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