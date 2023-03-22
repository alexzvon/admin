<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Http\Requests\ApiRequest;
use App\Http\Controllers\Api as ApiControllers;
use App\Http\Requests;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Validator::extend('trim', function($attribute, $value) {
            return empty($value) ? true : trim($value);
        });

        \Validator::extend('numericarray', function($attribute, $value, $parameters)
        {
            foreach($value as $v) {
                if(!is_int($v) && !intval($v)) return false;
            }

            return true;
        });
    }

    public function register()
    {
        $this->app
            ->when(ApiControllers\v1\ProductController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\PublicApiRequest::class);

        $this->app
            ->when(ApiControllers\v1\SearchController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\PublicApiRequest::class);

        $this->app
            ->when(ApiControllers\AdminController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\AdminRequest::class);

        $this->app
            ->when(ApiControllers\PermissionGroupController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\PermissionGroupRequest::class);

        $this->app
            ->when(ApiControllers\RoleController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\RoleRequest::class);

        $this->app
            ->when(ApiControllers\ReviewController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\ReviewRequest::class);


        $this->app
            ->when(ApiControllers\Shop\AttributeController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\Shop\AttributeRequest::class);

        $this->app
            ->when(ApiControllers\Shop\BannerController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\Shop\BannerRequest::class);

        $this->app
            ->when(ApiControllers\Shop\CategoryController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\Shop\CategoryRequest::class);

        $this->app
            ->when(ApiControllers\Shop\RoomController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\Shop\RoomRequest::class);

        $this->app
            ->when(ApiControllers\Shop\StyleController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\Shop\StyleRequest::class);

        $this->app
            ->when(ApiControllers\Shop\PriceTypeController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\Shop\PriceTypeRequest::class);

        $this->app
            ->when(ApiControllers\Shop\ProductController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\Shop\ProductRequest::class);

        $this->app
            ->when(ApiControllers\Shop\SupplierController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\Shop\SupplierRequest::class);

        $this->app
            ->when(ApiControllers\Shop\PromoCodeController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\Shop\PromoCodeRequest::class);

        $this->app
            ->when(ApiControllers\Shop\SaleController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\Shop\SaleRequest::class);

        $this->app
            ->when(ApiControllers\Shop\InteriorController::class)
            ->needs(ApiRequest::class)
            ->give(Requests\Shop\InteriorRequest::class);
    }
}
