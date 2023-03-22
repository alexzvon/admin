<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use MosseboShopCore\Repositories\CountryRepository as BaseCountryRepository;
use App\Models\Country;

class CountryRepository extends BaseCountryRepository
{
    public function getCollectionRaw(): Collection
    {
        return Country::orderBy('position', 'asc')
            ->get();
    }
}
