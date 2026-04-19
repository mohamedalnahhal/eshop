<?php

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\Customer;
use App\Models\User;

class CustomerPolicy extends TenantPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->check($user, TenantPermission::VIEW_CUSTOMERS);
    }

    public function view(User $user, Customer $customer): bool
    {
        return $this->check($user, TenantPermission::VIEW_CUSTOMERS);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Customer $customer): bool
    {
        return false;
    }

    public function delete(User $user, Customer $customer): bool
    {
        return false;
    }

    public function forceDelete(User $user, Customer $customer): bool
    {
        return false;
    }
}