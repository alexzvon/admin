<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('roles', function() {
            return app()->make(\App\Repositories\AdminRoleRepository::class);
        });

        $this->app->singleton('languages', function() {
            return app()->make(\App\Repositories\LanguageRepository::class);
        });

        $this->app->singleton('attributes', function() {
            return app()->make(\App\Repositories\Shop\AttributeRepository::class);
        });

        $this->app->singleton('categories', function() {
            return app()->make(\App\Repositories\Shop\CategoryRepository::class);
        });

        $this->app->singleton('rooms', function() {
            return app()->make(\App\Repositories\Shop\RoomRepository::class);
        });

        $this->app->singleton('styles', function() {
            return app()->make(\App\Repositories\Shop\StyleRepository::class);
        });

        $this->app->singleton('currencies', function() {
            return app()->make(\App\Repositories\Shop\CurrencyRepository::class);
        });

        $this->app->singleton('price-types', function() {
            return app()->make(\App\Repositories\Shop\PriceTypeRepository::class);
        });

        $this->app->singleton('badge-types', function() {
            return app()->make(\App\Repositories\Shop\BadgeTypeRepository::class);
        });

        $this->app->singleton('banner-places', function() {
            return app()->make(\App\Repositories\Shop\BannerPlaceRepository::class);
        });
    }
}
