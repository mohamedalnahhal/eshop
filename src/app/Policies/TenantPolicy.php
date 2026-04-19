<?php

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\TenantUser;
use App\Models\User;

abstract class TenantPolicy
{
    protected function tenantUser(User $user): ?TenantUser
    {
        if (! function_exists('tenant') || ! tenant()) {
            return null;
        }

        return $user->tenantUserFor(tenant('id'));
    }

    /**
     * check TenantPermission, falls back to false when there is no tenant context
     */
    protected function check(User $user, TenantPermission $permission): bool
    {
        return $this->tenantUser($user)?->can($permission) ?? false;
    }
}