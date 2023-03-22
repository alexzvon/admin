<?php

namespace App\Models;

use App\Support\Traits\Models\Positionable;
use App\Support\Traits\Models\RequestSaver;

use MosseboShopCore\Models\Base\BaseModel;

class AdminRolePermission extends BaseModel
{
    use RequestSaver, Positionable;

    protected $tableKey = 'AdminRolePermissions';

    protected $fillable = [
        'group_id',
        'name',
        'position',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function roles()
    {
        return $this->hasManyThrough(
            AdminRole::class,
            AdminRolePermissionRelation::class,
            'admin_role_permission_id',
            'id',
            'id',
            'admin_role_id'
        );
    }

    public function group()
    {
        return $this->hasOne(AdminRolePermissionGroup::class, 'group_id');
    }
}
