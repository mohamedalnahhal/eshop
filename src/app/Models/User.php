<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use App\Enums\UserRole;
use App\Enums\Gender;
use App\Enums\TenantUserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable implements FilamentUser
{
    use HasUuids, Notifiable , SoftDeletes;

    protected $fillable = ['name', 'username', 'email', 'avatar', 'password', 'phone', 'gender'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'role' => UserRole::class,
        'gender' => Gender::class,
        'email_verified_at' => 'datetime'
    ];

    public function tenantUsers(): HasMany
    {
        return $this->hasMany(TenantUser::class);
    }
 
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_users')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function ownedTenants(): HasMany
    {
        return $this->hasMany(Tenant::class, 'owner_id');
    }

    public function tenantUserFor(Tenant|string $tenant): ?TenantUser
    {
        $tenantId = $tenant instanceof Tenant ? $tenant->id : $tenant;
 
        return $this->tenantUsers()->where('tenant_id', $tenantId)->first();
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=2563eb&color=fff&bold=true';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'super_admin' => $this->isAdmin(),
            'tenant_admin' => $this->isTenant() && $this->_hasAccessToCurrentTenant(),
            default => false,
        };
    }

    public function hasTenantAccess(Tenant|string $tenant): bool
    {
        $tenantId = $tenant instanceof Tenant ? $tenant->id : $tenant;
 
        if ($this->isAdmin()) {
            return true;
        }
 
        $tenantModel = $tenant instanceof Tenant ? $tenant : Tenant::find($tenantId);
        if ($tenantModel && $tenantModel->owner_id === $this->id) {
            return true;
        }
 
        return $this->tenantUsers()->where('tenant_id', $tenantId)->exists();
    }
    
    private function _hasAccessToCurrentTenant(): bool
    {
        if (! function_exists('tenant') || ! tenant()) {
            return false;
        }
 
        return $this->hasTenantAccess(tenant()->id);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isTenant(): bool
    {
        return $this->role === UserRole::TENANT;
    }
}