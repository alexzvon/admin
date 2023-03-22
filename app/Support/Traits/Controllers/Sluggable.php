<?php

declare(strict_types = 1);

namespace App\Support\Traits\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Trait Sluggable
 *
 * @package App\Support\Traits\Controllers
 */
trait Sluggable
{
    /**
     * Проверка существования slug.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function slugAvailable(Request $request): JsonResponse
    {
        return response()->json([
            'status' => self::$modelClass::slugAvailableForTable($request->input('slug')) ? 'success' : 'error'
        ]);
    }

    /**
     * Проверка существования slug у конкретной сущности.
     *
     * @param Request $request
     * @param         $id
     *
     * @return JsonResponse
     */
    public function entitySlugAvailable(Request $request, $id): JsonResponse
    {
        $model = $this->getModel($id);

        return response()->json([
            'status' => $model->slugAvailableForModel($request->input('slug')) ? 'success' : 'error'
        ]);
    }
}
