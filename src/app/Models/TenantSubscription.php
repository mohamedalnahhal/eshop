<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TenantSubscription extends Model
{
    use HasUuids;
    use BelongsToTenant;
    use HasFactory;
    protected $fillable = ['tenant_id', 'subscription_id', 'starts_at', 'ends_at', 'status'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'status' => SubscriptionStatus::class,
    ];


    public function subscription() { return $this->belongsTo(Subscription::class); }
    public function tenant() { return $this->belongsTo(Tenant::class); }
}