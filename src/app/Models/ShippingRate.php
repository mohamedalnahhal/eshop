<?php

namespace App\Models;

use App\Enums\ShippingRateType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingRate extends Model
{
    use HasUuids;

    protected $fillable = [
        'shipping_method_id',
        'rate_type',
        'fee',
        'condition_min',
        'condition_max',
        'free_above',
        'sort_order',
    ];

    protected $casts = [
        'rate_type' => ShippingRateType::class,
        'fee' => 'integer',
        'condition_min' => 'integer',
        'condition_max' => 'integer',
        'free_above' => 'integer',
        'sort_order' => 'integer',
    ];

    public function method(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }
}