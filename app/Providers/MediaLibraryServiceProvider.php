<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MediaLibraryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        // Нужно, чтобы убрать base64svg placeholder из базы
        $this->app->bind('Spatie\MediaLibrary\ResponsiveImages\ResponsiveImageGenerator', 'App\MediaLibrary\ResponsiveImages\ResponsiveImageGenerator');
    }
}
