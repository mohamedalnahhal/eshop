<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class TenantSubscription extends Model
{
    use HasUuid;

    protected $primaryKey = 'tenant_subscription_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tenant_id',
        'subscription_id',
        'start_date',
        'end_date',
        'status' 
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];


    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }

    public function plan()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'subscription_id');
    }
}