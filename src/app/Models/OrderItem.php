<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToPrimaryModel;

class OrderItem extends Model
{
    use HasUuids;
    use BelongsToPrimaryModel;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function getRelationshipToPrimaryModel(): string
    {
        return 'order';
    }

    public function order() { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
