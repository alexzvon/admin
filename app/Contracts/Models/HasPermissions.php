<?php

namespace App\Contracts\Models;

interface HasPermissions
{
    public function isSuperAdmin(): bool;
    public function hasRole(int $roleId): bool;
    public function hasPermission($permissionNameOrId): bool;
    public function getPermissions(): array;
    public function hasEnoughImportance(int $importance): bool;
}