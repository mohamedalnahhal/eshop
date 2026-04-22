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
}