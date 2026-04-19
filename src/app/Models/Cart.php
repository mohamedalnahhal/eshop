<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Cart extends Model
{
    use HasUuids;
    use BelongsToTenant;
    
    protected $fillable = ['customer_id', 'session_token', 'expires_at'];

    public function customer(){ return $this->belongsTo(Customer::class); }
    public function items() { return $this->hasMany(CartItem::class); }

    public function isGuest()
    {
        return is_null($this->customer_id);
    }

    public function total()
    {
        return $this->items->sum(fn ($item) => $item->subtotal());
    }
}