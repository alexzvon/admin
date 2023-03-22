<?php

namespace App\Providers;

use App\Contracts\Repositories\Shop\QuadCRMTimesRepositoryInterface;
use App\Contracts\Repositories\Shop\SupplierRepositoryInterface;
use App\Repositories\Shop\QuadCRMTimesRepository;
use App\Repositories\Shop\SupplierRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            SupplierRepositoryInterface::class,
            SupplierRepository::class
        );

        $this->app->bind(
            QuadCRMTimesRepositoryInterface::class,
            QuadCRMTimesRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
