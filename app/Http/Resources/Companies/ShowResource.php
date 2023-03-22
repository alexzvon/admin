<?php

namespace App\Http\Resources\Companies;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MediaResource;
use Illuminate\Support\Facades\Storage;

class ShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $images = MediaResource::collection($this->getMedia('franchise'));
        $image = $images->first();
        $pathes = '';
        $original = '';

        if ($image) {
            $pathes = json_decode($image->pathes);
            $original = Storage::url($pathes->original);
        }

        $input_income  = [];

        if (0 < count($this->income)) {
            foreach($this->income as $item) {
                $type_name = '';

                switch($item->type) {
                    case 'FranchiseClientIndividually':
                        $type_name = 'Индивидуальный';
                        break;
                    case 'FranchiseClientDesigner':
                        $type_name = 'Дизайнер';
                        break;
                    case 'FranchiseClientEntity':
                        $type_name = 'Компания';
                        break;
                    default:
                        break;
                }

                $input_income[] = [
                    'value' => $item->id,
                    'label' => $item->i18n[0]->title,
                    'percent' => $item->pivot->percent,
                    'type_name' => $type_name,
                    'type' => $item->type,
                ];
            }
        }

        return [
            'id' => $this->id,
            'input_user' => $this->user_id,
            'input_status' => $this->status,
            'input_fio' => $this->i18n[0]->title,
            'input_name' => $this->name,
            'input_cities' => !is_null($this->cities) ? $this->cities->id : null,
            'input_delivery_address' => $this->delivery_address,
            'input_percent' => !is_null($this->cities) ? $this->cities->percent : null,
            'input_address' => $this->address,
            'input_email' => $this->email,
            'input_phone' => $this->phone,
            'input_bank' => $this->bank_name,
            'input_bik' => $this->bank_identification_code,
            'input_kor_account' => $this->bank_correspondent_account,
            'input_tin' => $this->taxpayer_identification_number,
            'input_trrc' => $this->tax_registration_reason_code,
            'input_rach_account' => $this->account,
            'input_image' => $original,
            'input_income' => $input_income,
            'input_retail_crm_id' => $this->retail_id,
        ];
    }
}

