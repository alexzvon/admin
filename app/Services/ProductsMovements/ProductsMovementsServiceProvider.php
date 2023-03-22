<?php

declare(strict_types = 1);

namespace App\Services\ProductsMovements;

use Illuminate\Support\ServiceProvider;

/**
 * Class ProductsMovementsServiceProvider
 *
 * @package App\Services\ProductsMovements
 */
class ProductsMovementsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('productsMovements', function () {
            return new ProductsMovements();
        });
        $this->app->alias('productsMovements', 'App\Services\ProductsMovements\ProductsMovements');
    }
}
