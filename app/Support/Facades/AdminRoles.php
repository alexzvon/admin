<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class AdminRoles extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'roles';
    }
}