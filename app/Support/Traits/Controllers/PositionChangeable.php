<?php

namespace App\Support\Traits\Controllers;

use Illuminate\Http\Request;
use App\Events\Entity\EntitiesPositionChanged;

trait PositionChangeable
{
    /**
     * Смена позиций.
     *
     * @param  Request
     * @return Response
     */
    public function positions(Request $request)
    {
        try {
            self::$modelClass::savePositions($request->input('ids'));
        } catch (\Exception $e) {
            //dd($e);
            return response()->json([
                'status' => 'error',
                'message' => "Техническая ошибка (9000). Обратитесь к разработчикам."
            ], 500);
        }

         \Event::fire(new EntitiesPositionChanged(self::$modelClass));

        return response()->json([
            'status' => 'success',
            'message' => $this->lang('positions', null, false)
        ], 201);
    }
}