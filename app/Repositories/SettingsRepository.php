<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use MosseboShopCore\Repositories\SettingsRepository as BaseSettingsRepository;
use App\Models\Settings;

class SettingsRepository extends BaseSettingsRepository
{
    public function getCollectionRaw(): Collection
    {
        return Settings::orderBy('position', 'asc')
            ->get();
    }
}
