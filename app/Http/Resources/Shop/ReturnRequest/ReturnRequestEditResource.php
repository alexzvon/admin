<?php

namespace App\Http\Resources\Shop\ReturnRequest;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

/**
 * Class ReturnRequestEditResource
 *
 * @package App\Http\Resources\Shop\ReturnRequest
 */
class ReturnRequestEditResource extends JsonResource
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
            'id'                => $this->resource->id,
            'name'              => $this->resource->name,
            'uses_count'        => $this->resource->uses_count,
            'amount'            => $this->resource->amount,
            'currency_code'     => $this->resource->currency_code,
            'percent'           => $this->resource->percent,
            'quantity'          => $this->resource->quantity,
            'quantity_per_user' => $this->resource->quantity_per_user,
            'position'          => $this->resource->position,
            'enabled'           => $this->resource->enabled,
            'created_at'        => dateFormatFull($this->resource->created_at),
            'updated_at'        => dateFormatFull($this->resource->updated_at),
            'i18n'              => $this->resource->i18n()->get()->toArray(),
        ];

        if ($this->resource->date_start) {
            $data['date_start'] = dateFormatFull(new Carbon($this->resource->date_start));
        }

        if ($this->resource->date_finish) {
            $data['date_finish'] = dateFormatFull(new Carbon($this->resource->date_finish));
        }

        foreach ($this->resource->conditions as $condition) {
            $condition = [
                'type' => $condition->type,
                'params' => json_decode($condition->params)
            ];

            $data['conditions'][] = $condition;
        }

        return $data;
    }
}















