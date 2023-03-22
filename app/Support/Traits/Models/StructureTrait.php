<?php

namespace App\Support\Traits\Models;

use Spatie\MediaLibrary\Models\Media;
use Spatie\Image\Manipulations;
use Spatie\Image\Exceptions\InvalidManipulation;

trait StructureTrait
{
    use StatusChangeable, Positionable, Sluggable, RequestSaver, HasMediaTrait, I18nTrait;

    /**
     * Задает преобразователи изображений.
     *
     * @param Media|null $media
     *
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('small')
            ->crop(Manipulations::CROP_CENTER, 400, 400)
            ->background('fff')
            ->withResponsiveImages()
            ->performOnCollections('image');

        $this->addMediaConversion('oneThird')
            ->crop(Manipulations::CROP_CENTER, 700, 420)
            ->background('fff')
            ->withResponsiveImages()
            ->performOnCollections('image');

        $this->addMediaConversion('oneHalf')
            ->crop(Manipulations::CROP_CENTER, 1080, 648)
            ->background('fff')
            ->withResponsiveImages()
            ->performOnCollections('image');

        $this->addMediaConversion('small')
            ->crop(Manipulations::CROP_CENTER, 400, 400)
            ->background('fff')
            ->withResponsiveImages()
            ->nonQueued()
            ->performOnCollections('temp');
    }
}
