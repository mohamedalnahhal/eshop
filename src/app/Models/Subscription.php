<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $fillable = ['name', 'price', 'duration_days', 'max_products', 'features'];

    protected $casts = [
        'price' => 'integer',
        'duration_days' => 'integer',
        'max_products' => 'integer',
        'features' => 'array',
    ];

    public function tenantSubscriptions()
    {
        return $this->hasMany(TenantSubscription::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price / 100, 2);
    }
}
