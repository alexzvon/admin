<?php

namespace App\Http\Resources\Income;

use Illuminate\Http\Resources\Json\ResourceCollection;

class IncomeNotPagenationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($item){
                $type = '';

                switch($item->type) {
                    case 'FranchiseClientIndividually':
                        $type = 'Индивидуальный';
                        break;
                    case 'FranchiseClientDesigner':
                        $type = 'Дизайнер';
                        break;
                    case 'FranchiseClientEntity':
                        $type = 'Компания';
                        break;
                    default:
                        break;
                }

                return [
                    'label' => $item->i18n[0]->title,
                    'value' => $item->id,
                    'percent' => $item->percent,
                    'type' => $item->type,
                    'type_name' => $type,
                ];
            })
        ];
    }
}
