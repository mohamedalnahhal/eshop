<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantSubscription extends Model
{
    use HasUuids, SoftDeletes;
    use BelongsToTenant;

    protected $fillable = ['subscription_id', 'starts_at', 'ends_at', 'status'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'status' => SubscriptionStatus::class,
    ];


    public function subscription() { return $this->belongsTo(Subscription::class); }
    public function tenant() { return $this->belongsTo(Tenant::class); }
}