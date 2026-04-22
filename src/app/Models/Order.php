<?php

namespace App\Models;

use App\Contracts\Payable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use App\Enums\OrderStatus;
use App\Traits\HasPayments;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model implements Payable
{
    use HasUuids;
    use BelongsToTenant;
    use SoftDeletes;
    use HasPayments;

    protected $fillable = [
        'customer_id',
        'shipping_method_id',
        'shipping_method_name',
        'guest_email',
        'guest_name',
        'guest_phone',
        'shipping_address',
        'billing_address',
        'shipping_fees',
        'discount',
        'notes',
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
    
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            // check if not empty in case of manually providing tracking_number
            // e.g. migrating orders from another db
            if (empty($order->tracking_number)) {
                $order->tracking_number = self::generateUniqueTrackingNumber();
            }
        });
    }

    public function customer() { return $this->belongsTo(Customer::class); }
    public function items(){ return $this->hasMany(OrderItem::class); }
    public function successfulPayment()
    {
        return $this->morphMany(Payment::class, 'payable')->where('status', 'completed');
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

    // to use it in routes instead of id e.g. /orders/ORD-2026-A8F9B2C4
    public function getRouteKeyName(): string
    {
        return 'tracking_number';
    }

    private static function generateUniqueTrackingNumber(): string
    {
        do {
            $trackingNumber = 'ORD-' . date('Y') . '-' . strtoupper(Str::random(8));
        } while (self::where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }
}