<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Cart extends Model
{
    use HasUuids;
    use BelongsToTenant;
    
    protected $fillable = ['customer_id'];

    public function customer(){ return $this->belongsTo(Customer::class); }
    public function items() { return $this->hasMany(CartItem::class); }
}