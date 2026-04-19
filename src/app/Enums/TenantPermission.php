<?php

namespace App\Enums;

enum TenantPermission: string
{
    case VIEW_PRODUCTS = 'view_products';
    case EDIT_PRODUCTS = 'edit_products';
    case DELETE_PRODUCTS  = 'delete_products';
    case VIEW_ORDERS = 'view_orders';
    case MANAGE_ORDERS = 'manage_orders';
    case VIEW_CUSTOMERS = 'view_customers';
    case MANAGE_SETTINGS = 'manage_settings';
    case MANAGE_TEAM = 'manage_team';
    case MANAGE_THEMES = 'manage_themes';

    public function label(): string
    {
        return match ($this) {
            self::VIEW_PRODUCTS => 'View / Browse Products',
            self::EDIT_PRODUCTS => 'Create & Edit Products',
            self::DELETE_PRODUCTS => 'Delete Products',
            self::VIEW_ORDERS => 'View Orders',
            self::MANAGE_ORDERS => 'Manage Orders',
            self::VIEW_CUSTOMERS => 'View Customers',
            self::MANAGE_SETTINGS => 'Manage Settings',
            self::MANAGE_TEAM => 'Manage Team',
            self::MANAGE_THEMES => 'Manage Themes',
        };
    }

    public function defaultFor(TenantUserRole $role): bool
    {
        return match ($this) {
            self::VIEW_PRODUCTS => true,
            self::EDIT_PRODUCTS => true,
            self::DELETE_PRODUCTS => $role->value >= TenantUserRole::MANAGER->value,
            self::VIEW_ORDERS => true,
            self::MANAGE_ORDERS => $role->value >= TenantUserRole::MANAGER->value,
            self::VIEW_CUSTOMERS => $role->value >= TenantUserRole::MANAGER->value,
            self::MANAGE_SETTINGS => $role === TenantUserRole::OWNER,
            self::MANAGE_TEAM => $role === TenantUserRole::OWNER,
            self::MANAGE_THEMES => $role === TenantUserRole::OWNER,
        };
    }

    public function isLockedFor(TenantUserRole $role): bool
    {
        if ($role === TenantUserRole::OWNER) {
            return true;
        }

        return match ($this) {
            self::MANAGE_SETTINGS,
            self::MANAGE_TEAM,
            self::MANAGE_THEMES => true,
            default => false,
        };
    }
}