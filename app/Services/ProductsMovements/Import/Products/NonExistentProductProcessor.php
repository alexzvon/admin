<?php

declare(strict_types = 1);

namespace App\Services\ProductsMovements\Import\Products;

/**
 * Class NonExistentProductProcessor
 *
 * @package App\Services\ProductsMovements\Import\Products
 */
class NonExistentProductProcessor extends ProductProcessor
{
    /**
     * Check if we receive any new images to associate.
     * Define their
     *
     */
    protected function saveImages()
    {
        $images = $this->parametersBag->images;

        if (null === $images) {
            return;
        }

        $hashes = self::getReceivedImagesHashes();

        self::addImagesToProduct($hashes);
    }

    /**
     * Returns array of hashes of files that we receive as array of URL's.
     *
     * @return array
     */
    protected function getReceivedImagesHashes(): array
    {
        return array_flip($this->parametersBag->images);
    }
}
