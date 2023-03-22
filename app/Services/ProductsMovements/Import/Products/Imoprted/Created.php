<?php

declare(strict_types = 1);

namespace App\Services\ProductsMovements\Import\Products\Imported;

/**
 * Class Created
 *
 * @package App\Services\ProductsMovements\Import\Products\Imported
 */
class Created extends Imported
{

    public function isCreated(): bool
    {
        return true;
    }
}
