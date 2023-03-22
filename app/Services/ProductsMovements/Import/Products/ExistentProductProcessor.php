<?php

declare(strict_types = 1);

namespace App\Services\ProductsMovements\Import\Products;

use App\MediaLibrary\Models\Media;
use App\Services\ProductsMovements\Helpers\PmHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class ExistentProductProcessor
 *
 * @package App\Services\ProductsMovements\Import\Products
 */
class ExistentProductProcessor extends ProductProcessor
{
    /**
     * Adds new
     */
    protected function saveImages()
    {
        $images = $this->parametersBag->images;

        if (null === $images) {
            return;
        }

        $hashes = self::getNeededHashes();

        self::addImagesToProduct($hashes);
    }

    /**
     * Returns array of hashes of images associated with product.
     *
     * @return array
     */
    protected function getImagesHashes(): array
    {
        $hashes = [];

        foreach ($this->product->getMedia() as $image) {
            /** @var Media $image */
            try {
                $hashes[] = hash_file('md5', $image->getUrl());
            } catch (\Throwable $exception) {

            }
        }

        return $hashes;
    }

    /**
     * Returns array of hashes of images that we have to associate with the product.
     *
     * We check if hash of received file does not match
     * any hash of already associated files.
     *
     * @return array
     */
    protected function getNeededHashes(): array
    {
        $receivedHashes = self::getReceivedImagesHashes();
        $hashes = self::getImagesHashes();

        return array_filter($receivedHashes, function ($item) use ($hashes) {
            return !in_array($item, $hashes);
        });
    }


    /**
     * Returns array of hashes of files that we receive as array of URL's.
     *
     * @return array
     */
    protected function getReceivedImagesHashes(): array
    {
        $addresses = $this->parametersBag->images;

        $retrievedFilesHashes = [];

        foreach ($addresses as $address) {
            try {
                $info = pathinfo($address);
                $contents = file_get_contents($address);
            } catch (\Throwable $exception) {
                try {
                    $address = self::tryToConvertFromCyrillic($address);
                    $contents = file_get_contents($address);
                } catch (\Throwable $exception) {
                    continue;
                }
            }

            $file = '/tmp/' . $info['basename'];
            file_put_contents($file, $contents);
            $uploadedFile = new UploadedFile($file, $info['basename']);

            $name = PmHelper::getFileNameFromUrl($address);
            $path = Storage::putFileAs(
                'tempImages', $uploadedFile, $name
            );

            $savedPath = Storage::disk('s3')->url($path);
            $retrievedFilesHashes[$address] = hash_file('md5', $savedPath);
            Storage::delete($savedPath);
        }

        return $retrievedFilesHashes;
    }

    /**
     * @param string $address
     *
     * @return string
     */
    protected function tryToConvertFromCyrillic(string $address): string
    {
        preg_match('/^(http|https):\/\/([а-яА-Я]+.+[а-яА-Я]+)/', $address, $new);
        $protocol = $new[1];
        $cyrDomain = $new[2];
        $len = mb_strlen($cyrDomain);
        $cyrDomain = mb_substr($address, strlen($protocol) + 3, $len);
        $latAddress = mb_substr($address, strlen($protocol) + 3 + $len);
        $latDomain = idn_to_ascii($cyrDomain);
        $address = $protocol . '://' . $latDomain . $latAddress;

        return $address;
    }
}
