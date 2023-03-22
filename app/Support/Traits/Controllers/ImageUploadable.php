<?php

namespace App\Support\Traits\Controllers;

use App\Http\Resources\MediaResource;
use App\Http\Requests\ImageUploadRequest;
use App\Models\Shop\Category\Category;

trait ImageUploadable {
    /**
    * Загрузка изображений для конкретного товара.
    *
    * @param ImageUploadRequest $request
    * @param Integer $entityId
    * @return \Illuminate\Http\JsonResponse
    */
    public function imageUpload(ImageUploadRequest $request, $entityId)
    {
        $model = static::getModel($entityId);
        $image = $model->addImageFromFile($request->file('file'));


        /*
         * @s3
         * if (static::$modelClass === Category::class) {
            $model->miniature_image = '/' . $image->getUrl();
            $model->save();
        }*/

        return response()->json([
            'status' => 'success',
            'image' => new MediaResource($image),
        ]);
    }
}
