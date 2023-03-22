<?php

namespace App\Support\Traits\Models;

trait Positionable
{
    public static function savePositions(Array $ids = [])
    {
        \DB::transaction(function() use($ids) {
            foreach ($ids as $position => $id) {
                self::where('id', $id)->update(['position' => $position]);
            }
        });
    }

    public static function getLastPosition()
    {
        $pos = self::select('position')->orderBy('position', 'desc')->first();

        return $pos['position'];
    }
}