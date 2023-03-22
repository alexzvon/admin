<?php

namespace App\Http\Resources\Shop\Product;

use App\Http\Resources\Shop\CargoPlaceResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\MediaResource;
use App\Http\Resources\Shop\Price\PriceResource;


class ProductEditResource extends JsonResource
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
        $badges = array_column($this->resource->badges()->get()->toArray(), 'badge_type_id');

        return [
            'id'               => $this->id,
            'supplier_id'      => $this->supplier_id,
            'slug'             => $this->slug,
            'is_payable'       => $this->is_payable,
            'enabled'          => $this->enabled,
            'available'        => $this->available,
            'width'            => $this->width,
            'height'           => $this->height,
            'length'           => $this->length,
            'weight'           => $this->weight,
            'delivery_width'   => $this->delivery_width,
            'delivery_height'  => $this->delivery_height,
            'delivery_length'  => $this->delivery_length,
            'delivery_weight'  => $this->delivery_weight,
            'id_from_supplier' => $this->id_from_supplier,
            'gtin'             => $this->gtin,
            'created_at'       => dateFormatFull($this->created_at),
            'updated_at'       => dateFormatFull($this->updated_at),
            'images'           => MediaResource::collection($this->getMedia()),
            'prices'           => PriceResource::collection($this->prices),
            'categories'       => ProductCategoriesResource::collection($this->categoryRelations),
            'rooms'            => ProductRoomsResource::collection($this->roomRelations),
            'styles'           => ProductStylesResource::collection($this->styleRelations),
            'related'          => ProductSearchResource::collection($this->related),
            'i18n'             => $this->i18n()->get()->toArray(),
            'badges'           => $badges,
            'production_time'  => $this->production_time,
            'delivery_time'    => $this->delivery_time,
            'quantity'         => $this->quantity,
            'receipt_date'     => $this->receiptDate,
            'on_order'         => $this->onOrder !== 0,
            'suppliers'        => ProductSuppliersResource::collection($this->suppliers),
            'sale'             => new ProductSaleResource($this->sale),
            'cargo_places'     => CargoPlaceResource::collection($this->cargoPlaces),
        ];
    }
}
