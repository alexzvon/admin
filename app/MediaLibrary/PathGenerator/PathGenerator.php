<?php

namespace App\MediaLibrary\PathGenerator;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator as PathGeneratorInterface;
use Spatie\MediaLibrary\PathGenerator\BasePathGenerator;
use App\MediaLibrary\Models\Media as ProjectMedia;

class PathGenerator extends BasePathGenerator implements PathGeneratorInterface
{
    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        $basePath = 'media';

        if ($media instanceof ProjectMedia) {
            $modelFolder = $media->getMediaFolder();

            if ($modelFolder) {
                $basePath .= '/' . trim($modelFolder, '/');
            }
        }

        $basePath .= '/' . $media->getKey();

        return $basePath;
    }
}
