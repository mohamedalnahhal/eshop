<?php

namespace App\Policies;

use App\Enums\TenantPermission;
use App\Models\Product;
use App\Models\User;

class ProductPolicy extends TenancyBasePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->check($user, TenantPermission::VIEW_PRODUCTS);
    }

    public function view(User $user, Product $product): bool
    {
        return $this->check($user, TenantPermission::VIEW_PRODUCTS);
    }

    public function create(User $user): bool
    {
        return $this->check($user, TenantPermission::EDIT_PRODUCTS);
    }

    public function update(User $user, Product $product): bool
    {
        return $this->check($user, TenantPermission::EDIT_PRODUCTS);
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->check($user, TenantPermission::DELETE_PRODUCTS);
    }

    public function deleteAny(User $user): bool
    {
        return $this->check($user, TenantPermission::DELETE_PRODUCTS);
    }

    public function restore(User $user, Product $product): bool
    {
        return $this->check($user, TenantPermission::DELETE_PRODUCTS);
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return false;
    }
}