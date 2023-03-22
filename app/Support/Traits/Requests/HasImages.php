<?php

namespace App\Support\Traits\Requests;

trait HasImages
{
    /**
     * Проверка изображений
     *
     * @param array $rules
     */
    protected function setImagesRule(array &$rules)
    {
        if ($images = $this->input('images')) {
            $existingImagesIds = $this->getEntity()->media()->get(['id']);
            $existingImagesIds = array_column($existingImagesIds->toArray(), 'id');

            foreach ($images as $imageKey => $image) {
                $rules["images.{$imageKey}"] = [
                    'bail', 'array',

                    function($attribute, $value, $fail) use($existingImagesIds, $image) {
                        if (! in_array($image['id'], $existingImagesIds)) {
                            return $fail(\Lang::get("validation.images.exists", ['image' => $image['id']]));
                        }
                    },
                ];
            }
        }
    }
}