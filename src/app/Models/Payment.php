<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasUuids;
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'paymentable_id',
        'paymentable_type',
        'payment_method',
        'amount',
        'currency',
        'status',
        'transaction_reference',
        'gateway_response',
        'metadata'
    ];

    protected $hidden = [
        'gateway_response'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'status' => PaymentStatus::class,
        'metadata' => 'array',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function method() { return $this->belongsTo(PaymentMethod::class, 'payment_method', 'payment_method'); }
    public function paymentable() { return $this->morphTo(); }
}
