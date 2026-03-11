<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Location extends Model
{
    use HasUuids, BelongsToTenant;

    protected $fillable = [
        'tenant_id', 
        'name', 
        'type',
        'city',
        'address_line', 
        'phone',
        'lat',
        'lng',
        'is_pickup_point',
        'is_visible_to_customers'
    ];

    protected $casts = [
        'is_pickup_point' => 'boolean',
        'is_visible_to_customers' => 'boolean',
        'lat' => 'float',
        'lng' => 'float',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    
    public function address() { return $this->morphOne(Address::class, 'addressable'); }
}