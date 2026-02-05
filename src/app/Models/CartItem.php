<?php

namespace App\Models;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasUuid;
    protected $primaryKey = 'cart_item_id';
    protected $fillable = ['cart_id', 'product_id', 'quantity'];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
