<?php

use App\Http\Controllers\Shop\QuadCRMController;
use App\Http\Controllers\Shop\QuadCRMTimesController;
use App\Http\Controllers\Shop\SupplierController;
use Illuminate\Support\Facades\Route;

use Mossebo\Grpc\Rate;

Route::get('/rate/{code}', function($code){
    try {
        $obj = Rate::Make();
        dump($obj->CodeRate($code), $obj->LoadRate());
    } catch (\Exception $exception) {
        dump($exception->getMessage());
    }
});

Route::get('/rate/calck/{code}/{value}', function($code, $value){
    dump(Rate::Make()->CurCalck($code, $value));
});

Route::prefix('test')->group(function () {
    Route::get('test', 'TestController@test');
});

Route::get('do', function () {
    //dd(pow(100, 0.2));


    // (new \App\Models\Admin)->where('id', 9)->update([
    //   'password' => encodePassword('4Fs!8O-ztV?uECY'),
    //   'api_token' => str_random(60)
    // ]);

    // dd((new \App\Models\Admin)->saveFromRequestData([
    //    'name' => 'Администратор',
    //    'email' => 'admin@candellabra.com',
    //    'password' => encodePassword('J0SS7t5tTN9q'),
    //    'api_token' => str_random(60),
    //    'enabled' => true
    // ]));
});

Route::group(['middleware' => 'web'], function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::any('logout', 'Auth\LoginController@logout')->name('logout');
});

Route::group(['middleware' => ['web', 'admin']], function () {
    Route::get('images/{product}', 'TestController@imageConverter');
    Route::get('languages', 'TestController@enableLanguages');
    Route::get('languages/disable', 'TestController@disableLanguages');
    Route::get('test', 'TestController@test');
    Route::get('test-page', 'TestController@testFileImport')->name('test-page');
    Route::get('test-hash', 'TestController@testFileHash')->name('test-hash');
    Route::post('test-import', 'TestController@testImport')->name('test-import');

    Route::get('exports/products', 'ExportsController@products');
    Route::get('exports/images', 'ExportsController@images');

    Route::prefix('suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'get'])->name('suppliers');
        Route::get('/add-to-quadCRM/{id}', [SupplierController::class, 'add'])->name('add-to-quadCRM');
        Route::get('/delete-from-quadCRM/{id}', [SupplierController::class, 'delete'])->name('delete-from-quadCRM');
    });

    Route::prefix('time')->group(function () {
        Route::get('/for-export', [QuadCRMTimesController::class, 'getExportTime'])->name('time-for-export');
        Route::get('/for-export/edit/{id}', [QuadCRMTimesController::class, 'editExportTime'])->name(
            'edit-export-time'
        );
        Route::post('/for-export/update/{id}', [QuadCRMTimesController::class, 'updateExportTime'])->name(
            'update-export-time'
        );
        Route::get('/for-import', [QuadCRMTimesController::class, 'getImportTime'])->name('time-for-import');
        Route::get('/for-import/edit/{id}', [QuadCRMTimesController::class, 'editImportTime'])->name(
            'edit-import-time'
        );
        Route::post('/for-import/update/{id}', [QuadCRMTimesController::class, 'updateImportTime'])->name(
            'update-import-time'
        );
    });

    Route::prefix('save-QuadDB_out')->group(function () {
        Route::get('/save', [QuadCRMController::class, 'createLink'])->name('save-QuadDB_out');
    });

    Route::get('/storage/app/public/fromqx/QuadDB_out.xlsx', [QuadCRMController::class, 'saveAndDownloadQuadDBOut']);

    Route::prefix('import-from-QuadCRM')->group(function () {
        Route::get('/', [QuadCRMController::class, 'importShow'])->name('import-from-QuadCRM-show');
        Route::post('/', [QuadCRMController::class, 'import'])->name('import-from-QuadCRM');
    });


    Route::get('{any}', function () {
        return view('master');
    })->where('any', '.*');
});
