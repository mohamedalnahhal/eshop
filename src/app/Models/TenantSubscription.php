<?php

namespace App\Models;

use App\Contracts\Payable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use App\Enums\SubscriptionStatus;
use App\Traits\HasPayments;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantSubscription extends Model implements Payable
{
    use HasUuids, SoftDeletes;
    use BelongsToTenant;
    use HasPayments;

    protected $fillable = ['subscription_id', 'starts_at', 'ends_at', 'status'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'status' => SubscriptionStatus::class,
    ];

    public function subscription() { return $this->belongsTo(Subscription::class); }

    public function isActive(): bool
    {
        return $this->status === SubscriptionStatus::ACTIVE
            && $this->ends_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->ends_at->isPast()
            || $this->status === SubscriptionStatus::EXPIRED;
    }

    public function daysRemaining(): int
    {
        if ($this->ends_at->isPast()) return 0;
        return (int) now()->diffInDays($this->ends_at);
    }

    public function scopeActive($query)
    {
        return $query->where('status', SubscriptionStatus::ACTIVE)
            ->where('ends_at', '>', now());
    }
}