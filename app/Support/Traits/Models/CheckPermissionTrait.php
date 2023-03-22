<?php namespace App\Support\Traits\Models;

use Illuminate\Support\Facades\Cache;

trait CheckPermissionTrait
{
    protected $permissions = [];

    protected $importance;

    public function isSuperAdmin(): bool
    {
        return true;
//        return $this->id === (int) \Config::get('roles.superAdminId');
    }

    public function hasRole(int $roleId): bool
    {
        return $this->roleRelations()->where('admin_role_id', $roleId)->exists();
    }

    public function hasPermission($permissionNameOrId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $permissions = $this->getPermissions();

        if (is_int($permissionNameOrId)){
            return isset($permissions[$permissionNameOrId]);
        }
        else {
            return in_array($permissionNameOrId, $permissions);
        }
    }

    // todo: нужна отчистка кэша

    public function getPermissions(): array
    {
        if ($this->permissions) {
            return $this->permissions;
        }

        Cache::flush('permissions' . get_class($this));

        $this->permissions = Cache::remember('permissions' . get_class($this), 18000, function () {
            return $this->buildPermissions();
        });

        return $this->permissions;
    }

    protected function buildPermissions(): array
    {
        $roles = $this->roles()->with(['groups' => function($query) {
            return $query->with(['permissions' => function($query) {
                return $query->distinct('admin_role_permission_id');
            }]);
        }])->get();

        $permissions = [];

        foreach($roles as $role) {
            foreach ($role->groups as $group) {
                foreach ($group->permissions as $permission) {
                    $permissions[$permission->id] = $group->namespace . '.' . $permission->name;
                }
            }
        }

        return $permissions;
    }

    public function hasEnoughImportance(int $importanceValue): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->getImportance() < $importanceValue) {
            return false;
        }

        return false;
    }

    protected function getImportance()
    {
        if (is_null($this->importance)) {
            $this->importance = min(array_column($this->roles->all('importance'), 'importance'));
        }

        return $this->importance;
    }
}