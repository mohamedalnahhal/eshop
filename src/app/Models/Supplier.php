<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Supplier extends Model
{
    use HasUuids;
    use BelongsToTenant;

    protected $fillable = [
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
