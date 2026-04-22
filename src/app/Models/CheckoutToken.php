<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class CheckoutToken extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'payment_id',
        'token',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
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