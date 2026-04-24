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

    const UPDATED_AT = null;

    protected $fillable = [
        'payable_id',
        'payable_type',
        'payment_method',
        'amount',
        'currency',
        'status',
        'payment_type',
        'parent_payment_id',
        'transaction_reference',
        'metadata'
    ];

    protected $hidden = [
        'gateway_response'
    ];

    protected $casts = [
        'amount' => 'integer',
        'gateway_response' => 'array',
        'status' => PaymentStatus::class,
        'metadata' => 'array',
    ];

    public function method() { return $this->belongsTo(PaymentMethod::class); }
    public function payable() { return $this->morphTo(); }
}
