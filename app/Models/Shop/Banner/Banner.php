<?php

namespace App\Models\Shop\Banner;

use MosseboShopCore\Models\Shop\Banner\Banner as BaseBanner;
use App\Support\Traits\Models\StatusChangeable;
use App\Support\Traits\Models\RequestSaver;
use App\Support\Traits\Models\Positionable;
use App\Support\Traits\Models\I18nTrait;

class Banner extends BaseBanner
{
    use Positionable, StatusChangeable, RequestSaver, I18nTrait;

    protected $needsToSaveFromRequest = [
        'i18n', 'places'
    ];

    public function placeRelations()
    {
        return $this->hasOne(BannerPlaceRelation::class, 'banner_id', 'id');
    }

    public function places()
    {
        return $this->hasManyThrough(
            BannerPlace::class, BannerPlaceRelation::class,
            $this->relationFieldName, 'id', 'id', 'place_id'
        );
    }

    protected function getFillableData(array $data): array
    {
        $data = parent::getFillableData($data);

        if (isset($data['gradient'])) {
            $data['gradient'] = json_encode($data['gradient'], JSON_UNESCAPED_UNICODE);
        }

        return $data;
    }

    protected function _savePlaces(array $placeIds = [])
    {
        $placeRelations = $this->placeRelations();
        $placeRelations->delete();

        foreach ($placeIds as $placeId) {
            $placeRelations->save(new BannerPlaceRelation(['place_id' => $placeId]));
        }
    }
}
