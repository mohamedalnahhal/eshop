<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Enums\TenantPermission;
use App\Models\Order;
use App\Models\User;

class OrderPolicy extends TenancyBasePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->check($user, TenantPermission::VIEW_ORDERS);
    }

    public function view(User $user, Order $order): bool
    {
        return $this->check($user, TenantPermission::VIEW_ORDERS);
    }

    public function create(User $user): bool
    {
        return $this->check($user, TenantPermission::MANAGE_ORDERS);
    }

    public function update(User $user, Order $order): bool
    {
        if (! $this->check($user, TenantPermission::MANAGE_ORDERS)) {
            return false;
        }

        // Deny editing if the order is not draft
        return $order->status === OrderStatus::DRAFT;
    }

    public function delete(User $user, Order $order): bool
    {
        return $this->check($user, TenantPermission::MANAGE_ORDERS);
    }

    public function deleteAny(User $user): bool
    {
        return $this->check($user, TenantPermission::MANAGE_ORDERS);
    }

    public function restore(User $user, Order $order): bool
    {
        return $this->check($user, TenantPermission::MANAGE_ORDERS);
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return false;
    }
}