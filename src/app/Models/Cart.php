<?php

namespace App\Models;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasUuid;
    protected $primaryKey = 'cart_id';
    protected $fillable = ['user_id', 'tenant_id'];

    public function items() {
        return $this->hasMany(CartItem::class, 'cart_id', 'cart_id');
    }
}