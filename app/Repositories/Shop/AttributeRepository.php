<?php

namespace App\Repositories\Shop;

use Illuminate\Support\Collection;
use MosseboShopCore\Repositories\Shop\AttributeRepository as BaseAttributeRepository;
use App\Models\Shop\Attribute\Attribute;

class AttributeRepository extends BaseAttributeRepository
{
    public function getCollectionRaw(): Collection
    {
        return Attribute::orderBy('position', 'asc')
            ->with([
                'i18n',
                'options' => function ($query) {
                    $query
                        ->with('i18n')
                        ->orderBy('position', 'asc');
                }
            ])->get();
    }
}
