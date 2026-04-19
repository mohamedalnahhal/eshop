<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\Gender;

class Customer extends Authenticatable
{
    use HasUuids, BelongsToTenant, SoftDeletes;
    
    protected $guard = 'customer';
 
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'gender',
        'email_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'gender' => Gender::class,
        'email_verified_at' => 'datetime'
    ];
    
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=2563eb&color=fff&bold=true';
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }
}