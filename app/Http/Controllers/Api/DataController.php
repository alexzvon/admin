<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\DataHandler;

// todo: надо прогонять через ресурсы

class DataController extends ApiController
{
    public function relevantKey()
    {
        return [
            'key' => DataHandler::getRelevantKey(),
        ];
    }

    public function get(Request $request)
    {
        $labels = $request->input('labels');

        if (empty($labels)) {
            $data = [];
        }
        else {
            if (!is_array($labels)) {
                $labels = [$labels];
            }

            $data = DataHandler::get($labels);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public function cache()
    {
        DataHandler::clearCache();

        return response()->json([
            'status' => 'success',
            'message' => 'Кэш очищен.'
        ], 200);
    }
}