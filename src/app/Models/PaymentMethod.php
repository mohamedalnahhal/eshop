<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $primaryKey = 'payment_method'; 
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'payment_method', 
        'provider',       
        'is_active',     
        'config'          
    ];

    protected $hidden = ['config'];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_method', 'payment_method');
    }
}