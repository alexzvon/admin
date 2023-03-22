<?php

namespace App\Providers;

use App\Shop\Shop as BaseShop;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class ShopServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('shop', function () {
            return new BaseShop;
        });
    }
}
