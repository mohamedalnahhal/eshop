<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'info',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }
}
