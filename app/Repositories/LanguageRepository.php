<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use MosseboShopCore\Repositories\LanguageRepository as BaseLanguageRepository;
use App\Models\Language;

class LanguageRepository extends BaseLanguageRepository
{
    public function getCollectionRaw(): Collection
    {
        return Language::orderBy('position', 'asc')->get();
    }
}
