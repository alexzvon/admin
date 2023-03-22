<?php

namespace App\Http\Resources\Franchise\Users;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GetListFranchiseUsersCollection extends ResourceCollection
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
                return [
                    'id' => $item->id,
                    'first_name' => $item->first_name,
                    'last_name' => $item->last_name,
                    'phone' => $item->phone,
                    'email' => $item->email,
                    'franchise' => is_null($item->franchise_companies) ? '' : $item->franchise_companies->i18n[0]->title
                ];
            })
        ];
    }
}
