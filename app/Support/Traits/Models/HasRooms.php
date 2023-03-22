<?php

namespace App\Support\Traits\Models;

use App\Models\Shop\Room\Room;

trait HasRooms
{
    public function rooms()
    {
        return $this->hasManyThrough(
            Room::class, $this->roomRelationModel,
            $this->relationFieldName, 'id', 'id', 'room_id'
        );
    }

    public function roomRelations()
    {
        return $this->hasMany($this->roomRelationModel, $this->relationFieldName);
    }

    /**
     * Сохранение товара.
     *
     * @param array $roomsIds
     */
    protected function _saveRooms(array $roomsIds = [])
    {
        $roomRelations = $this->roomRelations();
        $roomRelations->delete();

        foreach ($roomsIds as $roomId) {
            $roomRelations->save(new $this->roomRelationModel(['room_id' => $roomId]));
        }
    }
}