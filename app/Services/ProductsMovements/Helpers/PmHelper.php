<?php

declare(strict_types = 1);

namespace App\Services\ProductsMovements\Helpers;

/**
 * Class PmHelper
 *
 * @package App\Services\ProductsMovements\Helpers
 */
class PmHelper
{
    /**
     * @param string $address
     *
     * @return string
     */
    public static function getFileNameFromUrl(string $address): string
    {
        return substr($address, strrpos($address, '/') + 1) ?: '';
    }
}
