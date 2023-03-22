<?php

namespace App\Models;

use MosseboShopCore\Models\Base\BaseModel;

class AdminRolePermissionRelation extends BaseModel
{
    protected $tableKey = 'AdminRolePermissionRelations';
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'admin_role_id',
        'admin_role_permission_group_id',
        'admin_role_permission_id'
    ];

    public $timestamps = false;

    public function admins()
    {
        return $this->hasManyThrough(
            Admin::class,
            AdminRoleRelation::class,
            'admin_role_id',
            'id',
            'admin_role_id',
            'admin_id'
        );
    }

    public function role()
    {
        return $this->hasOne(AdminRole::class, 'admin_role_id');
    }

    public function group()
    {
        return $this->hasOne(AdminRolePermissionGroup::class, 'admin_role_permission_group_id');
    }

    public function permission()
    {
        return $this->hasOne(AdminRolePermission::class, 'admin_role_permission_id');
    }
}
