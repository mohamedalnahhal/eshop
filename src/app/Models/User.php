<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasUuid, Notifiable;

    protected $primaryKey = 'user_id';
    protected $fillable = ['name', 'username', 'email', 'password', 'role'];

    public function orders() {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }
}