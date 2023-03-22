<?php

declare(strict_types=1);

namespace App\Http\Resources\Shop\FastSale;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class FastSaleEditResource
 *
 * @package App\Http\Resources\Shop\FastSale
 */
class FastSaleEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'time_in_minutes'     => $this->time_in_minutes,
            'percent_of_discount' => $this->percent_of_discount,
            'is_enabled'          => $this->is_enabled,
        ];
    }
}
