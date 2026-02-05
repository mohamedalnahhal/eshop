<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUuid;

    protected $primaryKey = 'order_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'shipping_address_id',
        'total_price',
        'discount',
        'final_price',
        'status',
        'payment_status',
        'notes'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }

    // الطلب يخص  (User) واحداً
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // الطلب يحتوي على تفاصيل كثيرة (منتجات)
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id', 'address_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id', 'order_id');
    }
}