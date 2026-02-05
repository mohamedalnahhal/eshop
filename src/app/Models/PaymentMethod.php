<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasUuid;

    protected $primaryKey = 'payment_method'; 
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'payment_method', 
        'provider',       
        'is_active',     
        'config'          
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];
    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_method', 'payment_method');
    }
}