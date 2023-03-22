<?php

namespace App\Models\Shop\Promo;

use Carbon\Carbon;
use MosseboShopCore\Models\Shop\Promo\PromoCode as BasePromoCode;
use App\Support\Traits\Models\RequestSaver;
use App\Support\Traits\Models\StatusChangeable;
use App\Support\Traits\Models\I18nTrait;

class PromoCode extends BasePromoCode
{
    use RequestSaver, StatusChangeable, I18nTrait;

    protected $needsToSaveFromRequest = [
        'i18n',
        'conditions',
    ];

    public function conditions()
    {
        return $this->hasMany(PromoCondition::class, $this->relationFieldName, 'id');
    }

    public function uses()
    {
        return $this->hasMany(PromoUse::class, $this->relationFieldName, 'id');
    }

    protected function getFillableData(array $data): array
    {
        $data = parent::getFillableData($data);

        if (isset($data['date_start'])) {
            $data['date_start'] = Carbon::createFromTimestamp($data['date_start']);
        }

        if (isset($data['date_finish'])) {
            $data['date_finish'] = Carbon::createFromTimestamp($data['date_finish']);
        }

        return $data;
    }

    public function _saveConditions($conditions)
    {
        $this->conditions()->delete();

        $existingConditionTypes = config('shop.promo.conditions.types');

        foreach ($existingConditionTypes as $type) {
            if (! isset($conditions[$type])) {
                continue;
            }

            $this->conditions()->save(
                new PromoCondition([
                    'type'   => $type,
                    'params' => json_encode($conditions[$type], JSON_UNESCAPED_UNICODE)
                ])
            );
        }
    }
}