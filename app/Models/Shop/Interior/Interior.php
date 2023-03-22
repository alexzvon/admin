<?php

namespace App\Models\Shop\Interior;

use MosseboShopCore\Models\Shop\Interior\Interior as BaseInterior;
use App\Support\Traits\Models\I18nTrait;
use App\Support\Traits\Models\RequestSaver;
use App\Support\Traits\Models\HasRooms;
use App\Support\Traits\Models\HasStyles;

class Interior extends BaseInterior
{
    use I18nTrait, RequestSaver, HasRooms, HasStyles;

    protected $needsToSaveFromRequest = [
        'i18n',
        'rooms',
        'styles',
        'points'
    ];

    protected $roomRelationModel = InteriorRoom::class;
    protected $styleRelationModel = InteriorStyle::class;

    public function points()
    {
        return $this->hasMany(InteriorPoint::class, $this->relationFieldName, 'id');
    }

    public function _savePoints(array $points = [])
    {
        $pointsRelation = $this->points();
        $pointsRelation->delete();

        foreach ($points as $point) {
            $pointsRelation->save(new InteriorPoint([
                'product_id' => $point['product_id'],
                'position_x' => $point['position_x'],
                'position_y' => $point['position_y'],
                'is_similar' => $point['is_similar'],
            ]));
        }
    }
}