<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class CheckoutToken extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'order_id',
        'token',
        'locked_total',
        'customer_email',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'locked_total' => 'integer',
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isValid(): bool
    {
        return !$this->used && $this->expires_at->isFuture();
    }

    public function markUsed(): void
    {
        $this->update(['used' => true]);
    }
}