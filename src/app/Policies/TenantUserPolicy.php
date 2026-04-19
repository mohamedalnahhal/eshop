<?php

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Enums\TenantUserRole;
use App\Models\TenantUser;
use App\Models\User;

class TenantUserPolicy extends TenancyBasePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->check($user, TenantPermission::MANAGE_TEAM);
    }

    public function view(User $user, TenantUser $tenantUser): bool
    {
        return $this->check($user, TenantPermission::MANAGE_TEAM);
    }

    public function create(User $user): bool
    {
        return $this->check($user, TenantPermission::MANAGE_TEAM);
    }

    public function update(User $user, TenantUser $tenantUser): bool
    {
        if (! $this->check($user, TenantPermission::MANAGE_TEAM)) {
            return false;
        }

        // nobody can edit an owner's record
        return $tenantUser->role !== TenantUserRole::OWNER;
    }

    public function delete(User $user, TenantUser $tenantUser): bool
    {
        if (! $this->check($user, TenantPermission::MANAGE_TEAM)) {
            return false;
        }

        // owner cannot be removed from the staff
        return $tenantUser->role !== TenantUserRole::OWNER;
    }

    public function deleteAny(User $user): bool
    {
        return $this->check($user, TenantPermission::MANAGE_TEAM);
    }

    public function restore(User $user, TenantUser $tenantUser): bool
    {
        return false;
    }

    public function forceDelete(User $user, TenantUser $tenantUser): bool
    {
        return false;
    }
}