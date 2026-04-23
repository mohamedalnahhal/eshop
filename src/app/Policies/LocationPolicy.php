<?php

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\Location;
use App\Models\User;

class LocationPolicy extends TenancyBasePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->check($user, TenantPermission::MANAGE_SETTINGS);
    }

    public function view(User $user, Location $location): bool
    {
        return $this->check($user, TenantPermission::MANAGE_SETTINGS);
    }

    public function create(User $user): bool
    {
        return $this->check($user, TenantPermission::MANAGE_SETTINGS);
    }

    public function update(User $user, Location $location): bool
    {
        return $this->check($user, TenantPermission::MANAGE_SETTINGS);
    }

    public function delete(User $user, Location $location): bool
    {
        return $this->check($user, TenantPermission::MANAGE_SETTINGS);
    }

    public function restore(User $user, Location $location): bool
    {
        return $this->check($user, TenantPermission::MANAGE_SETTINGS);
    }

    public function forceDelete(User $user, Location $location): bool
    {
        return false;
    }
}