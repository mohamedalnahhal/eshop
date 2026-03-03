<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToPrimaryModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CartItem extends Model
{
    use HasUuids;
    use BelongsToPrimaryModel;
    use HasFactory;
    protected $fillable = ['cart_id', 'product_id', 'quantity'];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function getRelationshipToPrimaryModel(): string
    {
        return 'cart';
    }

    public function cart() { return $this->belongsTo(Cart::class)->onDelete('cascade'); }
    public function product() { return $this->belongsTo(Product::class); }
}
