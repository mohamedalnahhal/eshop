<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Enums\TenantStatus;

class Tenant extends BaseTenant
{
    use HasUuids, HasDomains;

    protected $fillable = ['name', 'subdomain', 'status'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_users')
        ->withPivot('role')
        ->withTimestamps();
    }

    protected $casts = [
        'status' => TenantStatus::class,
    ];

    public function products() { return $this->hasMany(Product::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function locations() { return $this->hasMany(Location::class); }
    public function carts() { return $this->hasMany(Cart::class); }
    public function categories() { return $this->hasMany(Category::class); }
    public function settings() { return $this->hasOne(TenantSetting::class); }
    public function subscriptions() { return $this->hasMany(TenantSubscription::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}