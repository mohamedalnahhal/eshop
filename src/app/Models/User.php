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
    ];

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=2563eb&color=fff&bold=true';
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_users')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'super_admin') {
            return $this->role === UserRole::ADMIN;
        }

        if ($panel->getId() === 'tenant_admin') {
            return $this->role === UserRole::TENANT_OWNER; // TODO: Check if the user own the scoped tenant
        }

        return false;
    }
}