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

class User extends Authenticatable
{
    use HasUuids, Notifiable;

    protected $fillable = ['name', 'username', 'email', 'password', 'phone', 'gender', 'role'];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
        'role' => UserRole::class,
        'gender' => Gender::class,
    ];

    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'tenant_users')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function orders() { return $this->hasMany(Order::class); }
    public function carts() { return $this->hasMany(Cart::class); }
    
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }
}