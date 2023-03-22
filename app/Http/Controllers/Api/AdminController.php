<?php

namespace App\Http\Controllers\Api;

use App\Models\Admin;

use App\Http\Resources\AdminEditResource;
use App\Http\Resources\AdminsTableResource;

use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Updatable;
use App\Support\Traits\Controllers\Deleteable;
use App\Support\Traits\Controllers\StatusChangeable;
use App\Support\Traits\Controllers\ImageUploadable;


class AdminController extends ApiController
{
    use Creatable, Updatable, Deleteable, StatusChangeable, ImageUploadable;

    protected static $modelClass = Admin::class;
    protected static $entityName = 'admin';
    protected static $editResource = AdminEditResource::class;
    protected static $tableResource = AdminsTableResource::class;

    // todo: протестировать хорошенько роли.
    public function index()
    {
        return [
            'admins' => static::toResource(static::$modelClass::get()),
        ];
    }
}
