<?php

namespace App\Support\Traits\Controllers;

use App\Events\Entity\EntityCreated;

trait Creatable
{
    /**
     * Создание сущности.
     *
     * @param  Integer
     * @return Response
     */
    public function store()
    {
        try {
            $entity = (new self::$modelClass())->saveFromRequestData($this->request->all());
        }
        catch (\Exception $e) {
            //dd($e);
            return response()->json([
                'status' => 'error',
                'message' => 'Техническая ошибка (2000). Обратитесь к разработчикам.'
            ], 500);
        }

        \Event::fire(new EntityCreated($entity));

        return response()->json([
            'status' => 'success',
            'message' => $this->lang('created', ['id' => $entity->id]),
            'id' => $entity->id,
        ], 200);
    }
}