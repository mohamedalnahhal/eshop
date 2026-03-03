<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToPrimaryModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class OrderItem extends Model
{
    use HasUuids;
    use BelongsToPrimaryModel;
    use HasFactory;

    const UPDATED_AT = null;
    
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function getRelationshipToPrimaryModel(): string
    {
        return 'order';
    }

    public function order() { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
