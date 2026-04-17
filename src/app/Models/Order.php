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
        'guest_email',
        'guest_name',
        'guest_phone',
        'shipping_address',
        'billing_address',
        'shipping_fees',
        'discount',
        'notes',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'shipping_fees' => 'integer',
        'discount' => 'integer',
        'total' => 'integer',
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

    public function isGuest(): bool
    {
        return is_null($this->customer_id);
    }

    public function getContactEmailAttribute(): string
    {
        return $this->customer?->email ?? $this->guest_email;
    }

    public function getContactNameAttribute(): string
    {
        return $this->customer?->name ?? $this->guest_name;
    }

    public function getContactPhoneAttribute(): ?string
    {
        return $this->isGuest()? $this->guest_phone : $this->customer->phone;
    }
}