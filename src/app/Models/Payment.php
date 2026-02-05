<?php

namespace App\Models;
use App\Models\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasUuid;
    protected $primaryKey = 'payment_id';
    protected $fillable = ['amount', 'currency', 'status', 'tenant_id', 'order_id', 'payment_method'];

    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
