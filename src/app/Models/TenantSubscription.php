<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Enums\SubscriptionStatus;

class TenantSubscription extends Model
{
    use HasUuids;

    protected $fillable = ['tenant_id', 'subscription_id', 'starts_at', 'ends_at', 'status'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => SubscriptionStatus::class,
    ];


    public function subscription() { return $this->belongsTo(Subscription::class); }
    public function tenant() { return $this->belongsTo(Tenant::class); }
}