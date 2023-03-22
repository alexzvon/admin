<?php

namespace App\Providers;

use App\Models\Shop\ReturnRequest\ReturnRequest;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Shop\QuickFilter\QuickFilter;

class MorphServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Relation::morphMap([
            'admin'         => \App\Models\Admin::class,
            'product'       => \App\Models\Shop\Product\Product::class,
            'category'      => \App\Models\Shop\Category\Category::class,
            'room'          => \App\Models\Shop\Room\Room::class,
            'style'         => \App\Models\Shop\Style\Style::class,
            'review'        => \App\Models\Review::class,
            'return'        => ReturnRequest::class,
            'quickfilter'   => QuickFilter::class
        ]);
    }

    public function register()
    {
        //
    }
}
