<?php

use App\Http\Controllers\Api\Product\Attribute\AttributeOptionController;
use App\Http\Controllers\Api\Product\Other\OptionsController;
use App\Http\Controllers\Api\Franchise\Users\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:api', 'api.auth', 'api.withData'], 'namespace' => 'Api'], function () {
    Route::post('import/check-schema', 'ImportController@checkSchema');
    Route::post('import', 'ImportController@import');
    Route::post('data', 'DataController@get');
    Route::get('data', 'DataController@get');
    Route::get('data/relevantKey', 'DataController@relevantKey');
    Route::get('cache', 'DataController@cache');

    // Система администрирования
    Route::group(['prefix' => 'system'], function () {
        Route::get('admins/{admin}/status', 'AdminController@status');
        Route::post('admins/{admin}/image', 'AdminController@imageUpload');
        Route::resource('admins', 'AdminController');

        // Контроль доступа
        Route::group(['prefix' => 'rbac'], function () {
            Route::resource('roles', 'RoleController');
            Route::get('permission-groups/all', 'PermissionGroupController@all');
            Route::resource('permission-groups', 'PermissionGroupController');
        });
    });

    Route::get('reviews/{review}/status', 'ReviewController@status');
    Route::resource('reviews', 'ReviewController');

    //Франшиза
    Route::prefix('franchise')->namespace('Franchise')->group(function(){
        //Пользователи
        Route::prefix('users')->namespace('Users')->group(function(){
            Route::get('/get/list/', [UsersController::class, 'getListFranchiseUsers']);
            Route::delete('/delete/user/', [UsersController::class, 'delFranchiseUser']);
            Route::get('/get/user/{user_id}', [UsersController::class, 'getFranchiseUser']);
            Route::post('/update/user/', [UsersController::class, 'updateFranchiseUser']);
            Route::post('/create/user/', [UsersController::class, 'createFranchiseUser']);
            Route::get('/list/users/{user_id}', [UsersController::class, 'getListFranchiseUsersNotCompanies']);
        });

        // Территория
        Route::prefix('territory')->namespace('Territory')->group(function(){
            Route::get('country', 'CityController@getListCountry');
            Route::get('gmt', 'CityController@getListGmt');
            Route::resource('city', 'CityController');

            Route::get('cities/search', 'CitiesController@search');
            Route::get('cities/ctr/{region_id}', 'CitiesController@getListCitiesTopRegion');
            Route::resource('cities', 'CitiesController');
        });

        Route::prefix('showrooms')->namespace('ShowRooms')->group(function(){
            Route::resource('group', 'GroupController');
            Route::post('/room/{id}/images/upload/', 'RoomController@imagesUpload');
            Route::post('/room/{id}/video/', 'RoomController@video');
            Route::resource('room', 'RoomController');
        });

        //Список возможных источников дохода Партнёра
        Route::prefix('income')->namespace('Income')->group(function(){
            Route::resource('source', 'IncomeController');
        });

        //
        Route::prefix('companies')->namespace('Companies')->group(function(){
            Route::get('cityNotPagination', 'CompaniesController@cityNotPagination');
            Route::get('incomeNotPagination', 'CompaniesController@incomeNotPagination');
            Route::get('users', 'CompaniesController@getListUsersForFilter');
            Route::resource('companies', 'CompaniesController');
        });
    });

    //New proccessing edit product (admin)
    Route::prefix('product')->namespace('Product')->group(function(){
       Route::prefix('attribute')->namespace('Attribute')->group(function(){
           Route::get('get/{product_id}', [AttributeOptionController::class, 'getAttributes']);
           Route::get('get_global/{product_id}', [AttributeOptionController::class, 'getGlobalAttributes']);
           Route::post('delete/{attribute_relation_id}', [AttributeOptionController::class, 'deleteAttribute']);
           Route::get('all', [AttributeOptionController::class, 'getAllAttributesI18n']);
           Route::get('get/add/{product_id}', [AttributeOptionController::class, 'getAddAttributesI18n']);
           Route::get('get_global/add/{product_id}', [AttributeOptionController::class, 'getAddGlobalAttributesI18n']);
           Route::post('update/state', [AttributeOptionController::class, 'updateState']);

           Route::prefix('option')->group(function(){
               Route::post('get/add', [AttributeOptionController::class, 'getAddOptionsAttributeProduct']);
               Route::post('delete/{option_id}', [AttributeOptionController::class, 'deleteOption']);
               Route::post('add', [AttributeOptionController::class, 'addOptionToAttribute']);
               Route::get('getadd', [AttributeOptionController::class, 'getAddOptionToAttribute']);
           });
       });

       Route::prefix('other')->namespace('Other')->group(function(){
           Route::prefix('options')->group(function() {
               Route::post('add/get', [OptionsController::class, 'getAddOptions']);
               Route::post('add/link', [OptionsController::class, 'addLinkOptions']);
               Route::post('del/link', [OptionsController::class, 'delLinkOptions']);
               Route::get('get/other/{product_id}', [OptionsController::class, 'getOtherProducts']);
           });
       });
    });

    // Магазин
    Route::group(['prefix' => 'shop', 'namespace' => 'Shop'], function () {

        //Возвраты
        Route::get('reply/list', 'ReturnsController@list');
        Route::get('reply/edit/{return_id}/{product_id}', 'ReturnsController@edit');
        Route::post('reply/save', 'ReturnsController@save');

        //Быстрые фильтры
        Route::get('quick_filter/categories', 'QuickFilterController@categories');
        Route::resource('quick_filter', 'QuickFilterController');

        Route::post('products/create', 'ProductController@create');
        Route::post('products/{product}/clone', 'ProductController@clone');
        Route::get('products/{product}/status', 'ProductController@status');
        Route::get('products/{product}/search', 'ProductController@query');
        Route::post('products/{product}/image', 'ProductController@imageUpload');
        Route::get('products/{product}/videos', 'ProductVideosController@getByProductId');
        Route::post('products/{product}/videos/add', 'ProductVideosController@add');
        Route::post('products/{product}/videos/update', 'ProductVideosController@update');
        Route::post('products/{product}/videos/delete', 'ProductVideosController@delete');
        Route::post('products/{product}/videos/vimeo/thumb', 'ProductVideosController@getVimeoThumb');
        Route::resource('products', 'ProductController');

        Route::post('attributor/create', 'AttributorController@create');
        Route::post('attributor/update', 'AttributorController@update');
        Route::post('attributor/delete', 'AttributorController@delete');
        Route::post('attributor/sync', 'AttributorController@sync');
        Route::post('attributor/options/create', 'AttributorController@createOption');
        Route::post('attributor/options/delete', 'AttributorController@deleteOption');
        Route::post('attributor/options/update', 'AttributorController@updateOption');
        Route::get('attributor/options/{id}/prices', 'AttributorPriceController@getPricesByAttributorId');
        Route::post('attributor/prices/create', 'AttributorPriceController@create');
        Route::post('attributor/prices/update', 'AttributorPriceController@update');
        Route::get('attributor/{relation}/{relation_id}', 'AttributorController@getByRelation');

        Route::post('categories/sort', 'CategoryController@positions');
        Route::get('categories/slug', 'CategoryController@slugAvailable');
        Route::get('categories/{category}/status', 'CategoryController@status');
        Route::get('categories/{category}/slug', 'CategoryController@entitySlugAvailable');
        Route::post('categories/{category}/image', 'CategoryController@imageUpload');
        Route::resource('categories', 'CategoryController');

        Route::post('rooms/sort', 'RoomController@positions');
        Route::get('rooms/slug', 'RoomController@slugAvailable');
        Route::get('rooms/{room}/status', 'RoomController@status');
        Route::get('rooms/{room}/slug', 'RoomController@entitySlugAvailable');
        Route::post('rooms/{room}/image', 'RoomController@imageUpload');
        Route::resource('rooms', 'RoomController');

        Route::get('contents', 'ContentsController@index');
        Route::patch('contents/{id}', 'ContentsController@update');

        Route::get('expand-banners', 'ExpandBannersController@index');
        Route::get('expand-banners/{id}', 'ExpandBannersController@index');
        Route::post('expand-banners/create', 'ExpandBannersController@create');
        Route::post('expand-banners/{id}/upload', 'ExpandBannersController@upload');
        Route::post('expand-banners/{id}/update', 'ExpandBannersController@update');
        Route::post('expand-banners/{id}/delete', 'ExpandBannersController@delete');

        Route::get('menus', 'MenusController@index');
        Route::post('menus/create', 'MenusController@create');
        Route::post('menus/sorting', 'MenusController@sorting');
        Route::post('menus/{id}/update', 'MenusController@update');
        Route::post('menus/{id}/delete', 'MenusController@delete');

        Route::post('styles/sort', 'StyleController@positions');
        Route::get('styles/slug', 'StyleController@slugAvailable');
        Route::get('styles/{style}/status', 'StyleController@status');
        Route::get('styles/{style}/slug', 'StyleController@entitySlugAvailable');
        Route::post('styles/{style}/image', 'StyleController@imageUpload');
        Route::resource('styles', 'StyleController');

        Route::get('suppliers/{supplier}/attributes', 'SupplierController@getAttributes');
        Route::get('suppliers/{supplier}/status', 'SupplierController@status');
        Route::resource('suppliers', 'SupplierController');


        Route::post('attributes/create', 'AttributeController@create');
        Route::post('attributes/sort', 'AttributeController@positions');
        Route::get('attributes/{attribute}/status', 'AttributeController@status');
        Route::get('attributes/{attribute}/options', 'AttributeController@options');
        Route::resource('attributes', 'AttributeController');

        Route::post('attribute-options/many', 'AttributeOptionController@optionsForManyAttributes');
        Route::post('attribute-options/many-used', 'AttributeOptionController@optionsForManyUsedAttributes');
        Route::post('attribute-options/create', 'AttributeOptionController@create');
        Route::post('attribute-options/delete', 'AttributeOptionController@delete');
        Route::post('attribute-options/update', 'AttributeOptionController@update');

        Route::post('price-types/sort', 'PriceTypeController@positions');
        Route::get('price-types/{priceType}/status', 'PriceTypeController@status');
        Route::resource('price-types', 'PriceTypeController');

        Route::get('promo-codes/{promoCode}/status', 'PromoCodeController@status');
        Route::resource('promo-codes', 'PromoCodeController');

        Route::post('badge-types/sort', 'BadgeTypeController@positions');
        Route::resource('badge-types', 'BadgeTypeController');

        Route::post('banners/sort', 'BannerController@positions');
        Route::get('banners/{category}/status', 'BannerController@status');
        Route::get('banners-type', 'BannerTypeController@getBannerType');
        Route::post('banners-type', 'BannerTypeController@setBannerType');
        Route::resource('banners', 'BannerController');

        Route::post('sale/sort', 'SaleController@positions');
        Route::get('sale/search', 'SaleController@query');
        Route::get('sale/price/{product}', 'SaleController@price');
        Route::get('sale/{category}/status', 'SaleController@status');
        Route::resource('sale', 'SaleController');

        Route::get('fast-sale', 'FastSaleController@index');
        Route::put('fast-sale', 'FastSaleController@update');

        Route::get('interiors/search', 'InteriorController@query');
        Route::get('interiors/product/{product}', 'InteriorController@product');
        Route::resource('interiors', 'InteriorController');
    });
});

Route::group(['namespace' => 'Api'], function () {
    Route::group(['namespace' => 'v1'], function () {
        Route::group(['prefix' => 'v1'], function () {
            Route::group(['prefix' => 'public'], function () {
                Route::get('products', 'ProductController@find');
                Route::get('products/{product}', 'ProductController@show');
                Route::get('products/search/query', 'SearchController@query');
            });
        });
    });
});
