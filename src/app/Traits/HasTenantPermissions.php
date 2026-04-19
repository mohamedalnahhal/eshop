<?php

namespace App\Traits;

use App\Enums\TenantPermission;
use App\Enums\TenantUserRole;

trait HasTenantPermissions
{
    public function can(string|TenantPermission $permission): bool
    {
        $key = $permission instanceof TenantPermission ? $permission->value : $permission;

        if ($this->role === TenantUserRole::OWNER) {
            return true;
        }

        $enum = TenantPermission::tryFrom($key);

        if ($enum && $enum->isLockedFor($this->role)) {
            return $enum->defaultFor($this->role);
        }

        $override = $this->permissions()
            ->where('permission', $key)
            ->whereNull('deleted_at')
            ->first();

        if ($override !== null) {
            return $override->granted;
        }

        return $enum ? $enum->defaultFor($this->role) : false;
    }
}