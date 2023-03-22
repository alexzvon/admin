<?php

namespace App\Models;

use App\Support\Traits\Models\Positionable;
use App\Support\Traits\Models\RequestSaver;

use MosseboShopCore\Models\Base\BaseModel;

class AdminRole extends BaseModel
{
    use Positionable, RequestSaver;

    protected $tableKey = 'AdminRoles';
    protected $relationFieldName = 'admin_role_id';

    protected $fillable = [
        'name',
        'importance',
        'position'
    ];

    protected $needsToSaveFromRequest = [
        'permissions'
    ];

    public function admins()
    {
        return $this->hasManyThrough(
            Admin::class,
            AdminRoleRelation::class,
            'admin_id',
            'admin_role_id',
            'id',
            'id'
        );
    }

    public function groups()
    {
        return $this->hasManyThrough(
            AdminRolePermissionGroup::class, AdminRolePermissionRelation::class,
            $this->relationFieldName,  'id', 'id', 'admin_role_permission_group_id'
        );
    }

    public function permissions()
    {
        return $this->hasManyThrough(
            AdminRolePermission::class, AdminRolePermissionRelation::class,
            $this->relationFieldName, 'id', 'id', 'admin_role_permission_id'
        );
    }

    public function permissionRelations()
    {
        return $this->hasMany(AdminRolePermissionRelation::class, $this->relationFieldName);
    }

    public function _savePermissions($permissionsIds = [])
    {
        $this->permissionRelations()->delete();

        if (empty($permissionsIds)) {
            return;
        }

        $permissions = AdminRolePermission::whereIn('id', $permissionsIds)->get();

        foreach ($permissions as $permission) {
            AdminRolePermissionRelation::create([
               'admin_role_id' => $this->id,
               'admin_role_permission_group_id' => $permission->group_id,
               'admin_role_permission_id' => $permission->id
            ]);
        }
    }
}
