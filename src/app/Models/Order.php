<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasUuids;
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'shipping_address',
        'billing_address',
        'total_price',
        'discount',
        'final_price',
        'currency',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
        'status' => OrderStatus::class,
        'shipping_address' => 'array',
        'billing_address'  => 'array',
    ];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function items(){ return $this->hasMany(OrderItem::class); }
    public function payments() { return $this->morphMany(Payment::class, 'paymentable'); }
    public function successfulPayment()
    {
        return $this->morphOne(Payment::class, 'paymentable')->where('status', 'completed');
    }
}