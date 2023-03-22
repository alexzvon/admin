<?php

namespace App\MediaLibrary\ResponsiveImages;

use Spatie\MediaLibrary\Models\Media;
use Spatie\TemporaryDirectory\TemporaryDirectory as BaseTemporaryDirectory;
use Spatie\MediaLibrary\ResponsiveImages\ResponsiveImageGenerator as Base;

class ResponsiveImageGenerator extends Base
{
    public function generateTinyJpg(Media $media, string $originalImagePath, string $conversionName, BaseTemporaryDirectory $temporaryDirectory)
    {
        return;
    }
}
