<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
//        \App\Models\Admin::class => \App\Policies\AdminPolicy::class,
//        \App\Models\Product::class => \App\Policies\AdminPolicy::class,
//        \App\Models\AdminRole::class => \App\Policies\AdminPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
