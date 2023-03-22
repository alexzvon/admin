<?php

namespace App\Repositories\Shop;

use Illuminate\Support\Collection;
use MosseboShopCore\Repositories\Shop\StyleRepository as BaseStyleRepository;
use App\Models\Shop\Style\Style;

class StyleRepository extends BaseStyleRepository
{
    public function getCollectionRaw(): Collection
    {
        return Style::with('i18n')
            ->withProductCount()
            ->with('image')
            ->get();
    }
}
