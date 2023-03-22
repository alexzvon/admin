<?php

namespace App\Http\Controllers\Api;

use App\Models\AdminRolePermissionGroup;

use App\Http\Resources\AdminPermissionGroupEditResource;
use App\Http\Resources\AdminPermissionGroupsTableResource;

use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Updatable;
use App\Support\Traits\Controllers\Deleteable;
use App\Support\Traits\Controllers\PositionChangeable;


class PermissionGroupController extends ApiController
{
    use Creatable, Updatable, Deleteable, PositionChangeable;

    protected static $modelClass = AdminRolePermissionGroup::class;
    protected static $entityName = 'permission-group';
    protected static $editResource = AdminPermissionGroupEditResource::class;
    protected static $tableResource = AdminPermissionGroupsTableResource::class;

    public function index()
    {
        return [
            'permission-groups' => static::toResource(self::$modelClass::get()->toTree()),
        ];
    }

    public function all()
    {
        return [
            'permission-groups' => static::toResource(self::$modelClass::with('permissions')->get()->toTree()),
        ];
    }
}