<?php

declare(strict_types = 1);

namespace App\Services\ProductsMovements\Import\Products\Imported;

/**
 * Class Updated
 *
 * @package App\Services\ProductsMovements\Import\Products\Imported
 */
class Updated extends Imported
{

    public function isCreated(): bool
    {
        return false;
    }
}
