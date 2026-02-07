<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Subscription extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'price', 'duration_days', 'max_products', 'features'];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
        'max_products' => 'integer',
        'features' => 'array',
    ];

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }
}
