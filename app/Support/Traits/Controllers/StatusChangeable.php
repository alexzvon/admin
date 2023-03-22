<?php

namespace App\Support\Traits\Controllers;

use App\Events\Entity\EntityStatusChanged;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;

trait StatusChangeable
{
    /**
     * Смена статуса сущности.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function status(int $id): JsonResponse
    {
        $model = $this->getModel($id);

        $model->statusToggle();

        Event::fire(new EntityStatusChanged($model));

        return response()->json([
            'status' => 'success',
            'message' => $this->lang("status." . ($model->enabled ? 'enabled' : 'disabled'), ['id' => $model[$model->getKeyName()]])
        ], 201);
    }
}