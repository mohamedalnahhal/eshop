<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToPrimaryModel;

class CartItem extends Model
{
    use HasUuids;
    use BelongsToPrimaryModel;

    protected $fillable = ['cart_id', 'product_id', 'quantity'];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function getRelationshipToPrimaryModel(): string
    {
        return 'cart';
    }

    public function cart() { return $this->belongsTo(Cart::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
