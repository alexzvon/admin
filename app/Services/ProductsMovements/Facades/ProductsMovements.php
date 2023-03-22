<?php

declare(strict_types = 1);

namespace App\Services\ProductsMovements\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ProductsMovements
 *
 * @package App\Services\ProductsMovements\Facades
 */
class ProductsMovements extends Facade

{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'productsMovements';
    }
}
