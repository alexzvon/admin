<?php

declare(strict_types = 1);

namespace App\Support\Traits\Controllers;

use App\Events\Entity\EntityUpdated;
use App\Exceptions\AdminException;
//use App\Models\Shop\Category\Category;
use App\Http\Requests\Shop\Product\Update\UpdateRequest;
use Illuminate\Http\JsonResponse;


/**
 * Trait Updatable
 *
 * @package App\Support\Traits\Controllers
 */
trait Updatable
{
    /**
     * Изменение сущности.
     * @param $entityId
     * @return JsonResponse
     */
    protected function update($entityId): JsonResponse
    {
        $entity = $this->getModel($entityId);

        try {
            $entity = $entity->saveFromRequestData($this->request->all());
        }
        catch (AdminException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'errors'  => $e->getParams(),
            ], 422);
        }
        catch (\Exception $e) {
            //dd($e);
            \Log::alert('--error of updating process--');
            \Log::alert($e->getMessage());
            \Log::alert($e->getTraceAsString());

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
                //'message' => 'Техническая ошибка (2001). Обратитесь к разработчикам.'
            ], 500);
        }

        $entity = $entity->refresh();

        /*
         * @s3
         *
         * if (!is_null($entity->image) && static::$modelClass === Category::class) {
            try {
                $entity->miniature_image = $entity->image->getPath('small');
                $entity->save();
            } catch (\Throwable $exception) {
                $entity->miniature_image = $entity->image->getPath();
                $entity->save();
            }
        }*/

        $entity = $entity->fresh();

        \Event::fire(new EntityUpdated($entity));
        $entityName = static::$entityName;

        return response()->json([
            'status'  => 'success',
            'message' => $this->lang('updated', ['id' => $entity->id]),
            "{$entityName}" => static::toResource($entity)
        ], 200);
    }
}
