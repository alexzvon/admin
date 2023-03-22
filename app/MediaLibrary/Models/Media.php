<?php

namespace App\MediaLibrary\Models;

use Spatie\MediaLibrary\Models\Media as BaseMedia;
use Spatie\MediaLibrary\Conversion\ConversionCollection;
use Illuminate\Support\Str;

class Media extends BaseMedia
{
    public function save(array $options = [])
    {
        if (!$this->id) {
            parent::save($options);
        }

        $this->pathes = json_encode($this->generateImagePathes());
        parent::save($options);
    }

    public function generateImagePathes()
    {
        $pathes = [
            'original' => $this->getPath()
        ];

        foreach ($this->responsive_images as $conversionName => $imageUrl) {
            $conversionImages = $this->responsiveImages($conversionName);

            $imageUrls = $conversionImages->getUrls();

            if (isset($imageUrls[0])) {
                if (isset($imageUrls[1])) {
                    $pathes[$conversionName]['srcset'] = $imageUrls[0];
                }
                else {
                    $pathes[$conversionName]['src'] = $imageUrls[0];
                }
            }

            if (isset($imageUrls[1])) {
                $pathes[$conversionName]['src'] = $imageUrls[1];
            }
        }

        return $pathes;
    }

    public function getImagePathes()
    {

        // @s3 adapted
        if ($this->pathes){
            $pathes = json_decode($this->pathes, true);
            foreach ($pathes as $index => $path){
                if ($index === 'original'){
                    $pathes['original'] = $this->getFullUrl();
                }
            }
            return $pathes;
        } else {
            return ['original' => $this->getFullUrl()];
        }

    }

    public function getMediaFolder()
    {
        return strtolower((new \ReflectionClass($this->model))->getShortName());
    }
}