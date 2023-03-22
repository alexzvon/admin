<?php

namespace App\Http\Controllers\Api;

use App\Models\AdminRole;

use App\Http\Resources\RoleEditResource;
use App\Http\Resources\RolesTableResource;

use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Updatable;
use App\Support\Traits\Controllers\Deleteable;
use App\Support\Traits\Controllers\PositionChangeable;

class RoleController extends ApiController
{
    use Creatable, Updatable, Deleteable, PositionChangeable;

    protected static $modelClass = AdminRole::class;
    protected static $entityName = 'role';
    protected static $editResource = RoleEditResource::class;
    protected static $tableResource = RolesTableResource::class;

    public function index()
    {
        return [
            'roles' => static::toResource(static::$modelClass::all()),
        ];
    }
}