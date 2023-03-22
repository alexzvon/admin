<?php

namespace App\Models\Shop\PriceType;

use App\Support\Traits\Models\StatusChangeable;
use App\Support\Traits\Models\RequestSaver;
use App\Support\Traits\Models\Positionable;
use App\Support\Traits\Models\I18nTrait;

use MosseboShopCore\Models\Shop\PriceType\PriceType as BasePriceType;

class PriceType extends BasePriceType
{
    use StatusChangeable, RequestSaver, Positionable, I18nTrait;

    protected $needsToSaveFromRequest = [
        'i18n'
    ];
}