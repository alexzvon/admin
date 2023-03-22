<?php

namespace App\MediaLibrary;

use Illuminate\Support\Collection;
use Spatie\Image\Image;
use Spatie\MediaLibrary\ResponsiveImages\WidthCalculator\WidthCalculator;

class FileSizeOptimizedWidthCalculator implements WidthCalculator
{
    public function calculateWidthsFromFile(string $imagePath): Collection
    {
        $image = Image::load($imagePath);

        $width = $image->getWidth();
        $height = $image->getHeight();
        $fileSize = filesize($imagePath);

        return $this->calculateWidths($fileSize, $width, $height);
    }

    public function calculateWidths(int $fileSize, int $width, int $height): Collection
    {
        $targetWidths = collect();

        $targetWidths->push($width);
        $targetWidths->push($width / 2);

        return $targetWidths;
    }
}
