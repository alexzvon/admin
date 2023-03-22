<?php

declare(strict_types=1);

namespace App\Http\Resources\Shop\FastSale;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class FastSaleTableResource
 *
 * @package App\Http\Resources\Shop\FastSale
 */
class FastSaleTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
         $data = [];

         return $data;
    }
}
